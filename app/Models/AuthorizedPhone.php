<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuthorizedPhone extends Model
{
    protected $fillable = ['phone', 'is_active', 'registered_at'];

    protected $casts = [
        'is_active' => 'boolean',
        'registered_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Normalize phone for storage/lookup: digits only, with country code.
     */
    public static function normalizePhone(string $value): string
    {
        $digits = preg_replace('/\D/', '', $value);
        if (strlen($digits) === 10 && in_array(substr($digits, 0, 1), ['1', '2', '3', '4', '5', '6', '7', '8', '9'])) {
            return '52' . $digits; // México default
        }
        if (strlen($digits) === 11 && substr($digits, 0, 1) === '1') {
            return $digits;
        }
        return $digits;
    }

    /**
     * Check if a phone number is authorized (active).
     */
    public static function isAuthorized(string $phone): bool
    {
        $normalized = self::normalizePhone($phone);
        return self::where('phone', $normalized)->where('is_active', true)->exists();
    }
}
