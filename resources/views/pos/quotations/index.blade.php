<x-layouts.app :title="__('Cotizaciones')">
    <div class="flex-1">
        <div class="border-b border-zinc-200 bg-white px-6 py-4 dark:border-zinc-700 dark:bg-zinc-900">
            <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Cotizaciones</h1>
            <p class="text-sm text-zinc-600 dark:text-zinc-400">Historial operativo y comercial de cotizaciones POS</p>
        </div>

        <div class="p-6">
            <!-- Metrics Cards -->
            <div class="mb-8 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <div class="rounded-xl border border-zinc-200 bg-white p-4 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                    <p class="text-xs font-semibold uppercase tracking-wider text-zinc-500 dark:text-zinc-400">Cotizaciones Hoy</p>
                    <div class="mt-1 flex items-baseline justify-between">
                        <h3 class="text-2xl font-bold text-zinc-900 dark:text-white">{{ $stats['total_day'] }}</h3>
                        <span class="text-xs font-medium text-blue-600 bg-blue-50 px-2 py-0.5 rounded-full">Actualizado</span>
                    </div>
                </div>
                <div class="rounded-xl border border-zinc-200 bg-white p-4 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                    <p class="text-xs font-semibold uppercase tracking-wider text-zinc-500 dark:text-zinc-400">Convertidas Hoy</p>
                    <div class="mt-1 flex items-baseline justify-between">
                        <h3 class="text-2xl font-bold text-zinc-900 dark:text-white">{{ $stats['converted_day'] }}</h3>
                        <span class="text-xs font-medium text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full">Ventas</span>
                    </div>
                </div>
                <div class="rounded-xl border border-zinc-200 bg-white p-4 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                    <p class="text-xs font-semibold uppercase tracking-wider text-zinc-500 dark:text-zinc-400">Monto Potencial</p>
                    <div class="mt-1 flex items-baseline justify-between">
                        <h3 class="text-2xl font-bold text-zinc-900 dark:text-white">${{ number_format($stats['potential_amount'], 2) }}</h3>
                        <span class="text-xs font-medium text-pink-600 bg-pink-50 px-2 py-0.5 rounded-full">Embudo</span>
                    </div>
                </div>
                <div class="rounded-xl border border-zinc-200 bg-white p-4 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                    <p class="text-xs font-semibold uppercase tracking-wider text-zinc-500 dark:text-zinc-400">Tasa de Conversión</p>
                    <div class="mt-1 flex items-baseline justify-between">
                        <h3 class="text-2xl font-bold text-zinc-900 dark:text-white">{{ $stats['conversion_rate'] }}%</h3>
                        <div class="flex items-center text-xs text-zinc-500 italic">Global</div>
                    </div>
                </div>
            </div>

            <!-- Filter Bar -->
            <div class="mb-6 rounded-lg border border-zinc-200 bg-white p-4 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                <form action="{{ route('dashboard.pos.quotations.index') }}" method="GET" class="flex flex-wrap items-end gap-4">
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

                    <!-- Status -->
                    <div class="w-full sm:w-auto">
                        <label for="status" class="block text-xs font-medium text-zinc-500 dark:text-zinc-400 mb-1">Estado</label>
                        <select name="status" id="status" class="w-full rounded-lg border-zinc-300 bg-white px-3 py-2 text-sm shadow-sm focus:border-pink-500 focus:ring-pink-500 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white">
                            <option value="">Todos los estados</option>
                            <option value="sent" {{ request('status') == 'sent' ? 'selected' : '' }}>Enviada</option>
                            <option value="accepted" {{ request('status') == 'accepted' ? 'selected' : '' }}>Aceptada</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rechazada</option>
                            <option value="converted" {{ request('status') == 'converted' ? 'selected' : '' }}>Convertida</option>
                            <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Vencida</option>
                        </select>
                    </div>

                    <!-- Date Range -->
                    <div class="w-full sm:w-auto">
                        <label for="date_range" class="block text-xs font-medium text-zinc-500 dark:text-zinc-400 mb-1">Periodo</label>
                        <select name="date_range" id="date_range" class="w-full rounded-lg border-zinc-300 bg-white px-3 py-2 text-sm shadow-sm focus:border-pink-500 focus:ring-pink-500 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white">
                            <option value="">Todo el tiempo</option>
                            <option value="today" {{ request('date_range') == 'today' ? 'selected' : '' }}>Hoy</option>
                            <option value="yesterday" {{ request('date_range') == 'yesterday' ? 'selected' : '' }}>Ayer</option>
                            <option value="week" {{ request('date_range') == 'week' ? 'selected' : '' }}>Esta semana</option>
                            <option value="month" {{ request('date_range') == 'month' ? 'selected' : '' }}>Este mes</option>
                        </select>
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
                        <a href="{{ route('dashboard.pos.quotations.index') }}" class="inline-flex items-center rounded-lg border border-zinc-300 bg-white px-4 py-2 text-sm font-medium text-zinc-700 shadow-sm hover:bg-zinc-50 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-offset-2 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white dark:hover:bg-zinc-700">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            <!-- Table -->
            <div class="overflow-hidden rounded-lg border border-zinc-200 bg-white shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-zinc-600 dark:text-zinc-400">
                        <thead class="bg-zinc-50 text-xs uppercase text-zinc-500 dark:bg-zinc-800 dark:text-zinc-400">
                            <tr>
                                <th class="px-6 py-3 whitespace-nowrap">Folio</th>
                                <th class="px-6 py-3 whitespace-nowrap">Cliente</th>
                                <th class="px-6 py-3 whitespace-nowrap">Usuario</th>
                                <th class="px-6 py-3 whitespace-nowrap">Total Estimado</th>
                                <th class="px-6 py-3 whitespace-nowrap">Expira</th>
                                <th class="px-6 py-3 whitespace-nowrap">Estado</th>
                                <th class="px-6 py-3 text-right whitespace-nowrap">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                            @forelse($quotations as $quotation)
                            <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50 group">
                                <td class="px-6 py-4 font-bold text-zinc-900 dark:text-white whitespace-nowrap">
                                    {{ $quotation->folio }}
                                    <div class="text-[10px] text-zinc-500 font-normal">Vía: {{ ucfirst($quotation->share_type) }}</div>
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
                                    {{ $quotation->expires_at ? $quotation->expires_at->format('d/m/Y') : 'N/A' }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex rounded-full px-2 py-1 text-[10px] font-black uppercase tracking-wider
                                        {{ $quotation->status === 'sent' ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' : 
                                           ($quotation->status === 'accepted' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400' : 
                                           ($quotation->status === 'converted' ? 'bg-pink-100 text-pink-700 dark:bg-pink-900/30 dark:text-pink-400' : 
                                           ($quotation->status === 'expired' ? 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400' :
                                           'bg-zinc-100 text-zinc-700 dark:bg-zinc-800 dark:text-zinc-400'))) }}">
                                        {{ $quotation->status_label }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end gap-2 transition-opacity">
                                        <button @click="$dispatch('open-quotation-modal', { id: {{ $quotation->id }} })" 
                                            class="p-2 text-zinc-400 hover:text-pink-600 dark:text-zinc-500 dark:hover:text-pink-400" title="Ver Detalle">
                                            <i class="fas fa-eye text-sm"></i>
                                        </button>
                                        
                                        @if($quotation->status !== 'converted' && $quotation->status !== 'expired')
                                        <a href="{{ route('dashboard.pos.index', ['quotation_id' => $quotation->id]) }}" 
                                            class="p-2 text-zinc-400 hover:text-emerald-500 dark:text-zinc-500 dark:hover:text-emerald-400" 
                                            title="Convertir en Venta">
                                            <i class="fas fa-shopping-cart text-sm"></i>
                                        </a>
                                        @endif

                                        <button class="p-2 text-zinc-400 hover:text-pink-600 dark:text-zinc-500 dark:hover:text-pink-400" title="Duplicar">
                                            <i class="fas fa-copy text-sm"></i>
                                        </button>
                                        
                                        <form action="{{ route('dashboard.pos.quotations.destroy', $quotation->id) }}" method="POST" onsubmit="return confirm('¿Eliminar cotización?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-zinc-400 hover:text-red-600 dark:text-zinc-500 dark:hover:text-red-400">
                                                <i class="fas fa-trash text-sm"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-zinc-500">
                                    <div class="flex flex-col items-center">
                                        <i class="fas fa-clipboard-list text-4xl mb-3 opacity-20"></i>
                                        <p class="font-black uppercase tracking-widest text-xs opacity-40">No se encontraron cotizaciones</p>
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

    <!-- Detail Modal -->
    <div x-data="{ open: false, data: null, loading: false }" 
         @open-quotation-modal.window="open = true; loading = true; fetch('/dashboard/pos/quotations/' + $event.detail.id).then(r => r.json()).then(d => { data = d; loading = false; })"
         class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-zinc-950/80 backdrop-blur-sm" 
         x-show="open" 
         x-cloak>
        
        <div class="bg-zinc-900 w-full max-w-2xl rounded-2xl border border-zinc-800 shadow-2xl overflow-hidden" @click.away="open = false">
            <div class="p-6 border-b border-zinc-800 flex justify-between items-center bg-zinc-950/50">
                <div x-show="data">
                    <h2 class="text-lg font-black text-white" x-text="'Detalle de Cotización: ' + data.folio"></h2>
                    <p class="text-xs text-zinc-500 uppercase tracking-widest mt-1" x-text="new Date(data.created_at).toLocaleString()"></p>
                </div>
                <button @click="open = false" class="text-zinc-500 hover:text-white transition-colors">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="p-6 max-h-[70vh] overflow-y-auto custom-scrollbar">
                <div x-show="loading" class="flex flex-col items-center justify-center py-12">
                    <i class="fas fa-circle-notch fa-spin text-3xl text-pink-500 mb-2"></i>
                    <p class="text-xs font-bold uppercase tracking-widest text-zinc-500">Cargando datos...</p>
                </div>

                <div x-show="data && !loading" class="space-y-6">
                    <!-- Customer and General Info -->
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-zinc-950 p-4 rounded-xl border border-zinc-800">
                            <h3 class="text-[10px] font-black uppercase text-zinc-500 tracking-widest mb-2">Cliente</h3>
                            <p class="text-sm font-bold text-white" x-text="data.customer_name"></p>
                            <p class="text-xs text-zinc-500" x-text="data.customer_phone || 'Sin teléfono'"></p>
                        </div>
                        <div class="bg-zinc-950 p-4 rounded-xl border border-zinc-800">
                            <h3 class="text-[10px] font-black uppercase text-zinc-500 tracking-widest mb-2">Información Operativa</h3>
                            <div class="flex justify-between items-center mb-1">
                                <span class="text-[10px] text-zinc-500">Vendedor:</span>
                                <span class="text-xs font-bold text-zinc-300" x-text="data.user ? data.user.name : '-'"></span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-[10px] text-zinc-500">Medio:</span>
                                <span class="text-xs font-bold text-zinc-300 uppercase" x-text="data.share_type"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Items Table -->
                    <div class="bg-zinc-950 rounded-xl border border-zinc-800 overflow-hidden">
                        <table class="w-full text-left text-xs">
                            <thead class="bg-zinc-900 text-zinc-500">
                                <tr>
                                    <th class="px-4 py-2 uppercase font-black">Producto</th>
                                    <th class="px-4 py-2 uppercase font-black text-center">Cant.</th>
                                    <th class="px-4 py-2 uppercase font-black text-right">Precio</th>
                                    <th class="px-4 py-2 uppercase font-black text-right">Total</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-zinc-900 text-zinc-300">
                                <template x-for="item in data.items" :key="item.id">
                                    <tr>
                                        <td class="px-4 py-3">
                                            <div class="font-bold text-zinc-200" x-text="item.product ? item.product.name : 'Producto'"></div>
                                            <div x-show="item.variant" class="text-[9px] text-pink-500/70 font-black uppercase" x-text="item.variant ? item.variant.name : ''"></div>
                                        </td>
                                        <td class="px-4 py-3 text-center font-bold" x-text="item.quantity"></td>
                                        <td class="px-4 py-3 text-right" x-text="'$' + parseFloat(item.unit_price).toLocaleString()"></td>
                                        <td class="px-4 py-3 text-right font-black text-white" x-text="'$' + parseFloat(item.total).toLocaleString()"></td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>

                    <!-- Totals -->
                    <div class="flex justify-end pt-4 border-t border-zinc-800">
                        <div class="w-48 space-y-2">
                            <div class="flex justify-between text-xs">
                                <span class="text-zinc-500 font-bold uppercase">Subtotal:</span>
                                <span class="text-zinc-300" x-text="'$' + parseFloat(data.subtotal).toLocaleString()"></span>
                            </div>
                            <div class="flex justify-between text-xs">
                                <span class="text-zinc-500 font-bold uppercase">IVA (16%):</span>
                                <span class="text-zinc-300" x-text="'$' + parseFloat(data.iva_total).toLocaleString()"></span>
                            </div>
                            <div class="flex justify-between text-lg pt-2 border-t border-zinc-900">
                                <span class="font-black text-white uppercase tracking-tighter">Total:</span>
                                <span class="font-black text-pink-500" x-text="'$' + parseFloat(data.total).toLocaleString()"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-6 bg-zinc-950/50 border-t border-zinc-800 flex justify-between gap-3">
                <div class="flex gap-2" x-show="data && data.status !== 'converted' && data.status !== 'expired'">
                    <a :href="'{{ route('dashboard.pos.index') }}?quotation_id=' + data.id" 
                       class="inline-flex items-center rounded-xl bg-emerald-600 px-5 py-2.5 text-xs font-black uppercase tracking-widest text-white hover:bg-emerald-700 transition-all shadow-lg shadow-emerald-900/20 active:scale-95 text-center">
                        <i class="fas fa-shopping-cart mr-2"></i> Convertir en Venta
                    </a>
                </div>
                <button @click="open = false" class="px-5 py-2.5 text-xs font-black uppercase tracking-widest text-zinc-500 hover:text-white transition-colors ml-auto">
                    Cerrar
                </button>
            </div>
        </div>
    </div>
</x-layouts.app>
