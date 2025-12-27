<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class POSTransaction extends Model
{
    protected $table = 'pos_transactions';

    protected $fillable = [
        'pos_session_id',
        'customer_id',
        'transaction_number',
        'subtotal',
        'iva_total',
        'total',
        'status',
        'payment_status',
        'shipping_contact_phone',
        'shipping_address',
        'shipping_notes',
        'reserved_at',
        'completed_at',
        'notes',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'iva_total' => 'decimal:2',
        'total' => 'decimal:2',
        'reserved_at' => 'datetime',
        'completed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($transaction) {
            if (!$transaction->transaction_number) {
                $transaction->transaction_number = 'TXN-' . date('YmdHis') . '-' . random_int(10000, 99999);
            }
        });
    }

    /**
     * Get the POS session for this transaction
     */
    public function posSession(): BelongsTo
    {
        // Explicit FK to match migration column 'pos_session_id'
        return $this->belongsTo(POSSession::class, 'pos_session_id');
    }

    /**
     * Get the customer for this transaction
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get all items in this transaction
     */
    public function items(): HasMany
    {
        // Explicit FK to avoid Laravel's p_o_s_transaction_id guess
        return $this->hasMany(POSTransactionItem::class, 'pos_transaction_id');
    }

    /**
     * Get all payments for this transaction
     */
    public function payments(): HasMany
    {
        return $this->hasMany(POSPayment::class, 'pos_transaction_id');
    }

    /**
     * Calculate total paid amount
     */
    public function getTotalPaidAttribute(): float
    {
        return $this->payments()
            ->where('status', 'completed')
            ->sum('amount') ?? 0;
    }

    /**
     * Calculate pending amount
     */
    public function getPendingAmountAttribute(): float
    {
        return $this->total - $this->total_paid;
    }

    /**
     * Check if transaction is fully paid
     */
    public function getIsFullyPaidAttribute(): bool
    {
        return $this->pending_amount <= 0;
    }
}
