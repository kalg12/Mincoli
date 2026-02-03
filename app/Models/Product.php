<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'category_id',
        'subcategory_id',
        'brand',
        'name',
        'slug',
        'description',
        'sku',
        'barcode',
        'price',
        'cost',
        'sale_price',
        'stock',
        'status',
        'iva_rate',
        'is_active',
        'is_featured',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'cost' => 'decimal:2',
        'iva_rate' => 'decimal:2',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    protected $appends = ['image_url'];

    /**
     * Get the category this product belongs to
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the subcategory this product belongs to
     */
    public function subcategory(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'subcategory_id');
    }

    /**
     * Get all variants of this product
     */
    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    /**
     * Get all images of this product
     */
    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('position');
    }

    /**
     * Get all inventory movements for this product
     */
    public function inventoryMovements(): HasMany
    {
        return $this->hasMany(InventoryMovement::class);
    }

    /**
     * Get all cart items for this product
     */
    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * Get all order items for this product
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get all offer items for this product
     */
    public function offerItems(): HasMany
    {
        return $this->hasMany(OfferItem::class);
    }

    /**
     * Get all live product highlights for this product
     */
    public function liveProductHighlights(): HasMany
    {
        return $this->hasMany(LiveProductHighlight::class);
    }

    /**
     * Calculate total stock including variants
     */
    public function getTotalStockAttribute(): int
    {
        // Si tiene variantes, suma su stock. Si no, usa el stock del producto.
        if ($this->variants->count() > 0) {
            return (int) $this->variants->sum('stock');
        }
        return (int) $this->stock;
    }

    /**
     * Get the first image URL or a placeholder
     */
    public function getImageUrlAttribute(): string
    {
        $image = $this->images->first();
        if ($image) {
            return asset('storage/' . $image->path);
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=111&color=fff&size=512';
    }

    /**
     * Calculate the IVA amount for a given price
     */
    public function calculateIva(float $basePrice): float
    {
        return round($basePrice * ($this->iva_rate / 100), 2);
    }
}
