<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::where('status', true)->get();
        return CategoryResource::collection($categories);
    }

    public function show(string $identifier)
    {
        $category = Category::where('status', true)
            ->where(function ($q) use ($identifier) {
                if (is_numeric($identifier)) {
                    $q->where('id', $identifier);
                } else {
                    $q->where('slug', $identifier);
                }
            })->firstOrFail();

        return new CategoryResource($category);
    }
}
