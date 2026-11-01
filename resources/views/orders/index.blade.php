@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="border-b border-slate-200 pb-4">
        <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">My Order History</h1>
    </div>

    @if($orders->isEmpty())
        <div class="bg-white rounded-2xl p-12 text-center border border-slate-200 shadow-sm space-y-4">
            <i class="fa-solid fa-box text-5xl text-slate-300"></i>
            <h2 class="text-xl font-bold text-slate-800">No orders placed yet</h2>
            <p class="text-xs text-slate-500">Your order history will appear here once you make a purchase.</p>
            <div>
                <a href="{{ route('products.index') }}" class="inline-block bg-indigo-600 text-white font-bold text-xs px-6 py-3 rounded-xl hover:bg-indigo-700 transition">
                    Start Shopping
                </a>
            </div>
        </div>
    @else
        <div class="space-y-4">
            @foreach($orders as $order)
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between border-b border-slate-100 pb-4 gap-2">
                        <div>
                            <span class="text-xs text-slate-400 font-mono">Order #{{ $order->order_number }}</span>
                            <p class="text-xs text-slate-500 mt-0.5">Placed on {{ $order->created_at->format('M d, Y') }}</p>
                        </div>
                        <div class="flex items-center space-x-3">
                            <span class="text-xs font-bold px-2.5 py-1 rounded-full capitalize {{ $order->status === 'delivered' ? 'bg-emerald-100 text-emerald-800' : 'bg-indigo-100 text-indigo-800' }}">
                                {{ $order->status }}
                            </span>
                            <span class="text-xs font-bold px-2.5 py-1 rounded-full capitalize {{ $order->payment_status === 'paid' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                {{ $order->payment_status }}
                            </span>
                            <a href="{{ route('orders.show', $order->id) }}" class="text-xs font-bold text-indigo-600 hover:underline">
                                View Details <i class="fa-solid fa-chevron-right text-[10px] ml-1"></i>
                            </a>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                        <div class="flex space-x-2 overflow-x-auto">
                            @foreach($order->items as $item)
                                <div class="w-12 h-12 rounded bg-slate-100 border border-slate-200 overflow-hidden flex-shrink-0" title="{{ $item->product_name }}">
                                    <img src="{{ $item->product->primaryImage->url ?? 'https://via.placeholder.com/150' }}" class="w-full h-full object-cover">
                                </div>
                            @endforeach
                        </div>
                        <div class="text-right w-full sm:w-auto">
                            <span class="text-xs text-slate-500">Total Amount:</span>
                            <span class="text-base font-extrabold text-slate-900 ml-1">${{ number_format($order->total, 2) }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="pt-4">
            {{ $orders->links() }}
        </div>
    @endif
</div>
@endsection
