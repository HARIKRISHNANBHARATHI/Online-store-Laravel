@extends('layouts.app')

@section('content')
<div class="space-y-8">
    <div class="flex items-center justify-between border-b border-slate-200 pb-4">
        <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Shopping Cart</h1>
        @if($cart->items->isNotEmpty())
            <form action="{{ route('cart.clear') }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-xs text-rose-600 font-semibold hover:underline flex items-center space-x-1">
                    <i class="fa-solid fa-trash-can"></i>
                    <span>Clear Cart</span>
                </button>
            </form>
        @endif
    </div>

    @if($cart->items->isEmpty())
        <div class="bg-white rounded-2xl p-12 text-center border border-slate-200 shadow-sm space-y-4">
            <i class="fa-solid fa-cart-flatbed text-5xl text-slate-300"></i>
            <h2 class="text-xl font-bold text-slate-800">Your shopping cart is empty</h2>
            <p class="text-xs text-slate-500 max-w-sm mx-auto">Explore our catalog and add your favorite items to cart.</p>
            <div>
                <a href="{{ route('products.index') }}" class="inline-block bg-indigo-600 text-white font-bold text-xs px-6 py-3 rounded-xl hover:bg-indigo-700 transition">
                    Browse Shop
                </a>
            </div>
        </div>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Items Table -->
            <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="divide-y divide-slate-100">
                    @foreach($cart->items as $item)
                        <div class="p-4 sm:p-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                            <!-- Image & Details -->
                            <div class="flex items-center space-x-4">
                                <div class="w-16 h-16 rounded-lg bg-slate-100 border border-slate-200 overflow-hidden flex-shrink-0">
                                    <img src="{{ $item->product->primaryImage->url }}" class="w-full h-full object-cover">
                                </div>
                                <div>
                                    <h3 class="font-bold text-slate-900 text-sm hover:text-indigo-600 transition">
                                        <a href="{{ route('products.show', $item->product->slug) }}">{{ $item->product->name }}</a>
                                    </h3>
                                    <p class="text-xs text-slate-400 font-mono mt-0.5">SKU: {{ $item->product->sku }}</p>
                                    <p class="text-xs font-semibold text-slate-700 mt-1">${{ number_format($item->product->effective_price, 2) }} each</p>
                                </div>
                            </div>

                            <!-- Quantity Form & Subtotal -->
                            <div class="flex items-center justify-between w-full sm:w-auto space-x-6">
                                <form action="{{ route('cart.update', $item->id) }}" method="POST" class="flex items-center space-x-2">
                                    @csrf
                                    @method('PATCH')
                                    <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" max="{{ $item->product->stock_quantity }}" class="w-16 text-xs font-bold rounded-lg border-slate-300 text-center py-1.5 focus:ring-indigo-500 focus:border-indigo-500" onchange="this.form.submit()">
                                </form>

                                <div class="text-right">
                                    <span class="block text-sm font-extrabold text-slate-900">${{ number_format($item->subtotal, 2) }}</span>
                                </div>

                                <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-slate-400 hover:text-rose-600 transition text-xs">
                                        <i class="fa-solid fa-xmark text-lg"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Summary & Coupon Sidebar -->
            <div class="space-y-6">
                <!-- Coupon Box -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
                    <h3 class="font-bold text-slate-900 text-sm">Promo Code</h3>
                    @if($cart->coupon_code)
                        <div class="bg-emerald-50 border border-emerald-200 p-3 rounded-xl flex items-center justify-between">
                            <div>
                                <span class="text-xs font-bold text-emerald-800 uppercase">{{ $cart->coupon_code }}</span>
                                <span class="block text-[10px] text-emerald-600">Applied to cart</span>
                            </div>
                            <form action="{{ route('cart.coupon.remove') }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-xs text-rose-600 font-bold hover:underline">Remove</button>
                            </form>
                        </div>
                    @else
                        <form action="{{ route('cart.coupon.apply') }}" method="POST" class="flex space-x-2">
                            @csrf
                            <input type="text" name="coupon_code" placeholder="e.g. WELCOME10" class="w-full text-xs uppercase font-bold rounded-xl border-slate-300 focus:ring-indigo-500 focus:border-indigo-500">
                            <button type="submit" class="bg-slate-900 text-white font-bold text-xs px-4 py-2.5 rounded-xl hover:bg-slate-800 transition">
                                Apply
                            </button>
                        </form>
                    @endif
                </div>

                <!-- Order Calculation Box -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
                    <h3 class="font-bold text-slate-900 text-sm pb-3 border-b border-slate-100">Order Summary</h3>

                    <div class="space-y-2 text-xs font-medium text-slate-600">
                        <div class="flex justify-between">
                            <span>Subtotal</span>
                            <span class="font-bold text-slate-900">${{ number_format($summary['subtotal'], 2) }}</span>
                        </div>

                        @if($summary['discount'] > 0)
                            <div class="flex justify-between text-emerald-600">
                                <span>Discount</span>
                                <span class="font-bold">-${{ number_format($summary['discount'], 2) }}</span>
                            </div>
                        @endif

                        <div class="flex justify-between">
                            <span>Estimated Tax (8%)</span>
                            <span class="font-bold text-slate-900">${{ number_format($summary['tax'], 2) }}</span>
                        </div>

                        <div class="flex justify-between">
                            <span>Estimated Shipping</span>
                            <span class="font-bold text-slate-900">
                                @if($summary['shipping'] == 0)
                                    <span class="text-emerald-600 uppercase text-[10px]">FREE</span>
                                @else
                                    ${{ number_format($summary['shipping'], 2) }}
                                @endif
                            </span>
                        </div>
                    </div>

                    <div class="pt-3 border-t border-slate-100 flex justify-between items-baseline">
                        <span class="text-sm font-bold text-slate-900">Total</span>
                        <span class="text-2xl font-black text-slate-900">${{ number_format($summary['total'], 2) }}</span>
                    </div>

                    <a href="{{ route('checkout.index') }}" class="w-full bg-indigo-600 text-white text-center font-bold text-sm py-3.5 px-6 rounded-xl hover:bg-indigo-700 transition shadow-md block">
                        Proceed to Checkout <i class="fa-solid fa-arrow-right ml-2 text-xs"></i>
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
