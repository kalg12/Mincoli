<?php

namespace App\Livewire;

use App\Models\LiveSession;
use Livewire\Component;

class LiveIndicator extends Component
{
    public ?LiveSession $activeLive = null;
    public bool $showPreview = false;

    public function mount(): void
    {
        $this->loadActiveLive();
    }

    public function loadActiveLive(): void
    {
        $this->activeLive = LiveSession::where('is_live', true)
            ->where('starts_at', '<=', now())
            ->where(function ($query) {
                $query->whereNull('ends_at')
                    ->orWhere('ends_at', '>', now());
            })
            ->with('productHighlights')
            ->first();
    }

    public function openPreview(): void
    {
        $this->loadActiveLive();
        if ($this->activeLive) {
            $this->showPreview = true;
        }
    }

    public function closePreview(): void
    {
        $this->showPreview = false;
    }

    public function goToLive(): void
    {
        if ($this->activeLive && $this->activeLive->live_url) {
            $this->redirect($this->activeLive->live_url);
        }
    }

    #[\Livewire\Attributes\On('live-session-updated')]
    public function onLiveSessionUpdated(): void
    {
        $this->loadActiveLive();
    }

    public function render()
    {
        return view('livewire.live-indicator');
    }
}
