<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddToCartRequest;
use App\Http\Requests\UpdateCartItemRequest;
use App\Http\Resources\CartResource;
use App\Models\Product;
use App\Services\CartService;
use Exception;
use Illuminate\Http\Request;

class CartController extends Controller
{
    protected CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function index()
    {
        $cart = $this->cartService->getCart();
        return new CartResource($cart->load(['items.product.primaryImage']));
    }

    public function store(AddToCartRequest $request)
    {
        $product = Product::findOrFail($request->product_id);

        try {
            $this->cartService->addToCart($product, $request->quantity);
            $cart = $this->cartService->getCart();

            return response()->json([
                'message' => 'Product added to cart',
                'cart' => new CartResource($cart->load(['items.product.primaryImage'])),
            ], 201);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    public function update(UpdateCartItemRequest $request, int $itemId)
    {
        try {
            $this->cartService->updateQuantity($itemId, $request->quantity);
            $cart = $this->cartService->getCart();

            return response()->json([
                'message' => 'Cart item updated',
                'cart' => new CartResource($cart->load(['items.product.primaryImage'])),
            ]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    public function destroy(int $itemId)
    {
        $this->cartService->removeItem($itemId);
        $cart = $this->cartService->getCart();

        return response()->json([
            'message' => 'Cart item removed',
            'cart' => new CartResource($cart->load(['items.product.primaryImage'])),
        ]);
    }
}
