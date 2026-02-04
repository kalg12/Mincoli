<?php

namespace App\Livewire;

use App\Models\LiveSession;
use App\Models\Product;
use Livewire\Component;
use Livewire\Attributes\Validate;
use Livewire\WithPagination;

class ManageLiveSession extends Component
{
    use WithPagination;

    public $showForm = false;
    public $showProducts = false;
    public $selectedLive = null;
    public $editingLive = null;

    #[Validate('required|min:5|max:255')]
    public $title = '';

    #[Validate('required')]
    public $platform = 'Instagram Live';

    #[Validate('nullable|url')]
    public $live_url = '';

    public $selectedProducts = [];

    public function render()
    {
        $lives = LiveSession::orderBy('created_at', 'desc')->paginate(10);

        return view('livewire.manage-live-session', [
            'lives' => $lives,
            'availableProducts' => Product::where('is_active', true)->get(),
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
        $this->platform = 'Instagram Live';
        $this->live_url = '';
        $this->selectedProducts = [];
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
            ]);

            $live = $this->editingLive;
            $this->dispatch('notify', type: 'success', message: 'Transmisión actualizada correctamente');
        } else {
            $live = LiveSession::create([
                'title' => $this->title,
                'platform' => $this->platform,
                'live_url' => $this->live_url ?: null,
                'is_live' => false,
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
            $this->dispatch('notify', type: 'success', message: '🔴 ¡Transmisión iniciada!');
        }
    }

    public function stopLive($liveId)
    {
        $live = LiveSession::find($liveId);

        if ($live) {
            $live->end();
            $this->dispatch('notify', type: 'success', message: '⚫ Transmisión detenida');
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

    public function openProducts($liveId)
    {
        $this->selectedLive = LiveSession::find($liveId);
        $this->showProducts = true;
    }

    public function closeProducts()
    {
        $this->showProducts = false;
        $this->selectedLive = null;
    }

    public function addProductToLive($productId, $description = '')
    {
        if ($this->selectedLive) {
            $position = $this->selectedLive->productHighlights()->count() + 1;

            $this->selectedLive->productHighlights()->create([
                'product_id' => $productId,
                'description' => $description,
                'position' => $position,
            ]);

            $this->dispatch('notify', type: 'success', message: 'Producto agregado a la transmisión');
        }
    }

    public function removeProductFromLive($highlightId)
    {
        if ($this->selectedLive) {
            $this->selectedLive->productHighlights()->find($highlightId)?->delete();
            $this->dispatch('notify', type: 'success', message: 'Producto removido de la transmisión');
        }
    }
}
