<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InventoryCount extends Model
{
    protected $fillable = [
        'name',
        'notes',
        'status',
        'created_by',
        'reviewed_by',
        'started_at',
        'completed_at',
        'reviewed_at',
        'public_token',
        'public_capture_enabled',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'reviewed_at' => 'datetime',
        'public_capture_enabled' => 'boolean',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(InventoryCountItem::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function reviewedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function getTotalDifferenceAttribute()
    {
        return $this->items->sum('difference');
    }

    public function getTotalValueDifferenceAttribute()
    {
        return $this->items->sum(function ($item) {
            $price = $item->variant ? $item->variant->price : $item->product->price;
            return ($item->difference ?? 0) * $price;
        });
    }
}
