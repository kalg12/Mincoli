<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Category extends Model
{
    protected $fillable = ['name', 'slug', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get all products in this category
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
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
}
