@extends('layouts.admin')

@section('title', 'Customers Management')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h2 class="text-xl font-bold text-slate-900">Registered Customers</h2>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <table class="w-full text-left border-collapse text-xs">
            <thead>
                <tr class="bg-slate-50 text-slate-500 font-bold uppercase border-b border-slate-100">
                    <th class="py-3 px-4">Customer Name</th>
                    <th class="py-3 px-4">Email</th>
                    <th class="py-3 px-4">Role</th>
                    <th class="py-3 px-4">Total Orders</th>
                    <th class="py-3 px-4">Joined Date</th>
                    <th class="py-3 px-4 text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($customers as $cust)
                    <tr class="hover:bg-slate-50/50">
                        <td class="py-3 px-4 font-bold text-slate-900">{{ $cust->name }}</td>
                        <td class="py-3 px-4 text-slate-600 font-mono">{{ $cust->email }}</td>
                        <td class="py-3 px-4">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $cust->role === 'admin' ? 'bg-rose-100 text-rose-800' : 'bg-slate-100 text-slate-700' }}">
                                {{ $cust->role }}
                            </span>
                        </td>
                        <td class="py-3 px-4 font-bold text-slate-900">{{ $cust->orders_count }}</td>
                        <td class="py-3 px-4 text-slate-500">{{ $cust->created_at->format('M d, Y') }}</td>
                        <td class="py-3 px-4 text-right">
                            <a href="{{ route('admin.customers.show', $cust->id) }}" class="text-indigo-600 font-bold hover:underline">View History</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="p-4 border-t border-slate-100">
            {{ $customers->links() }}
        </div>
    </div>
</div>
@endsection
