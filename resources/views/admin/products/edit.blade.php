@extends('layouts.admin')

@section('title', 'Edit Product')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex justify-between items-center">
        <h2 class="text-xl font-bold text-slate-900">Edit Product: {{ $product->name }}</h2>
        <a href="{{ route('admin.products.index') }}" class="text-xs text-indigo-600 font-bold hover:underline">Back to List</a>
    </div>

    <!-- Product Form -->
    <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200 shadow-sm space-y-4">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Product Name</label>
                <input type="text" name="name" value="{{ old('name', $product->name) }}" required class="w-full text-xs rounded-lg border-slate-300">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Category</label>
                <select name="category_id" required class="w-full text-xs rounded-lg border-slate-300">
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="grid grid-cols-3 gap-4">
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">SKU</label>
                <input type="text" name="sku" value="{{ old('sku', $product->sku) }}" required class="w-full text-xs rounded-lg border-slate-300">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Regular Price ($)</label>
                <input type="number" step="0.01" name="price" value="{{ old('price', $product->price) }}" required class="w-full text-xs rounded-lg border-slate-300">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Discount Price ($)</label>
                <input type="number" step="0.01" name="discount_price" value="{{ old('discount_price', $product->discount_price) }}" class="w-full text-xs rounded-lg border-slate-300">
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Stock Quantity</label>
                <input type="number" name="stock_quantity" value="{{ old('stock_quantity', $product->stock_quantity) }}" required class="w-full text-xs rounded-lg border-slate-300">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Status</label>
                <select name="status" class="w-full text-xs rounded-lg border-slate-300">
                    <option value="1" {{ $product->status ? 'selected' : '' }}>Active / Published</option>
                    <option value="0" {{ !$product->status ? 'selected' : '' }}>Draft / Inactive</option>
                </select>
            </div>
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Description</label>
            <textarea name="description" rows="4" required class="w-full text-xs rounded-lg border-slate-300">{{ old('description', $product->description) }}</textarea>
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Add More Images</label>
            <input type="file" name="images[]" multiple accept="image/*" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700">
        </div>

        <div class="pt-4 flex justify-end space-x-3">
            <a href="{{ route('admin.products.index') }}" class="px-4 py-2.5 text-xs font-semibold text-slate-600 hover:underline">Cancel</a>
            <button type="submit" class="bg-indigo-600 text-white font-bold text-xs px-6 py-2.5 rounded-xl hover:bg-indigo-700 transition">Update Product</button>
        </div>
    </form>

    <!-- Existing Images Gallery -->
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
        <h3 class="font-bold text-slate-900 text-sm">Product Image Gallery</h3>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            @foreach($product->images as $img)
                <div class="relative group rounded-lg overflow-hidden border border-slate-200 bg-slate-100">
                    <img src="{{ $img->url }}" class="w-full h-28 object-cover">
                    @if($img->is_primary)
                        <span class="absolute top-1 left-1 bg-indigo-600 text-white text-[9px] font-bold px-1.5 py-0.5 rounded">Primary</span>
                    @else
                        <form action="{{ route('admin.products.images.primary', $img->id) }}" method="POST" class="absolute top-1 left-1">
                            @csrf
                            <button type="submit" class="bg-slate-900/80 text-white text-[9px] font-bold px-1.5 py-0.5 rounded hover:bg-slate-900">Make Primary</button>
                        </form>
                    @endif

                    <form action="{{ route('admin.products.images.destroy', $img->id) }}" method="POST" class="absolute top-1 right-1" onsubmit="return confirm('Delete image?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-6 h-6 rounded-full bg-rose-600 text-white text-[10px] flex items-center justify-center shadow">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </form>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
