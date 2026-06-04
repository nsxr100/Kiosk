<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    /**
     * Get all categories with their menu items.
     */
    public function index(): JsonResponse
    {
        $categories = Category::with([
                'menuItems' => fn ($query) => $query->select('id', 'category_id', 'name', 'description', 'base_price', 'image_url', 'order', 'is_active', 'created_at', 'updated_at'),
                'menuItems.variants',
            ])
            ->orderBy('order')
            ->get();

        $this->attachMenuImageUrls($categories);

        return response()->json([
            'success' => true,
            'data' => $categories,
        ]);
    }

    /**
     * Get a specific category with its menu items.
     */
    public function show(Category $category): JsonResponse
    {
        $category->load([
            'menuItems' => fn ($query) => $query->select('id', 'category_id', 'name', 'description', 'base_price', 'image_url', 'order', 'is_active', 'created_at', 'updated_at'),
            'menuItems.variants',
        ]);
        $this->attachMenuImageUrls(collect([$category]));

        return response()->json([
            'success' => true,
            'data' => $category,
        ]);
    }

    /**
     * Create a new category.
     */
    public function store(): JsonResponse
    {
        $validated = request()->validate([
            'name' => 'required|unique:categories|string',
            'slug' => 'required|unique:categories|string',
            'description' => 'nullable|string',
            'order' => 'nullable|integer',
        ]);

        $category = Category::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Category created successfully',
            'data' => $category,
        ], 201);
    }

    /**
     * Update a category.
     */
    public function update(Category $category): JsonResponse
    {
        $validated = request()->validate([
            'name' => 'required|unique:categories,name,' . $category->id . '|string',
            'slug' => 'required|unique:categories,slug,' . $category->id . '|string',
            'description' => 'nullable|string',
            'order' => 'nullable|integer',
        ]);

        $category->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Category updated successfully',
            'data' => $category,
        ]);
    }

    /**
     * Delete a category.
     */
    public function destroy(Category $category): JsonResponse
    {
        $category->delete();

        return response()->json([
            'success' => true,
            'message' => 'Category deleted successfully',
        ]);
    }

    private function attachMenuImageUrls($categories): void
    {
        foreach ($categories as $category) {
            foreach ($category->menuItems as $item) {
                $version = $item->updated_at?->timestamp ?? time();
                $base = str_replace('http://backend-production-6121.up.railway.app', 'https://backend-production-6121.up.railway.app', request()->getSchemeAndHttpHost());
                $item->image_full_url = $item->image_url
                    ? $base . '/api/menu-image-data/' . $item->id . '?v=' . $version
                    : null;
            }
        }
    }
}
