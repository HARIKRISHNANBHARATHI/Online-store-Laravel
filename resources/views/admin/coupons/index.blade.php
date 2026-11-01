@extends('layouts.admin')

@section('title', 'Coupons Management')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h2 class="text-xl font-bold text-slate-900">Promotional Coupons</h2>
        <a href="{{ route('admin.coupons.create') }}" class="bg-indigo-600 text-white font-bold text-xs px-4 py-2.5 rounded-xl hover:bg-indigo-700 transition">
            <i class="fa-solid fa-plus mr-1"></i> Add Coupon
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <table class="w-full text-left border-collapse text-xs">
            <thead>
                <tr class="bg-slate-50 text-slate-500 font-bold uppercase border-b border-slate-100">
                    <th class="py-3 px-4">Code</th>
                    <th class="py-3 px-4">Type</th>
                    <th class="py-3 px-4">Discount Value</th>
                    <th class="py-3 px-4">Min Spend</th>
                    <th class="py-3 px-4">Uses / Limit</th>
                    <th class="py-3 px-4">Expires</th>
                    <th class="py-3 px-4">Status</th>
                    <th class="py-3 px-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($coupons as $coup)
                    <tr class="hover:bg-slate-50/50">
                        <td class="py-3 px-4 font-mono font-bold text-slate-900 uppercase">{{ $coup->code }}</td>
                        <td class="py-3 px-4 text-slate-600 capitalize">{{ $coup->type }}</td>
                        <td class="py-3 px-4 font-bold text-slate-900">
                            {{ $coup->type === 'fixed' ? '$' . number_format($coup->value, 2) : $coup->value . '%' }}
                        </td>
                        <td class="py-3 px-4 text-slate-600">${{ number_format($coup->min_spend ?? 0, 2) }}</td>
                        <td class="py-3 px-4 text-slate-600">{{ $coup->used_count }} / {{ $coup->usage_limit ?? '&infin;' }}</td>
                        <td class="py-3 px-4 text-slate-500">{{ $coup->expires_at ? $coup->expires_at->format('M d, Y') : 'Never' }}</td>
                        <td class="py-3 px-4">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $coup->status ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-600' }}">
                                {{ $coup->status ? 'Active' : 'Disabled' }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-right space-x-2">
                            <a href="{{ route('admin.coupons.edit', $coup->id) }}" class="text-indigo-600 font-bold hover:underline">Edit</a>
                            <form action="{{ route('admin.coupons.destroy', $coup->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete coupon?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-rose-600 font-bold hover:underline">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="p-4 border-t border-slate-100">
            {{ $coupons->links() }}
        </div>
    </div>
</div>
@endsection
