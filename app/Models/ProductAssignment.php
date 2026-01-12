<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductAssignment extends Model
{
    protected $fillable = [
        'user_id',
        'product_id',
        'variant_id',
        'partner_lob',
        'quantity',
        'base_price',
        'unit_price',
        'iva_amount',
        'total_amount',
        'amount_collected',
        'status',
        'assigned_at',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'unit_price' => 'decimal:2',
        'iva_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'amount_collected' => 'decimal:2',
        'assigned_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function variant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class);
    }

    /**
     * Get the CSS classes for the status color
     */
    public function getStatusColorClassesAttribute(): string
    {
        return match ($this->status) {
            'quotation' => 'bg-[#FFFF00] text-black',      // Amarillo Puro
            'paid_customer' => 'bg-[#00FFFF] text-black',   // Cyan / Azul Cielo
            'paid_partner' => 'bg-[#000080] text-white',    // Navy / Azul Marino
            'deferred' => 'bg-[#A9A9A9] text-white',       // Dark Gray / Gris
            'incident' => 'bg-[#FF0000] text-white',       // Red / Rojo
            default => 'bg-white text-zinc-900',
        };
    }

    /**
     * Get the Spanish label for current status
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'quotation' => 'Cotización enviada',
            'paid_customer' => 'Pagado por cliente',
            'paid_partner' => 'Pagado a socio',
            'deferred' => 'Pendiente / Jalo productos',
            'incident' => 'Incidencia',
            default => ucfirst($this->status),
        };
    }
}
