<?php

use App\Models\BlogCategory;
use Illuminate\Support\Str;
use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\WithPagination;

new 
#[Layout('components.layouts.app')] 
class extends Component {
    use WithPagination;

    #[Rule('required|min:3|max:255')]
    public $name = '';

    #[Rule('nullable|string')]
    public $description = '';

    public $is_active = true;
    public $editingId = null;
    public $showModal = false;

    public function updatedName($value)
    {
        // Auto-generate slug only on create
        // Logic handled in save/update or via observer, but simple here
    }

    public function create()
    {
        $this->reset(['name', 'description', 'is_active', 'editingId']);
        $this->showModal = true;
    }

    public function edit(BlogCategory $category)
    {
        $this->editingId = $category->id;
        $this->name = $category->name;
        $this->description = $category->description;
        $this->is_active = $category->is_active;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        $slug = Str::slug($this->name);
        
        // Ensure unique slug
        if ($this->editingId) {
             // Check uniqueness excluding current
             $exists = BlogCategory::where('slug', $slug)->where('id', '!=', $this->editingId)->exists();
             if ($exists) $slug .= '-' . uniqid(); // simplified logic
             
             BlogCategory::where('id', $this->editingId)->update([
                 'name' => $this->name,
                 'slug' => $slug,
                 'description' => $this->description,
                 'is_active' => $this->is_active,
             ]);
        } else {
             $exists = BlogCategory::where('slug', $slug)->exists();
             if ($exists) $slug .= '-' . uniqid();

             BlogCategory::create([
                 'name' => $this->name,
                 'slug' => $slug,
                 'description' => $this->description,
                 'is_active' => $this->is_active,
             ]);
        }

        $this->showModal = false;
        $this->reset(['name', 'description', 'is_active', 'editingId']);
        $this->dispatch('notify', 'Categoría guardada correctamente'); // Assuming a global notify listener exists or using flash
        session()->flash('success', 'Categoría guardada correctamente');
    }

    public function delete($id)
    {
        BlogCategory::findOrFail($id)->delete();
        session()->flash('success', 'Categoría eliminada');
    }

    public function with()
    {
        return [
            'categories' => BlogCategory::latest()->paginate(10),
        ];
    }
}; ?>

<div>
    <div class="flex-1">
        <div class="border-b border-zinc-200 bg-white px-6 py-4 dark:border-zinc-700 dark:bg-zinc-900">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Categorías del Blog</h1>
                    <p class="text-sm text-zinc-600 dark:text-zinc-400">Gestiona las categorías de tus artículos</p>
                </div>
                <div>
                    <button wire:click="create" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:bg-blue-500 dark:hover:bg-blue-600">
                        Nueva Categoría
                    </button>
                </div>
            </div>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative m-6" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <div class="bg-white dark:bg-zinc-900">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="border-b border-zinc-200 text-left text-zinc-600 dark:border-zinc-700 dark:text-zinc-400">
                        <tr>
                            <th class="px-6 py-4 font-medium">Nombre</th>
                            <th class="px-6 py-4 font-medium">Slug</th>
                            <th class="px-6 py-4 font-medium">Estado</th>
                            <th class="px-6 py-4 font-medium text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800">
                        @foreach($categories as $category)
                        <tr class="transition-colors hover:bg-zinc-100/50 dark:hover:bg-zinc-800/70">
                            <td class="px-6 py-4 font-medium text-zinc-900 dark:text-white">{{ $category->name }}</td>
                            <td class="px-6 py-4 text-zinc-600 dark:text-zinc-400">{{ $category->slug }}</td>
                            <td class="px-6 py-4">
                                <span class="rounded-full px-2 py-1 text-xs font-semibold {{ $category->is_active ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400' : 'bg-gray-100 text-gray-700 dark:bg-gray-900/30 dark:text-gray-400' }}">
                                    {{ $category->is_active ? 'Activa' : 'Inactiva' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right flex justify-end gap-2">
                                <button wire:click="edit({{ $category->id }})" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">Editar</button>
                                <button wire:click="delete({{ $category->id }})" wire:confirm="¿Estás seguro de eliminar esta categoría?" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">Eliminar</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-zinc-200 dark:border-zinc-700">
                {{ $categories->links() }}
            </div>
        </div>
    </div>

    <!-- Modal (Alpine based for simplicity inside Volt) -->
    @if($showModal)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" wire:click="$set('showModal', false)"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block align-bottom bg-white dark:bg-zinc-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white dark:bg-zinc-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg leading-6 font-medium text-zinc-900 dark:text-white" id="modal-title">
                        {{ $editingId ? 'Editar Categoría' : 'Nueva Categoría' }}
                    </h3>
                    <div class="mt-4 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Nombre</label>
                            <input type="text" wire:model="name" class="mt-1 block w-full rounded-md border-zinc-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-zinc-700 dark:border-zinc-600 dark:text-white sm:text-sm">
                            @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Descripción</label>
                            <textarea wire:model="description" class="mt-1 block w-full rounded-md border-zinc-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-zinc-700 dark:border-zinc-600 dark:text-white sm:text-sm"></textarea>
                        </div>
                        <div class="flex items-center">
                            <input id="is_active" type="checkbox" wire:model="is_active" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="is_active" class="ml-2 block text-sm text-zinc-900 dark:text-zinc-300">
                                Activa
                            </label>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-zinc-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button wire:click="save" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Guardar
                    </button>
                    <button wire:click="$set('showModal', false)" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm dark:bg-zinc-600 dark:text-white dark:border-zinc-500 dark:hover:bg-zinc-500">
                        Cancelar
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
