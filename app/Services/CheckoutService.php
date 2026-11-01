<?php

namespace App\Services;

use App\Events\OrderPlaced;
use App\Models\Address;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class CheckoutService
{
    public function processCheckout(
        User $user,
        Address $shippingAddress,
        ?Address $billingAddress = null
    ): Order {
        $billingAddress = $billingAddress ?? $shippingAddress;

        return DB::transaction(function () use ($user, $shippingAddress, $billingAddress) {
            $cartService = app(CartService::class);
            $cart = $cartService->getCart($user);

            if ($cart->items->isEmpty()) {
                throw new InvalidArgumentException("Your shopping cart is empty.");
            }

            // 1. Validate Stock & Lock Products for update
            foreach ($cart->items as $item) {
                $product = Product::where('id', $item->product_id)->lockForUpdate()->first();

                if (!$product || !$product->status) {
                    throw new InvalidArgumentException("Product '{$item->product->name}' is no longer available.");
                }

                if ($product->stock_quantity < $item->quantity) {
                    throw new InvalidArgumentException("Insufficient stock for '{$product->name}'. Available: {$product->stock_quantity}.");
                }
            }

            // 2. Server-side price calculation
            $subtotal = $cart->subtotal;
            $discount = $cart->discount;
            $tax = $cart->tax;
            $shipping = $cart->shipping;
            $total = $cart->total;

            // 3. Create Order
            $order = Order::create([
                'user_id' => $user->id,
                'billing_address_id' => $billingAddress->id,
                'shipping_address_id' => $shippingAddress->id,
                'order_number' => Order::generateOrderNumber(),
                'subtotal' => $subtotal,
                'discount' => $discount,
                'tax' => $tax,
                'shipping' => $shipping,
                'total' => $total,
                'status' => 'pending',
                'payment_status' => 'pending',
                'coupon_code' => $cart->coupon_code,
                'billing_address_data' => $billingAddress->toArray(),
                'shipping_address_data' => $shippingAddress->toArray(),
            ]);

            // 4. Create Order Items & Decrement Inventory Safely
            foreach ($cart->items as $item) {
                $product = $item->product;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'quantity' => $item->quantity,
                    'price' => $product->effective_price,
                    'subtotal' => $item->subtotal,
                ]);

                // Reduce inventory
                $product->decrement('stock_quantity', $item->quantity);
            }

            // 5. Update Coupon use count if applicable
            if ($cart->coupon_code) {
                app(CouponService::class)->recordCouponUse($cart->coupon_code);
            }

            // 6. Clear user cart
            $cartService->clearCart($user);

            // 7. Dispatch OrderPlaced Event
            event(new OrderPlaced($order));

            return $order->fresh(['items', 'user', 'payment']);
        });
    }
}
