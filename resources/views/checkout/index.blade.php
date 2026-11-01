@extends('layouts.app')

@section('content')
<div class="space-y-8" x-data="{ useNewAddress: {{ $addresses->isEmpty() ? 'true' : 'false' }} }">
    <!-- Page Header -->
    <div class="border-b border-slate-200 pb-4">
        <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Checkout</h1>
        <p class="text-xs text-slate-500 mt-1">Review your order, choose a delivery address and place your order.</p>
    </div>

    <form action="{{ route('checkout.process') }}" method="POST" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        @csrf

        <div class="lg:col-span-2 space-y-6">
            <!-- Saved Addresses Selector -->
            @if($addresses->isNotEmpty())
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
                    <h3 class="font-bold text-slate-900 text-sm">Select Delivery Address</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($addresses as $addr)
                            <label class="relative bg-slate-50 p-4 rounded-xl border border-slate-200 cursor-pointer hover:border-indigo-500 transition block has-[:checked]:border-indigo-600 has-[:checked]:bg-indigo-50">
                                <div class="flex items-start justify-between">
                                    <input type="radio" name="shipping_address_id" value="{{ $addr->id }}"
                                        {{ $addr->is_default ? 'checked' : '' }}
                                        @click="useNewAddress = false"
                                        class="text-indigo-600 focus:ring-indigo-500">
                                    @if($addr->is_default)
                                        <span class="text-[10px] bg-indigo-100 text-indigo-700 font-bold px-2 py-0.5 rounded">Default</span>
                                    @endif
                                </div>
                                <div class="mt-2 space-y-1">
                                    <h4 class="font-bold text-slate-900 text-xs">{{ $addr->full_name }}</h4>
                                    <p class="text-[11px] text-slate-600 leading-snug">
                                        {{ $addr->address_line_1 }}@if($addr->address_line_2), {{ $addr->address_line_2 }}@endif<br>
                                        {{ $addr->city }}, {{ $addr->state }} {{ $addr->postal_code }}<br>
                                        Phone: {{ $addr->phone }}
                                    </p>
                                </div>
                            </label>
                        @endforeach
                    </div>
                    <div class="pt-2">
                        <button type="button" @click="useNewAddress = !useNewAddress"
                            class="text-xs text-indigo-600 font-bold hover:underline flex items-center space-x-1">
                            <i class="fa-solid fa-plus text-[10px]"></i>
                            <span x-text="useNewAddress ? 'Cancel new address' : 'Or deliver to a new address'"></span>
                        </button>
                    </div>
                </div>
            @endif

            <!-- New Address Form -->
            <div x-show="useNewAddress" x-transition class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
                <h3 class="font-bold text-slate-900 text-sm">New Delivery Address</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">First Name</label>
                        <input type="text" name="new_address[first_name]" value="{{ old('new_address.first_name') }}"
                            class="w-full text-xs rounded-lg border-slate-300 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Last Name</label>
                        <input type="text" name="new_address[last_name]" value="{{ old('new_address.last_name') }}"
                            class="w-full text-xs rounded-lg border-slate-300 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Address Line 1</label>
                    <input type="text" name="new_address[address_line_1]" value="{{ old('new_address.address_line_1') }}"
                        class="w-full text-xs rounded-lg border-slate-300 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Address Line 2 (Optional)</label>
                    <input type="text" name="new_address[address_line_2]" value="{{ old('new_address.address_line_2') }}"
                        class="w-full text-xs rounded-lg border-slate-300">
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">City</label>
                        <input type="text" name="new_address[city]" value="{{ old('new_address.city') }}"
                            class="w-full text-xs rounded-lg border-slate-300 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">State</label>
                        <input type="text" name="new_address[state]" value="{{ old('new_address.state') }}"
                            class="w-full text-xs rounded-lg border-slate-300 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Postal Code</label>
                        <input type="text" name="new_address[postal_code]" value="{{ old('new_address.postal_code') }}"
                            class="w-full text-xs rounded-lg border-slate-300 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Country</label>
                        <input type="text" name="new_address[country]" value="{{ old('new_address.country', 'United States') }}"
                            class="w-full text-xs rounded-lg border-slate-300 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Phone</label>
                        <input type="text" name="new_address[phone]" value="{{ old('new_address.phone') }}"
                            class="w-full text-xs rounded-lg border-slate-300 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>
            </div>

            <!-- Payment Method: COD only -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-3">
                <h3 class="font-bold text-slate-900 text-sm">Payment Method</h3>
                <label class="flex items-center space-x-3 bg-emerald-50 border border-emerald-200 rounded-xl p-4 cursor-pointer">
                    <input type="radio" name="payment_method" value="cod" checked class="text-emerald-600 focus:ring-emerald-500">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center text-emerald-700 text-xl flex-shrink-0">
                            <i class="fa-solid fa-money-bill-wave"></i>
                        </div>
                        <div>
                            <span class="text-sm font-bold text-slate-900 block">Cash on Delivery (COD)</span>
                            <span class="text-xs text-slate-500">Pay in cash when your order arrives at your door.</span>
                        </div>
                    </div>
                </label>
            </div>
        </div>

        <!-- Order Summary Sidebar -->
        <div class="h-fit space-y-6">
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-5">
                <h3 class="font-bold text-slate-900 text-sm pb-3 border-b border-slate-100">
                    Order Summary ({{ $cart->items->count() }} {{ Str::plural('item', $cart->items->count()) }})
                </h3>

                <div class="space-y-3 max-h-56 overflow-y-auto pr-1">
                    @foreach($cart->items as $item)
                        <div class="flex items-center justify-between text-xs gap-2">
                            <div class="flex items-center space-x-2">
                                <div class="w-10 h-10 rounded bg-slate-100 border border-slate-200 overflow-hidden flex-shrink-0">
                                    <img src="{{ $item->product->primaryImage->url }}" class="w-full h-full object-cover">
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-900 line-clamp-1">{{ $item->product->name }}</h4>
                                    <span class="text-[10px] text-slate-500">Qty: {{ $item->quantity }}</span>
                                </div>
                            </div>
                            <span class="font-bold text-slate-900 flex-shrink-0">${{ number_format($item->subtotal, 2) }}</span>
                        </div>
                    @endforeach
                </div>

                <div class="pt-3 border-t border-slate-100 space-y-2 text-xs font-medium text-slate-600">
                    <div class="flex justify-between">
                        <span>Subtotal</span>
                        <span class="font-bold text-slate-900">${{ number_format($summary['subtotal'], 2) }}</span>
                    </div>
                    @if($summary['discount'] > 0)
                        <div class="flex justify-between text-emerald-600">
                            <span>Discount ({{ $cart->coupon_code }})</span>
                            <span class="font-bold">-${{ number_format($summary['discount'], 2) }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between">
                        <span>Tax (8%)</span>
                        <span class="font-bold text-slate-900">${{ number_format($summary['tax'], 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Shipping</span>
                        <span class="font-bold text-slate-900">
                            @if($summary['shipping'] == 0)
                                <span class="text-emerald-600 font-extrabold uppercase text-[10px]">FREE</span>
                            @else
                                ${{ number_format($summary['shipping'], 2) }}
                            @endif
                        </span>
                    </div>
                </div>

                <div class="pt-3 border-t border-slate-100 flex justify-between items-baseline">
                    <span class="text-sm font-bold text-slate-900">Total Payable (COD)</span>
                    <span class="text-2xl font-black text-slate-900">${{ number_format($summary['total'], 2) }}</span>
                </div>

                <button type="submit"
                    class="w-full bg-emerald-600 text-white font-extrabold text-sm py-4 rounded-xl hover:bg-emerald-700 transition shadow-lg flex items-center justify-center space-x-2">
                    <i class="fa-solid fa-money-bill-wave"></i>
                    <span>Place Order &mdash; Pay on Delivery</span>
                </button>

                <div class="text-center text-[10px] text-slate-400 leading-tight">
                    <i class="fa-solid fa-lock mr-1"></i>
                    Your address and order details are secure and encrypted.
                </div>
            </div>

            <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 text-xs text-amber-800 flex items-start space-x-2">
                <i class="fa-solid fa-circle-info text-amber-500 mt-0.5"></i>
                <span>
                    <strong>Cash on Delivery:</strong> Please have the exact amount ready when our delivery agent arrives.
                    You can track your order from the <a href="{{ route('orders.index') }}" class="underline font-semibold">My Orders</a> page.
                </span>
            </div>
        </div>
    </form>
</div>
@endsection
