@extends('layouts.admin')

@section('title', 'Categories Management')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h2 class="text-xl font-bold text-slate-900">Categories</h2>
        <a href="{{ route('admin.categories.create') }}" class="bg-indigo-600 text-white font-bold text-xs px-4 py-2.5 rounded-xl hover:bg-indigo-700 transition">
            <i class="fa-solid fa-plus mr-1"></i> Add Category
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <table class="w-full text-left border-collapse text-xs">
            <thead>
                <tr class="bg-slate-50 text-slate-500 font-bold uppercase border-b border-slate-100">
                    <th class="py-3 px-4">Name</th>
                    <th class="py-3 px-4">Slug</th>
                    <th class="py-3 px-4">Products Count</th>
                    <th class="py-3 px-4">Status</th>
                    <th class="py-3 px-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($categories as $cat)
                    <tr class="hover:bg-slate-50/50">
                        <td class="py-3 px-4 font-bold text-slate-900">{{ $cat->name }}</td>
                        <td class="py-3 px-4 font-mono text-slate-500">{{ $cat->slug }}</td>
                        <td class="py-3 px-4 font-bold text-slate-900">{{ $cat->products_count }}</td>
                        <td class="py-3 px-4">
                            <span class="px-2 py-0.5 rounded font-bold text-[10px] {{ $cat->status ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-600' }}">
                                {{ $cat->status ? 'Active' : 'Disabled' }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-right space-x-2">
                            <a href="{{ route('admin.categories.edit', $cat->id) }}" class="text-indigo-600 font-bold hover:underline">Edit</a>
                            <form action="{{ route('admin.categories.destroy', $cat->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete category?')">
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
            {{ $categories->links() }}
        </div>
    </div>
</div>
@endsection
