<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cart extends Model
{
    protected $fillable = [
        'customer_id',
        'session_id',
        'status',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the customer this cart belongs to
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get all items in this cart
     */
    public function items(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * Calculate the subtotal of the cart
     */
    public function getSubtotalAttribute(): float
    {
        return $this->items->sum(fn($item) => $item->quantity * $item->unit_price);
    }

    /**
     * Calculate the total IVA for the cart
     */
    public function getTotalIvaAttribute(): float
    {
        $total = 0;
        foreach ($this->items as $item) {
            $product = $item->product;
            $basePrice = $item->quantity * $item->unit_price;
            $total += $product->calculateIva($basePrice);
        }
        return $total;
    }

    /**
     * Calculate the total of the cart
     */
    public function getTotalAttribute(): float
    {
        return $this->subtotal + $this->total_iva;
    }

    /**
     * Check if cart has expired
     */
    public function hasExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    /**
     * Mark cart as converted (to an order)
     */
    public function markAsConverted(): void
    {
        $this->update(['status' => 'converted']);
    }

    /**
     * Mark cart as abandoned
     */
    public function markAsAbandoned(): void
    {
        $this->update(['status' => 'abandoned']);
    }
}
