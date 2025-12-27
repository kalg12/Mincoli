<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class POSSession extends Model
{
    protected $table = 'pos_sessions';

    protected $fillable = [
        'user_id',
        'session_number',
        'total_sales',
        'total_payments',
        'status',
        'opened_at',
        'closed_at',
    ];

    protected $casts = [
        'total_sales' => 'decimal:2',
        'total_payments' => 'decimal:2',
        'opened_at' => 'datetime',
        'closed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($session) {
            if (!$session->session_number) {
                $session->session_number = 'POS-' . date('YmdHis') . '-' . random_int(1000, 9999);
            }
        });
    }

    /**
     * Get the user who opened this session
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all transactions in this session
     */
    public function transactions(): HasMany
    {
        // Explicit FK to match migration column 'pos_session_id'
        return $this->hasMany(POSTransaction::class, 'pos_session_id');
    }
}
