<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LiveSession extends Model
{
    protected $fillable = [
        'title',
        'platform',
        'live_url',
        'is_live',
        'starts_at',
        'ends_at',
        'duration_minutes',
    ];

    protected $casts = [
        'is_live' => 'boolean',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get all product highlights for this session
     */
    public function productHighlights(): HasMany
    {
        return $this->hasMany(LiveProductHighlight::class)->orderBy('position');
    }

    /**
     * Start the live session
     */
    public function start(?string $liveUrl = null): void
    {
        $this->update([
            'is_live' => true,
            'starts_at' => now(),
            'ends_at' => null,
            'live_url' => $liveUrl ?? $this->live_url,
        ]);
    }

    /**
     * End the live session
     */
    public function end(): void
    {
        $this->update([
            'is_live' => false,
            'ends_at' => now(),
        ]);
    }

    /**
     * Check if session is scheduled
     */
    public function isScheduled(): bool
    {
        return !$this->is_live && $this->starts_at && now()->isBefore($this->starts_at);
    }
    /**
     * Get human readable platform label
     */
    public function getPlatformLabelAttribute(): string
    {
        return match($this->platform) {
            'instagram' => 'Instagram Live',
            'facebook' => 'Facebook Live',
            'tiktok' => 'TikTok Live',
            'other' => 'Otro / YouTube',
            default => ucfirst($this->platform ?? ''),
        };
    }
}
