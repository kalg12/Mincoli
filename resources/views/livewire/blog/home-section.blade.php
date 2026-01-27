<?php

use App\Models\Post;
use Livewire\Volt\Component;

new class extends Component {
    public function with()
    {
        $posts = Post::published()
            ->where(function ($q) {
                $q->where('show_on_home', true)
                  ->orWhere('is_featured', true);
            })
            ->latest('published_at')
            ->take(3)
            ->get();
            
        return [
            'posts' => $posts
        ];
    }
}; ?>

<div>
    @if($posts->count() > 0)
    <section class="py-16 bg-white border-t border-gray-100">
        <div class="container mx-auto px-4">
             <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                    Novedades y Artículos
                </h2>
                <p class="text-gray-600 max-w-2xl mx-auto">
                    Mantente informado con nuestros últimos posts
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto">
                @foreach($posts as $post)
                <article class="flex flex-col h-full group">
                    <a href="{{ route('blog.show', $post->slug) }}" class="block overflow-hidden rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 relative h-64 mb-6">
                        @if($post->banner_url)
                            <img src="{{ $post->banner_url }}" alt="{{ $post->title }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                                <i class="fas fa-pen-nib text-4xl text-gray-300"></i>
                            </div>
                        @endif
                        
                        @if($post->category)
                        <div class="absolute top-4 left-4">
                            <span class="bg-white/95 backdrop-blur-sm text-gray-900 text-xs font-bold px-3 py-1 rounded-full shadow-sm">
                                {{ $post->category->name }}
                            </span>
                        </div>
                        @endif
                    </a>
                    
                    <div class="flex-1 flex flex-col">
                        <div class="text-xs text-pink-600 font-bold mb-2 uppercase tracking-wide">
                            @if($post->published_at)
                                {{ $post->published_at->format('d M, Y') }}
                            @endif
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-pink-600 transition-colors leading-tight">
                            <a href="{{ route('blog.show', $post->slug) }}">{{ $post->title }}</a>
                        </h3>
                        <p class="text-gray-600 line-clamp-3 mb-4 text-sm leading-relaxed">
                            {{ $post->excerpt }}
                        </p>
                        <div class="mt-auto">
                            <a href="{{ route('blog.show', $post->slug) }}" class="text-gray-900 font-semibold text-sm hover:text-pink-600 inline-flex items-center transition-colors">
                                Leer más <i class="fas fa-arrow-right ml-2 text-xs"></i>
                            </a>
                        </div>
                    </div>
                </article>
                @endforeach
            </div>

            <div class="text-center mt-12">
                <a href="{{ route('blog.index') }}" class="inline-flex items-center text-pink-600 font-bold hover:text-pink-700 transition">
                    Ver todo el blog
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
            </div>
        </div>
    </section>
    @endif
</div>

