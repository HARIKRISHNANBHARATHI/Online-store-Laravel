@extends('layouts.admin')

@section('title', 'Sales & Analytics Reports')

@section('content')
<div class="space-y-8">
    <div class="flex justify-between items-center">
        <h2 class="text-xl font-bold text-slate-900">Sales Reports</h2>
    </div>

    <!-- Filter Form -->
    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
        <form action="{{ route('admin.reports.index') }}" method="GET" class="flex flex-wrap items-center gap-4">
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Start Date</label>
                <input type="date" name="start_date" value="{{ request('start_date', $startDate) }}" class="text-xs rounded-lg border-slate-300">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">End Date</label>
                <input type="date" name="end_date" value="{{ request('end_date', $endDate) }}" class="text-xs rounded-lg border-slate-300">
            </div>
            <div class="self-end">
                <button type="submit" class="bg-slate-900 text-white text-xs font-bold px-4 py-2 rounded-lg hover:bg-slate-800 transition">
                    Generate Report
                </button>
            </div>
        </form>
    </div>

    <!-- Summary Metrics -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Revenue</span>
            <h3 class="text-3xl font-black text-slate-900 mt-1">${{ number_format($totalRevenue, 2) }}</h3>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Orders</span>
            <h3 class="text-3xl font-black text-slate-900 mt-1">{{ number_format($totalOrders) }}</h3>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Average Order Value</span>
            <h3 class="text-3xl font-black text-slate-900 mt-1">${{ number_format($averageOrderValue, 2) }}</h3>
        </div>
    </div>

    <!-- Top Selling Products Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100 font-bold text-slate-900 text-sm">
            Top Selling Products
        </div>
        <table class="w-full text-left border-collapse text-xs">
            <thead>
                <tr class="bg-slate-50 text-slate-500 font-bold uppercase border-b border-slate-100">
                    <th class="py-3 px-4">Product Name</th>
                    <th class="py-3 px-4">SKU</th>
                    <th class="py-3 px-4">Units Sold</th>
                    <th class="py-3 px-4">Total Revenue Generated</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($topProducts as $item)
                    <tr class="hover:bg-slate-50/50">
                        <td class="py-3 px-4 font-bold text-slate-900">{{ $item->product_name }}</td>
                        <td class="py-3 px-4 font-mono text-slate-500">{{ $item->product->sku ?? 'N/A' }}</td>
                        <td class="py-3 px-4 font-bold text-slate-900">{{ $item->total_quantity }}</td>
                        <td class="py-3 px-4 font-extrabold text-emerald-600">${{ number_format($item->total_revenue, 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="p-6 text-center text-slate-500 italic">No sales recorded for this date range.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
