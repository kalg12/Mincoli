<x-layouts.app :title="__('POS - Transacciones')">
<div class="p-6 space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-zinc-900 dark:text-zinc-100">Transacciones POS</h1>
            <p class="text-sm text-zinc-600 dark:text-zinc-400">Busca, filtra y abre transacciones para gestionarlas.</p>
        </div>
        <a href="{{ route('dashboard.pos.index') }}" class="px-4 py-2 rounded bg-gray-200 text-gray-800 font-semibold hover:bg-gray-300 dark:bg-zinc-800 dark:text-zinc-100 dark:hover:bg-zinc-700">
            ← Volver al dashboard
        </a>
    </div>

    <!-- Filtros -->
    <form method="GET" class="rounded-xl border border-zinc-200 bg-white p-4 shadow flex flex-wrap gap-4 items-end dark:border-zinc-700 dark:bg-zinc-900">
        <div>
            <label class="block text-sm font-semibold text-zinc-800 dark:text-zinc-200 mb-1">Buscar</label>
            <input type="text" name="q" value="{{ $search }}" placeholder="N° transacción, cliente o teléfono"
                   class="px-3 py-2 rounded border border-zinc-300 bg-white text-zinc-900 placeholder-zinc-400
                          focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                          dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-100 dark:placeholder-zinc-500">
        </div>
        <div>
            <label class="block text-sm font-semibold text-zinc-800 dark:text-zinc-200 mb-1">Estado</label>
            <select name="status"
                    class="px-3 py-2 rounded border border-zinc-300 bg-white text-zinc-900
                           focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                           dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-100">
                <option value="">-- Todos --</option>
                <option value="pending" @selected($status === 'pending')>Pendiente</option>
                <option value="reserved" @selected($status === 'reserved')>Apartado</option>
                <option value="completed" @selected($status === 'completed')>Completado</option>
                <option value="cancelled" @selected($status === 'cancelled')>Cancelado</option>
            </select>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="px-4 py-2 rounded bg-blue-600 text-white font-semibold hover:bg-blue-700">
                Filtrar
            </button>
            <a href="{{ route('dashboard.pos.transactions.index') }}" class="px-4 py-2 rounded bg-gray-200 text-gray-800 font-semibold hover:bg-gray-300 dark:bg-zinc-800 dark:text-zinc-100 dark:hover:bg-zinc-700">
                Limpiar
            </a>
        </div>
    </form>

    <!-- Tabla -->
    <div class="rounded-xl border border-zinc-200 bg-white shadow overflow-hidden dark:border-zinc-700 dark:bg-zinc-900">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-zinc-200 dark:border-zinc-700 text-zinc-700 dark:text-zinc-200">
                        <th class="text-left py-3 px-3">Transacción</th>
                        <th class="text-left py-3 px-3">Cliente</th>
                        <th class="text-left py-3 px-3">Estado</th>
                        <th class="text-left py-3 px-3">Pago</th>
                        <th class="text-right py-3 px-3">Total</th>
                        <th class="text-left py-3 px-3">Creada</th>
                        <th class="text-left py-3 px-3">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $txn)
                        <tr class="border-b border-zinc-200 dark:border-zinc-700 hover:bg-zinc-50 dark:hover:bg-zinc-800/60 text-zinc-900 dark:text-zinc-100">
                            <td class="py-3 px-3 font-mono text-xs">{{ $txn->transaction_number }}</td>
                            <td class="py-3 px-3">
                                <div class="font-semibold">{{ $txn->customer?->name ?? 'Sin cliente' }}</div>
                                <div class="text-xs text-zinc-500 dark:text-zinc-400">{{ $txn->customer?->phone }}</div>
                            </td>
                            <td class="py-3 px-3">
                                @php
                                    $statusLabels = [
                                        'pending' => 'Pendiente',
                                        'reserved' => 'Reservado',
                                        'completed' => 'Completado',
                                        'cancelled' => 'Cancelado',
                                        'refunded' => 'Reembolsado'
                                    ];
                                    $statusText = $statusLabels[$txn->status] ?? ucfirst($txn->status);
                                @endphp
                                <span class="px-3 py-1 rounded-full text-xs font-semibold
                                    @class([
                                        'bg-yellow-100 text-yellow-800' => $txn->status === 'pending',
                                        'bg-blue-100 text-blue-800' => $txn->status === 'reserved',
                                        'bg-green-100 text-green-800' => $txn->status === 'completed',
                                        'bg-red-100 text-red-800' => $txn->status === 'cancelled',
                                    ])
                                    dark:bg-opacity-40">
                                    {{ $statusText }}
                                </span>
                            </td>
                            <td class="py-3 px-3">
                                @php
                                    $paymentStatusLabels = [
                                        'pending' => 'Pendiente',
                                        'partial' => 'Pago Parcial',
                                        'completed' => 'Completado',
                                        'paid' => 'Pagado',
                                        'cancelled' => 'Cancelado'
                                    ];
                                    $paymentStatusText = $paymentStatusLabels[$txn->payment_status] ?? ucfirst($txn->payment_status);
                                @endphp
                                <span class="px-3 py-1 rounded-full text-xs font-semibold
                                    @class([
                                        'bg-yellow-100 text-yellow-800' => $txn->payment_status === 'pending',
                                        'bg-blue-100 text-blue-800' => $txn->payment_status === 'partial',
                                        'bg-green-100 text-green-800' => $txn->payment_status === 'completed',
                                    ])
                                    dark:bg-opacity-40">
                                    {{ $paymentStatusText }}
                                </span>
                            </td>
                            <td class="py-3 px-3 text-right">
                                {{ currency($txn->total) }}
                            </td>
                            <td class="py-3 px-3 text-xs text-zinc-600 dark:text-zinc-400">
                                {{ $txn->created_at?->format('d/m/Y H:i') }}
                            </td>
                            <td class="py-3 px-3">
                                <div class="flex flex-wrap gap-2">
                                    <a href="{{ route('dashboard.pos.transaction.edit', $txn) }}" class="px-3 py-1 rounded bg-blue-600 text-white text-xs font-semibold hover:bg-blue-700">
                                        Abrir
                                    </a>
                                    <a href="{{ route('dashboard.pos.ticket.print', $txn) }}" target="_blank" class="px-3 py-1 rounded bg-gray-200 text-gray-800 text-xs font-semibold hover:bg-gray-300 dark:bg-zinc-800 dark:text-zinc-100 dark:hover:bg-zinc-700">
                                        Ticket
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-6 text-center text-zinc-500 dark:text-zinc-400">
                                No hay transacciones para mostrar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4">
            {{ $transactions->withQueryString()->links() }}
        </div>
    </div>
</div>
</x-layouts.app>
