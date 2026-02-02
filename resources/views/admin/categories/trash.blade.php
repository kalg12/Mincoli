<x-layouts.app :title="__('Papelera de Categorías')">
    <div class="p-6 space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Papelera de Categorías</h1>
                <p class="text-sm text-zinc-600 dark:text-zinc-400">Restaura o elimina permanentemente categorías</p>
            </div>
            <a href="{{ route('dashboard.categories.index') }}" class="inline-flex items-center justify-center rounded-lg border border-zinc-200 px-4 py-2 text-sm font-semibold text-zinc-900 hover:bg-zinc-50 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:border-zinc-700 dark:text-white dark:hover:bg-zinc-800 dark:focus:ring-offset-zinc-900 transition-colors">
                Volver a categorías
            </a>
        </div>

        <div class="overflow-hidden rounded-xl border border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-900 shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-zinc-500 dark:text-zinc-400">
                    <thead class="bg-zinc-50 text-xs uppercase text-zinc-700 dark:bg-zinc-800/50 dark:text-zinc-400">
                        <tr>
                            <th class="px-6 py-4 font-semibold">Categoría</th>
                            <th class="hidden md:table-cell px-6 py-4 font-semibold">Productos</th>
                            <th class="hidden sm:table-cell px-6 py-4 font-semibold">Eliminado</th>
                            <th class="px-6 py-4 font-semibold text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800">
                        @forelse($categories as $category)
                            <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors">
                                <td class="px-6 py-4 text-zinc-900 dark:text-white font-medium">
                                    {{ $category->name }}
                                    <!-- Mobile Meta -->
                                    <div class="md:hidden mt-1 text-xs text-zinc-500">
                                        <span>{{ $category->products_count }} prod.</span>
                                        <span class="mx-1">•</span>
                                        <span>Eliminado {{ $category->deleted_at->diffForHumans() }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 hidden md:table-cell">
                                    {{ $category->products_count }} productos
                                </td>
                                <td class="px-6 py-4 hidden sm:table-cell text-xs text-zinc-500">
                                    {{ $category->deleted_at->diffForHumans() }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="inline-flex items-center justify-end gap-1 rounded-lg bg-zinc-100 p-1 dark:bg-zinc-800">
                                        <form action="{{ route('dashboard.categories.restore', $category->id) }}" method="POST" class="inline-flex">
                                            @csrf
                                            <button type="submit" title="Restaurar" class="rounded-md p-1.5 text-emerald-600 hover:bg-white hover:shadow-sm dark:text-emerald-400 dark:hover:bg-zinc-700 transition-all">
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                                </svg>
                                            </button>
                                        </form>
                                        <div class="h-4 w-px bg-zinc-200 dark:bg-zinc-700"></div>
                                        <form action="{{ route('dashboard.categories.forceDelete', $category->id) }}" method="POST" class="inline-flex" onsubmit="return confirm('¿Estás seguro? Esta acción es irreversible.');">
                                            @csrf @method('DELETE')
                                            <button type="submit" title="Eliminar Permanentemente" class="rounded-md p-1.5 text-red-600 hover:bg-white hover:text-red-700 hover:shadow-sm dark:text-red-400 dark:hover:bg-zinc-700 dark:hover:text-red-300 transition-all">
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-20 text-center">
                                    <svg class="mx-auto h-12 w-12 text-zinc-300 dark:text-zinc-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    <h3 class="mt-4 text-sm font-semibold text-zinc-900 dark:text-white">La papelera está vacía</h3>
                                    <p class="mt-1 text-xs text-zinc-500">No hay categorías eliminadas</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layouts.app>
