<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WeeklyCut extends Model
{
    const UPDATED_AT = null;

    protected $fillable = [
        'week_start',
        'week_end',
        'created_by',
        'notes',
    ];

    protected $casts = [
        'week_start' => 'date',
        'week_end' => 'date',
        'created_at' => 'datetime',
    ];

    /**
     * Get the user who created this cut
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get all details for this cut
     */
    public function details(): HasMany
    {
        return $this->hasMany(WeeklyCutDetail::class);
    }

    /**
     * Get the total sales for all categories
     */
    public function getTotalSalesAttribute(): float
    {
        return $this->details->sum('sales_total');
    }

    /**
     * Get the total costs for all categories
     */
    public function getTotalCostsAttribute(): float
    {
        return $this->details->sum('costs_total');
    }

    /**
     * Get the total IVA for all categories
     */
    public function getTotalIvaAttribute(): float
    {
        return $this->details->sum('iva_total');
    }

    /**
     * Get the total net profit for all categories
     */
    public function getTotalNetProfitAttribute(): float
    {
        return $this->details->sum('net_profit');
    }

    /**
     * Get the total orders count for all categories
     */
    public function getTotalOrdersAttribute(): int
    {
        return $this->details->sum('orders_count');
    }
}
