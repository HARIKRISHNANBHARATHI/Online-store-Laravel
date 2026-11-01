<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddToCartRequest;
use App\Http\Requests\UpdateCartItemRequest;
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
        $summary = $this->cartService->getSummary();

        return view('cart.index', compact('cart', 'summary'));
    }

    public function add(AddToCartRequest $request)
    {
        $product = Product::findOrFail($request->product_id);

        try {
            $this->cartService->addToCart($product, $request->quantity);

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Product added to cart successfully.',
                    'summary' => $this->cartService->getSummary(),
                ]);
            }

            return redirect()->back()->with('success', 'Product added to cart!');
        } catch (Exception $e) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
            }

            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function update(UpdateCartItemRequest $request, int $id)
    {
        try {
            $this->cartService->updateQuantity($id, $request->quantity);

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Cart updated successfully.',
                    'summary' => $this->cartService->getSummary(),
                ]);
            }

            return redirect()->route('cart.index')->with('success', 'Cart updated successfully.');
        } catch (Exception $e) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
            }

            return redirect()->route('cart.index')->with('error', $e->getMessage());
        }
    }

    public function remove(int $id)
    {
        $this->cartService->removeItem($id);

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Item removed from cart.',
                'summary' => $this->cartService->getSummary(),
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Item removed from cart.');
    }

    public function clear()
    {
        $this->cartService->clearCart();

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Cart cleared.',
                'summary' => $this->cartService->getSummary(),
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Cart cleared.');
    }

    public function applyCoupon(Request $request)
    {
        $request->validate(['coupon_code' => ['required', 'string']]);

        $result = $this->cartService->applyCoupon($request->coupon_code);

        if ($result['valid']) {
            return redirect()->route('cart.index')->with('success', $result['message']);
        }

        return redirect()->route('cart.index')->with('error', $result['message']);
    }

    public function removeCoupon()
    {
        $this->cartService->removeCoupon();
        return redirect()->route('cart.index')->with('success', 'Coupon removed.');
    }
}
