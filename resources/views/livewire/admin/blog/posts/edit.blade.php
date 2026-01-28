<?php

use App\Models\Post;
use App\Models\BlogCategory;
use Illuminate\Support\Str;
use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

new 
#[Layout('components.layouts.app')]
class extends Component {
    use WithFileUploads;

    public Post $post;

    public $title = '';
    public $slug = '';
    public $excerpt = '';
    public $content = '';
    public $banner; // New upload
    public $existingBannerUrl = '';
    public $category_id = '';
    
    public $is_published = false;
    public $is_featured = false;
    public $show_author = true;
    public $show_date = true;
    public $show_on_home = false;

    public function mount(Post $post)
    {
        $this->post = $post;
        $this->title = $post->title;
        $this->slug = $post->slug;
        $this->excerpt = $post->excerpt;
        $this->content = $post->content;
        $this->existingBannerUrl = $post->banner_url;
        $this->category_id = $post->blog_category_id;
        $this->is_published = $post->is_published;
        $this->is_featured = $post->is_featured;
        $this->show_author = $post->show_author;
        $this->show_date = $post->show_date;
        $this->show_on_home = $post->show_on_home;
    }

    public function updatedTitle($value)
    {
        // Don't auto-update slug on edit unless empty or explicitly desired?
        // Usually good practice to leave slug stable on edit to avoid breaking links.
        if (empty($this->slug)) {
             $this->slug = Str::slug($value);
        }
    }

    public function save()
    {
        $this->validate([
            'title' => 'required|min:3',
            'slug' => 'required|unique:posts,slug,' . $this->post->id,
            'content' => 'required',
            'category_id' => 'nullable|exists:blog_categories,id',
            'banner' => 'nullable|image|max:2048',
        ]);

        $bannerUrl = $this->existingBannerUrl;
        if ($this->banner) {
            $path = $this->banner->store('blog-banners', 'public');
            $bannerUrl = Storage::url($path);
        }

        $this->post->update([
            'title' => $this->title,
            'slug' => $this->slug,
            'excerpt' => $this->excerpt,
            'content' => $this->content,
            'banner_url' => $bannerUrl,
            'blog_category_id' => $this->category_id ?: null,
            'is_published' => $this->is_published,
            'is_featured' => $this->is_featured,
            'show_author' => $this->show_author,
            'show_date' => $this->show_date,
            'show_on_home' => $this->show_on_home,
            'published_at' => ($this->is_published && !$this->post->published_at) ? now() : $this->post->published_at,
        ]);
        
        session()->flash('success', 'Artículo actualizado correctamente');
        return redirect()->route('dashboard.blog.posts.index');
    }

    public function with()
    {
        return [
            'categories' => BlogCategory::where('is_active', true)->get(),
        ];
    }
}; ?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6 flex justify-between items-center">
        <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Editar Artículo</h1>
        <a href="{{ route('dashboard.blog.posts.index') }}" class="text-sm text-zinc-600 hover:text-blue-600 dark:text-zinc-400">Volver al listado</a>
    </div>

    <form wire:submit="save" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Colonia Izquierda: Contenido Principal -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white dark:bg-zinc-900 p-6 rounded-lg shadow-sm border border-zinc-200 dark:border-zinc-700">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Título</label>
                        <input type="text" wire:model.live="title" class="mt-1 block w-full rounded-md border-zinc-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-zinc-800 dark:border-zinc-700 dark:text-white px-4 py-2">
                        @error('title') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Slug</label>
                        <input type="text" wire:model="slug" class="mt-1 block w-full rounded-md border-zinc-300 bg-gray-50 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-zinc-800 dark:border-zinc-700 dark:text-zinc-400 px-4 py-2">
                        @error('slug') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Extracto / Resumen</label>
                        <textarea wire:model="excerpt" rows="3" class="mt-1 block w-full rounded-md border-zinc-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-zinc-800 dark:border-zinc-700 dark:text-white px-4 py-2"></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">Contenido</label>
                        <x-quill-editor wire:model="content" />
                        @error('content') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Columna Derecha: Configuración -->
        <div class="space-y-6">
            <div class="bg-white dark:bg-zinc-900 p-6 rounded-lg shadow-sm border border-zinc-200 dark:border-zinc-700">
                <h3 class="text-lg font-medium text-zinc-900 dark:text-white mb-4">Publicación</h3>
                
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <label class="text-sm text-zinc-700 dark:text-zinc-300">Publicado</label>
                        <input type="checkbox" wire:model="is_published" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    </div>
                    <div class="flex items-center justify-between">
                        <label class="text-sm text-zinc-700 dark:text-zinc-300">Destacado</label>
                        <input type="checkbox" wire:model="is_featured" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    </div>
                     <div class="flex items-center justify-between">
                         <label class="text-sm text-zinc-700 dark:text-zinc-300">Mostrar en Home</label>
                         <input type="checkbox" wire:model="show_on_home" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    </div>
                    <hr class="border-zinc-200 dark:border-zinc-700">
                     <div class="flex items-center justify-between">
                        <label class="text-sm text-zinc-700 dark:text-zinc-300">Mostrar Autor</label>
                        <input type="checkbox" wire:model="show_author" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    </div>
                    <div class="flex items-center justify-between">
                        <label class="text-sm text-zinc-700 dark:text-zinc-300">Mostrar Fecha</label>
                        <input type="checkbox" wire:model="show_date" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    </div>
                    
                    <div class="pt-4">
                        <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Actualizar Artículo
                        </button>
                    </div>
                </div>
            </div>

            <!-- Organización -->
            <div class="bg-white dark:bg-zinc-900 p-6 rounded-lg shadow-sm border border-zinc-200 dark:border-zinc-700">
                <h3 class="text-lg font-medium text-zinc-900 dark:text-white mb-4">Organización</h3>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Categoría</label>
                        <select wire:model="category_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-zinc-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md dark:bg-zinc-800 dark:border-zinc-700 dark:text-white">
                            <option value="">Sin Categoría</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Banner -->
            <div class="bg-white dark:bg-zinc-900 p-6 rounded-lg shadow-sm border border-zinc-200 dark:border-zinc-700">
                <h3 class="text-lg font-medium text-zinc-900 dark:text-white mb-4">Imagen Destacada</h3>
                
                <div class="space-y-4">
                    @if ($banner)
                        <img src="{{ $banner->temporaryUrl() }}" class="w-full h-32 object-cover rounded-md mb-2">
                    @elseif($existingBannerUrl)
                        <img src="{{ $existingBannerUrl }}" class="w-full h-32 object-cover rounded-md mb-2">
                    @endif
                    
                    <div>
                        <label class="block w-full cursor-pointer rounded-md border-2 border-dashed border-zinc-300 p-4 text-center hover:border-zinc-400 dark:border-zinc-700 dark:hover:border-zinc-600">
                            <span class="text-sm text-zinc-500 dark:text-zinc-400">Cambiar imagen</span>
                            <input type="file" wire:model="banner" class="hidden" accept="image/*">
                        </label>
                        @error('banner') <span class="text-red-500 text-xs block mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
