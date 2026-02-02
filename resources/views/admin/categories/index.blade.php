<x-layouts.app :title="__('Categorías')">
    <div class="p-6 space-y-6">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Categorías</h1>
                <p class="text-sm text-zinc-600 dark:text-zinc-400">Organiza tu catálogo por categorías y subcategorías</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('dashboard.categories.trash') }}" class="rounded-lg border border-zinc-200 px-4 py-2 text-sm font-semibold text-zinc-900 hover:bg-zinc-50 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:border-zinc-700 dark:text-white dark:hover:bg-zinc-800 dark:focus:ring-offset-zinc-900 inline-flex items-center gap-2">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Papelera
                </a>
                <a href="{{ route('dashboard.categories.create') }}" class="inline-flex items-center justify-center rounded-lg bg-pink-600 px-4 py-2 text-sm font-semibold text-white hover:bg-pink-700 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-offset-2 dark:bg-pink-500 dark:hover:bg-pink-600 dark:focus:ring-offset-zinc-900 transition-colors">
                    Nueva categoría
                </a>
            </div>
        </div>

        <div class="rounded-xl border border-zinc-200 bg-white p-4 dark:border-zinc-700 dark:bg-zinc-900 shadow-sm">
            <form id="search-form" action="{{ route('dashboard.categories.index') }}" method="GET" class="flex gap-4">
                <div class="relative flex-1">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                        <svg class="h-4 w-4 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text" id="search-input" name="search" value="{{ request('search') }}" placeholder="Buscar categorías..." class="w-full rounded-lg border border-zinc-200 bg-white py-2 pl-10 pr-4 text-sm text-zinc-900 placeholder-zinc-500 focus:border-pink-500 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:placeholder-zinc-400 dark:focus:ring-offset-zinc-900 transition-shadow">
                </div>
                <button type="submit" class="hidden md:block rounded-lg bg-zinc-900 px-4 py-2 text-sm font-semibold text-white hover:bg-zinc-800 focus:outline-none focus:ring-2 focus:ring-zinc-900 focus:ring-offset-2 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-100 dark:focus:ring-offset-zinc-900 transition-colors">
                    Filtrar
                </button>
            </form>
        </div>

        <!-- Categories Table -->
        <div class="overflow-hidden rounded-xl border border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-900">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-zinc-500 dark:text-zinc-400">
                    <thead class="bg-zinc-50 text-xs uppercase text-zinc-700 dark:bg-zinc-800/50 dark:text-zinc-400">
                        <tr>
                            <th class="px-6 py-4 font-semibold">Categoría</th>
                            <th class="hidden md:table-cell px-6 py-4 font-semibold">Productos</th>
                            <th class="hidden sm:table-cell px-6 py-4 font-semibold">Estado</th>
                            <th class="px-6 py-4 font-semibold text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800">
                        @php
                            $parents = $categories->whereNull('parent_id');
                            $orphans = $categories->whereNotNull('parent_id')->filter(function($cat) use ($parents) {
                                return !$parents->pluck('id')->contains($cat->parent_id);
                            });
                        @endphp

                        @foreach($parents as $category)
                            <tr class="group hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-pink-50 text-pink-600 dark:bg-pink-900/20 dark:text-pink-500">
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="font-bold text-zinc-900 dark:text-white">{{ $category->name }}</div>
                                            <div class="text-xs text-zinc-400 line-clamp-1">{{ $category->description ?? 'Sin descripción' }}</div>
                                            <!-- Mobile Meta -->
                                            <div class="md:hidden mt-1 flex items-center gap-2 text-xs text-zinc-500">
                                                <a href="{{ route('dashboard.products.index', ['category_id' => $category->id]) }}" class="hover:text-pink-600 hover:underline">
                                                    {{ $category->products_count }} prod.
                                                </a>
                                                <span class="text-zinc-300">•</span>
                                                <span class="{{ $category->is_active ? 'text-emerald-600' : 'text-zinc-400' }}">
                                                    {{ $category->is_active ? 'Activa' : 'Inactiva' }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 hidden md:table-cell">
                                    <a href="{{ route('dashboard.products.index', ['category_id' => $category->id]) }}" class="inline-flex items-center rounded-md bg-zinc-100 px-2.5 py-0.5 text-xs font-medium text-zinc-800 hover:bg-zinc-200 dark:bg-zinc-800 dark:text-zinc-300 dark:hover:bg-zinc-700 transition-colors">
                                        {{ $category->products_count }} producto{{ $category->products_count != 1 ? 's' : '' }}
                                    </a>
                                </td>
                                <td class="px-6 py-4 hidden sm:table-cell">
                                    @if($category->is_active)
                                        <span class="inline-flex items-center gap-1.5 text-emerald-600 dark:text-emerald-400 text-xs font-medium">
                                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-600 dark:bg-emerald-400"></span>
                                            Activa
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 text-zinc-400 dark:text-zinc-500 text-xs font-medium">
                                            <span class="h-1.5 w-1.5 rounded-full bg-zinc-400 dark:bg-zinc-500"></span>
                                            Inactiva
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="inline-flex items-center justify-end gap-1 rounded-lg bg-zinc-100 p-1 dark:bg-zinc-800">
                                        <a href="{{ route('dashboard.categories.create', ['parent_id' => $category->id]) }}" title="Añadir Subcategoría" class="rounded-md p-1.5 text-zinc-500 hover:bg-white hover:text-pink-600 hover:shadow-sm dark:text-zinc-400 dark:hover:bg-zinc-700 dark:hover:text-white transition-all">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                            </svg>
                                        </a>
                                        <div class="h-4 w-px bg-zinc-200 dark:bg-zinc-700"></div>
                                        <a href="{{ route('dashboard.categories.edit', $category->id) }}" title="Editar" class="rounded-md p-1.5 text-zinc-500 hover:bg-white hover:text-zinc-900 hover:shadow-sm dark:text-zinc-400 dark:hover:bg-zinc-700 dark:hover:text-white transition-all">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                        <form action="{{ route('dashboard.categories.destroy', $category->id) }}" method="POST" class="inline-flex" onsubmit="return confirm('¿Estás seguro?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" title="Eliminar" class="rounded-md p-1.5 text-zinc-500 hover:bg-white hover:text-red-600 hover:shadow-sm dark:text-zinc-400 dark:hover:bg-zinc-700 dark:hover:text-red-400 transition-all">
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            
                            @foreach($categories->where('parent_id', $category->id) as $sub)
                                <tr class="group hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors">
                                    <td class="px-6 py-3 pl-8 md:pl-14">
                                        <div class="flex items-center gap-3">
                                            <div class="h-px w-4 bg-zinc-200 dark:bg-zinc-700"></div>
                                            <div>
                                                <div class="font-medium text-zinc-900 dark:text-white">{{ $sub->name }}</div>
                                                <div class="text-xs text-zinc-400 line-clamp-1">{{ $sub->description ?? 'Sin descripción' }}</div>
                                                <!-- Mobile Meta -->
                                                <div class="md:hidden mt-1 flex items-center gap-2 text-xs text-zinc-500">
                                                    <a href="{{ route('dashboard.products.index', ['category_id' => $sub->id]) }}" class="hover:text-pink-600 hover:underline">
                                                        {{ $sub->products_count }} prod.
                                                    </a>
                                                    <span class="text-zinc-300">•</span>
                                                    <span class="{{ $sub->is_active ? 'text-emerald-600' : 'text-zinc-400' }}">
                                                        {{ $sub->is_active ? 'Activa' : 'Inactiva' }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-3 text-xs hidden md:table-cell">
                                        <a href="{{ route('dashboard.products.index', ['category_id' => $sub->id]) }}" class="hover:text-pink-600 hover:underline transition-colors">
                                            {{ $sub->products_count }} productos
                                        </a>
                                    </td>
                                    <td class="px-6 py-3 hidden sm:table-cell">
                                        @if($sub->is_active)
                                            <span class="text-xs font-medium text-emerald-600 dark:text-emerald-400">Activa</span>
                                        @else
                                            <span class="text-xs font-medium text-zinc-400">Inactiva</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-3 text-right">
                                        <div class="inline-flex items-center justify-end gap-1 rounded-lg bg-zinc-100 p-1 dark:bg-zinc-800">
                                            <a href="{{ route('dashboard.categories.edit', $sub->id) }}" title="Editar" class="rounded-md p-1.5 text-zinc-500 hover:bg-white hover:text-zinc-900 hover:shadow-sm dark:text-zinc-400 dark:hover:bg-zinc-700 dark:hover:text-white transition-all">
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </a>
                                            <form action="{{ route('dashboard.categories.destroy', $sub->id) }}" method="POST" class="inline-flex" onsubmit="return confirm('¿Estás seguro?');">
                                                @csrf @method('DELETE')
                                                <button type="submit" title="Eliminar" class="rounded-md p-1.5 text-zinc-500 hover:bg-white hover:text-red-600 hover:shadow-sm dark:text-zinc-400 dark:hover:bg-zinc-700 dark:hover:text-red-400 transition-all">
                                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach

                        @if($parents->isEmpty() && $orphans->isNotEmpty())
                            @foreach($orphans as $category)
                                <tr class="group hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="font-bold text-zinc-900 dark:text-white">{{ $category->name }}</div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">{{ $category->products_count }}</td>
                                    <td class="px-6 py-4">...</td>
                                    <td class="px-6 py-4">...</td>
                                </tr>
                            @endforeach
                        @endif

                        @if($categories->isEmpty())
                            <tr>
                                <td colspan="4" class="px-6 py-20 text-center">
                                    <svg class="mx-auto h-12 w-12 text-zinc-300 dark:text-zinc-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                    </svg>
                                    <h3 class="mt-4 text-sm font-semibold text-zinc-900 dark:text-white">Sin resultados</h3>
                                    <p class="mt-1 text-xs text-zinc-500">No encontramos categorías que coincidan con tu búsqueda</p>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        // Live search with debounce
        let searchTimeout;
        const searchInput = document.getElementById('search-input');
        const searchForm = document.getElementById('search-form');

        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                searchForm.submit();
            }, 600); // Wait 600ms after user stops typing
        });

        // Set cursor at the end of input after reload
        searchInput.focus();
        const val = searchInput.value;
        searchInput.value = '';
        searchInput.value = val;
    </script>
</x-layouts.app>
