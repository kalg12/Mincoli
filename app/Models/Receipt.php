<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Receipt extends Model
{
    const UPDATED_AT = null;

    protected $fillable = [
        'payment_id',
        'code',
        'file_url',
        'type',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    /**
     * Get the payment this receipt belongs to
     */
    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }
}
