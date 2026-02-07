<x-layouts.app :title="__('Papelera - Cotizaciones')">
    <div class="flex-1">
        <div class="border-b border-zinc-200 bg-white px-6 py-4 dark:border-zinc-700 dark:bg-zinc-900">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Papelera de Cotizaciones</h1>
                    <p class="text-sm text-zinc-600 dark:text-zinc-400">Cotizaciones eliminadas</p>
                </div>
                <a href="{{ route('dashboard.pos.quotations.index') }}" 
                   class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-zinc-600 hover:bg-zinc-700 text-white text-sm font-semibold transition-all">
                    <i class="fas fa-arrow-left"></i>
                    <span>Volver</span>
                </a>
            </div>
        </div>

        <div class="p-6">
            <!-- Filter Bar -->
            <div class="mb-6 rounded-lg border border-zinc-200 bg-white p-4 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                <form action="{{ route('dashboard.pos.quotations.trash') }}" method="GET" class="flex flex-wrap items-end gap-4">
                    <!-- Search -->
                    <div class="flex-1 min-w-[250px]">
                        <label for="search" class="block text-xs font-medium text-zinc-500 dark:text-zinc-400 mb-1">Buscar Folio o Cliente</label>
                        <div class="relative">
                            <input type="text" name="search" id="search" value="{{ request('search') }}" 
                                placeholder="Folio, nombre o teléfono..." 
                                class="w-full rounded-lg border-zinc-300 bg-white px-3 py-2 pl-10 text-sm shadow-sm focus:border-pink-500 focus:ring-pink-500 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                <i class="fas fa-search text-zinc-400"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Per Page -->
                    <div class="w-full sm:w-auto">
                        <label for="per_page" class="block text-xs font-medium text-zinc-500 dark:text-zinc-400 mb-1">Registros</label>
                        <select name="per_page" id="per_page" onchange="this.form.submit()"
                            class="w-full rounded-lg border-zinc-300 bg-white px-3 py-2 text-sm shadow-sm focus:border-pink-500 focus:ring-pink-500 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white">
                            <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                            <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                            <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                        </select>
                    </div>

                    <!-- Actions -->
                    <div class="flex gap-2">
                        <button type="submit" class="inline-flex items-center rounded-lg bg-pink-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-pink-700 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-offset-2">
                            <i class="fas fa-filter mr-2"></i> Filtrar
                        </button>
                        <a href="{{ route('dashboard.pos.quotations.trash') }}" class="inline-flex items-center rounded-lg border border-zinc-300 bg-white px-4 py-2 text-sm font-medium text-zinc-700 shadow-sm hover:bg-zinc-50 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-offset-2 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white dark:hover:bg-zinc-700">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            <!-- Table -->
            <div class="rounded-lg border border-zinc-200 bg-white shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-zinc-600 dark:text-zinc-400">
                        <thead class="bg-zinc-50 text-xs uppercase text-zinc-500 dark:bg-zinc-800 dark:text-zinc-400">
                            <tr>
                                <th class="px-6 py-3 whitespace-nowrap">Folio</th>
                                <th class="px-6 py-3 whitespace-nowrap">Cliente</th>
                                <th class="px-6 py-3 whitespace-nowrap">Usuario</th>
                                <th class="px-6 py-3 whitespace-nowrap">Total Estimado</th>
                                <th class="px-6 py-3 whitespace-nowrap">Eliminada</th>
                                <th class="px-6 py-3 text-right whitespace-nowrap" style="min-width: 320px;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                            @forelse($quotations as $quotation)
                            <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50 group">
                                <td class="px-6 py-4 font-bold text-zinc-900 dark:text-white whitespace-nowrap">
                                    {{ $quotation->folio }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-medium text-zinc-800 dark:text-zinc-200">{{ $quotation->customer_name }}</div>
                                    <div class="text-xs text-zinc-500">{{ $quotation->customer_phone ?? 'Sin teléfono' }}</div>
                                </td>
                                <td class="px-6 py-4 text-xs whitespace-nowrap">
                                    {{ $quotation->user->name }}
                                </td>
                                <td class="px-6 py-4 font-black whitespace-nowrap">
                                    ${{ number_format($quotation->total, 2) }}
                                </td>
                                <td class="px-6 py-4 text-xs whitespace-nowrap">
                                    {{ $quotation->deleted_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-end gap-2 flex-wrap">
                                        <!-- Ver Detalle -->
                                        <a href="{{ route('dashboard.pos.quotations.show', $quotation->id) }}" 
                                            class="inline-flex items-center justify-center gap-2 px-3 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold transition-all shadow-sm hover:shadow-md"
                                            title="Ver Detalle">
                                            <i class="fas fa-eye"></i>
                                            <span class="hidden sm:inline">Ver</span>
                                        </a>
                                        
                                        <!-- Restaurar -->
                                        <form action="{{ route('dashboard.pos.quotations.restore', $quotation->id) }}" method="POST" class="inline-block">
                                            @csrf
                                            <button type="submit" 
                                                class="inline-flex items-center justify-center gap-2 px-3 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold transition-all shadow-sm hover:shadow-md"
                                                title="Restaurar"
                                                onclick="return confirm('¿Restaurar esta cotización?');">
                                                <i class="fas fa-undo"></i>
                                                <span class="hidden sm:inline">Restaurar</span>
                                            </button>
                                        </form>
                                        
                                        <!-- Eliminar Permanentemente -->
                                        <form action="{{ route('dashboard.pos.quotations.force-delete', $quotation->id) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                class="inline-flex items-center justify-center gap-2 px-3 py-2 rounded-lg bg-red-600 hover:bg-red-700 text-white text-xs font-semibold transition-all shadow-sm hover:shadow-md"
                                                title="Eliminar Permanentemente"
                                                onclick="return confirm('¿Eliminar permanentemente esta cotización? Esta acción no se puede deshacer.');">
                                                <i class="fas fa-trash-alt"></i>
                                                <span class="hidden sm:inline">Eliminar</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-zinc-500">
                                    <div class="flex flex-col items-center">
                                        <i class="fas fa-trash text-4xl mb-3 opacity-20"></i>
                                        <p class="font-black uppercase tracking-widest text-xs opacity-40">La papelera está vacía</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-4 border-t border-zinc-200 dark:border-zinc-800">
                    {{ $quotations->links() }}
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
