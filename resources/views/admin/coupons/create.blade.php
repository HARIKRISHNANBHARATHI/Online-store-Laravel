@extends('layouts.admin')

@section('title', 'Add Coupon')

@section('content')
<div class="max-w-xl mx-auto space-y-6">
    <div class="flex justify-between items-center">
        <h2 class="text-xl font-bold text-slate-900">Create Coupon</h2>
        <a href="{{ route('admin.coupons.index') }}" class="text-xs text-indigo-600 font-bold hover:underline">Back to List</a>
    </div>

    <form action="{{ route('admin.coupons.store') }}" method="POST" class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200 shadow-sm space-y-4">
        @csrf

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Coupon Code</label>
                <input type="text" name="code" value="{{ old('code') }}" placeholder="e.g. SAVE20" required class="w-full text-xs uppercase font-mono rounded-lg border-slate-300">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Discount Type</label>
                <select name="type" required class="w-full text-xs rounded-lg border-slate-300">
                    <option value="fixed">Fixed Amount ($)</option>
                    <option value="percentage">Percentage (%)</option>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Discount Value</label>
                <input type="number" step="0.01" name="value" value="{{ old('value') }}" required class="w-full text-xs rounded-lg border-slate-300">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Min Spend ($)</label>
                <input type="number" step="0.01" name="min_spend" value="{{ old('min_spend') }}" placeholder="Optional" class="w-full text-xs rounded-lg border-slate-300">
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Usage Limit</label>
                <input type="number" name="usage_limit" value="{{ old('usage_limit') }}" placeholder="Optional (e.g. 100)" class="w-full text-xs rounded-lg border-slate-300">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Expires At</label>
                <input type="date" name="expires_at" value="{{ old('expires_at') }}" class="w-full text-xs rounded-lg border-slate-300">
            </div>
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Status</label>
            <select name="status" class="w-full text-xs rounded-lg border-slate-300">
                <option value="1">Active</option>
                <option value="0">Disabled</option>
            </select>
        </div>

        <div class="pt-4 flex justify-end space-x-3">
            <a href="{{ route('admin.coupons.index') }}" class="px-4 py-2.5 text-xs font-semibold text-slate-600 hover:underline">Cancel</a>
            <button type="submit" class="bg-indigo-600 text-white font-bold text-xs px-6 py-2.5 rounded-xl hover:bg-indigo-700 transition">Save Coupon</button>
        </div>
    </form>
</div>
@endsection
