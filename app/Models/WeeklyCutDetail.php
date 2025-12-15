<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WeeklyCutDetail extends Model
{
    const UPDATED_AT = null;
    const CREATED_AT = null;

    protected $fillable = [
        'weekly_cut_id',
        'category_id',
        'sales_total',
        'costs_total',
        'iva_total',
        'net_profit',
        'orders_count',
    ];

    protected $casts = [
        'sales_total' => 'decimal:2',
        'costs_total' => 'decimal:2',
        'iva_total' => 'decimal:2',
        'net_profit' => 'decimal:2',
        'orders_count' => 'integer',
    ];

    /**
     * Get the weekly cut this detail belongs to
     */
    public function weeklyCut(): BelongsTo
    {
        return $this->belongsTo(WeeklyCut::class);
    }

    /**
     * Get the category for this detail
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the profit margin percentage
     */
    public function getProfitMarginAttribute(): float
    {
        if ($this->sales_total <= 0) {
            return 0;
        }

        return round(($this->net_profit / $this->sales_total) * 100, 2);
    }
}
