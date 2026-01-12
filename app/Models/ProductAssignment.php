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
        'quantity',
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
}
