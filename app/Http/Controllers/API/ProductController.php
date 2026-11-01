<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->only(['search', 'category_id', 'min_price', 'max_price', 'in_stock', 'sort']);

        $products = Product::active()
            ->with(['category', 'images', 'primaryImage'])
            ->filter($filters)
            ->paginate($request->get('per_page', 15));

        return ProductResource::collection($products);
    }

    public function show(string $identifier)
    {
        $product = Product::active()
            ->where(function ($q) use ($identifier) {
                if (is_numeric($identifier)) {
                    $q->where('id', $identifier);
                } else {
                    $q->where('slug', $identifier);
                }
            })
            ->with(['category', 'images', 'primaryImage'])
            ->firstOrFail();

        return new ProductResource($product);
    }
}
