<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class ExclusiveLandingConfig extends Model
{
    protected $fillable = [
        'is_active',
        'starts_at',
        'ends_at',
        'contact_phone',
        'restricted_message',
        'expired_message',
        'show_filter_category',
        'show_filter_type',
        'show_filter_price',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'show_filter_category' => 'boolean',
        'show_filter_type' => 'boolean',
        'show_filter_price' => 'boolean',
    ];

    protected static function cacheKey(): string
    {
        return 'exclusive_landing_config';
    }

    /**
     * Get the current config (single row). Cached for 1 minute.
     */
    public static function current(): ?self
    {
        return Cache::remember(self::cacheKey(), 60, function () {
            return self::first();
        });
    }

    public static function clearCache(): void
    {
        Cache::forget(self::cacheKey());
    }

    /**
     * Whether the landing is currently available (active and within dates).
     */
    public function isAvailable(): bool
    {
        if (!$this->is_active) {
            return false;
        }
        $now = now();
        if ($this->starts_at && $now->lt($this->starts_at)) {
            return false;
        }
        if ($this->ends_at && $now->gt($this->ends_at)) {
            return false;
        }
        return true;
    }

    /**
     * Whether the campaign has expired (past ends_at).
     */
    public function isExpired(): bool
    {
        return $this->ends_at && now()->gt($this->ends_at);
    }
}
