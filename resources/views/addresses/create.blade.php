@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="border-b border-slate-200 pb-4">
        <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Add New Address</h1>
    </div>

    <form action="{{ route('addresses.store') }}" method="POST" class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200 shadow-sm space-y-4">
        @csrf

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">First Name</label>
                <input type="text" name="first_name" value="{{ old('first_name') }}" required class="w-full text-xs rounded-lg border-slate-300">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Last Name</label>
                <input type="text" name="last_name" value="{{ old('last_name') }}" required class="w-full text-xs rounded-lg border-slate-300">
            </div>
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Address Line 1</label>
            <input type="text" name="address_line_1" value="{{ old('address_line_1') }}" required class="w-full text-xs rounded-lg border-slate-300">
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Address Line 2 (Optional)</label>
            <input type="text" name="address_line_2" value="{{ old('address_line_2') }}" class="w-full text-xs rounded-lg border-slate-300">
        </div>

        <div class="grid grid-cols-3 gap-4">
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">City</label>
                <input type="text" name="city" value="{{ old('city') }}" required class="w-full text-xs rounded-lg border-slate-300">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">State / Province</label>
                <input type="text" name="state" value="{{ old('state') }}" required class="w-full text-xs rounded-lg border-slate-300">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Postal Code</label>
                <input type="text" name="postal_code" value="{{ old('postal_code') }}" required class="w-full text-xs rounded-lg border-slate-300">
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Country</label>
                <input type="text" name="country" value="{{ old('country', 'United States') }}" required class="w-full text-xs rounded-lg border-slate-300">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Phone Number</label>
                <input type="text" name="phone" value="{{ old('phone') }}" required class="w-full text-xs rounded-lg border-slate-300">
            </div>
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Address Type</label>
            <select name="type" class="w-full text-xs rounded-lg border-slate-300">
                <option value="both">Shipping & Billing</option>
                <option value="shipping">Shipping Only</option>
                <option value="billing">Billing Only</option>
            </select>
        </div>

        <div class="flex items-center space-x-2 pt-2">
            <input type="checkbox" name="is_default" value="1" id="is_default" class="rounded text-indigo-600">
            <label for="is_default" class="text-xs font-medium text-slate-700">Set as default address</label>
        </div>

        <div class="pt-4 flex justify-end space-x-3">
            <a href="{{ route('addresses.index') }}" class="px-4 py-2.5 text-xs font-semibold text-slate-600 hover:underline">Cancel</a>
            <button type="submit" class="bg-indigo-600 text-white font-bold text-xs px-6 py-2.5 rounded-xl hover:bg-indigo-700 transition">Save Address</button>
        </div>
    </form>
</div>
@endsection
