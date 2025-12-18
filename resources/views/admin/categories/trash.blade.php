<x-layouts.app :title="__('Papelera de Categorías')">
    <div class="p-6 space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Papelera de Categorías</h1>
                <p class="text-sm text-zinc-600 dark:text-zinc-400">Restaura o elimina permanentemente categorías</p>
            </div>
            <a href="{{ route('dashboard.categories.index') }}" class="rounded-lg border border-zinc-200 px-4 py-2 text-sm font-semibold text-zinc-900 hover:bg-zinc-50 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:border-zinc-700 dark:text-white dark:hover:bg-zinc-800 dark:focus:ring-offset-zinc-900">
                Volver a categorías
            </a>
        </div>

        <!-- Trashed Categories Grid -->
        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
            @forelse($categories as $category)
            <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
                <div class="mb-4 flex items-center justify-between">
                    <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-zinc-100 dark:bg-zinc-800">
                        <svg class="h-6 w-6 text-zinc-400 dark:text-zinc-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                        </svg>
                    </div>
                    <span class="rounded-full bg-red-100 px-2 py-1 text-xs font-semibold text-red-700 dark:bg-red-900/30 dark:text-red-400">Eliminada</span>
                </div>
                <h3 class="mb-2 text-lg font-semibold text-zinc-900 dark:text-white">{{ $category->name }}</h3>
                <p class="mb-2 text-sm text-zinc-600 dark:text-zinc-400">{{ $category->products_count }} producto{{ $category->products_count != 1 ? 's' : '' }}</p>
                <p class="mb-4 text-xs text-zinc-500 dark:text-zinc-500">Eliminada: {{ $category->deleted_at->diffForHumans() }}</p>
                <div class="flex gap-2">
                    <form action="{{ route('dashboard.categories.restore', $category->id) }}" method="POST" class="flex-1">
                        @csrf
                        <button type="submit" class="w-full rounded-lg border border-emerald-200 bg-white px-3 py-1.5 text-xs font-medium text-emerald-600 hover:bg-emerald-50 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:border-emerald-900/50 dark:bg-emerald-900/20 dark:text-emerald-400 dark:hover:bg-emerald-900/30 dark:focus:ring-offset-zinc-900 transition-colors text-center">
                            Restaurar
                        </button>
                    </form>
                    <form action="{{ route('dashboard.categories.forceDelete', $category->id) }}" method="POST" class="flex-1" onsubmit="return confirm('¿Estás seguro? Esta acción no se puede deshacer y la categoría se eliminará permanentemente.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full rounded-lg border border-red-200 bg-white px-3 py-1.5 text-xs font-medium text-red-600 hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-red-500 dark:border-red-900/50 dark:bg-red-900/20 dark:text-red-400 dark:hover:bg-red-900/30 dark:focus:ring-offset-zinc-900 transition-colors">
                            Eliminar
                        </button>
                    </form>
                </div>
            </div>
            @empty
            <div class="col-span-full rounded-xl border border-dashed border-zinc-200 bg-zinc-50 p-12 text-center dark:border-zinc-700 dark:bg-zinc-900">
                <svg class="mx-auto h-12 w-12 text-zinc-400 dark:text-zinc-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
                <h3 class="mt-4 text-lg font-semibold text-zinc-900 dark:text-white">La papelera está vacía</h3>
                <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-400">No hay categorías eliminadas</p>
            </div>
            @endforelse
        </div>
    </div>
</x-layouts.app>
