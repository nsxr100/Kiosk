<?php

namespace App\Http\Controllers;

use App\Models\MenuItem;
use Illuminate\Http\JsonResponse;

class MenuItemController extends Controller
{
    /**
     * Get all menu items.
     */
    public function index(): JsonResponse
    {
        $menuItems = MenuItem::with('category', 'variants')
            ->select('id', 'category_id', 'name', 'description', 'base_price', 'image_url', 'order', 'is_active', 'created_at', 'updated_at')
            ->where('is_active', true)
            ->orderBy('order')
            ->get();

        $this->attachImageUrls($menuItems);

        return response()->json([
            'success' => true,
            'data' => $menuItems,
        ]);
    }

    /**
     * Get a specific menu item.
     */
    public function show(MenuItem $menuItem): JsonResponse
    {
        $menuItem->makeHidden('image_data_url');
        $menuItem->load('category', 'variants');
        $this->attachImageUrls(collect([$menuItem]));

        return response()->json([
            'success' => true,
            'data' => $menuItem,
        ]);
    }

    /**
     * Create a new menu item.
     */
    public function store(): JsonResponse
    {
        $validated = request()->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string',
            'description' => 'nullable|string',
            'base_price' => 'required|numeric|min:0',
            'image_url' => 'nullable|string',
            'image_data_url' => 'nullable|string',
            'order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        $menuItem = MenuItem::create($validated);
        $menuItem->load('category', 'variants');
        $this->attachImageUrls(collect([$menuItem]));

        return response()->json([
            'success' => true,
            'message' => 'Menu item created successfully',
            'data' => $menuItem,
        ], 201);
    }

    /**
     * Update a menu item.
     */
    public function update(MenuItem $menuItem): JsonResponse
    {
        $validated = request()->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string',
            'description' => 'nullable|string',
            'base_price' => 'required|numeric|min:0',
            'image_url' => 'nullable|string',
            'image_data_url' => 'nullable|string',
            'order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        $menuItem->update($validated);
        $menuItem->load('category', 'variants');
        $this->attachImageUrls(collect([$menuItem]));

        return response()->json([
            'success' => true,
            'message' => 'Menu item updated successfully',
            'data' => $menuItem,
        ]);
    }

    /**
     * Delete a menu item.
     */
    public function destroy(MenuItem $menuItem): JsonResponse
    {
        $menuItem->delete();

        return response()->json([
            'success' => true,
            'message' => 'Menu item deleted successfully',
        ]);
    }

    /**
     * Search menu items by category.
     */
    public function byCategory(int $categoryId): JsonResponse
    {
        $menuItems = MenuItem::where('category_id', $categoryId)
            ->select('id', 'category_id', 'name', 'description', 'base_price', 'image_url', 'order', 'is_active', 'created_at', 'updated_at')
            ->where('is_active', true)
            ->with('variants')
            ->orderBy('order')
            ->get();

        $this->attachImageUrls($menuItems);

        return response()->json([
            'success' => true,
            'data' => $menuItems,
        ]);
    }

    /**
     * Search menu items by name.
     */
    public function search(): JsonResponse
    {
        $query = request()->query('q');

        $menuItems = MenuItem::where('name', 'like', "%{$query}%")
            ->select('id', 'category_id', 'name', 'description', 'base_price', 'image_url', 'order', 'is_active', 'created_at', 'updated_at')
            ->where('is_active', true)
            ->with('category', 'variants')
            ->orderBy('order')
            ->get();

        $this->attachImageUrls($menuItems);

        return response()->json([
            'success' => true,
            'data' => $menuItems,
        ]);
    }
    
    private function attachImageUrls($menuItems): void
    {
        foreach ($menuItems as $item) {
            $version = $item->updated_at?->timestamp ?? time();
            $base = str_replace('http://backend-production-6121.up.railway.app', 'https://backend-production-6121.up.railway.app', request()->getSchemeAndHttpHost());
            $item->image_full_url = $item->image_url
                ? $base . '/api/menu-image-data/' . $item->id . '?v=' . $version
                : null;
        }
    }

}
