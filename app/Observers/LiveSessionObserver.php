<?php

namespace App\Observers;

use App\Models\LiveSession;
use Livewire\Livewire;

class LiveSessionObserver
{
    public function created(LiveSession $liveSession): void
    {
        // El componente se actualiza automáticamente con wire:poll
    }

    public function updated(LiveSession $liveSession): void
    {
        // El componente se actualiza automáticamente con wire:poll
    }

    public function deleted(LiveSession $liveSession): void
    {
        // El componente se actualiza automáticamente con wire:poll
    }
}
