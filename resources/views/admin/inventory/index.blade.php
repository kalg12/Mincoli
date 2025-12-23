<x-layouts.app :title="__('Inventario')">
    <div class="p-6 grid gap-6">
        <h1 class="text-2xl font-semibold">Dashboard de Inventario</h1>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="p-4 rounded-lg bg-white dark:bg-zinc-800 border dark:border-zinc-700">
                <div class="text-sm text-zinc-500">Productos</div>
                <div class="text-3xl font-bold">{{ $stats['total_products'] ?? 0 }}</div>
            </div>
            <div class="p-4 rounded-lg bg-white dark:bg-zinc-800 border dark:border-zinc-700">
                <div class="text-sm text-zinc-500">Stock Total</div>
                <div class="text-3xl font-bold">{{ $stats['total_stock'] ?? 0 }}</div>
            </div>
            <div class="p-4 rounded-lg bg-white dark:bg-zinc-800 border dark:border-zinc-700">
                <div class="text-sm text-zinc-500">Valor Total</div>
                <div class="text-3xl font-bold">$ {{ number_format($stats['total_value'] ?? 0, 2) }}</div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="rounded-lg bg-white dark:bg-zinc-800 border dark:border-zinc-700">
                <div class="p-4 font-semibold">Movimientos recientes</div>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="border-t dark:border-zinc-700">
                                <th class="px-4 py-2 text-left">Fecha</th>
                                <th class="px-4 py-2 text-left">Producto</th>
                                <th class="px-4 py-2 text-left">Tipo</th>
                                <th class="px-4 py-2 text-left">Cantidad</th>
                                <th class="px-4 py-2 text-left">Motivo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentMovements as $mv)
                                <tr class="border-t dark:border-zinc-700 hover:bg-zinc-50 dark:hover:bg-zinc-800/50">
                                    <td class="px-4 py-2 text-zinc-700 dark:text-zinc-300 text-xs">{{ $mv->created_at?->format('d/m/Y H:i') }}</td>
                                    <td class="px-4 py-2 text-zinc-900 dark:text-white">{{ $mv->product?->name }} @if($mv->variant) <span class="text-zinc-500 text-xs">- {{ $mv->variant->name }}</span> @endif</td>
                                    <td class="px-4 py-2">
                                        @if($mv->type === 'in')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300">
                                                Entrada
                                            </span>
                                        @elseif($mv->type === 'out')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300">
                                                Salida
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300">
                                                Ajuste
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2 font-semibold text-zinc-900 dark:text-white">
                                        {{ $mv->type === 'in' ? '+' : '-' }}{{ $mv->quantity }}
                                    </td>
                                    <td class="px-4 py-2 text-zinc-700 dark:text-zinc-300 text-xs">{{ ucfirst($mv->reason) }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="px-4 py-6 text-center text-zinc-500">Sin movimientos</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-4">
                    <a href="{{ route('dashboard.inventory.movements') }}" class="text-primary-600 hover:underline">Ver todos</a>
                </div>
            </div>
            <div class="rounded-lg bg-white dark:bg-zinc-800 border dark:border-zinc-700">
                <div class="p-4 font-semibold">Stock bajo</div>
                <ul class="divide-y dark:divide-zinc-700">
                    @forelse($lowStockProducts as $p)
                        <li class="px-4 py-3 flex items-center justify-between">
                            <span>{{ $p->name }}</span>
                            <span class="text-sm">Stock: <span class="font-semibold">{{ $p->stock }}</span></span>
                        </li>
                    @empty
                        <li class="px-4 py-6 text-center text-zinc-500">Sin alertas</li>
                    @endforelse
                </ul>
            </div>
        </div>

        <div class="flex gap-3">
            <a href="{{ route('dashboard.inventory.movements.create') }}" class="px-4 py-2 rounded bg-primary-600 text-white">Registrar movimiento</a>
            <a href="{{ route('dashboard.inventory.counts.create') }}" class="px-4 py-2 rounded bg-zinc-800 text-white">Nuevo conteo físico</a>
            <a href="{{ route('dashboard.inventory.counts.index') }}" class="px-4 py-2 rounded border dark:border-zinc-700">Ver conteos</a>
        </div>
    </div>
</x-layouts.app>
