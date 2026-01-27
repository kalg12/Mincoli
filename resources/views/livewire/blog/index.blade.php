<?php

use App\Models\Post;
use App\Models\BlogCategory;
use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\WithPagination;

new 
#[Layout('layouts.app')]
class extends Component {
    use WithPagination;

    #[Url]
    public $search = '';

    #[Url]
    public $category = '';
    
    // Reset pagination when filtering
    public function updatedSearch() { $this->resetPage(); }
    public function updatedCategory() { $this->resetPage(); }

    public function with()
    {
        $posts = Post::with(['author', 'category'])
            ->published()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('title', 'like', '%' . $this->search . '%')
                      ->orWhere('excerpt', 'like', '%' . $this->search . '%')
                      ->orWhere('content', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->category, function ($query) {
                $query->whereHas('category', fn($q) => $q->where('slug', $this->category));
            })
            ->latest('published_at')
            ->paginate(9);

        return [
            'posts' => $posts,
            'categories' => BlogCategory::where('is_active', true)->get(),
        ];
    }
}; ?>

<div class="bg-gray-50 min-h-screen">
    <!-- Header / Title -->
    <div class="bg-white shadow-sm border-b border-gray-100">
        <div class="container mx-auto px-4 py-12 text-center">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Blog y Novedades</h1>
            <p class="text-gray-600 max-w-2xl mx-auto text-lg">
                Descubre las últimas noticias, consejos y actualizaciones de Mincoli.
            </p>
        </div>
    </div>

    <!-- Filters & Search -->
    <div class="container mx-auto px-4 py-8">
        <div class="flex flex-col md:flex-row gap-4 items-center justify-between mb-8">
            <!-- Search -->
            <div class="relative w-full md:w-96">
                <input 
                    type="text" 
                    wire:model.live.debounce.300ms="search" 
                    placeholder="Buscar artículos..." 
                    class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-pink-500 text-gray-900"
                >
                <div class="absolute left-3 top-2.5 text-gray-400">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
            </div>

            <!-- Categories -->
            <div class="w-full md:w-auto">
                <select wire:model.live="category" class="w-full md:w-64 border border-gray-300 rounded-lg py-2 pl-3 pr-10 focus:ring-pink-500 focus:border-pink-500 text-gray-900">
                    <option value="">Todas las categorías</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->slug }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Posts Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($posts as $post)
            <article class="bg-white rounded-2xl shadow-sm hover:shadow-lg transition-shadow duration-300 overflow-hidden flex flex-col h-full border border-gray-100">
                <a href="{{ route('blog.show', $post->slug) }}" class="block overflow-hidden h-56 relative group">
                    @if($post->banner_url)
                        <img src="{{ $post->banner_url }}" alt="{{ $post->title }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-pink-100 to-purple-100 flex items-center justify-center">
                            <i class="fas fa-newspaper text-4xl text-pink-300"></i>
                        </div>
                    @endif
                    
                    @if($post->category)
                        <div class="absolute top-4 left-4">
                            <span class="bg-white/90 backdrop-blur-sm text-pink-600 text-xs font-bold px-3 py-1 rounded-full shadow-sm">
                                {{ $post->category->name }}
                            </span>
                        </div>
                    @endif
                </a>
                
                <div class="p-6 flex flex-col flex-1">
                    <div class="flex items-center text-xs text-gray-500 mb-3 space-x-2">
                        @if($post->show_date && $post->published_at)
                            <time datetime="{{ $post->published_at->format('Y-m-d') }}">
                                <i class="far fa-calendar-alt mr-1"></i>
                                {{ $post->published_at->format('d M, Y') }}
                            </time>
                        @endif
                        @if($post->show_author && $post->author)
                            <span>•</span>
                            <span>
                                <i class="far fa-user mr-1"></i>
                                {{ $post->author->name }}
                            </span>
                        @endif
                    </div>

                    <h2 class="text-xl font-bold text-gray-900 mb-3 line-clamp-2 hover:text-pink-600 transition-colors">
                        <a href="{{ route('blog.show', $post->slug) }}">{{ $post->title }}</a>
                    </h2>

                    <p class="text-gray-600 text-sm line-clamp-3 mb-4 flex-1">
                        {{ $post->excerpt }}
                    </p>

                    <div class="mt-auto pt-4 border-t border-gray-100">
                        <a href="{{ route('blog.show', $post->slug) }}" class="text-pink-600 font-semibold text-sm hover:text-pink-700 inline-flex items-center group">
                            Leer artículo completo
                            <svg class="w-4 h-4 ml-2 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </a>
                    </div>
                </div>
            </article>
            @empty
            <div class="col-span-full py-12 text-center text-gray-500">
                <i class="fas fa-search text-4xl mb-4 text-gray-300"></i>
                <p class="text-lg">No se encontraron artículos con los filtros seleccionados.</p>
                <button wire:click="$set('search', '')" class="text-pink-600 hover:underline mt-2">Limpiar búsqueda</button>
            </div>
            @endforelse
        </div>

        <div class="mt-12">
            {{ $posts->links() }}
        </div>
    </div>
</div>
