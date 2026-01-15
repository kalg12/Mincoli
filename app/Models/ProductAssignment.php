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
            'quotation' => 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-300',      // Soft Amber
            'paid_customer' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300',   // Soft Blue
            'paid_partner' => 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-300',    // Soft Indigo
            'deferred' => 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300',       // Soft Slate
            'incident' => 'bg-rose-100 text-rose-800 dark:bg-rose-900/30 dark:text-rose-300',       // Soft Rose
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
