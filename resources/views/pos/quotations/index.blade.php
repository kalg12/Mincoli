<x-layouts.app :title="__('Cotizaciones')">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <div class="flex-1" x-data="quotationManager()">
        <div class="border-b border-zinc-200 bg-white px-6 py-4 dark:border-zinc-700 dark:bg-zinc-900">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Cotizaciones</h1>
                    <p class="text-sm text-zinc-600 dark:text-zinc-400">Historial operativo y comercial de cotizaciones POS</p>
                </div>
                <a href="{{ route('dashboard.pos.quotations.trash') }}" 
                   class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-zinc-600 hover:bg-zinc-700 text-white text-sm font-semibold transition-all">
                    <i class="fas fa-trash"></i>
                    <span>Papelera</span>
                </a>
            </div>
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
            <div class="rounded-lg border border-zinc-200 bg-white shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
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
                                <th class="px-6 py-3 text-right whitespace-nowrap" style="min-width: 320px;">Acciones</th>
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
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-end gap-2 flex-wrap">
                                        <!-- Ver Detalle -->
                                        <a href="{{ route('dashboard.pos.quotations.show', $quotation->id) }}" 
                                            class="inline-flex items-center justify-center gap-2 px-3 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold transition-all shadow-sm hover:shadow-md"
                                            title="Ver Detalle">
                                            <i class="fas fa-eye"></i>
                                            <span class="hidden sm:inline">Ver</span>
                                        </a>
                                        
                                        <!-- Editar -->
                                        <a href="{{ route('dashboard.pos.quotations.edit', $quotation->id) }}" 
                                            class="inline-flex items-center justify-center gap-2 px-3 py-2 rounded-lg bg-amber-600 hover:bg-amber-700 text-white text-xs font-semibold transition-all shadow-sm hover:shadow-md"
                                            title="Editar">
                                            <i class="fas fa-edit"></i>
                                            <span class="hidden sm:inline">Editar</span>
                                        </a>
                                        
                                        <!-- Compartir Modal -->
                                        <div>
                                            <button @click="openShareModal(@js($quotation))" 
                                                class="inline-flex items-center justify-center gap-2 px-3 py-2 rounded-lg bg-pink-600 hover:bg-pink-700 text-white text-xs font-semibold transition-all shadow-sm hover:shadow-md"
                                                title="Compartir">
                                                <i class="fas fa-share-alt"></i>
                                                <span class="hidden sm:inline">Compartir</span>
                                            </button>
                                        </div>

                                        @if($quotation->status !== 'converted' && $quotation->status !== 'expired')
                                        <a href="{{ route('dashboard.pos.index', ['quotation_id' => $quotation->id]) }}" 
                                            class="inline-flex items-center justify-center gap-2 px-3 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold transition-all shadow-sm hover:shadow-md"
                                            title="Convertir en Venta">
                                            <i class="fas fa-shopping-cart"></i>
                                            <span class="hidden sm:inline">Venta</span>
                                        </a>
                                        @endif
                                        
                                        <form action="{{ route('dashboard.pos.quotations.destroy', $quotation->id) }}" method="POST" onsubmit="return confirm('¿Eliminar cotización?');" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                class="inline-flex items-center justify-center gap-2 px-3 py-2 rounded-lg bg-red-600 hover:bg-red-700 text-white text-xs font-semibold transition-all shadow-sm hover:shadow-md"
                                                title="Eliminar">
                                                <i class="fas fa-trash"></i>
                                                <span class="hidden sm:inline">Eliminar</span>
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

        <!-- Share Modal -->
        <div x-show="shareModalOpen" 
         x-cloak
         class="fixed inset-0 z-[200] flex items-center justify-center p-4 bg-zinc-950/90 backdrop-blur-sm"
         @click.away="shareModalOpen = false"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        <div class="bg-zinc-900 w-full max-w-md rounded-2xl border-2 border-zinc-700 shadow-2xl overflow-hidden"
            @click.stop
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95">
            <!-- Header -->
            <div class="p-6 border-b border-zinc-800 bg-zinc-950/50">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-black text-white">Compartir Cotización</h3>
                        <p class="text-xs text-zinc-400 mt-1" x-text="'Folio: ' + (shareModalData ? shareModalData.folio : '')"></p>
                    </div>
                    <button @click="shareModalOpen = false" 
                        class="text-zinc-400 hover:text-white transition-colors p-2">
                        <i class="fas fa-times text-lg"></i>
                    </button>
                </div>
            </div>
            
            <!-- Options Grid -->
            <div class="p-6">
                <div class="grid grid-cols-2 gap-4">
                    <!-- WhatsApp -->
                    <button @click="shareWhatsApp(shareModalData); shareModalOpen = false" 
                        class="group flex flex-col items-center justify-center gap-3 p-6 rounded-xl bg-emerald-600/10 hover:bg-emerald-600 border-2 border-emerald-600/30 hover:border-emerald-500 transition-all transform hover:scale-105">
                        <div class="w-14 h-14 rounded-full bg-emerald-600/20 group-hover:bg-emerald-600 flex items-center justify-center transition-colors">
                            <i class="fab fa-whatsapp text-2xl text-emerald-400 group-hover:text-white"></i>
                        </div>
                        <div class="text-center">
                            <p class="text-sm font-bold text-white">WhatsApp</p>
                            <p class="text-xs text-zinc-400 group-hover:text-zinc-300">Enviar texto</p>
                        </div>
                    </button>
                    
                    <!-- Copiar Imagen -->
                    <button @click="exportQuotation(shareModalData, 'copy'); shareModalOpen = false" 
                        class="group flex flex-col items-center justify-center gap-3 p-6 rounded-xl bg-pink-600/10 hover:bg-pink-600 border-2 border-pink-600/30 hover:border-pink-500 transition-all transform hover:scale-105">
                        <div class="w-14 h-14 rounded-full bg-pink-600/20 group-hover:bg-pink-600 flex items-center justify-center transition-colors">
                            <i class="fas fa-copy text-2xl text-pink-400 group-hover:text-white"></i>
                        </div>
                        <div class="text-center">
                            <p class="text-sm font-bold text-white">Copiar</p>
                            <p class="text-xs text-zinc-400 group-hover:text-zinc-300">Al portapapeles</p>
                        </div>
                    </button>
                    
                    <!-- Descargar Imagen -->
                    <button @click="exportQuotation(shareModalData, 'image'); shareModalOpen = false" 
                        class="group flex flex-col items-center justify-center gap-3 p-6 rounded-xl bg-blue-600/10 hover:bg-blue-600 border-2 border-blue-600/30 hover:border-blue-500 transition-all transform hover:scale-105">
                        <div class="w-14 h-14 rounded-full bg-blue-600/20 group-hover:bg-blue-600 flex items-center justify-center transition-colors">
                            <i class="fas fa-image text-2xl text-blue-400 group-hover:text-white"></i>
                        </div>
                        <div class="text-center">
                            <p class="text-sm font-bold text-white">Imagen</p>
                            <p class="text-xs text-zinc-400 group-hover:text-zinc-300">Descargar JPG</p>
                        </div>
                    </button>
                    
                    <!-- Descargar PDF -->
                    <button @click="exportQuotation(shareModalData, 'pdf'); shareModalOpen = false" 
                        class="group flex flex-col items-center justify-center gap-3 p-6 rounded-xl bg-red-600/10 hover:bg-red-600 border-2 border-red-600/30 hover:border-red-500 transition-all transform hover:scale-105">
                        <div class="w-14 h-14 rounded-full bg-red-600/20 group-hover:bg-red-600 flex items-center justify-center transition-colors">
                            <i class="fas fa-file-pdf text-2xl text-red-400 group-hover:text-white"></i>
                        </div>
                        <div class="text-center">
                            <p class="text-sm font-bold text-white">PDF</p>
                            <p class="text-xs text-zinc-400 group-hover:text-zinc-300">Descargar</p>
                        </div>
                    </button>
                </div>
            </div>
            
            <!-- Footer -->
            <div class="p-4 border-t border-zinc-800 bg-zinc-950/50">
                <button @click="shareModalOpen = false" 
                    class="w-full px-4 py-2 text-sm font-semibold text-zinc-400 hover:text-white transition-colors">
                    Cancelar
                </button>
            </div>
        </div>

        <!-- Detail Modal -->
        <div class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-zinc-950/80 backdrop-blur-sm" 
             x-show="modalOpen" 
             x-cloak>
        
        <div class="bg-zinc-900 w-full max-w-2xl rounded-2xl border border-zinc-800 shadow-2xl overflow-hidden" @click.away="modalOpen = false">
            <div class="p-6 border-b border-zinc-800 flex justify-between items-center bg-zinc-950/50">
                <div x-show="modalData">
                    <h2 class="text-lg font-black text-white" x-text="'Detalle de Cotización: ' + modalData.folio"></h2>
                    <p class="text-xs text-zinc-500 uppercase tracking-widest mt-1" x-text="new Date(modalData.created_at).toLocaleString()"></p>
                </div>
                <button @click="modalOpen = false" class="text-zinc-500 hover:text-white transition-colors">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="p-6 max-h-[70vh] overflow-y-auto custom-scrollbar">
                <div x-show="isLoadingModal" class="flex flex-col items-center justify-center py-12">
                    <i class="fas fa-circle-notch fa-spin text-3xl text-pink-500 mb-2"></i>
                    <p class="text-xs font-bold uppercase tracking-widest text-zinc-500">Cargando datos...</p>
                </div>

                <div x-show="modalData && !isLoadingModal" class="space-y-6">
                    <!-- Customer and General Info -->
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-zinc-950 p-4 rounded-xl border border-zinc-800">
                            <h3 class="text-[10px] font-black uppercase text-zinc-500 tracking-widest mb-2">Cliente</h3>
                            <p class="text-sm font-bold text-white" x-text="modalData.customer_name"></p>
                            <p class="text-xs text-zinc-500" x-text="modalData.customer_phone || 'Sin teléfono'"></p>
                        </div>
                        <div class="bg-zinc-950 p-4 rounded-xl border border-zinc-800">
                            <h3 class="text-[10px] font-black uppercase text-zinc-500 tracking-widest mb-2">Información Operativa</h3>
                            <div class="flex justify-between items-center mb-1">
                                <span class="text-[10px] text-zinc-500">Vendedor:</span>
                                <span class="text-xs font-bold text-zinc-300" x-text="modalData.user ? modalData.user.name : '-'"></span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-[10px] text-zinc-500">Medio:</span>
                                <span class="text-xs font-bold text-zinc-300 uppercase" x-text="modalData.share_type"></span>
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
                                <template x-for="item in modalData.items" :key="item.id">
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
                                <span class="text-zinc-300" x-text="'$' + parseFloat(modalData.subtotal).toLocaleString()"></span>
                            </div>
                            <div class="flex justify-between text-xs">
                                <span class="text-zinc-500 font-bold uppercase">IVA (16%):</span>
                                <span class="text-zinc-300" x-text="'$' + parseFloat(modalData.iva_total).toLocaleString()"></span>
                            </div>
                            <div class="flex justify-between text-lg pt-2 border-t border-zinc-900">
                                <span class="font-black text-white uppercase tracking-tighter">Total:</span>
                                <span class="font-black text-pink-500" x-text="'$' + parseFloat(modalData.total).toLocaleString()"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-6 bg-zinc-950/50 border-t border-zinc-800 flex justify-between gap-3">
                <div class="flex gap-2" x-show="modalData">
                    <button @click="shareWhatsApp(modalData)" class="p-2.5 rounded-xl bg-emerald-600/10 text-emerald-500 border border-emerald-500/20 hover:bg-emerald-600 hover:text-white transition-all">
                        <i class="fab fa-whatsapp"></i>
                    </button>
                    <button @click="exportQuotation(modalData, 'copy')" class="p-2.5 rounded-xl bg-pink-600/10 text-pink-500 border border-pink-500/20 hover:bg-pink-600 hover:text-white transition-all">
                        <i class="fas fa-copy"></i>
                    </button>
                    <button @click="exportQuotation(modalData, 'pdf')" class="p-2.5 rounded-xl bg-red-600/10 text-red-500 border border-red-500/20 hover:bg-red-600 hover:text-white transition-all">
                        <i class="fas fa-file-pdf"></i>
                    </button>
                </div>
                <button @click="modalOpen = false" class="px-5 py-2.5 text-xs font-black uppercase tracking-widest text-zinc-500 hover:text-white transition-colors ml-auto">
                    Cerrar
                </button>
            </div>
        </div>
    </div>

    <script>
        function quotationManager() {
            return {
                modalOpen: false,
                modalData: null,
                isLoadingModal: false,
                isExporting: false,
                shareModalOpen: false,
                shareModalData: null,
                paymentMethods: @json($paymentMethods),

                openShareModal(quotation) {
                    this.shareModalData = quotation;
                    this.shareModalOpen = true;
                },

                async openModal(id) {
                    this.modalOpen = true;
                    this.isLoadingModal = true;
                    this.modalData = null;
                    try {
                        const resp = await fetch(`/dashboard/pos/quotations/${id}`);
                        this.modalData = await resp.json();
                    } catch (e) {
                        console.error('Error:', e);
                    } finally {
                        this.isLoadingModal = false;
                    }
                },

                shareWhatsApp(q) {
                    let text = `*COTIZACIÓN MINCOLI*\n`;
                    text += `Folio: *${q.folio}*\n`;
                    text += `Cliente: ${q.customer_name}\n`;
                    text += `Fecha: ${new Date(q.created_at).toLocaleDateString()}\n`;
                    text += `--------------------------\n`;
                    
                    if (q.items && q.items.length > 0) {
                        q.items.forEach(item => {
                            const name = item.product ? item.product.name : 'Producto';
                            const variant = item.variant ? ` (${item.variant.name})` : '';
                            text += `• ${item.quantity}x ${name}${variant} - $${parseFloat(item.total).toLocaleString()}\n`;
                        });
                    }
                    
                    text += `--------------------------\n`;
                    text += `*TOTAL: $${parseFloat(q.total).toLocaleString()}*\n\n`;
                    text += `Lo atendió: ${q.user ? q.user.name : 'Vendedor'}\n`;
                    text += `¡Gracias por tu preferencia!`;
                    
                    const encodedText = encodeURIComponent(text);
                    const phone = q.customer_phone ? q.customer_phone.replace(/\D/g, '') : '';
                    if (phone && phone.length >= 10) {
                        window.open(`https://wa.me/52${phone}?text=${encodedText}`, '_blank');
                    } else {
                        window.open(`https://wa.me/?text=${encodedText}`, '_blank');
                    }
                },

                async exportQuotation(q, type) {
                    this.isExporting = true;
                    try {
                        // Reproduce exactly the same logic as pos/index.blade.php
                        const html = this.generateHTML(q);
                        const tempDiv = document.createElement('div');
                        tempDiv.innerHTML = html;
                        tempDiv.style.position = 'absolute';
                        tempDiv.style.left = '-9999px';
                        tempDiv.style.top = '-9999px';
                        tempDiv.style.width = '650px';
                        tempDiv.style.backgroundColor = '#ffffff';
                        document.body.appendChild(tempDiv);

                        const canvas = await html2canvas(tempDiv, {
                            scale: 2,
                            backgroundColor: '#ffffff',
                            logging: false,
                            useCORS: true,
                            width: 650,
                            windowWidth: 650
                        });
                        document.body.removeChild(tempDiv);

                        if (type === 'pdf') {
                            const imgData = canvas.toDataURL('image/jpeg', 0.95);
                            const { jsPDF } = window.jspdf;
                            const pdf = new jsPDF({ orientation: 'portrait', unit: 'mm', format: 'a4' });
                            const pdfWidth = pdf.internal.pageSize.getWidth();
                            const imgHeight = (canvas.height * pdfWidth) / canvas.width;
                            pdf.addImage(imgData, 'JPEG', 10, 10, pdfWidth - 20, imgHeight);
                            pdf.save(`Cotizacion_${q.folio}.pdf`);
                        } else if (type === 'copy' || type === 'image') {
                             if (type === 'copy') {
                                canvas.toBlob(async (blob) => {
                                    if (navigator.clipboard && navigator.clipboard.write) {
                                        const data = [new ClipboardItem({ [blob.type]: blob })];
                                        await navigator.clipboard.write(data);
                                        alert('¡Imagen de Cotización copiada al portapapeles!');
                                    } else {
                                        // Fallback to download
                                        const link = document.createElement('a');
                                        link.download = `Cotizacion_${q.folio}.jpg`;
                                        link.href = canvas.toDataURL('image/jpeg', 0.95);
                                        link.click();
                                    }
                                }, 'image/png');
                             } else {
                                const dataUrl = canvas.toDataURL('image/jpeg', 0.95);
                                const link = document.createElement('a');
                                link.download = `Cotizacion_${q.folio}.jpg`;
                                link.href = dataUrl;
                                link.click();
                             }
                        }
                    } catch (e) {
                        console.error('Export error', e);
                        alert('Error al generar archivo');
                    } finally {
                        this.isExporting = false;
                    }
                },

                generateHTML(q) {
                    const dateStr = new Date(q.created_at).toLocaleString('es-MX', { dateStyle: 'long', timeStyle: 'short' });
                    const itemsHTML = q.items.map((item, index) => `
                        <tr style="background-color: ${index % 2 === 0 ? '#ffffff' : '#f9fafb'};">
                            <td style="padding: 16px; border: 1px solid #e5e7eb; font-weight: 700; color: #111827;">
                                ${item.product ? item.product.name : 'Producto'}
                                ${item.variant ? `<br><small style="color: #6b7280;">Variante: ${item.variant.name}</small>` : ''}
                            </td>
                            <td style="padding: 16px; border: 1px solid #e5e7eb; text-align: center; font-weight: 700; color: #111827;">${item.quantity}</td>
                            <td style="padding: 16px; border: 1px solid #e5e7eb; text-align: right; font-weight: 700; color: #111827;">$${parseFloat(item.unit_price).toFixed(2)}</td>
                            <td style="padding: 16px; border: 1px solid #e5e7eb; text-align: right; font-weight: 900; color: #db2777;">$${parseFloat(item.total).toFixed(2)}</td>
                        </tr>
                    `).join('');

                    return `
                        <div style="width: 650px; padding: 32px; background-color: #ffffff; font-family: 'Inter', Arial, sans-serif;">
                            <!-- Header -->
                            <div style="border-bottom: 3px solid #ec4899; background-color: #fef2f2; margin: -32px -32px 24px -32px; padding: 32px; display: flex; justify-content: space-between; align-items: center;">
                                <div style="display: flex; align-items: center; gap: 16px;">
                                    <div style="width: 80px; height: 80px; background-color: #ec4899; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                        <span style="color: #ffffff; font-weight: 900; font-size: 24px;">M</span>
                                    </div>
                                    <div>
                                        <h1 style="font-size: 24px; font-weight: 900; color: #111827; margin: 0 0 4px 0;">MINCOLI</h1>
                                        <p style="font-size: 14px; color: #4b5563; margin: 0;">Tienda Online • Moda y Accesorios</p>
                                    </div>
                                </div>
                                <div style="text-align: right;">
                                    <div style="background-color: #fdf2f8; border: 2px solid #f9a8d4; border-radius: 8px; padding: 8px 16px; margin-bottom: 8px;">
                                        <h2 style="font-size: 18px; font-weight: 900; color: #db2777; margin: 0;">COTIZACIÓN</h2>
                                    </div>
                                    <p style="font-size: 12px; color: #6b7280; margin: 0;">${dateStr}</p>
                                    <p style="font-size: 10px; color: #9ca3af; margin: 4px 0 0 0;">FOLIO: ${q.folio}</p>
                                </div>
                            </div>

                            <!-- Customer Info -->
                            <div style="display: flex; gap: 32px; margin-bottom: 24px;">
                                <div style="flex: 1; background-color: #f9fafb; border-radius: 8px; padding: 16px;">
                                    <h3 style="font-size: 14px; font-weight: 900; color: #374151; margin: 0 0 12px 0; text-transform: uppercase;">CLIENTE</h3>
                                    <p style="font-size: 16px; font-weight: 700; color: #111827; margin: 0 0 4px 0;">${q.customer_name}</p>
                                    <p style="font-size: 14px; color: #4b5563; margin: 0;">${q.customer_phone || 'Sin teléfono'}</p>
                                </div>
                                <div style="flex: 1; background-color: #f9fafb; border-radius: 8px; padding: 16px;">
                                    <h3 style="font-size: 14px; font-weight: 900; color: #374151; margin: 0 0 12px 0; text-transform: uppercase;">MÉTODOS DE PAGO</h3>
                                    ${this.paymentMethods.filter(m => !m.name.toLowerCase().includes('mercado')).map((method, index) => `
                                        <div style="margin-bottom: ${index === this.paymentMethods.filter(m => !m.name.toLowerCase().includes('mercado')).length - 1 ? '0' : '8px'}; background-color: #ffffff; border-radius: 4px; padding: 8px; border: 1px solid #e5e7eb;">
                                            <p style="font-size: 11px; font-weight: 900; color: #374151; margin: 0 0 2px 0;">${method.name}</p>
                                            <p style="font-size: 13px; font-weight: 700; color: #111827; margin: 0;">${method.supports_card_number && method.card_number ? method.card_number : (method.code || 'N/A')}</p>
                                        </div>
                                    `).join('')}
                                </div>
                            </div>

                            <!-- Products Table -->
                            <div style="margin-bottom: 24px;">
                                <div style="background-color: #111827; color: #ffffff; padding: 12px 16px; border-radius: 8px 8px 0 0;">
                                    <h3 style="font-size: 14px; font-weight: 900; margin: 0; text-transform: uppercase;">DETALLE DE PRODUCTOS</h3>
                                </div>
                                <table style="width: 100%; border: 2px solid #d1d5db; border-collapse: collapse; border-top: none;">
                                    <thead>
                                        <tr style="background-color: #f3f4f6;">
                                            <th style="padding: 12px 16px; border: 1px solid #d1d5db; text-align: left; font-size: 12px; font-weight: 900; color: #374151;">PRODUCTO</th>
                                            <th style="padding: 12px 16px; border: 1px solid #d1d5db; text-align: center; font-size: 12px; font-weight: 900; color: #374151;">CANT.</th>
                                            <th style="padding: 12px 16px; border: 1px solid #d1d5db; text-align: right; font-size: 12px; font-weight: 900; color: #374151;">PRECIO</th>
                                            <th style="padding: 12px 16px; border: 1px solid #d1d5db; text-align: right; font-size: 12px; font-weight: 900; color: #374151;">TOTAL</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        ${itemsHTML}
                                    </tbody>
                                </table>
                            </div>

                            <!-- Total -->
                            <div style="text-align: right; margin-bottom: 0; margin-top: 32px; padding-bottom: 60px;">
                                <div style="display: flex; justify-content: flex-end; align-items: baseline; gap: 16px;">
                                    <span style="font-size: 16px; font-weight: 900; color: #111827; text-transform: uppercase;">TOTAL A PAGAR:</span>
                                    <span style="font-size: 32px; font-weight: 900; color: #db2777;">$${parseFloat(q.total).toFixed(2)}</span>
                                </div>
                            </div>

                            <!-- Footer -->
                            <div style="text-align: center; padding-top: 50px; margin-top: 0; border-top: 2px solid #d1d5db;">
                                <div style="margin-bottom: 20px;">
                                    <p style="font-size: 18px; font-weight: 900; color: #111827; margin: 0 0 12px 0;">¡Gracias por tu preferencia!</p>
                                    <p style="font-size: 14px; color: #4b5563; margin: 0 0 12px 0;">Te esperamos pronto en</p>
                                    <p style="font-size: 20px; font-weight: 900; color: #db2777; margin: 0 0 16px 0;">mincoli.com</p>
                                </div>
                                <div style="font-size: 12px; color: #6b7280; padding-top: 8px;">
                                    <p style="margin: 0 0 6px 0;">📱 WhatsApp para pedidos: +52 56 1170 11660</p>
                                    <p style="margin: 0;">📍 Envíos a toda la República Mexicana</p>
                                </div>
                            </div>
                        </div>
                    `;
                }
            }
        }
    </script>
</x-layouts.app>
