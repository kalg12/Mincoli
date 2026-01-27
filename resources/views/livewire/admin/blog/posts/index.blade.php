<?php

use App\Models\Post;
use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;

new 
#[Layout('components.layouts.app')]
class extends Component {
    use WithPagination;

    public function delete($id)
    {
        Post::findOrFail($id)->delete();
        session()->flash('success', 'Artículo eliminado');
    }

    public function with()
    {
        return [
            'posts' => Post::with(['author', 'category'])->latest()->paginate(10),
        ];
    }
}; ?>

<div>
    <div class="flex-1">
        <div class="border-b border-zinc-200 bg-white px-6 py-4 dark:border-zinc-700 dark:bg-zinc-900">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Artículos del Blog</h1>
                    <p class="text-sm text-zinc-600 dark:text-zinc-400">Publica novedades y contenido para tus clientes</p>
                </div>
                <div>
                    <a href="{{ route('dashboard.blog.posts.create') }}" class="inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:bg-blue-500 dark:hover:bg-blue-600">
                        Nuevo Artículo
                    </a>
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
                            <th class="px-6 py-4 font-medium">Título</th>
                            <th class="px-6 py-4 font-medium">Autor</th>
                            <th class="px-6 py-4 font-medium">Categoría</th>
                            <th class="px-6 py-4 font-medium">Estado</th>
                            <th class="px-6 py-4 font-medium text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800">
                        @forelse($posts as $post)
                        <tr class="transition-colors hover:bg-zinc-100/50 dark:hover:bg-zinc-800/70">
                            <td class="px-6 py-4">
                                <div class="font-medium text-zinc-900 dark:text-white">{{ $post->title }}</div>
                                <div class="text-xs text-zinc-500">{{ $post->created_at->format('d M Y') }}</div>
                            </td>
                            <td class="px-6 py-4 text-zinc-600 dark:text-zinc-400">
                                {{ $post->author->name ?? 'Desconocido' }}
                            </td>
                            <td class="px-6 py-4">
                                @if($post->category)
                                    <span class="inline-flex items-center rounded-full bg-zinc-100 px-2.5 py-0.5 text-xs font-medium text-zinc-800 dark:bg-zinc-700 dark:text-zinc-300">
                                        {{ $post->category->name }}
                                    </span>
                                @else
                                    <span class="text-xs text-zinc-400">Sin categoría</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex gap-2">
                                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $post->is_published ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400' }}">
                                        {{ $post->is_published ? 'Publicado' : 'Borrador' }}
                                    </span>
                                    @if($post->is_featured)
                                        <span class="inline-flex items-center rounded-full bg-purple-100 px-2.5 py-0.5 text-xs font-medium text-purple-800 dark:bg-purple-900/30 dark:text-purple-400">
                                            Destacado
                                        </span>
                                    @endif
                                </div>
                            </td>
                    <td class="px-6 py-4 text-right flex justify-end gap-2">
                                <a href="{{ route('dashboard.blog.posts.edit', $post->id) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">Editar</a>
                                <button wire:click="delete({{ $post->id }})" wire:confirm="¿Estás seguro de eliminar este artículo?" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">Eliminar</button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-zinc-500 dark:text-zinc-400">No hay artículos creados aún.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-zinc-200 dark:border-zinc-700">
                {{ $posts->links() }}
            </div>
        </div>
    </div>
</div>
