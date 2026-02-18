<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PaymentMethod extends Model
{
    protected $fillable = [
        'name',
        'code',
        'description',
        'is_active',
        'settings',
        'instructions',
        'supports_card_number',
        'requires_transfer_details',
        'card_number',
        'card_type',
        'card_holder_name',
        'bank_name',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'supports_card_number' => 'boolean',
        'requires_transfer_details' => 'boolean',
        'settings' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get all payments using this method
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'method_id');
    }
}
