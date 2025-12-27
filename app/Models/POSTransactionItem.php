<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class POSTransactionItem extends Model
{
    protected $table = 'pos_transaction_items';

    protected $fillable = [
        'pos_transaction_id',
        'product_id',
        'product_variant_id',
        'quantity',
        'unit_price',
        'iva_rate',
        'subtotal',
        'iva_amount',
        'total',
        'status',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'unit_price' => 'decimal:2',
        'iva_rate' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'iva_amount' => 'decimal:2',
        'total' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the transaction this item belongs to
     */
    public function posTransaction(): BelongsTo
    {
        return $this->belongsTo(POSTransaction::class);
    }

    /**
     * Get the product for this item
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the product variant if applicable
     */
    public function variant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }

    /**
     * Get product name (variant or base product)
     */
    public function getProductNameAttribute(): string
    {
        if ($this->variant) {
            return "{$this->product->name} - {$this->variant->name}";
        }
        return $this->product->name;
    }

    /**
     * Get product SKU/Barcode
     */
    public function getProductSkuAttribute(): string
    {
        return $this->variant?->sku ?? $this->product->sku;
    }

    /**
     * Get product barcode
     */
    public function getProductBarcodeAttribute(): ?string
    {
        return $this->variant?->barcode ?? $this->product->barcode;
    }
}
