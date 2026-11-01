@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
<div class="space-y-8">
    <!-- Stat Counter Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Sales</span>
                <h3 class="text-2xl font-black text-slate-900 mt-1">${{ number_format($totalSales, 2) }}</h3>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-dollar-sign"></i>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Orders</span>
                <h3 class="text-2xl font-black text-slate-900 mt-1">{{ number_format($totalOrders) }}</h3>
            </div>
            <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-receipt"></i>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Customers</span>
                <h3 class="text-2xl font-black text-slate-900 mt-1">{{ number_format($totalCustomers) }}</h3>
            </div>
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-users"></i>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Active Products</span>
                <h3 class="text-2xl font-black text-slate-900 mt-1">{{ number_format($totalProducts) }}</h3>
            </div>
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-box-archive"></i>
            </div>
        </div>
    </div>

    <!-- Recent Orders & Low Stock Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Recent Orders Table -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                <h3 class="font-bold text-slate-900 text-sm">Recent Orders</h3>
                <a href="{{ route('admin.orders.index') }}" class="text-xs text-indigo-600 font-bold hover:underline">View All</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-slate-50 text-slate-500 font-bold uppercase border-b border-slate-100">
                            <th class="py-3 px-4">Order #</th>
                            <th class="py-3 px-4">Customer</th>
                            <th class="py-3 px-4">Total</th>
                            <th class="py-3 px-4">Status</th>
                            <th class="py-3 px-4">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($recentOrders as $order)
                            <tr class="hover:bg-slate-50/50">
                                <td class="py-3 px-4 font-mono font-bold text-slate-900">#{{ $order->order_number }}</td>
                                <td class="py-3 px-4 text-slate-700">{{ $order->user->name ?? 'Guest' }}</td>
                                <td class="py-3 px-4 font-bold text-slate-900">${{ number_format($order->total, 2) }}</td>
                                <td class="py-3 px-4">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold capitalize {{ $order->status === 'delivered' ? 'bg-emerald-100 text-emerald-800' : 'bg-indigo-100 text-indigo-800' }}">
                                        {{ $order->status }}
                                    </span>
                                </td>
                                <td class="py-3 px-4">
                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="text-indigo-600 font-bold hover:underline">View</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Low Stock Products List -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
            <h3 class="font-bold text-slate-900 text-sm pb-3 border-b border-slate-100 flex items-center justify-between">
                <span>Low Stock Alerts</span>
                <i class="fa-solid fa-triangle-exclamation text-amber-500 text-xs"></i>
            </h3>
            <div class="space-y-3">
                @forelse($lowStockProducts as $prod)
                    <div class="flex items-center justify-between text-xs p-2 rounded-lg bg-slate-50 border border-slate-100">
                        <div>
                            <h4 class="font-bold text-slate-900 line-clamp-1">{{ $prod->name }}</h4>
                            <span class="text-[10px] text-slate-400 font-mono">SKU: {{ $prod->sku }}</span>
                        </div>
                        <span class="bg-rose-100 text-rose-700 font-bold px-2 py-0.5 rounded text-[10px]">
                            {{ $prod->stock_quantity }} left
                        </span>
                    </div>
                @empty
                    <p class="text-xs text-slate-500 italic">All products are adequately stocked.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
