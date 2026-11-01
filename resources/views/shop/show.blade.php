@extends('layouts.app')

@section('content')
<div class="space-y-12">
    <!-- Breadcrumb -->
    <nav class="flex text-xs font-medium text-slate-500 space-x-2">
        <a href="{{ route('products.index') }}" class="hover:text-slate-900">Home</a>
        <span>/</span>
        <a href="{{ route('categories.show', $product->category->slug) }}" class="hover:text-slate-900">{{ $product->category->name }}</a>
        <span>/</span>
        <span class="text-slate-900 font-semibold">{{ $product->name }}</span>
    </nav>

    <!-- Main Detail Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-10 bg-white p-6 sm:p-8 rounded-2xl border border-slate-200 shadow-sm" x-data="{ mainImage: '{{ $product->primaryImage->url }}' }">
        <!-- Image Gallery -->
        <div class="space-y-4">
            <div class="h-96 rounded-xl bg-slate-100 overflow-hidden border border-slate-200">
                <img :src="mainImage" alt="{{ $product->name }}" class="w-full h-full object-cover">
            </div>
            <div class="flex space-x-3 overflow-x-auto pb-2">
                @foreach($product->images as $img)
                    <button @click="mainImage = '{{ $img->url }}'" class="w-20 h-20 rounded-lg bg-slate-100 border border-slate-200 overflow-hidden flex-shrink-0 focus:ring-2 focus:ring-indigo-600">
                        <img src="{{ $img->url }}" class="w-full h-full object-cover">
                    </button>
                @endforeach
            </div>
        </div>

        <!-- Product Specs & Action -->
        <div class="flex flex-col justify-between space-y-6">
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <span class="bg-indigo-50 text-indigo-700 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider">
                        {{ $product->category->name }}
                    </span>
                    <span class="text-xs text-slate-400 font-mono">SKU: {{ $product->sku }}</span>
                </div>

                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight leading-tight">
                    {{ $product->name }}
                </h1>

                <!-- Price Block -->
                <div class="flex items-baseline space-x-3 pt-2">
                    @if($product->discount_price && $product->discount_price < $product->price)
                        <span class="text-3xl font-black text-slate-900">${{ number_format($product->discount_price, 2) }}</span>
                        <span class="text-lg text-slate-400 line-through">${{ number_format($product->price, 2) }}</span>
                        <span class="bg-rose-100 text-rose-700 text-xs font-bold px-2 py-0.5 rounded">
                            Save ${{ number_format($product->price - $product->discount_price, 2) }}
                        </span>
                    @else
                        <span class="text-3xl font-black text-slate-900">${{ number_format($product->price, 2) }}</span>
                    @endif
                </div>

                <!-- Stock Status -->
                <div>
                    @if($product->is_in_stock)
                        <span class="inline-flex items-center text-xs font-semibold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-md border border-emerald-200">
                            <i class="fa-solid fa-circle-check mr-1.5 text-emerald-500"></i> In Stock ({{ $product->stock_quantity }} available)
                        </span>
                    @else
                        <span class="inline-flex items-center text-xs font-semibold text-rose-700 bg-rose-50 px-2.5 py-1 rounded-md border border-rose-200">
                            <i class="fa-solid fa-circle-xmark mr-1.5 text-rose-500"></i> Out of Stock
                        </span>
                    @endif
                </div>

                <!-- Description -->
                <div class="prose prose-sm text-slate-600 border-t border-slate-100 pt-4 leading-relaxed">
                    <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-2">Description</h4>
                    <p>{{ $product->description }}</p>
                </div>
            </div>

            <!-- Add to Cart Form -->
            @if($product->is_in_stock)
                <form action="{{ route('cart.add') }}" method="POST" class="border-t border-slate-100 pt-6 space-y-4">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    
                    <div class="flex items-center space-x-4">
                        <label class="text-xs font-bold text-slate-700 uppercase">Quantity:</label>
                        <div class="w-32">
                            <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock_quantity }}" class="w-full text-sm font-semibold rounded-lg border-slate-300 focus:ring-indigo-500 focus:border-indigo-500 text-center">
                        </div>
                    </div>

                    <div class="flex items-center space-x-3">
                        <button type="submit" class="flex-grow bg-indigo-600 text-white font-bold text-sm py-3.5 px-6 rounded-xl hover:bg-indigo-700 transition shadow-md flex items-center justify-center space-x-2">
                            <i class="fa-solid fa-cart-plus text-base"></i>
                            <span>Add to Shopping Cart</span>
                        </button>

                        @auth
                            <form action="{{ route('wishlist.toggle', $product) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-12 h-12 rounded-xl bg-slate-100 border border-slate-200 text-slate-700 hover:text-rose-600 hover:bg-rose-50 flex items-center justify-center transition">
                                    <i class="fa-solid fa-heart text-base"></i>
                                </button>
                            </form>
                        @endauth
                    </div>
                </form>
            @endif
        </div>
    </div>

    <!-- Related Products -->
    @if($relatedProducts->isNotEmpty())
        <div class="space-y-6">
            <h2 class="text-xl font-bold text-slate-900 tracking-tight">Related Products</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($relatedProducts as $rel)
                    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden flex flex-col hover:shadow-md transition">
                        <div class="h-40 bg-slate-100 overflow-hidden">
                            <img src="{{ $rel->primaryImage->url }}" alt="{{ $rel->name }}" class="w-full h-full object-cover">
                        </div>
                        <div class="p-4 flex-grow flex flex-col justify-between">
                            <div>
                                <h4 class="font-bold text-slate-900 text-xs line-clamp-1">
                                    <a href="{{ route('products.show', $rel->slug) }}" class="hover:text-indigo-600">{{ $rel->name }}</a>
                                </h4>
                                <p class="text-xs font-bold text-slate-900 mt-1">${{ number_format($rel->effective_price, 2) }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection
