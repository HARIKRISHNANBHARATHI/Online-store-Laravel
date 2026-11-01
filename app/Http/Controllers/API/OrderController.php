<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\CheckoutRequest;
use App\Http\Resources\OrderResource;
use App\Models\Address;
use App\Models\Order;
use App\Services\CheckoutService;
use Exception;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected CheckoutService $checkoutService;

    public function __construct(CheckoutService $checkoutService)
    {
        $this->checkoutService = $checkoutService;
    }

    public function index(Request $request)
    {
        $orders = $request->user()->orders()
            ->with(['items'])
            ->latest()
            ->paginate($request->get('per_page', 10));

        return OrderResource::collection($orders);
    }

    public function show(Request $request, Order $order)
    {
        $this->authorize('view', $order);
        return new OrderResource($order->load(['items']));
    }

    public function store(CheckoutRequest $request)
    {
        $user = $request->user();

        try {
            if ($request->filled('shipping_address_id')) {
                $shippingAddress = Address::where('user_id', $user->id)->findOrFail($request->shipping_address_id);
            } elseif ($request->filled('new_address')) {
                $shippingAddress = $user->addresses()->create($request->input('new_address'));
            } else {
                return response()->json(['message' => 'Shipping address is required.'], 422);
            }

            $billingAddress = $shippingAddress;
            if ($request->filled('billing_address_id')) {
                $billingAddress = Address::where('user_id', $user->id)->findOrFail($request->billing_address_id);
            }

            $order = $this->checkoutService->processCheckout($user, $shippingAddress, $billingAddress);

            return response()->json([
                'message' => 'Order created successfully',
                'order' => new OrderResource($order->load(['items'])),
            ], 201);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }
}
