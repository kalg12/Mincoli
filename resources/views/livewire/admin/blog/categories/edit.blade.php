<?php

use App\Models\BlogCategory;
use Illuminate\Support\Str;
use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Illuminate\Validation\Rule as ValidationRule;

new 
#[Layout('components.layouts.app')]
class extends Component {
    public BlogCategory $category;

    #[Rule('required|min:3|max:255')]
    public $name = '';

    public $slug = '';

    #[Rule('nullable|string')]
    public $description = '';

    public $is_active = true;

    public function mount(BlogCategory $category)
    {
        $this->category = $category;
        $this->name = $category->name;
        $this->slug = $category->slug;
        $this->description = $category->description;
        $this->is_active = $category->is_active;
    }

    public function updatedName($value)
    {
        $this->slug = Str::slug($value);
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|min:3|max:255',
            'slug' => ['required', ValidationRule::unique('blog_categories', 'slug')->ignore($this->category->id)],
            'description' => 'nullable|string',
        ]);

        $this->category->update([
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'is_active' => $this->is_active,
        ]);

        session()->flash('success', 'Categoría actualizada correctamente');
        return redirect()->route('dashboard.blog.categories.index');
    }
}; ?>

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Editar Categoría</h1>
        
        <a href="{{ route('dashboard.blog.categories.index') }}" class="rounded-lg border border-zinc-200 px-4 py-2 text-sm font-semibold text-zinc-900 hover:bg-zinc-50 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-zinc-700 dark:text-white dark:hover:bg-zinc-800">
            Volver
        </a>
    </div>

    <form wire:submit="save" class="bg-white dark:bg-zinc-900 p-6 rounded-lg shadow-sm border border-zinc-200 dark:border-zinc-700 space-y-6">
        
        <!-- Nombre -->
        <div>
            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">Nombre</label>
            <input type="text" wire:model.live="name" class="block w-full rounded-md border-zinc-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-zinc-800 dark:border-zinc-700 dark:text-white">
            @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <!-- Slug -->
        <div>
            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">Slug</label>
            <input type="text" wire:model="slug" class="block w-full rounded-md border-zinc-300 bg-gray-50 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-zinc-800 dark:border-zinc-700 dark:text-zinc-400" readonly>
            @error('slug') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <!-- Descripción -->
        <div>
            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">Descripción</label>
            <textarea wire:model="description" rows="3" class="block w-full rounded-md border-zinc-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-zinc-800 dark:border-zinc-700 dark:text-white"></textarea>
             @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <!-- Estado -->
        <div class="flex items-center">
            <input id="is_active" type="checkbox" wire:model="is_active" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
            <label for="is_active" class="ml-2 block text-sm text-zinc-900 dark:text-zinc-300">
                Categoría Activa
            </label>
        </div>

        <div class="pt-4 flex justify-end">
            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:bg-blue-500 dark:hover:bg-blue-600">
                Actualizar Categoría
            </button>
        </div>
    </form>
</div>
