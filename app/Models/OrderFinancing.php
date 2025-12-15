<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderFinancing extends Model
{
    protected $fillable = [
        'order_id',
        'payment_plan_id',
        'down_payment',
        'financed_amount',
        'start_date',
        'due_date',
        'status',
    ];

    protected $casts = [
        'down_payment' => 'decimal:2',
        'financed_amount' => 'decimal:2',
        'start_date' => 'date',
        'due_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the order this financing belongs to
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the payment plan
     */
    public function paymentPlan(): BelongsTo
    {
        return $this->belongsTo(PaymentPlan::class);
    }

    /**
     * Get the total amount financed
     */
    public function getTotalAmountAttribute(): float
    {
        return $this->down_payment + $this->financed_amount;
    }

    /**
     * Check if financing is overdue
     */
    public function isOverdue(): bool
    {
        return $this->status === 'active' && now()->isAfter($this->due_date);
    }

    /**
     * Mark as paid
     */
    public function markAsPaid(): void
    {
        $this->update(['status' => 'paid']);
    }
}
