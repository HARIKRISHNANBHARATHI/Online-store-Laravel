@extends('layouts.admin')

@section('title', 'Add New Product')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex justify-between items-center">
        <h2 class="text-xl font-bold text-slate-900">Create Product</h2>
        <a href="{{ route('admin.products.index') }}" class="text-xs text-indigo-600 font-bold hover:underline">Back to List</a>
    </div>

    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200 shadow-sm space-y-4">
        @csrf

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Product Name</label>
                <input type="text" name="name" value="{{ old('name') }}" required class="w-full text-xs rounded-lg border-slate-300">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Category</label>
                <select name="category_id" required class="w-full text-xs rounded-lg border-slate-300">
                    <option value="">Select Category</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="grid grid-cols-3 gap-4">
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">SKU</label>
                <input type="text" name="sku" value="{{ old('sku') }}" placeholder="e.g. ELEC-1001" required class="w-full text-xs rounded-lg border-slate-300">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Regular Price ($)</label>
                <input type="number" step="0.01" name="price" value="{{ old('price') }}" required class="w-full text-xs rounded-lg border-slate-300">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Discount Price ($)</label>
                <input type="number" step="0.01" name="discount_price" value="{{ old('discount_price') }}" placeholder="Optional" class="w-full text-xs rounded-lg border-slate-300">
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Stock Quantity</label>
                <input type="number" name="stock_quantity" value="{{ old('stock_quantity', 10) }}" required class="w-full text-xs rounded-lg border-slate-300">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Status</label>
                <select name="status" class="w-full text-xs rounded-lg border-slate-300">
                    <option value="1">Active / Published</option>
                    <option value="0">Draft / Inactive</option>
                </select>
            </div>
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Description</label>
            <textarea name="description" rows="4" required class="w-full text-xs rounded-lg border-slate-300"></textarea>
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Product Images</label>
            <input type="file" name="images[]" multiple accept="image/*" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
        </div>

        <div class="pt-4 flex justify-end space-x-3">
            <a href="{{ route('admin.products.index') }}" class="px-4 py-2.5 text-xs font-semibold text-slate-600 hover:underline">Cancel</a>
            <button type="submit" class="bg-indigo-600 text-white font-bold text-xs px-6 py-2.5 rounded-xl hover:bg-indigo-700 transition">Create Product</button>
        </div>
    </form>
</div>
@endsection
