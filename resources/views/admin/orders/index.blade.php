@extends('layouts.admin')

@section('title', 'Orders Management')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h2 class="text-xl font-bold text-slate-900">All Orders</h2>
    </div>

    <!-- Filters -->
    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
        <form action="{{ route('admin.orders.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-4 gap-4">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Order # or Customer..." class="text-xs rounded-lg border-slate-300">
            <select name="status" class="text-xs rounded-lg border-slate-300">
                <option value="">All Fulfillment Statuses</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Shipped</option>
                <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
            <select name="payment_status" class="text-xs rounded-lg border-slate-300">
                <option value="">All Payment Statuses</option>
                <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Paid</option>
                <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="failed" {{ request('payment_status') == 'failed' ? 'selected' : '' }}>Failed</option>
            </select>
            <button type="submit" class="bg-slate-900 text-white text-xs font-bold py-2 rounded-lg hover:bg-slate-800 transition">
                Filter Orders
            </button>
        </form>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50 text-slate-500 font-bold uppercase border-b border-slate-100">
                        <th class="py-3 px-4">Order #</th>
                        <th class="py-3 px-4">Customer</th>
                        <th class="py-3 px-4">Date</th>
                        <th class="py-3 px-4">Total</th>
                        <th class="py-3 px-4">Fulfillment</th>
                        <th class="py-3 px-4">Payment</th>
                        <th class="py-3 px-4 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($orders as $ord)
                        <tr class="hover:bg-slate-50/50">
                            <td class="py-3 px-4 font-mono font-bold text-slate-900">#{{ $ord->order_number }}</td>
                            <td class="py-3 px-4 text-slate-700">
                                <strong>{{ $ord->user->name ?? 'Guest' }}</strong><br>
                                <span class="text-[10px] text-slate-400">{{ $ord->user->email ?? '' }}</span>
                            </td>
                            <td class="py-3 px-4 text-slate-500">{{ $ord->created_at->format('M d, Y') }}</td>
                            <td class="py-3 px-4 font-extrabold text-slate-900">${{ number_format($ord->total, 2) }}</td>
                            <td class="py-3 px-4">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold capitalize {{ $ord->status === 'delivered' ? 'bg-emerald-100 text-emerald-800' : 'bg-indigo-100 text-indigo-800' }}">
                                    {{ $ord->status }}
                                </span>
                            </td>
                            <td class="py-3 px-4">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold capitalize {{ $ord->payment_status === 'paid' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                    {{ $ord->payment_status }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-right">
                                <a href="{{ route('admin.orders.show', $ord->id) }}" class="text-indigo-600 font-bold hover:underline">View / Update</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-100">
            {{ $orders->links() }}
        </div>
    </div>
</div>
@endsection
