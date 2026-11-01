@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center border-b border-slate-200 pb-4">
        <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Saved Addresses</h1>
        <a href="{{ route('addresses.create') }}" class="bg-indigo-600 text-white font-bold text-xs px-4 py-2 rounded-xl hover:bg-indigo-700 transition">
            <i class="fa-solid fa-plus mr-1"></i> Add New Address
        </a>
    </div>

    @if($addresses->isEmpty())
        <div class="bg-white rounded-2xl p-12 text-center border border-slate-200 shadow-sm space-y-3">
            <i class="fa-solid fa-map-location-dot text-4xl text-slate-300"></i>
            <h3 class="text-lg font-bold text-slate-800">No saved addresses</h3>
            <p class="text-xs text-slate-500">Add shipping and billing addresses for faster checkout.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($addresses as $addr)
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-3 flex flex-col justify-between">
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <h3 class="font-bold text-slate-900 text-sm">{{ $addr->full_name }}</h3>
                            @if($addr->is_default)
                                <span class="bg-indigo-50 text-indigo-700 text-[10px] font-bold px-2.5 py-0.5 rounded-full border border-indigo-200">
                                    Default
                                </span>
                            @endif
                        </div>
                        <p class="text-xs text-slate-600 leading-relaxed">
                            {{ $addr->address_line_1 }}@if($addr->address_line_2), {{ $addr->address_line_2 }}@endif<br>
                            {{ $addr->city }}, {{ $addr->state }} {{ $addr->postal_code }}<br>
                            {{ $addr->country }}<br>
                            Phone: {{ $addr->phone }}
                        </p>
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex items-center justify-between text-xs">
                        <div class="flex space-x-3">
                            <a href="{{ route('addresses.edit', $addr->id) }}" class="text-indigo-600 font-semibold hover:underline">Edit</a>
                            <form action="{{ route('addresses.destroy', $addr->id) }}" method="POST" onsubmit="return confirm('Delete address?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-rose-600 font-semibold hover:underline">Delete</button>
                            </form>
                        </div>
                        @if(!$addr->is_default)
                            <form action="{{ route('addresses.default', $addr->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="text-slate-500 hover:text-slate-900 font-semibold">Make Default</button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
