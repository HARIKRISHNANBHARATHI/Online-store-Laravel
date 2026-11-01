<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Session;

class CartService
{
    public function getCart(?User $user = null): Cart
    {
        $user = $user ?? auth()->user();
        $sessionId = Session::getId();

        if ($user) {
            $cart = Cart::firstOrCreate(['user_id' => $user->id]);

            // Merge guest cart if exists
            $guestCart = Cart::where('session_id', $sessionId)->whereNull('user_id')->first();
            if ($guestCart && $guestCart->id !== $cart->id) {
                foreach ($guestCart->items as $item) {
                    $existingItem = CartItem::where('cart_id', $cart->id)
                        ->where('product_id', $item->product_id)
                        ->first();

                    if ($existingItem) {
                        $existingItem->update([
                            'quantity' => $existingItem->quantity + $item->quantity
                        ]);
                    } else {
                        CartItem::create([
                            'cart_id' => $cart->id,
                            'product_id' => $item->product_id,
                            'quantity' => $item->quantity,
                        ]);
                    }
                }

                if ($guestCart->coupon_code && !$cart->coupon_code) {
                    $cart->update(['coupon_code' => $guestCart->coupon_code]);
                }

                $guestCart->delete();
            }

            return $cart->load(['items.product.primaryImage']);
        }

        return Cart::firstOrCreate(['session_id' => $sessionId])->load(['items.product.primaryImage']);
    }

    public function addToCart(Product $product, int $quantity = 1, ?User $user = null): CartItem
    {
        $cart = $this->getCart($user);

        if ($quantity > $product->stock_quantity) {
            throw new \InvalidArgumentException("Requested quantity exceeds available stock ({$product->stock_quantity}).");
        }

        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $product->id)
            ->first();

        if ($cartItem) {
            $newQuantity = $cartItem->quantity + $quantity;
            if ($newQuantity > $product->stock_quantity) {
                throw new \InvalidArgumentException("Cannot add more. Total in cart exceeds available stock ({$product->stock_quantity}).");
            }
            $cartItem->update(['quantity' => $newQuantity]);
        } else {
            $cartItem = CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'quantity' => $quantity,
            ]);
        }

        return $cartItem->fresh(['product']);
    }

    public function updateQuantity(int $cartItemId, int $quantity, ?User $user = null): CartItem
    {
        $cart = $this->getCart($user);
        $cartItem = CartItem::where('cart_id', $cart->id)->findOrFail($cartItemId);

        if ($quantity <= 0) {
            $cartItem->delete();
            throw new \InvalidArgumentException("Item removed from cart.");
        }

        if ($quantity > $cartItem->product->stock_quantity) {
            throw new \InvalidArgumentException("Requested quantity exceeds available stock ({$cartItem->product->stock_quantity}).");
        }

        $cartItem->update(['quantity' => $quantity]);
        return $cartItem->fresh(['product']);
    }

    public function removeItem(int $cartItemId, ?User $user = null): bool
    {
        $cart = $this->getCart($user);
        return CartItem::where('cart_id', $cart->id)->where('id', $cartItemId)->delete() > 0;
    }

    public function clearCart(?User $user = null): void
    {
        $cart = $this->getCart($user);
        $cart->items()->delete();
        $cart->update(['coupon_code' => null]);
    }

    public function applyCoupon(string $code, ?User $user = null): array
    {
        $cart = $this->getCart($user);
        $couponService = app(CouponService::class);
        $result = $couponService->validateCoupon($code, $cart->subtotal);

        if ($result['valid']) {
            $cart->update(['coupon_code' => strtoupper($code)]);
        }

        return $result;
    }

    public function removeCoupon(?User $user = null): void
    {
        $cart = $this->getCart($user);
        $cart->update(['coupon_code' => null]);
    }

    public function getSummary(?User $user = null): array
    {
        $cart = $this->getCart($user);

        return [
            'subtotal' => $cart->subtotal,
            'discount' => $cart->discount,
            'tax' => $cart->tax,
            'shipping' => $cart->shipping,
            'total' => $cart->total,
            'coupon_code' => $cart->coupon_code,
            'item_count' => $cart->items->sum('quantity'),
        ];
    }
}
