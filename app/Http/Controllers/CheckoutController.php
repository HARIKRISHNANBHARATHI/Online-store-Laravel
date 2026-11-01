<?php

namespace App\Http\Controllers;

use App\Events\OrderPlaced;
use App\Http\Requests\CheckoutRequest;
use App\Models\Address;
use App\Models\Order;
use App\Services\CartService;
use App\Services\CheckoutService;
use App\Services\PaymentService;
use Exception;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    protected CartService $cartService;
    protected CheckoutService $checkoutService;
    protected PaymentService $paymentService;

    public function __construct(
        CartService $cartService,
        CheckoutService $checkoutService,
        PaymentService $paymentService
    ) {
        $this->cartService = $cartService;
        $this->checkoutService = $checkoutService;
        $this->paymentService = $paymentService;
    }

    /**
     * Show the checkout form (address selection + order review).
     */
    public function index()
    {
        $user = auth()->user();
        $cart = $this->cartService->getCart($user);

        if ($cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $addresses = $user->addresses;
        $summary = $this->cartService->getSummary($user);

        return view('checkout.index', compact('cart', 'addresses', 'summary'));
    }

    /**
     * Process address selection, create the order, apply COD payment, and redirect to success.
     */
    public function process(CheckoutRequest $request)
    {
        $user = auth()->user();

        try {
            // Resolve shipping address
            if ($request->filled('shipping_address_id')) {
                $shippingAddress = Address::where('user_id', $user->id)
                    ->findOrFail($request->shipping_address_id);
            } elseif ($request->filled('new_address')) {
                $shippingAddress = $user->addresses()->create(
                    array_merge($request->input('new_address'), ['type' => 'both'])
                );
            } else {
                return redirect()->back()->with('error', 'Please select or enter a shipping address.');
            }

            $billingAddress = $shippingAddress;

            // Create the order via CheckoutService
            $order = $this->checkoutService->processCheckout($user, $shippingAddress, $billingAddress);

            // Record COD payment – no external API calls
            $this->paymentService->recordCodPayment($order);

            // Fire event: sends confirmation email, updates inventory
            event(new OrderPlaced($order));

            return redirect()
                ->route('checkout.success', $order->id)
                ->with('success', 'Order placed successfully! We will collect payment on delivery.');

        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Could not place order: ' . $e->getMessage());
        }
    }

    /**
     * Show order success/confirmation page.
     */
    public function success(Order $order)
    {
        $this->authorize('view', $order);
        return view('checkout.success', compact('order'));
    }
}
