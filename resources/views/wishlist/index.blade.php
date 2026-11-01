@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="border-b border-slate-200 pb-4">
        <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">My Wishlist</h1>
    </div>

    @if(!$wishlist || $wishlist->items->isEmpty())
        <div class="bg-white rounded-2xl p-12 text-center border border-slate-200 shadow-sm space-y-4">
            <i class="fa-regular fa-heart text-5xl text-slate-300"></i>
            <h2 class="text-xl font-bold text-slate-800">Your wishlist is empty</h2>
            <p class="text-xs text-slate-500">Save items you love by clicking the heart icon on any product.</p>
            <div>
                <a href="{{ route('products.index') }}" class="inline-block bg-indigo-600 text-white font-bold text-xs px-6 py-3 rounded-xl hover:bg-indigo-700 transition">
                    Explore Products
                </a>
            </div>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($wishlist->items as $item)
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden flex flex-col justify-between">
                    <div class="h-48 bg-slate-100 overflow-hidden relative">
                        <img src="{{ $item->product->primaryImage->url }}" class="w-full h-full object-cover">
                        <form action="{{ route('wishlist.remove', $item->product_id) }}" method="POST" class="absolute top-3 right-3">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-8 h-8 rounded-full bg-white/90 text-rose-500 hover:bg-rose-50 flex items-center justify-center shadow-sm">
                                <i class="fa-solid fa-xmark text-xs"></i>
                            </button>
                        </form>
                    </div>
                    <div class="p-4 flex-grow flex flex-col justify-between space-y-3">
                        <div>
                            <h3 class="font-bold text-slate-900 text-sm line-clamp-1">
                                <a href="{{ route('products.show', $item->product->slug) }}" class="hover:text-indigo-600">{{ $item->product->name }}</a>
                            </h3>
                            <p class="text-sm font-extrabold text-slate-900 mt-1">${{ number_format($item->product->effective_price, 2) }}</p>
                        </div>

                        <form action="{{ route('cart.add') }}" method="POST">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $item->product_id }}">
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" class="w-full bg-indigo-600 text-white font-bold text-xs py-2.5 rounded-lg hover:bg-indigo-700 transition flex items-center justify-center space-x-1">
                                <i class="fa-solid fa-cart-plus mr-1"></i> Add to Cart
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
