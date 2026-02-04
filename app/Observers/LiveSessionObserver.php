<?php

namespace App\Observers;

use App\Models\LiveSession;
use Livewire\Livewire;

class LiveSessionObserver
{
    public function created(LiveSession $liveSession): void
    {
        // Livewire::dispatch('live-session-updated');
    }

    public function updated(LiveSession $liveSession): void
    {
        // Especialmente cuando is_live cambia
        if ($liveSession->isDirty('is_live')) {
            // Livewire::dispatch('live-session-updated');
        }
    }

    public function deleted(LiveSession $liveSession): void
    {
        // Livewire::dispatch('live-session-updated');
    }
}
