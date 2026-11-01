<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Support\Str;

class AdminCategoryController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Category::class);
        $categories = Category::withCount('products')->latest()->paginate(15);
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        $this->authorize('create', Category::class);
        return view('admin.categories.create');
    }

    public function store(StoreCategoryRequest $request)
    {
        $this->authorize('create', Category::class);

        $data = $request->validated();
        $data['slug'] = Str::slug($data['name']);

        Category::create($data);

        return redirect()->route('admin.categories.index')->with('success', 'Category created successfully.');
    }

    public function edit(Category $category)
    {
        $this->authorize('update', $category);
        return view('admin.categories.edit', compact('category'));
    }

    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $this->authorize('update', $category);

        $data = $request->validated();
        if ($data['name'] !== $category->name) {
            $data['slug'] = Str::slug($data['name']);
        }

        $category->update($data);

        return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        $this->authorize('delete', $category);

        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', 'Category deleted successfully.');
    }
}
