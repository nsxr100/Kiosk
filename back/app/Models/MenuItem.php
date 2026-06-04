<?php

namespace App\Models;

use App\Support\MenuImageData;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MenuItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'description',
        'base_price',
        'image_url',
        'image_data_url',
        'order',
        'is_active',
    ];

    protected $casts = [
        'base_price' => 'decimal:2',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $hidden = [
        'image_data_url',
    ];

    protected static function booted(): void
    {
        static::saved(function (MenuItem $menuItem): void {
            if (! $menuItem->image_url) {
                return;
            }

            if ($menuItem->image_data_url && ! $menuItem->wasChanged('image_url')) {
                return;
            }

            $dataUrl = MenuImageData::fromPublicPath($menuItem->image_url);

            if ($dataUrl) {
                $menuItem->forceFill([
                    'image_data_url' => $dataUrl,
                ])->saveQuietly();
            }
        });
    }

    /**
     * Get the category of the menu item.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the variants of the menu item.
     */
    public function variants(): HasMany
    {
        return $this->hasMany(MenuVariant::class);
    }
}
