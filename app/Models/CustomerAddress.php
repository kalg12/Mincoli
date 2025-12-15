<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerAddress extends Model
{
    protected $fillable = [
        'customer_id',
        'label',
        'street',
        'ext_number',
        'int_number',
        'colony',
        'city',
        'state',
        'zip',
        'references',
        'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the customer this address belongs to
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Set this address as default (only one per customer)
     */
    public function setAsDefault(): void
    {
        CustomerAddress::where('customer_id', $this->customer_id)
            ->update(['is_default' => false]);

        $this->update(['is_default' => true]);
    }

    /**
     * Get formatted full address
     */
    public function getFormattedAddressAttribute(): string
    {
        $parts = [
            $this->street,
            $this->ext_number,
            $this->int_number ? "Int. {$this->int_number}" : null,
            $this->colony,
            $this->city,
            $this->state,
            $this->zip,
        ];

        return implode(', ', array_filter($parts));
    }
}
