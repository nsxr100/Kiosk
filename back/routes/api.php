<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MenuItemController;
use App\Http\Controllers\MenuVariantController;
use App\Http\Controllers\OrderController;
use App\Models\Category;
use App\Models\MenuItem;
use App\Models\MenuVariant;
use App\Support\MenuImageData;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Route;

Route::get('menu-version', function () {
    return response()->json([
        'version' => implode('|', [
            Category::count(),
            Category::max('updated_at'),
            MenuItem::count(),
            MenuItem::max('updated_at'),
            MenuVariant::count(),
            MenuVariant::max('updated_at'),
        ]),
    ])->header('Cache-Control', 'no-store, no-cache, must-revalidate');
});

// Category routes
Route::prefix('categories')->group(function () {
    Route::get('/', [CategoryController::class, 'index']);
    Route::post('/', [CategoryController::class, 'store']);
    Route::get('{category}', [CategoryController::class, 'show']);
    Route::put('{category}', [CategoryController::class, 'update']);
    Route::delete('{category}', [CategoryController::class, 'destroy']);
});

// Menu Item routes
Route::prefix('menu-items')->group(function () {
    Route::get('/', [MenuItemController::class, 'index']);
    Route::post('/', [MenuItemController::class, 'store']);
    Route::get('search', [MenuItemController::class, 'search']);
    Route::get('category/{categoryId}', [MenuItemController::class, 'byCategory']);
    Route::get('{menuItem}', [MenuItemController::class, 'show']);
    Route::put('{menuItem}', [MenuItemController::class, 'update']);
    Route::delete('{menuItem}', [MenuItemController::class, 'destroy']);
});

// Menu Variant routes
Route::prefix('menu-items/{menuItemId}/variants')->group(function () {
    Route::get('/', [MenuVariantController::class, 'index']);
    Route::post('/', [MenuVariantController::class, 'store']);
    Route::get('{menuVariant}', [MenuVariantController::class, 'show']);
    Route::put('{menuVariant}', [MenuVariantController::class, 'update']);
    Route::delete('{menuVariant}', [MenuVariantController::class, 'destroy']);
});

// Order routes
Route::prefix('orders')->group(function () {
    Route::get('/', [OrderController::class, 'index']);
    Route::post('/', [OrderController::class, 'store']);
    Route::get('{order}', [OrderController::class, 'show']);
    Route::post('{order}/pay', [OrderController::class, 'pay']);
    Route::patch('{order}/status', [OrderController::class, 'updateStatus']);
});

Route::get('menu-image/{path}', function (string $path) {
    $menuItem = MenuItem::where('image_url', $path)->first();

    if ($image = MenuImageData::bytesFromDataUrl($menuItem?->image_data_url)) {
        return response($image['bytes'], 200)
            ->header('Content-Type', $image['mime'])
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Cache-Control', 'public, max-age=31536000');
    }

    if (Storage::disk('public')->exists($path)) {
        $bytes = Storage::disk('public')->get($path);
        $info = getimagesizefromstring($bytes);

        abort_unless($info, 404);

        if ($menuItem && ! $menuItem->image_data_url) {
            $dataUrl = MenuImageData::fromPublicPath($path);

            if ($dataUrl) {
                $menuItem->forceFill([
                    'image_data_url' => $dataUrl,
                ])->saveQuietly();
            }
        }

        return response($bytes, 200)
            ->header('Content-Type', $info['mime'] ?? 'image/jpeg')
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Cache-Control', 'public, max-age=31536000');
    }

    abort(404);
})->where('path', '.*');

Route::get('menu-image-data/{menuItem}', function (MenuItem $menuItem) {
    if (! $menuItem->image_data_url && $menuItem->image_url && Storage::disk('public')->exists($menuItem->image_url)) {
        $dataUrl = MenuImageData::fromPublicPath($menuItem->image_url);

        if ($dataUrl) {
            $menuItem->forceFill([
                'image_data_url' => $dataUrl,
            ])->saveQuietly();
        }
    }

    $image = MenuImageData::bytesFromDataUrl($menuItem->image_data_url);
    abort_unless($image, 404);

    return response($image['bytes'], 200)
        ->header('Content-Type', $image['mime'])
        ->header('Access-Control-Allow-Origin', '*')
        ->header('Cache-Control', 'public, max-age=31536000');
});
