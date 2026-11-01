@extends('layouts.admin')

@section('title', 'Products Management')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h2 class="text-xl font-bold text-slate-900">All Products</h2>
        <a href="{{ route('admin.products.create') }}" class="bg-indigo-600 text-white font-bold text-xs px-4 py-2.5 rounded-xl hover:bg-indigo-700 transition">
            <i class="fa-solid fa-plus mr-1"></i> Add Product
        </a>
    </div>

    <!-- Filter & Search -->
    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
        <form action="{{ route('admin.products.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name or SKU..." class="text-xs rounded-lg border-slate-300">
            <select name="category_id" class="text-xs rounded-lg border-slate-300">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
            <button type="submit" class="bg-slate-900 text-white text-xs font-bold py-2 rounded-lg hover:bg-slate-800 transition">
                Search Products
            </button>
        </form>
    </div>

    <!-- Products Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50 text-slate-500 font-bold uppercase border-b border-slate-100">
                        <th class="py-3 px-4">Image</th>
                        <th class="py-3 px-4">Product Name</th>
                        <th class="py-3 px-4">Category</th>
                        <th class="py-3 px-4">SKU</th>
                        <th class="py-3 px-4">Price</th>
                        <th class="py-3 px-4">Stock</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($products as $prod)
                        <tr class="hover:bg-slate-50/50">
                            <td class="py-3 px-4">
                                <div class="w-10 h-10 rounded bg-slate-100 border border-slate-200 overflow-hidden">
                                    <img src="{{ $prod->primaryImage->url ?? 'https://via.placeholder.com/150' }}" class="w-full h-full object-cover">
                                </div>
                            </td>
                            <td class="py-3 px-4 font-bold text-slate-900">{{ $prod->name }}</td>
                            <td class="py-3 px-4 text-slate-600">{{ $prod->category->name }}</td>
                            <td class="py-3 px-4 font-mono text-slate-500">{{ $prod->sku }}</td>
                            <td class="py-3 px-4 font-bold text-slate-900">
                                @if($prod->discount_price)
                                    <span class="text-rose-600">${{ number_format($prod->discount_price, 2) }}</span>
                                    <span class="text-slate-400 line-through text-[10px] ml-1">${{ number_format($prod->price, 2) }}</span>
                                @else
                                    ${{ number_format($prod->price, 2) }}
                                @endif
                            </td>
                            <td class="py-3 px-4">
                                <span class="px-2 py-0.5 rounded font-bold text-[10px] {{ $prod->stock_quantity <= 5 ? 'bg-rose-100 text-rose-800' : 'bg-slate-100 text-slate-800' }}">
                                    {{ $prod->stock_quantity }}
                                </span>
                            </td>
                            <td class="py-3 px-4">
                                <span class="px-2 py-0.5 rounded font-bold text-[10px] {{ $prod->status ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-600' }}">
                                    {{ $prod->status ? 'Active' : 'Draft' }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-right space-x-2">
                                <a href="{{ route('admin.products.edit', $prod->id) }}" class="text-indigo-600 font-bold hover:underline">Edit</a>
                                <form action="{{ route('admin.products.destroy', $prod->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete product?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-rose-600 font-bold hover:underline">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-100">
            {{ $products->links() }}
        </div>
    </div>
</div>
@endsection
