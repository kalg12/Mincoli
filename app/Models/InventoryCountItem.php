<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryCountItem extends Model
{
    protected $fillable = [
        'inventory_count_id',
        'product_id',
        'variant_id',
        'system_quantity',
        'counted_quantity',
        'difference',
        'notes',
        'counted_by',
        'counted_by_name',
        'counted_at',
    ];

    protected $casts = [
        'system_quantity' => 'integer',
        'counted_quantity' => 'integer',
        'difference' => 'integer',
        'counted_at' => 'datetime',
    ];

    public function inventoryCount(): BelongsTo
    {
        return $this->belongsTo(InventoryCount::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function variant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class);
    }

    public function countedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'counted_by');
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($item) {
            if ($item->counted_quantity !== null) {
                $item->difference = $item->counted_quantity - $item->system_quantity;
            }
        });
    }
}
