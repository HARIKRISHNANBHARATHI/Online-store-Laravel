@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Hero Banner -->
    <div class="relative rounded-2xl bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 text-white p-8 sm:p-12 overflow-hidden shadow-xl">
        <div class="relative z-10 max-w-2xl">
            <span class="inline-block bg-indigo-500/20 text-indigo-300 border border-indigo-500/30 text-xs font-semibold px-3 py-1 rounded-full mb-3 uppercase tracking-wider">
                New Season Arrivals
            </span>
            <h1 class="text-3xl sm:text-5xl font-extrabold tracking-tight mb-4 leading-tight">
                Discover Premium Products Crafted For Excellence.
            </h1>
            <p class="text-slate-300 text-sm sm:text-base leading-relaxed mb-6">
                Explore top quality categories, seamless checkout with Stripe test integration, and instant server-side price validation.
            </p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Sidebar Filters -->
        <div class="space-y-6">
            <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm space-y-6">
                <h3 class="font-bold text-slate-900 text-sm uppercase tracking-wider pb-3 border-b border-slate-100 flex justify-between items-center">
                    <span>Filters</span>
                    @if(request()->anyFilled(['search', 'category_id', 'min_price', 'max_price', 'in_stock', 'sort']))
                        <a href="{{ route('products.index') }}" class="text-xs text-indigo-600 font-normal hover:underline">Clear All</a>
                    @endif
                </h3>

                <form action="{{ route('products.index') }}" method="GET" class="space-y-4">
                    <!-- Search Field -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Search</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Keyword or SKU..." class="w-full text-xs rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <!-- Category Filter -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Category</label>
                        <select name="category_id" class="w-full text-xs rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">All Categories</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id || (isset($category) && $category->id == $cat->id) ? 'selected' : '' }}>
                                    {{ $cat->name }} ({{ $cat->products_count }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Price Range -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Price Range ($)</label>
                        <div class="grid grid-cols-2 gap-2">
                            <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="Min" class="w-full text-xs rounded-lg border-slate-300">
                            <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Max" class="w-full text-xs rounded-lg border-slate-300">
                        </div>
                    </div>

                    <!-- In Stock Checkbox -->
                    <div class="flex items-center space-x-2">
                        <input type="checkbox" name="in_stock" value="1" id="in_stock" {{ request('in_stock') ? 'checked' : '' }} class="rounded text-indigo-600 focus:ring-indigo-500">
                        <label for="in_stock" class="text-xs font-medium text-slate-700">In Stock Only</label>
                    </div>

                    <!-- Sort -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Sort By</label>
                        <select name="sort" class="w-full text-xs rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest Arrivals</option>
                            <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                            <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Name: A-Z</option>
                        </select>
                    </div>

                    <button type="submit" class="w-full bg-slate-900 text-white text-xs font-bold py-2.5 rounded-lg hover:bg-slate-800 transition">
                        Apply Filters
                    </button>
                </form>
            </div>
        </div>

        <!-- Product Grid -->
        <div class="lg:col-span-3 space-y-6">
            <div class="flex justify-between items-center pb-4 border-b border-slate-200">
                <p class="text-xs font-semibold text-slate-500">
                    Showing <span class="text-slate-900 font-bold">{{ $products->firstItem() ?? 0 }} - {{ $products->lastItem() ?? 0 }}</span> of <span class="text-slate-900 font-bold">{{ $products->total() }}</span> products
                </p>
            </div>

            @if($products->isEmpty())
                <div class="bg-white rounded-xl p-12 text-center border border-slate-200 shadow-sm space-y-3">
                    <i class="fa-solid fa-box-open text-4xl text-slate-300"></i>
                    <h3 class="text-lg font-bold text-slate-800">No products found</h3>
                    <p class="text-xs text-slate-500">Try adjusting your search query or price filters.</p>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($products as $product)
                        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden flex flex-col hover:shadow-md transition group">
                            <!-- Image Container -->
                            <div class="relative h-48 bg-slate-100 overflow-hidden">
                                <img src="{{ $product->primaryImage->url }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                
                                @if($product->discount_price && $product->discount_price < $product->price)
                                    <span class="absolute top-3 left-3 bg-rose-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-md uppercase">
                                        Sale
                                    </span>
                                @endif

                                @if(!$product->is_in_stock)
                                    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-[2px] flex items-center justify-center">
                                        <span class="bg-slate-900 text-white text-xs font-bold px-3 py-1 rounded">Out of Stock</span>
                                    </div>
                                @endif

                                @auth
                                    <form action="{{ route('wishlist.toggle', $product) }}" method="POST" class="absolute top-3 right-3">
                                        @csrf
                                        <button type="submit" class="w-8 h-8 rounded-full bg-white/90 text-slate-600 hover:text-rose-500 flex items-center justify-center shadow-sm transition">
                                            <i class="fa-solid fa-heart text-xs"></i>
                                        </button>
                                    </form>
                                @endauth
                            </div>

                            <!-- Content -->
                            <div class="p-4 flex-grow flex flex-col justify-between space-y-3">
                                <div>
                                    <span class="text-[10px] font-bold uppercase text-indigo-600 tracking-wider">
                                        {{ $product->category->name }}
                                    </span>
                                    <h3 class="font-bold text-slate-900 text-sm leading-snug hover:text-indigo-600 transition line-clamp-1 mt-0.5">
                                        <a href="{{ route('products.show', $product->slug) }}">{{ $product->name }}</a>
                                    </h3>
                                    <p class="text-xs text-slate-500 line-clamp-2 mt-1 leading-relaxed">
                                        {{ $product->description }}
                                    </p>
                                </div>

                                <div class="pt-2 border-t border-slate-100 flex items-center justify-between">
                                    <div>
                                        @if($product->discount_price && $product->discount_price < $product->price)
                                            <span class="text-base font-extrabold text-slate-900">${{ number_format($product->discount_price, 2) }}</span>
                                            <span class="text-xs text-slate-400 line-through ml-1">${{ number_format($product->price, 2) }}</span>
                                        @else
                                            <span class="text-base font-extrabold text-slate-900">${{ number_format($product->price, 2) }}</span>
                                        @endif
                                    </div>

                                    @if($product->is_in_stock)
                                        <form action="{{ route('cart.add') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                                            <input type="hidden" name="quantity" value="1">
                                            <button type="submit" class="bg-indigo-600 text-white text-xs font-semibold px-3 py-1.5 rounded-lg hover:bg-indigo-700 transition flex items-center space-x-1">
                                                <i class="fa-solid fa-cart-plus text-xs"></i>
                                                <span>Add</span>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="pt-4">
                    {{ $products->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
