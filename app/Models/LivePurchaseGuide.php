<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LivePurchaseGuide extends Model
{
    protected $fillable = [
        'video_url',
        'text',
        'whatsapp_url',
        'cart_url',
        'offers_url',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
