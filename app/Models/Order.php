<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    protected $fillable = [
        'customer_id',
        'order_number',
        'status',
        'channel',
        'subtotal',
        'iva_total',
        'shipping_cost',
        'total',
        'notes',
        'placed_at',
        'customer_name',
        'customer_email',
        'customer_phone',
    ];

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'draft' => 'Borrador',
            'pending' => 'Pendiente',
            'paid' => 'Pagado',
            'partially_paid' => 'Pago Parcial',
            'shipped' => 'Enviado',
            'delivered' => 'Entregado',
            'cancelled' => 'Cancelado',
            'refunded' => 'Reembolsado',
            default => ucfirst($this->status),
        };
    }

    public function getPaymentMethodNameAttribute(): string
    {
        return $this->payments->first()->method->name ?? 'Sin pago';
    }

    protected $casts = [
        'subtotal' => 'decimal:2',
        'iva_total' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'total' => 'decimal:2',
        'placed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            if (!$order->order_number) {
                $order->order_number = 'ORD-' . date('YmdHis') . '-' . random_int(1000, 9999);
            }
        });
    }

    /**
     * Get the customer this order belongs to
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get all items in this order
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get the shipment for this order
     */
    public function shipment(): HasOne
    {
        return $this->hasOne(Shipment::class);
    }

    /**
     * Get all status history for this order
     */
    public function statusHistory(): HasMany
    {
        return $this->hasMany(OrderStatusHistory::class);
    }

    /**
     * Get the financing details for this order
     */
    public function financing(): HasOne
    {
        return $this->hasOne(OrderFinancing::class);
    }

    /**
     * Get all payments for this order
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Get the total amount paid
     */
    public function getTotalPaidAttribute(): float
    {
        return $this->payments()->where('status', 'paid')->sum('amount');
    }

    /**
     * Get the remaining amount to pay
     */
    public function getRemainingAttribute(): float
    {
        return max(0, $this->total - $this->total_paid);
    }

    /**
     * Change the order status
     */
    public function changeStatus(string $newStatus, ?string $note = null, ?int $userId = null): void
    {
        $oldStatus = $this->status;
        $this->update(['status' => $newStatus]);

        OrderStatusHistory::create([
            'order_id' => $this->id,
            'from_status' => $oldStatus,
            'to_status' => $newStatus,
            'note' => $note,
        ]);
    }

    /**
     * Check if order is paid
     */
    public function isPaid(): bool
    {
        return in_array($this->status, ['paid', 'shipped', 'delivered']);
    }

    /**
     * Check if order can be canceled
     */
    public function canBeCanceled(): bool
    {
        return in_array($this->status, ['draft', 'pending', 'paid']);
    }
}
