<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'slug', 'description', 'parent_id', 'is_active', 'image'];

    protected $casts = [
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the image to display for the category
     */
    public function getDisplayImageAttribute(): ?string
    {
        // 1. Check if category has a custom image
        if ($this->image) {
            return asset('storage/' . $this->image);
        }

        // 2. Fallback: Get a random product image from this category or its subcategories
        $randomProduct = Product::where('is_active', true)
            ->where(function($q) {
                $q->where('category_id', $this->id)
                  ->orWhere('subcategory_id', $this->id);
            })
            ->whereHas('images')
            ->inRandomOrder()
            ->first();

        if ($randomProduct && $randomProduct->images->first()) {
            return $randomProduct->images->first()->url;
        }

        return null;
    }

    /**
     * Get all products in this category
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Get all products where this category is used as subcategory
     */
    public function subcategoryProducts(): HasMany
    {
        return $this->hasMany(Product::class, 'subcategory_id');
    }

    /**
     * Get all inventory movements for products in this category
     */
    public function inventoryMovements(): HasManyThrough
    {
        return $this->hasManyThrough(InventoryMovement::class, Product::class);
    }

    /**
     * Get all weekly cut details for this category
     */
    public function weeklyCutDetails(): HasMany
    {
        return $this->hasMany(WeeklyCutDetail::class);
    }

    /**
     * Get the parent category
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * Get the child categories
     */
    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }
}
