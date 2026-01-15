<x-layouts.app title="Asignación de Productos">
    <div class="flex-1">
        <div class="border-b border-zinc-200 bg-white px-6 py-4 dark:border-zinc-700 dark:bg-zinc-900 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Asignación de Productos</h1>
            <div class="flex gap-2">
                <a href="{{ route('dashboard.assignments.export-excel', request()->query()) }}" target="_blank" class="rounded-lg bg-green-600 px-4 py-2 text-sm font-medium text-white hover:bg-green-700 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Exportar Excel
                </a>
                <a href="{{ route('dashboard.assignments.export-pdf', request()->query()) }}" target="_blank" class="rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700 flex items-center gap-2">
                    <i class="fas fa-file-pdf"></i> Exportar PDF
                </a>
                <a href="{{ route('dashboard.assignments.create') }}" class="rounded-lg bg-pink-600 px-4 py-2 text-sm font-medium text-white hover:bg-pink-700">
                    <i class="fas fa-plus mr-2"></i> Nueva Asignación
                </a>
            </div>
        </div>

        <div class="p-6 space-y-6">
            <!-- Filters Section -->
            <div class="rounded-lg border border-zinc-200 bg-white shadow-sm dark:border-zinc-700 dark:bg-zinc-900 p-6">
                <form method="GET" action="{{ route('dashboard.assignments.index') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-zinc-700 dark:text-zinc-300 mb-2">Responsable</label>
                        <select name="user_id" class="w-full rounded-lg border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white text-sm">
                            <option value="">Todos</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-zinc-700 dark:text-zinc-300 mb-2">Categoría</label>
                        <select name="category_id" class="w-full rounded-lg border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white text-sm">
                            <option value="">Todas</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-zinc-700 dark:text-zinc-300 mb-2">Desde</label>
                        <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full rounded-lg border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white text-sm">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-zinc-700 dark:text-zinc-300 mb-2">Hasta</label>
                        <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full rounded-lg border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white text-sm">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-zinc-700 dark:text-zinc-300 mb-2">LOB / Socio</label>
                        <select name="lob" class="w-full rounded-lg border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white text-sm">
                            <option value="">Todos</option>
                            @foreach($lobs as $lob)
                                <option value="{{ $lob }}" {{ request('lob') == $lob ? 'selected' : '' }}>{{ $lob }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-zinc-700 dark:text-zinc-300 mb-2">Estado</label>
                        <select name="status" class="w-full rounded-lg border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white text-sm">
                            <option value="">Todos</option>
                            <option value="quotation" {{ request('status') == 'quotation' ? 'selected' : '' }}>Cotización</option>
                            <option value="paid_customer" {{ request('status') == 'paid_customer' ? 'selected' : '' }}>Pagado Cliente</option>
                            <option value="paid_partner" {{ request('status') == 'paid_partner' ? 'selected' : '' }}>Pagado Socio</option>
                            <option value="deferred" {{ request('status') == 'deferred' ? 'selected' : '' }}>Pendiente</option>
                            <option value="incident" {{ request('status') == 'incident' ? 'selected' : '' }}>Incidencia</option>
                        </select>
                    </div>

                    <div class="flex items-end gap-2">
                        <button type="submit" class="flex-1 rounded-lg bg-zinc-900 px-4 py-2 text-sm font-bold text-white hover:bg-zinc-800 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-100">
                            <i class="fas fa-filter mr-2"></i> Filtrar
                        </button>
                        <a href="{{ route('dashboard.assignments.index') }}" class="rounded-lg bg-zinc-200 px-4 py-2 text-sm font-bold text-zinc-700 hover:bg-zinc-300 dark:bg-zinc-700 dark:text-zinc-300 dark:hover:bg-zinc-600">
                            <i class="fas fa-times"></i>
                        </a>
                    </div>
                </form>
            </div>

            <!-- Paid Orders Suggestions (if date range is set) -->
            @if($paidOrders->isNotEmpty())
            <div class="rounded-lg border border-blue-200 bg-blue-50 dark:border-blue-900 dark:bg-blue-900/20 p-6">
                <h3 class="text-sm font-black uppercase tracking-wider text-blue-900 dark:text-blue-300 mb-4 flex items-center gap-2">
                    <i class="fas fa-lightbulb"></i> Pedidos Pagados en el Rango de Fechas
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                    @foreach($paidOrders as $order)
                    <div class="bg-white dark:bg-zinc-800 rounded-lg p-4 border border-zinc-200 dark:border-zinc-700">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <p class="text-xs font-black text-zinc-900 dark:text-white">{{ $order->order_number }}</p>
                                <p class="text-[10px] text-zinc-500">{{ $order->customer_name }}</p>
                            </div>
                            <span class="text-xs font-bold text-green-600">${{ number_format($order->total, 0) }}</span>
                        </div>
                        <div class="text-[9px] text-zinc-400 space-y-1">
                            @foreach($order->items->take(2) as $item)
                            <p>• {{ $item->product->name }} ({{ $item->quantity }})</p>
                            @endforeach
                            @if($order->items->count() > 2)
                            <p class="italic">+ {{ $order->items->count() - 2 }} más...</p>
                            @endif
                        </div>
                        <a href="{{ route('dashboard.orders.show', $order->id) }}" target="_blank" class="mt-2 block text-center text-[10px] font-bold text-blue-600 hover:text-blue-700">
                            Ver Detalle <i class="fas fa-external-link-alt ml-1"></i>
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Assignments Table -->
            <div class="rounded-lg border border-zinc-200 bg-white shadow-sm dark:border-zinc-700 dark:bg-zinc-900 overflow-hidden">
                <table class="w-full text-left text-sm border-collapse border-2 border-zinc-800 dark:border-zinc-700" id="assignments-table">
                    <thead class="bg-zinc-900 text-white text-[10px] font-black uppercase tracking-widest">
                        <tr>
                            <th class="px-3 py-3 border border-zinc-800">Responsable</th>
                            <th class="px-3 py-3 border border-zinc-800">Producto</th>
                            <th class="px-3 py-3 border border-zinc-800 w-16 text-center">Cant</th>
                            <th class="px-3 py-3 border border-zinc-800 w-24 text-center text-pink-400">Total</th>
                            <th class="px-3 py-3 border border-zinc-800 w-24 text-center">Base</th>
                            <th class="px-3 py-3 border border-zinc-800 w-24 text-center">IVA</th>
                            <th class="px-3 py-3 border border-zinc-800 w-32">LOB</th>
                            <th class="px-3 py-3 border border-zinc-800 w-48">Estado / Acción</th>
                            <th class="px-3 py-3 border border-zinc-800 w-20 text-center bg-red-950">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mx-auto text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y-2 divide-zinc-800">
                        @foreach($assignments as $assignment)
                        <tr class="{{ $assignment->status_color_classes }}">
                            <td class="px-3 py-3 border border-zinc-800 font-black uppercase text-[10px]">{{ $assignment->user->name }}</td>
                            <td class="px-3 py-3 border border-zinc-800 font-bold">
                                {{ $assignment->product->name }}
                            </td>
                            <td class="px-3 py-3 border border-zinc-800 text-center font-black">{{ $assignment->quantity }}</td>
                            <td class="px-3 py-3 border border-zinc-800 text-right font-black text-pink-800 dark:text-pink-400">${{ number_format($assignment->unit_price, 2) }}</td>
                            <td class="px-3 py-3 border border-zinc-800 text-right font-bold text-zinc-900 dark:text-zinc-100">${{ number_format($assignment->base_price, 0) }}</td>
                            <td class="px-3 py-3 border border-zinc-800 text-right font-bold text-zinc-800 dark:text-zinc-200">${{ number_format($assignment->iva_amount, 2) }}</td>
                            <td class="px-3 py-3 border border-zinc-800 font-black uppercase text-[10px]">{{ $assignment->partner_lob ?: '-' }}</td>
                            <td class="px-3 py-1 border-2 border-zinc-950">
                                <form action="{{ route('dashboard.assignments.update-status', $assignment->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" onchange="this.form.submit()" class="w-full border-0 py-1 pl-2 pr-8 text-[10px] font-black uppercase appearance-none cursor-pointer focus:ring-0 {{ $assignment->status_color_classes }}">
                                        <option value="quotation" {{ $assignment->status === 'quotation' ? 'selected' : '' }}>Cotización</option>
                                        <option value="paid_customer" {{ $assignment->status === 'paid_customer' ? 'selected' : '' }}>Pagado Cliente</option>
                                        <option value="paid_partner" {{ $assignment->status === 'paid_partner' ? 'selected' : '' }}>Pagado Socio</option>
                                        <option value="deferred" {{ $assignment->status === 'deferred' ? 'selected' : '' }}>Pendiente</option>
                                        <option value="incident" {{ $assignment->status === 'incident' ? 'selected' : '' }}>Incidencia</option>
                                    </select>
                                </form>
                            </td>
                            <td class="px-2 py-2 border-2 border-zinc-950 text-center bg-red-950/20">
                                <form action="{{ route('dashboard.assignments.destroy', $assignment->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar esta asignación?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 font-bold p-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="p-4">
                    {{ $assignments->links() }}
                </div>
            </div>

            <!-- Corte de Caja Summary - Excel Style -->
            <div class="mt-8 flex justify-end">
                <div class="w-full md:w-96 rounded-xl border-2 border-zinc-800 bg-white shadow-2xl dark:border-zinc-700 dark:bg-zinc-900 overflow-hidden">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-zinc-800 text-[10px] uppercase tracking-widest text-white font-black">
                            <tr>
                                <th class="px-6 py-4 border-b border-zinc-700">Resumen de Corte</th>
                                <th class="px-6 py-4 text-right border-b border-zinc-700">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                            <tr class="bg-zinc-50 dark:bg-zinc-800/50 italic">
                                <td class="px-6 py-4 font-bold text-zinc-500">IVA (16% Retenido)</td>
                                <td class="px-6 py-4 text-right font-black text-zinc-900 dark:text-white">${{ number_format($corteSummary['iva'], 2) }}</td>
                            </tr>
                            @foreach($corteSummary['partners'] as $partner => $total)
                            <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800 transition-colors">
                                <td class="px-6 py-4 font-bold text-zinc-700 dark:text-zinc-300">Socio: {{ $partner ?: 'General' }}</td>
                                <td class="px-6 py-4 text-right font-black text-green-600 dark:text-green-400">${{ number_format($total, 2) }}</td>
                            </tr>
                            @endforeach
                            <tr class="bg-zinc-900 text-white">
                                <td class="px-6 py-4 font-black uppercase text-[10px] tracking-widest">Gran Total a Liquidar</td>
                                <td class="px-6 py-4 text-right font-black text-xl text-pink-500">
                                    ${{ number_format($corteSummary['iva'] + collect($corteSummary['partners'])->sum(), 2) }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
