<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use App\Models\Tutorial;
use App\Models\TutorialView;
use Illuminate\Support\Facades\Auth;

new #[Layout('components.layouts.app')] class extends Component {
    public $search = '';
    public $activeTab = 'tutorials'; // tutorials, analytics
    
    // Create Modal
    public $showCreateModal = false;
    public $title = '';
    public $description = '';
    public $youtube_url = '';

    // Player Modal
    public $showPlayerModal = false;
    public $currentTutorial = null;

    public function with()
    {
        return [
            'tutorials' => Tutorial::where('is_active', true)
                ->where('title', 'like', '%' . $this->search . '%')
                ->latest()
                ->get(),
            'views' => $this->canViewAnalytics() 
                ? TutorialView::with(['user', 'tutorial'])->latest()->paginate(20)
                : [],
        ];
    }

    public function canViewAnalytics()
    {
        return Auth::user()->email === 'luciano19940@hotmail.com';
    }

    public function save()
    {
        $this->validate([
            'title' => 'required|min:3',
            'youtube_url' => 'required|url',
        ]);

        // Extract ID from URL
        preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $this->youtube_url, $matches);
        $youtubeId = $matches[1] ?? null;

        if (!$youtubeId) {
            $this->addError('youtube_url', 'URL de YouTube inválida');
            return;
        }

        Tutorial::create([
            'title' => $this->title,
            'description' => $this->description,
            'youtube_id' => $youtubeId,
        ]);

        $this->reset(['title', 'description', 'youtube_url', 'showCreateModal']);
        session()->flash('success', 'Tutorial agregado');
    }

    public function openTutorial(Tutorial $tutorial)
    {
        $this->currentTutorial = $tutorial;
        $this->showPlayerModal = true;
        
        // Record View
        TutorialView::firstOrCreate([
            'user_id' => Auth::id(),
            'tutorial_id' => $tutorial->id,
        ], [
            'created_at' => now(), // Force update timestamp if re-watching? No, just first view or latest log? 
            // Request was "registro de que personas lo han visto". Single record per user/video is usually enough, or separate log table. 
            // I'll stick to simple check: if exists, don't duplicate, or maybe update timestamp.
            // Let's create a new record for every view effectively? No, `firstOrCreate` avoids duplicates.
            // If we want history of EVERY watch, we just use create. 
            // Let's use `create` to track frequency, or `firstOrCreate` to track completion. 
            // I'll use `firstOrCreate` to avoid spam, but update the timestamp.
        ]);
        
        // Update timestamp if exists to show "last watched"
        TutorialView::where('user_id', Auth::id())
            ->where('tutorial_id', $tutorial->id)
            ->update(['updated_at' => now()]);
    }
    
    public function closePlayer()
    {
        $this->showPlayerModal = false;
        $this->currentTutorial = null;
    }
}; ?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Tutoriales del Sistema</h1>
            <p class="text-zinc-500 dark:text-zinc-400">Capacitación y guías de uso</p>
        </div>
        
        @if($this->canViewAnalytics())
        <div class="flex bg-zinc-100 dark:bg-zinc-800 p-1 rounded-lg">
            <button wire:click="$set('activeTab', 'tutorials')" class="px-4 py-2 text-sm font-medium rounded-md transition {{ $activeTab === 'tutorials' ? 'bg-white dark:bg-zinc-700 shadow text-zinc-900 dark:text-white' : 'text-zinc-500 hover:text-zinc-700 dark:hover:text-zinc-300' }}">
                Videos
            </button>
            <button wire:click="$set('activeTab', 'analytics')" class="px-4 py-2 text-sm font-medium rounded-md transition {{ $activeTab === 'analytics' ? 'bg-white dark:bg-zinc-700 shadow text-zinc-900 dark:text-white' : 'text-zinc-500 hover:text-zinc-700 dark:hover:text-zinc-300' }}">
                Historial de Vistas
            </button>
        </div>
        @endif
        
        @if($this->canViewAnalytics())
        <button wire:click="$set('showCreateModal', true)" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded-lg transition flex items-center">
            <flux:icon name="plus" class="w-5 h-5 mr-2" />
            Nuevo Video
        </button>
        @endif
    </div>

    @if($activeTab === 'tutorials')
        <!-- Search -->
        <div class="mb-6">
            <input type="text" wire:model.live="search" placeholder="Buscar tutorial..." class="w-full max-w-md rounded-md border-zinc-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-zinc-800 dark:border-zinc-700 dark:text-white">
        </div>

        <!-- Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($tutorials as $tutorial)
            <div wire:click="openTutorial({{ $tutorial->id }})" class="group bg-white dark:bg-zinc-900 rounded-xl shadow-sm hover:shadow-lg transition cursor-pointer overflow-hidden border border-zinc-200 dark:border-zinc-700">
                <div class="relative aspect-video bg-black">
                    <img src="{{ $tutorial->thumbnail }}" class="w-full h-full object-cover opacity-80 group-hover:opacity-100 transition">
                    <div class="absolute inset-0 flex items-center justify-center">
                        <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center group-hover:scale-110 transition">
                             <flux:icon name="play" class="w-6 h-6 text-white ml-1" />
                        </div>
                    </div>
                </div>
                <div class="p-4">
                    <h3 class="font-bold text-lg text-zinc-900 dark:text-white mb-1 line-clamp-1">{{ $tutorial->title }}</h3>
                    <p class="text-sm text-zinc-500 dark:text-zinc-400 line-clamp-2">{{ $tutorial->description }}</p>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center py-12">
                <div class="mb-4">
                     <flux:icon name="video-camera" class="w-12 h-12 mx-auto text-zinc-300" />
                </div>
                <h3 class="text-lg font-medium text-zinc-900 dark:text-white">No hay tutoriales encontrados</h3>
            </div>
            @endforelse
        </div>
    @else
        <!-- Analytics Table -->
        <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-700 overflow-hidden">
            <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                <thead class="bg-zinc-50 dark:bg-zinc-800/50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 uppercase tracking-wider">Usuario</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 uppercase tracking-wider">Tutorial</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 uppercase tracking-wider">Visto el</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                    @foreach($views as $view)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <span class="h-8 w-8 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center text-blue-700 dark:text-blue-300 text-xs font-bold mr-3">
                                    {{ $view->user ? $view->user->initials() : '??' }}
                                </span>
                                <div class="text-sm font-medium text-zinc-900 dark:text-white">{{ $view->user ? $view->user->name : 'Usuario Eliminado' }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-500 dark:text-zinc-300">
                            {{ $view->tutorial->title }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-500 dark:text-zinc-400">
                            {{ $view->created_at->format('d M Y, h:i A') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="p-4 border-t border-zinc-200 dark:border-zinc-700">
                {{ $views->links() }}
            </div>
        </div>
    @endif

    <!-- Create Modal -->
    @if($showCreateModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm" wire:transition>
        <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-xl w-full max-w-lg p-6">
            <h2 class="text-xl font-bold text-zinc-900 dark:text-white mb-4">Agregar Nuevo Tutorial</h2>
            
            <form wire:submit="save" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Título</label>
                    <input type="text" wire:model="title" class="mt-1 block w-full rounded-md border-zinc-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-zinc-800 dark:border-zinc-700 dark:text-white">
                    @error('title') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">URL de YouTube</label>
                    <input type="text" wire:model="youtube_url" placeholder="https://www.youtube.com/watch?v=..." class="mt-1 block w-full rounded-md border-zinc-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-zinc-800 dark:border-zinc-700 dark:text-white">
                    @error('youtube_url') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Descripción (Opcional)</label>
                    <textarea wire:model="description" rows="3" class="mt-1 block w-full rounded-md border-zinc-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-zinc-800 dark:border-zinc-700 dark:text-white"></textarea>
                </div>

                <div class="flex justify-end gap-3 pt-4">
                    <button type="button" wire:click="$set('showCreateModal', false)" class="px-4 py-2 text-sm font-medium text-zinc-700 bg-white border border-zinc-300 rounded-md hover:bg-zinc-50 dark:bg-zinc-800 dark:text-zinc-300 dark:border-zinc-600 dark:hover:bg-zinc-700">
                        Cancelar
                    </button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">
                        Guardar Video
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    <!-- Player Modal -->
    @if($showPlayerModal && $currentTutorial)
    <div 
        class="fixed inset-0 z-50 flex items-center justify-center p-0 bg-black/95 backdrop-blur-md"
        x-data
        x-on:keydown.escape.window="$wire.closePlayer()"
    >
        <!-- Backdrop -->
        <div class="absolute inset-0 z-[-1]" wire:click="closePlayer"></div>

        <!-- Close button -->
        <button wire:click="closePlayer" class="absolute top-4 right-4 z-50 text-white/70 hover:text-white transition p-2 bg-black/40 hover:bg-black/60 rounded-full backdrop-blur-sm group">
            <span class="sr-only">Cerrar</span>
            <flux:icon name="x-mark" class="w-6 h-6 group-hover:scale-110 transition" />
        </button>

        <!-- Modal Container: Full screen optimized -->
        <div class="relative w-full h-full max-w-none max-h-none bg-black flex flex-col">
            
            <!-- Video Container: Responsive 16:9 with better sizing -->
            <div class="flex-1 flex items-center justify-center min-h-0">
                <div class="relative w-full h-full max-w-full">
                    <div class="aspect-video w-full h-full max-h-[85vh] flex items-center justify-center">
                        <iframe 
                            class="w-full h-full"
                            src="https://www.youtube.com/embed/{{ $currentTutorial->youtube_id }}?autoplay=1&rel=0&modestbranding=1&controls=1&showinfo=0" 
                            title="{{ $currentTutorial->title }}" 
                            frameborder="0" 
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; fullscreen" 
                            allowfullscreen>
                        </iframe>
                    </div>
                </div>
            </div>
            
            <!-- Info Section: Fixed bottom, always visible -->
            <div class="bg-zinc-900/95 backdrop-blur-sm border-t border-zinc-800 px-6 py-4">
                <div class="max-w-4xl mx-auto">
                    <h2 class="text-lg sm:text-xl font-bold text-white mb-1">{{ $currentTutorial->title }}</h2>
                    @if($currentTutorial->description)
                    <p class="text-zinc-400 text-sm leading-relaxed">{{ $currentTutorial->description }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
