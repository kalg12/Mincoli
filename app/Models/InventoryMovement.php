<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryMovement extends Model
{
    const UPDATED_AT = null;

    protected $fillable = [
        'product_id',
        'variant_id',
        'type',
        'quantity',
        'reason',
        'reference_type',
        'reference_id',
        'created_by',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'created_at' => 'datetime',
    ];

    /**
     * Get the product this movement belongs to
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the variant this movement belongs to (if any)
     */
    public function variant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }

    /**
     * Get the user who created this movement
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the reference (Order, Return, etc)
     */
    public function getReferenceable()
    {
        if (!$this->reference_type || !$this->reference_id) {
            return null;
        }

        $className = 'App\\Models\\' . ucfirst($this->reference_type);
        if (class_exists($className)) {
            return $className::find($this->reference_id);
        }

        return null;
    }

    public function getOrderReferenceIdAttribute(): ?int
    {
        if (!$this->reference_type || !$this->reference_id) {
            return null;
        }

        if ($this->reference_type === 'Order') {
            return (int) $this->reference_id;
        }

        if (str_starts_with($this->reference_type, 'OrderMovement:')) {
            return (int) $this->reference_id;
        }

        if (str_starts_with($this->reference_type, 'OrderStatusHistory:')) {
            $history = OrderStatusHistory::find($this->reference_id);
            return $history?->order_id;
        }

        if ($this->reference_type === 'OrderItem') {
            $item = \App\Models\OrderItem::find($this->reference_id);
            return $item?->order_id;
        }

        return null;
    }
}
