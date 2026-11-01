@extends('layouts.admin')

@section('title', 'Order Details')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-xl font-bold text-slate-900">Order #{{ $order->order_number }}</h2>
            <p class="text-xs text-slate-500">Placed on {{ $order->created_at->format('M d, Y \a\t h:i A') }}</p>
        </div>
        <a href="{{ route('admin.orders.index') }}" class="text-xs text-indigo-600 font-bold hover:underline">Back to Orders</a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Order Items & Status Update Form -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Status Update Box -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
                <h3 class="font-bold text-slate-900 text-sm border-b border-slate-100 pb-3">Update Order Status</h3>
                <form action="{{ route('admin.orders.update-status', $order->id) }}" method="POST" class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    @csrf
                    @method('PATCH')

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Fulfillment Status</label>
                        <select name="status" class="w-full text-xs rounded-lg border-slate-300">
                            <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing</option>
                            <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Shipped</option>
                            <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                            <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Payment Status</label>
                        <select name="payment_status" class="w-full text-xs rounded-lg border-slate-300">
                            <option value="paid" {{ $order->payment_status === 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="pending" {{ $order->payment_status === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="failed" {{ $order->payment_status === 'failed' ? 'selected' : '' }}>Failed</option>
                            <option value="refunded" {{ $order->payment_status === 'refunded' ? 'selected' : '' }}>Refunded</option>
                        </select>
                    </div>

                    <div class="flex items-end">
                        <button type="submit" class="w-full bg-indigo-600 text-white font-bold text-xs py-2.5 rounded-lg hover:bg-indigo-700 transition">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>

            <!-- Items List -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-4 border-b border-slate-100 font-bold text-slate-900 text-sm">
                    Items List
                </div>
                <div class="divide-y divide-slate-100">
                    @foreach($order->items as $item)
                        <div class="p-4 flex items-center justify-between text-xs">
                            <div class="flex items-center space-x-3">
                                <div class="w-12 h-12 rounded bg-slate-100 border border-slate-200 overflow-hidden">
                                    <img src="{{ $item->product->primaryImage->url ?? 'https://via.placeholder.com/150' }}" class="w-full h-full object-cover">
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-900">{{ $item->product_name }}</h4>
                                    <span class="text-slate-500">Qty: {{ $item->quantity }} &times; ${{ number_format($item->price, 2) }}</span>
                                </div>
                            </div>
                            <span class="font-extrabold text-slate-900">${{ number_format($item->subtotal, 2) }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Sidebar Customer & Address Info -->
        <div class="space-y-6">
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-3 text-xs">
                <h3 class="font-bold text-slate-900 text-sm border-b border-slate-100 pb-2">Customer Info</h3>
                <p><strong class="text-slate-900">Name:</strong> {{ $order->user->name ?? 'N/A' }}</p>
                <p><strong class="text-slate-900">Email:</strong> {{ $order->user->email ?? 'N/A' }}</p>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-3 text-xs">
                <h3 class="font-bold text-slate-900 text-sm border-b border-slate-100 pb-2">Shipping Address</h3>
                @if($order->shipping_address_data)
                    <p class="text-slate-600 leading-relaxed">
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
