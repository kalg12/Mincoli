<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PaymentPlan extends Model
{
    protected $fillable = [
        'name',
        'frequency',
        'installments_count',
        'days_between_payments',
        'is_active',
    ];

    protected $casts = [
        'installments_count' => 'integer',
        'days_between_payments' => 'integer',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get all order financings using this plan
     */
    public function orderFinancings(): HasMany
    {
        return $this->hasMany(OrderFinancing::class);
    }

    /**
     * Get the display name with frequency
     */
    public function getDisplayNameAttribute(): string
    {
        return "{$this->name} - {$this->installments_count} pagos ({$this->frequency})";
    }
}
