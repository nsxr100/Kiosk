<?php

namespace App\Http\Controllers;

use App\Models\MenuVariant;
use Illuminate\Http\JsonResponse;

class MenuVariantController extends Controller
{
    /**
     * Get all variants for a menu item.
     */
    public function index(int $menuItemId): JsonResponse
    {
        $variants = MenuVariant::where('menu_item_id', $menuItemId)
            ->where('is_active', true)
            ->orderBy('order')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $variants,
        ]);
    }

    /**
     * Get a specific variant.
     */
    public function show(MenuVariant $menuVariant): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $menuVariant,
        ]);
    }

    /**
     * Create a new variant.
     */
    public function store(int $menuItemId): JsonResponse
    {
        $validated = request()->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['menu_item_id'] = $menuItemId;

        $variant = MenuVariant::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Variant created successfully',
            'data' => $variant,
        ], 201);
    }

    /**
     * Update a variant.
     */
    public function update(int $menuItemId, MenuVariant $menuVariant): JsonResponse
    {
        $validated = request()->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['menu_item_id'] = $menuItemId;

        $menuVariant->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Variant updated successfully',
            'data' => $menuVariant,
        ]);
    }

    /**
     * Delete a variant.
     */
    public function destroy(MenuVariant $menuVariant): JsonResponse
    {
        $menuVariant->delete();

        return response()->json([
            'success' => true,
            'message' => 'Variant deleted successfully',
        ]);
    }
}
