<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrackingPixel extends Model
{
    protected $fillable = [
        'platform',
        'pixel_id',
        'is_active',
        'settings',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'settings' => 'json',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get all active tracking pixels
     */
    public static function active()
    {
        return self::where('is_active', true);
    }

    /**
     * Get pixel by platform
     */
    public static function getByPlatform(string $platform)
    {
        return self::where('platform', $platform)
            ->where('is_active', true)
            ->first();
    }
}
