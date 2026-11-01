<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\WishlistService;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    protected WishlistService $wishlistService;

    public function __construct(WishlistService $wishlistService)
    {
        $this->wishlistService = $wishlistService;
    }

    public function index()
    {
        $wishlist = $this->wishlistService->getWishlist(auth()->user());
        return view('wishlist.index', compact('wishlist'));
    }

    public function toggle(Product $product, Request $request)
    {
        $result = $this->wishlistService->toggleWishlist(auth()->user(), $product);

        if ($request->wantsJson()) {
            return response()->json($result);
        }

        return redirect()->back()->with('success', $result['message']);
    }

    public function remove(int $productId)
    {
        $this->wishlistService->removeItem(auth()->user(), $productId);
        return redirect()->route('wishlist.index')->with('success', 'Product removed from wishlist.');
    }
}
