<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->only(['search', 'category_id', 'min_price', 'max_price', 'in_stock', 'sort']);

        $products = Product::active()
            ->with(['category', 'primaryImage', 'images'])
            ->filter($filters)
            ->paginate(12)
            ->withQueryString();

        $categories = Category::where('status', true)->withCount('products')->get();

        return view('shop.index', compact('products', 'categories', 'filters'));
    }

    public function show(string $slug)
    {
        $product = Product::active()
            ->where('slug', $slug)
            ->with(['category', 'images', 'primaryImage'])
            ->firstOrFail();

        $relatedProducts = Product::active()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->with(['primaryImage'])
            ->take(4)
            ->get();

        return view('shop.show', compact('product', 'relatedProducts'));
    }

    public function category(string $slug, Request $request)
    {
        $category = Category::where('slug', $slug)->where('status', true)->firstOrFail();

        $filters = array_merge(
            $request->only(['search', 'min_price', 'max_price', 'in_stock', 'sort']),
            ['category_id' => $category->id]
        );

        $products = Product::active()
            ->with(['category', 'primaryImage'])
            ->filter($filters)
            ->paginate(12)
            ->withQueryString();

        $categories = Category::where('status', true)->withCount('products')->get();

        return view('shop.index', compact('products', 'categories', 'category', 'filters'));
    }
}
