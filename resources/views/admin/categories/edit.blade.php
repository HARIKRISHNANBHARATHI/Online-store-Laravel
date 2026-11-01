@extends('layouts.admin')

@section('title', 'Edit Category')

@section('content')
<div class="max-w-xl mx-auto space-y-6">
    <div class="flex justify-between items-center">
        <h2 class="text-xl font-bold text-slate-900">Edit Category</h2>
        <a href="{{ route('admin.categories.index') }}" class="text-xs text-indigo-600 font-bold hover:underline">Back to List</a>
    </div>

    <form action="{{ route('admin.categories.update', $category->id) }}" method="POST" class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200 shadow-sm space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Category Name</label>
            <input type="text" name="name" value="{{ old('name', $category->name) }}" required class="w-full text-xs rounded-lg border-slate-300">
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Description</label>
            <textarea name="description" rows="3" class="w-full text-xs rounded-lg border-slate-300">{{ old('description', $category->description) }}</textarea>
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Status</label>
            <select name="status" class="w-full text-xs rounded-lg border-slate-300">
                <option value="1" {{ $category->status ? 'selected' : '' }}>Active</option>
                <option value="0" {{ !$category->status ? 'selected' : '' }}>Disabled</option>
            </select>
        </div>

        <div class="pt-4 flex justify-end space-x-3">
            <a href="{{ route('admin.categories.index') }}" class="px-4 py-2.5 text-xs font-semibold text-slate-600 hover:underline">Cancel</a>
            <button type="submit" class="bg-indigo-600 text-white font-bold text-xs px-6 py-2.5 rounded-xl hover:bg-indigo-700 transition">Update Category</button>
        </div>
    </form>
</div>
@endsection
