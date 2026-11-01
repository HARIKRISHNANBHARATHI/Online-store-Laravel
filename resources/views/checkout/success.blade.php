@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto space-y-8 py-6">
    <div class="bg-white p-8 sm:p-10 rounded-2xl border border-slate-200 shadow-sm text-center space-y-6">
        <!-- Success Icon -->
        <div class="w-20 h-20 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto text-4xl shadow-inner">
            <i class="fa-solid fa-check"></i>
        </div>

        <div>
            <span class="text-xs font-bold text-emerald-600 uppercase tracking-wider">Order Confirmed</span>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 mt-1 leading-snug">
                Thank you for your order!
            </h1>
            <p class="text-sm text-slate-500 mt-2">
                Order <span class="font-bold font-mono text-slate-900">#{{ $order->order_number }}</span> has been placed.
            </p>
        </div>

        <!-- COD Notice -->
        <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 text-left flex items-start space-x-3">
            <i class="fa-solid fa-money-bill-wave text-amber-500 text-lg mt-0.5"></i>
            <div>
                <p class="text-sm font-bold text-amber-800">Cash on Delivery</p>
                <p class="text-xs text-amber-700 mt-0.5 leading-relaxed">
                    Payment will be collected when your order is delivered.
                    Please have the exact amount of <strong>${{ number_format($order->total, 2) }}</strong> ready.
                </p>
            </div>
        </div>

        <!-- Order Summary Card -->
        <div class="bg-slate-50 p-6 rounded-xl border border-slate-200 text-left space-y-3 text-xs">
            <h3 class="font-bold text-slate-900 uppercase tracking-wider border-b border-slate-200 pb-2">
                Order Details
            </h3>
            <div class="flex justify-between">
                <span class="text-slate-600">Order Date</span>
                <span class="font-semibold text-slate-900">{{ $order->created_at->format('M d, Y \a\t h:i A') }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-600">Fulfillment Status</span>
                <span class="bg-indigo-100 text-indigo-700 font-bold px-2 py-0.5 rounded capitalize">{{ $order->status }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-600">Payment Method</span>
                <span class="bg-emerald-100 text-emerald-700 font-bold px-2 py-0.5 rounded uppercase">COD</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-600">Payment Status</span>
                <span class="bg-amber-100 text-amber-700 font-bold px-2 py-0.5 rounded capitalize">
                    Collect on Delivery
                </span>
            </div>
            <div class="flex justify-between pt-2 border-t border-slate-200 font-extrabold text-sm text-slate-900">
                <span>Total to Pay</span>
                <span>${{ number_format($order->total, 2) }}</span>
            </div>
        </div>

        <!-- CTA Buttons -->
        <div class="flex flex-col sm:flex-row justify-center gap-3 pt-2">
            <a href="{{ route('orders.show', $order->id) }}"
                class="bg-indigo-600 text-white font-bold text-xs px-6 py-3 rounded-xl hover:bg-indigo-700 transition shadow-md">
                <i class="fa-solid fa-receipt mr-1"></i> View Order Details
            </a>
            <a href="{{ route('products.index') }}"
                class="bg-slate-100 text-slate-700 font-bold text-xs px-6 py-3 rounded-xl hover:bg-slate-200 transition">
                <i class="fa-solid fa-store mr-1"></i> Continue Shopping
            </a>
        </div>
    </div>
</div>
@endsection
