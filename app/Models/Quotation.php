<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Quotation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'folio',
        'user_id',
        'customer_id',
        'customer_name',
        'customer_phone',
        'subtotal',
        'iva_total',
        'total',
        'status',
        'share_type',
        'expires_at',
        'notes',
        'internal_notes',
        'converted_at',
        'order_id',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'iva_total' => 'decimal:2',
        'total' => 'decimal:2',
        'expires_at' => 'datetime',
        'converted_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(QuotationItem::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'draft' => 'zinc',
            'sent' => 'blue',
            'accepted' => 'emerald',
            'rejected' => 'red',
            'expired' => 'amber',
            'converted' => 'pink',
            default => 'zinc',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'draft' => 'Borrador',
            'sent' => 'Enviada',
            'accepted' => 'Aceptada',
            'rejected' => 'Rechazada',
            'expired' => 'Vencida',
            'converted' => 'Convertida',
            default => ucfirst($this->status),
        };
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($quotation) {
            if (!$quotation->folio) {
                $quotation->folio = 'COT-' . date('Ymd') . '-' . strtoupper(bin2hex(random_bytes(2)));
            }
            if (!$quotation->expires_at) {
                $quotation->expires_at = now()->addDays(7);
            }
        });
    }
}
