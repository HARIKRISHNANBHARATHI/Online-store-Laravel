<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Services\ProductService;
use Illuminate\Http\Request;

class AdminProductController extends Controller
{
    protected ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index(Request $request)
    {
        $this->authorize('viewAny', Product::class);

        $query = Product::with(['category', 'primaryImage', 'images']);

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('sku', 'like', "%{$request->search}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $products = $query->latest()->paginate(15)->withQueryString();
        $categories = Category::all();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $this->authorize('create', Product::class);
        $categories = Category::where('status', true)->get();
        return view('admin.products.create', compact('categories'));
    }

    public function store(StoreProductRequest $request)
    {
        $this->authorize('create', Product::class);

        $images = $request->file('images', []);
        $product = $this->productService->createProduct($request->validated(), $images);

        return redirect()->route('admin.products.index')->with('success', "Product '{$product->name}' created successfully.");
    }

    public function edit(Product $product)
    {
        $this->authorize('update', $product);
        $categories = Category::all();
        $product->load(['images']);

        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $this->authorize('update', $product);

        $images = $request->file('images', []);
        $this->productService->updateProduct($product, $request->validated(), $images);

        return redirect()->route('admin.products.index')->with('success', "Product '{$product->name}' updated successfully.");
    }

    public function destroy(Product $product)
    {
        $this->authorize('delete', $product);

        $name = $product->name;
        $this->productService->deleteProduct($product);

        return redirect()->route('admin.products.index')->with('success', "Product '{$name}' deleted.");
    }

    public function deleteImage(ProductImage $image)
    {
        $this->authorize('update', $image->product);

        $this->productService->deleteImage($image);
        return redirect()->back()->with('success', 'Image deleted successfully.');
    }

    public function setPrimaryImage(ProductImage $image)
    {
        $this->authorize('update', $image->product);

        $this->productService->setPrimaryImage($image);
        return redirect()->back()->with('success', 'Primary image updated.');
    }
}
