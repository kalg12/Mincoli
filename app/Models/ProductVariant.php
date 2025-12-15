<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductVariant extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'product_id',
        'name',
        'size',
        'color',
        'sku',
        'barcode',
        'price',
        'stock',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'stock' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Get the product this variant belongs to
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get all images of this variant
     */
    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class, 'variant_id')->orderBy('position');
    }

    /**
     * Get all inventory movements for this variant
     */
    public function inventoryMovements(): HasMany
    {
        return $this->hasMany(InventoryMovement::class, 'variant_id');
    }

    /**
     * Get all cart items for this variant
     */
    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class, 'variant_id');
    }

    /**
     * Get all order items for this variant
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'variant_id');
    }

    /**
     * Get all offer items for this variant
     */
    public function offerItems(): HasMany
    {
        return $this->hasMany(OfferItem::class, 'variant_id');
    }

    /**
     * Get all live product highlights for this variant
     */
    public function liveProductHighlights(): HasMany
    {
        return $this->hasMany(LiveProductHighlight::class, 'variant_id');
    }

    /**
     * Get the effective price (variant price if set, otherwise product price)
     */
    public function getEffectivePriceAttribute(): float
    {
        return $this->price ?? $this->product->price;
    }

    /**
     * Check if variant has sufficient stock
     */
    public function hasStock(int $quantity): bool
    {
        return $this->stock >= $quantity;
    }
}
