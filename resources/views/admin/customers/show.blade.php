@extends('layouts.admin')

@section('title', 'Customer Profile')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-xl font-bold text-slate-900">{{ $customer->name }}</h2>
            <p class="text-xs text-slate-500 font-mono">{{ $customer->email }}</p>
        </div>
        <a href="{{ route('admin.customers.index') }}" class="text-xs text-indigo-600 font-bold hover:underline">Back to Customers</a>
    </div>

    <!-- Customer Details & Order History -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden p-6 space-y-4">
        <h3 class="font-bold text-slate-900 text-sm border-b border-slate-100 pb-3">Order History</h3>

        @if($customer->orders->isEmpty())
            <p class="text-xs text-slate-500 italic">No orders placed by this customer yet.</p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-slate-50 text-slate-500 font-bold uppercase border-b border-slate-100">
                            <th class="py-3 px-4">Order #</th>
                            <th class="py-3 px-4">Date</th>
                            <th class="py-3 px-4">Total</th>
                            <th class="py-3 px-4">Fulfillment</th>
                            <th class="py-3 px-4">Payment</th>
                            <th class="py-3 px-4 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($customer->orders as $ord)
                            <tr class="hover:bg-slate-50/50">
                                <td class="py-3 px-4 font-mono font-bold text-slate-900">#{{ $ord->order_number }}</td>
                                <td class="py-3 px-4 text-slate-500">{{ $ord->created_at->format('M d, Y') }}</td>
                                <td class="py-3 px-4 font-extrabold text-slate-900">${{ number_format($ord->total, 2) }}</td>
                                <td class="py-3 px-4">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold capitalize bg-indigo-100 text-indigo-800">
                                        {{ $ord->status }}
                                    </span>
                                </td>
                                <td class="py-3 px-4">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold capitalize bg-emerald-100 text-emerald-800">
                                        {{ $ord->payment_status }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-right">
                                    <a href="{{ route('admin.orders.show', $ord->id) }}" class="text-indigo-600 font-bold hover:underline">View</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
