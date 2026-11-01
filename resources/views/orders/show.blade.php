@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between border-b border-slate-200 pb-4">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Order #{{ $order->order_number }}</h1>
            <p class="text-xs text-slate-500 mt-1">Placed on {{ $order->created_at->format('M d, Y \a\t h:i A') }}</p>
        </div>
        <a href="{{ route('orders.index') }}" class="text-xs text-indigo-600 font-bold hover:underline">
            <i class="fa-solid fa-arrow-left mr-1"></i> Back to Orders
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Order Items Table -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-4 sm:p-6 border-b border-slate-100 font-bold text-slate-900 text-sm">
                Ordered Items ({{ $order->items->count() }})
            </div>
            <div class="divide-y divide-slate-100">
                @foreach($order->items as $item)
                    <div class="p-4 sm:p-6 flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="w-14 h-14 rounded bg-slate-100 border border-slate-200 overflow-hidden flex-shrink-0">
                                <img src="{{ $item->product->primaryImage->url ?? 'https://via.placeholder.com/150' }}" class="w-full h-full object-cover">
                            </div>
                            <div>
                                <h3 class="font-bold text-slate-900 text-sm">{{ $item->product_name }}</h3>
                                <p class="text-xs text-slate-500 mt-0.5">Quantity: {{ $item->quantity }} &times; ${{ number_format($item->price, 2) }}</p>
                            </div>
                        </div>
                        <span class="font-extrabold text-slate-900 text-sm">${{ number_format($item->subtotal, 2) }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Sidebar Details -->
        <div class="space-y-6">
            <!-- Breakdown Box -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
                <h3 class="font-bold text-slate-900 text-sm pb-3 border-b border-slate-100">Payment Breakdown</h3>
                <div class="space-y-2 text-xs font-medium text-slate-600">
                    <div class="flex justify-between">
                        <span>Subtotal</span>
                        <span class="font-bold text-slate-900">${{ number_format($order->subtotal, 2) }}</span>
                    </div>
                    @if($order->discount > 0)
                        <div class="flex justify-between text-emerald-600">
                            <span>Discount ({{ $order->coupon_code }})</span>
                            <span class="font-bold">-${{ number_format($order->discount, 2) }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between">
                        <span>Tax</span>
                        <span class="font-bold text-slate-900">${{ number_format($order->tax, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Shipping</span>
                        <span class="font-bold text-slate-900">${{ number_format($order->shipping, 2) }}</span>
                    </div>
                </div>
                <div class="pt-3 border-t border-slate-100 flex justify-between items-baseline font-black text-slate-900 text-base">
                    <span>Total</span>
                    <span>${{ number_format($order->total, 2) }}</span>
                </div>
            </div>

            <!-- Shipping Address Box -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-2">
                <h3 class="font-bold text-slate-900 text-sm pb-2 border-b border-slate-100">Shipping Address</h3>
                @if($order->shipping_address_data)
                    <p class="text-xs text-slate-600 leading-relaxed">
                        <strong class="text-slate-900">{{ $order->shipping_address_data['first_name'] ?? '' }} {{ $order->shipping_address_data['last_name'] ?? '' }}</strong><br>
                        {{ $order->shipping_address_data['address_line_1'] ?? '' }}<br>
                        {{ $order->shipping_address_data['city'] ?? '' }}, {{ $order->shipping_address_data['state'] ?? '' }} {{ $order->shipping_address_data['postal_code'] ?? '' }}<br>
                        Phone: {{ $order->shipping_address_data['phone'] ?? '' }}
                    </p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
