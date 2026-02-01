<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tutorial extends Model
{
    protected $fillable = [
        'title',
        'description',
        'youtube_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function views(): HasMany
    {
        return $this->hasMany(TutorialView::class);
    }

    // Helper to get thumbnail
    public function getThumbnailAttribute(): string
    {
        return "https://img.youtube.com/vi/{$this->youtube_id}/hqdefault.jpg";
    }
}
