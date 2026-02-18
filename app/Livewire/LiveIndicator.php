<?php

namespace App\Livewire;

use App\Models\LiveSession;
use Livewire\Component;

class LiveIndicator extends Component
{
    public ?LiveSession $activeLive = null;
    public bool $showPreview = false;
    public ?string $embedUrl = null;

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

        if (! $this->activeLive) {
            // Fallback to last finished live with recording
            $this->activeLive = LiveSession::where('is_live', false)
                ->whereNotNull('ends_at')
                ->whereNotNull('live_url')
                ->orderByDesc('ends_at')
                ->first();
        }

        $this->embedUrl = $this->activeLive?->live_url
            ? $this->buildEmbedUrl($this->activeLive->live_url)
            : null;
    }

    private function buildEmbedUrl(string $url): ?string
    {
        $host = parse_url($url, PHP_URL_HOST);
        $path = parse_url($url, PHP_URL_PATH) ?? '';

        if (!$host) {
            return null;
        }

        $host = str_replace('www.', '', $host);

        // YouTube
        if ($host === 'youtube.com' || $host === 'youtu.be') {
            if ($host === 'youtu.be') {
                $videoId = ltrim($path, '/');
            } else {
                parse_str(parse_url($url, PHP_URL_QUERY) ?? '', $query);
                $videoId = $query['v'] ?? null;
            }

            return $videoId
                ? "https://www.youtube.com/embed/{$videoId}?autoplay=1&mute=1&rel=0"
                : null;
        }

        // Facebook Live
        if ($host === 'facebook.com' || $host === 'fb.watch') {
            $encoded = urlencode($url);
            return "https://www.facebook.com/plugins/video.php?href={$encoded}&show_text=0&autoplay=1";
        }

        // Default: try direct embed
        return $url;
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

    public function render()
    {
        // Recargar el live activo en cada render para mantenerlo actualizado
        $this->loadActiveLive();
        return view('livewire.live-indicator');
    }
}
