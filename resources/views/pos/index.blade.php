<x-layouts.app :title="__('POS - Sistema de Ventas')">
<div class="p-6 space-y-6">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold">POS - Punto de Venta</h1>
        @if($activeSession)
            <div class="flex flex-wrap gap-2">
                <span class="px-4 py-2 bg-green-100 text-green-800 rounded-lg font-semibold">
                    Sesión Abierta: {{ $activeSession->session_number }}
                </span>
                <form action="{{ route('dashboard.pos.transaction.create', $activeSession) }}" method="GET" class="inline">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700">
                        Nueva Transacción
                    </button>
                </form>
                <a href="{{ route('dashboard.pos.transactions.index') }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg font-semibold hover:bg-gray-300">
                    Transacciones
                </a>
                <form action="{{ route('dashboard.pos.session.close', $activeSession) }}" method="POST" class="inline" onsubmit="return confirm('¿Cerrar sesión?')">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg font-semibold hover:bg-red-700">
                        Cerrar Sesión
                    </button>
                </form>
            </div>
        @else
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('dashboard.pos.session.open') }}" class="px-6 py-3 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700">
                    Abrir Nueva Sesión
                </a>
                <a href="{{ route('dashboard.pos.transactions.index') }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg font-semibold hover:bg-gray-300">
                    Transacciones
                </a>
            </div>
        @endif
    </div>

    <!-- Estadísticas -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded-lg shadow dark:bg-zinc-900 dark:border dark:border-zinc-700">
            <div class="text-gray-600 font-semibold mb-2">Ventas Hoy</div>
            <div class="text-3xl font-bold text-green-600">${{ number_format($stats['today_sales'], 2) }}</div>
        </div>
        <div class="bg-white p-6 rounded-lg shadow dark:bg-zinc-900 dark:border dark:border-zinc-700">
            <div class="text-gray-600 font-semibold mb-2">Transacciones Hoy</div>
            <div class="text-3xl font-bold text-blue-600">{{ $stats['today_transactions'] }}</div>
        </div>
        <div class="bg-white p-6 rounded-lg shadow dark:bg-zinc-900 dark:border dark:border-zinc-700">
            <div class="text-gray-600 font-semibold mb-2">Pagos Pendientes</div>
            <div class="text-3xl font-bold text-yellow-600">${{ number_format($stats['pending_payments'], 2) }}</div>
        </div>
    </div>

    <!-- Items Pendientes por Enviar -->
    <div class="rounded-xl border border-zinc-200 bg-white p-6 mb-8 shadow dark:border-zinc-700 dark:bg-zinc-900">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold">Productos Pendientes por Enviar</h2>
            <a href="{{ route('dashboard.pos.pending-shipments.index') }}" class="text-blue-600 hover:text-blue-800 font-semibold">
                Ver todos →
            </a>
        </div>

        @if($pendingShipments->count())
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-zinc-200 dark:border-zinc-700">
                            <th class="text-left py-3 px-4 font-semibold">Transacción</th>
                            <th class="text-left py-3 px-4 font-semibold">Cliente</th>
                            <th class="text-left py-3 px-4 font-semibold">Producto</th>
                            <th class="text-left py-3 px-4 font-semibold">Cantidad</th>
                            <th class="text-left py-3 px-4 font-semibold">Estado</th>
                            <th class="text-left py-3 px-4 font-semibold">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pendingShipments as $item)
                            <tr class="border-b border-zinc-200 dark:border-zinc-700 hover:bg-gray-50 dark:hover:bg-zinc-800/60">
                                <td class="py-3 px-4 font-mono text-sm">{{ $item->posTransaction->transaction_number }}</td>
                                <td class="py-3 px-4">{{ $item->posTransaction->customer?->name ?? 'Sin cliente' }}</td>
                                <td class="py-3 px-4">{{ $item->product_name }}</td>
                                <td class="py-3 px-4">{{ $item->quantity }}</td>
                                <td class="py-3 px-4">
                                    <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm font-semibold dark:bg-yellow-900 dark:text-yellow-200">
                                        {{ ucfirst(str_replace('_', ' ', $item->status)) }}
                                    </span>
                                </td>
                                <td class="py-3 px-4">
                                    <form action="{{ route('dashboard.pos.item.shipped', $item) }}" method="POST" class="inline">
        @csrf @method('PATCH')
                                        <button type="submit" class="text-blue-600 hover:text-blue-800 font-semibold">
                                            Enviado
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $pendingShipments->links() }}
            </div>
        @else
            <div class="text-center py-8 text-gray-500">
                No hay productos pendientes por enviar
            </div>
        @endif
    </div>
</div>
</x-layouts.app>
