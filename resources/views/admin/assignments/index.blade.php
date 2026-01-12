<x-layouts.app title="Asignación de Productos">
    <div class="flex-1">
        <div class="border-b border-zinc-200 bg-white px-6 py-4 dark:border-zinc-700 dark:bg-zinc-900 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Asignación de Productos</h1>
            <a href="{{ route('dashboard.assignments.create') }}" class="rounded-lg bg-pink-600 px-4 py-2 text-sm font-medium text-white hover:bg-pink-700">
                <i class="fas fa-plus mr-2"></i> Nueva Asignación
            </a>
        </div>

        <div class="p-6">
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
                                    <select name="status" onchange="this.form.submit()" class="w-full border-0 py-1 pl-2 pr-8 text-[10px] font-black uppercase appearance-none cursor-pointer focus:ring-0
                                        {{ $assignment->status === 'quotation' ? 'bg-[#FFFF00] text-black' : '' }}
                                        {{ $assignment->status === 'paid_customer' ? 'bg-[#00FFFF] text-black' : '' }}
                                        {{ $assignment->status === 'paid_partner' ? 'bg-[#000080] text-white' : '' }}
                                        {{ $assignment->status === 'deferred' ? 'bg-[#A9A9A9] text-white' : '' }}
                                        {{ $assignment->status === 'incident' ? 'bg-[#FF0000] text-white' : '' }}">
                                        <option value="quotation" {{ $assignment->status === 'quotation' ? 'selected' : '' }} style="background-color: #FFFF00; color: #000;">[ AMARILLO ] - Cotización</option>
                                        <option value="paid_customer" {{ $assignment->status === 'paid_customer' ? 'selected' : '' }} style="background-color: #00FFFF; color: #000;">[ AZUL CIELO ] - Pagado Cli</option>
                                        <option value="paid_partner" {{ $assignment->status === 'paid_partner' ? 'selected' : '' }} style="background-color: #000080; color: #fff;">[ AZUL MARINO ] - Pagado Soc</option>
                                        <option value="deferred" {{ $assignment->status === 'deferred' ? 'selected' : '' }} style="background-color: #A9A9A9; color: #fff;">[ GRIS ] - Pendiente</option>
                                        <option value="incident" {{ $assignment->status === 'incident' ? 'selected' : '' }} style="background-color: #FF0000; color: #fff;">[ ROJO ] - Incidencia</option>
                                    </select>
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
                                <td class="px-6 py-4 font-bold text-zinc-500">Iva (16% Retenido)</td>
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
