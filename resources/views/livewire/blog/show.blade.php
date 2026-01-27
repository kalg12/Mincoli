<?php

use App\Models\Post;
use Livewire\Volt\Component;
use Livewire\Attributes\Layout;

new 
#[Layout('layouts.app')]
class extends Component {
    public Post $post;

    public function mount($slug)
    {
        $this->post = Post::with(['author', 'category'])
            ->published()
            ->where('slug', $slug)
            ->firstOrFail();
            
        // SEO logic could be set here using view()->share() or custom blade stack
    }

    public function with()
    {
        // Related posts logic
        $related = Post::published()
            ->where('id', '!=', $this->post->id)
            ->when($this->post->blog_category_id, function($q) {
                return $q->where('blog_category_id', $this->post->blog_category_id);
            })
            ->latest()
            ->limit(3)
            ->get();
            
        if ($related->count() < 3) {
             $exclude = $related->pluck('id')->push($this->post->id);
             $more = Post::published()->whereNotIn('id', $exclude)->latest()->limit(3 - $related->count())->get();
             $related = $related->merge($more);
        }

        return [
            'relatedPosts' => $related
        ];
    }
}; ?>

<div class="bg-white min-h-screen">
    <!-- Hero Banner (Condicional) -->
    @if($post->banner_url)
    <div class="w-full h-[400px] md:h-[500px] relative">
        <img src="{{ $post->banner_url }}" alt="{{ $post->title }}" class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/30 to-transparent"></div>
        <div class="absolute bottom-0 left-0 right-0 p-8 md:p-16 text-white container mx-auto">
            @if($post->category)
                <span class="bg-pink-600 text-white text-xs font-bold px-3 py-1 rounded-full mb-4 inline-block shadow-lg">
                    {{ $post->category->name }}
                </span>
            @endif
            <h1 class="text-3xl md:text-5xl font-bold leading-tight mb-4 drop-shadow-md max-w-4xl">
                {{ $post->title }}
            </h1>
            <div class="flex items-center text-white/90 text-sm md:text-base gap-4">
                 @if($post->show_author && $post->author)
                    <div class="flex items-center">
                        <i class="far fa-user mr-2"></i> {{ $post->author->name }}
                    </div>
                 @endif
                 @if($post->show_date && $post->published_at)
                    <div class="flex items-center">
                        <i class="far fa-calendar-alt mr-2"></i> {{ $post->published_at->format('d M, Y') }}
                    </div>
                 @endif
            </div>
        </div>
    </div>
    @else
    <!-- Titulo sin banner -->
    <div class="bg-gradient-to-br from-gray-900 to-gray-800 text-white py-16 md:py-24">
        <div class="container mx-auto px-4 text-center">
            @if($post->category)
                <span class="bg-pink-600 text-white text-xs font-bold px-3 py-1 rounded-full mb-4 inline-block">
                    {{ $post->category->name }}
                </span>
            @endif
             <h1 class="text-3xl md:text-5xl font-bold leading-tight mb-4 max-w-4xl mx-auto">
                {{ $post->title }}
            </h1>
            <div class="flex justify-center items-center text-white/80 text-sm md:text-base gap-6">
                 @if($post->show_author && $post->author)
                    <span>Por {{ $post->author->name }}</span>
                 @endif
                 @if($post->show_date && $post->published_at)
                    <span>•</span>
                    <span>{{ $post->published_at->format('d M, Y') }}</span>
                 @endif
            </div>
        </div>
    </div>
    @endif

    <!-- Content -->
    <div class="container mx-auto px-4 py-12 md:py-16">
        <div class="max-w-3xl mx-auto">
            @if($post->excerpt)
                <div class="text-xl md:text-2xl text-gray-600 leading-relaxed mb-8 font-light italic border-l-4 border-pink-500 pl-6">
                    {{ $post->excerpt }}
                </div>
            @endif

            <div class="prose prose-lg md:prose-xl max-w-none prose-pink prose-img:rounded-xl prose-img:shadow-lg text-gray-800">
                {!! $post->content !!}
            </div>

            <!-- Tags -->
            @if(false && $post->tags->count() > 0) 
            <!-- Placeholder for tags if implemented -->
            <div class="mt-12 pt-8 border-t border-gray-100">
                <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-4">Etiquetas</h3>
                <div class="flex flex-wrap gap-2">
                    <!-- loop tags -->
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Related Posts -->
    @if($relatedPosts->count() > 0)
    <div class="bg-gray-50 py-16 border-t border-gray-100">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center text-gray-900 mb-12">Quizás te pueda interesar</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto">
                @foreach($relatedPosts as $rPost)
                <a href="{{ route('blog.show', $rPost->slug) }}" class="group block bg-white rounded-xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden transform hover:-translate-y-1">
                    <div class="relative h-48 overflow-hidden">
                        @if($rPost->banner_url)
                            <img src="{{ $rPost->banner_url }}" alt="{{ $rPost->title }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                        @else
                             <div class="w-full h-full bg-gradient-to-br from-pink-100 to-purple-100 flex items-center justify-center">
                                <i class="fas fa-newspaper text-3xl text-pink-300"></i>
                            </div>
                        @endif
                        <div class="absolute inset-0 bg-black/5 group-hover:bg-transparent transition-colors"></div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-2 group-hover:text-pink-600 transition-colors">
                            {{ $rPost->title }}
                        </h3>
                         @if($rPost->published_at)
                            <p class="text-xs text-gray-500">{{ $rPost->published_at->format('d M, Y') }}</p>
                        @endif
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </div>
    @endif
</div>
