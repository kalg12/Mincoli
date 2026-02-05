<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class POSPayment extends Model
{
    protected $table = 'pos_payments';

    protected $fillable = [
        'pos_transaction_id',
        'payment_method_id',
        'amount',
        'reference',
        'status',
        'notes',
        'paid_at',
        'card_number',
        'card_type',
        'card_holder_name',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the transaction this payment belongs to
     */
    public function posTransaction(): BelongsTo
    {
        return $this->belongsTo(POSTransaction::class);
    }

    /**
     * Get the payment method
     */
    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class);
    }
}
