<?php

namespace App\Livewire;

use App\Models\LiveSession;
use Livewire\Component;
use Livewire\Attributes\Validate;
use Livewire\WithPagination;

class ManageLiveSession extends Component
{
    use WithPagination;

    public $showForm = false;
    public $editingLive = null;

    #[Validate('required|min:5|max:255')]
    public $title = '';

    #[Validate('required')]
    public $platform = 'instagram';

    #[Validate('nullable|url')]
    public $live_url = '';

    #[Validate('nullable|integer|min:1|max:480')]
    public $duration_minutes = 60;

    public function render()
    {
        $lives = LiveSession::orderBy('created_at', 'desc')->paginate(10);

        return view('livewire.manage-live-session', [
            'lives' => $lives,
        ]);
    }

    public function openForm($liveId = null)
    {
        if ($liveId) {
            $live = LiveSession::find($liveId);
            $this->editingLive = $live;
            $this->title = $live->title;
            $this->platform = $live->platform;
            $this->live_url = $live->live_url ?? '';
            $this->duration_minutes = $live->duration_minutes ?? 60;
        } else {
            $this->resetForm();
        }

        $this->showForm = true;
    }

    public function closeForm()
    {
        $this->showForm = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->editingLive = null;
        $this->title = '';
        $this->platform = 'instagram';
        $this->live_url = '';
        $this->duration_minutes = 60;
        $this->resetValidation();
    }

    public function saveLive()
    {
        $this->validate();

        if ($this->editingLive) {
            $this->editingLive->update([
                'title' => $this->title,
                'platform' => $this->platform,
                'live_url' => $this->live_url ?: null,
                'duration_minutes' => $this->duration_minutes,
            ]);

            $live = $this->editingLive;
            $this->dispatch('notify', type: 'success', message: 'Transmisión actualizada correctamente');
        } else {
            $live = LiveSession::create([
                'title' => $this->title,
                'platform' => $this->platform,
                'live_url' => $this->live_url ?: null,
                'is_live' => false,
                'duration_minutes' => $this->duration_minutes,
            ]);

            $this->dispatch('notify', type: 'success', message: 'Transmisión creada correctamente');
        }

        $this->closeForm();
    }

    public function startLive($liveId)
    {
        $live = LiveSession::find($liveId);

        if ($live) {
            $live->start();
            $this->dispatch('notify', type: 'success', message: '🔴 ¡Transmisión iniciada! Duración: ' . $live->duration_minutes . ' minutos');
        }
    }

    public function stopLive($liveId)
    {
        $live = LiveSession::find($liveId);

        if ($live) {
            $live->end();
            $this->dispatch('notify', type: 'success', message: '⚫ Transmisión finalizada. Ya puedes visualizar la grabación.');
        }
    }

    public function deleteLive($liveId)
    {
        $live = LiveSession::find($liveId);

        if ($live) {
            $live->delete();
            $this->dispatch('notify', type: 'success', message: 'Transmisión eliminada');
        }
    }
}
