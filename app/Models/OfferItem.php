<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OfferItem extends Model
{
    const UPDATED_AT = null;

    protected $fillable = [
        'offer_id',
        'product_id',
        'variant_id',
        'discount_type',
        'discount_value',
    ];

    protected $casts = [
        'discount_value' => 'decimal:2',
        'created_at' => 'datetime',
    ];

    /**
     * Get the offer this item belongs to
     */
    public function offer(): BelongsTo
    {
        return $this->belongsTo(Offer::class);
    }

    /**
     * Get the product for this item
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the variant for this item (if any)
     */
    public function variant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }

    /**
     * Calculate the discount amount for a given price
     */
    public function calculateDiscountAmount(float $basePrice): float
    {
        if ($this->discount_type === 'percent') {
            return round($basePrice * ($this->discount_value / 100), 2);
        }

        return (float)$this->discount_value;
    }

    /**
     * Calculate the final price after discount
     */
    public function calculateFinalPrice(float $basePrice): float
    {
        return max(0, $basePrice - $this->calculateDiscountAmount($basePrice));
    }
}
