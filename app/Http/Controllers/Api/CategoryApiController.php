<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\JsonResponse;

class CategoryApiController extends Controller
{
    public function index(): JsonResponse
    {
        $categories = Category::active()
            ->ordered()
            ->withCount('activeProducts')
            ->get();

        return response()->json(CategoryResource::collection($categories));
    }

    public function show(Category $category): JsonResponse
    {
        if (!$category->is_active) {
            return response()->json(['message' => 'Category not found.'], 404);
        }

        $category->loadCount('activeProducts');
        return response()->json(new CategoryResource($category));
    }
}
