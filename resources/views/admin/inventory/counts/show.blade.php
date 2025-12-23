<x-layouts.app :title="__('Detalle de Conteo')">
    <div class="p-6 grid gap-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold">{{ $count->name }}</h1>
                <p class="text-sm text-zinc-500">Estado: {{ ucfirst(str_replace('_',' ', $count->status)) }}</p>
            </div>
            <div class="flex gap-2">
                @if($count->status === 'draft')
                    <form method="POST" action="{{ route('dashboard.inventory.counts.start', $count) }}">
                        @csrf
                        <button class="px-4 py-2 rounded bg-primary-600 text-white">Iniciar conteo</button>
                    </form>
                @elseif($count->status === 'in_progress')
                    <a href="{{ route('dashboard.inventory.counts.capture', $count) }}" class="px-4 py-2 rounded bg-zinc-800 text-white">Capturar (interno)</a>
                    @if($count->public_capture_enabled && $count->public_token)
                        <a href="{{ route('inventory.public.capture', $count->public_token) }}" target="_blank" class="px-4 py-2 rounded bg-emerald-600 text-white">Capturar (público)</a>
                    @endif
                    <form method="POST" action="{{ route('dashboard.inventory.counts.complete', $count) }}">
                        @csrf
                        <button class="px-4 py-2 rounded border dark:border-zinc-700">Completar</button>
                    </form>
                @elseif($count->status === 'completed')
                    <form method="POST" action="{{ route('dashboard.inventory.counts.reopen', $count) }}" class="inline">
                        @csrf
                        <button class="px-4 py-2 rounded bg-yellow-600 text-white">Reabrir conteo</button>
                    </form>
                    <form method="POST" action="{{ route('dashboard.inventory.counts.review', $count) }}">
                        @csrf
                        <button class="px-4 py-2 rounded bg-primary-600 text-white">Aplicar ajustes</button>
                    </form>
                @elseif($count->status === 'reviewed')
                    <form method="POST" action="{{ route('dashboard.inventory.counts.reopen', $count) }}" class="inline">
                        @csrf
                        <button class="px-4 py-2 rounded bg-yellow-600 text-white">Reabrir conteo</button>
                    </form>
                @endif
                <a href="{{ route('dashboard.inventory.counts.export', $count) }}" class="px-4 py-2 rounded border dark:border-zinc-700">Exportar CSV</a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="p-4 rounded-lg bg-white dark:bg-zinc-800 border dark:border-zinc-700">
                <div class="text-sm text-zinc-500">Items</div>
                <div class="text-3xl font-bold">{{ $stats['total_items'] }}</div>
            </div>
            <div class="p-4 rounded-lg bg-white dark:bg-zinc-800 border dark:border-zinc-700">
                <div class="text-sm text-zinc-500">Contados</div>
                <div class="text-3xl font-bold">{{ $stats['counted_items'] }}</div>
            </div>
            <div class="p-4 rounded-lg bg-white dark:bg-zinc-800 border dark:border-zinc-700">
                <div class="text-sm text-zinc-500">Diferencia</div>
                <div class="text-3xl font-bold">{{ $stats['total_difference'] }}</div>
            </div>
            <div class="p-4 rounded-lg bg-white dark:bg-zinc-800 border dark:border-zinc-700">
                <div class="text-sm text-zinc-500">Merma/Excedente</div>
                <div class="text-3xl font-bold">$ {{ number_format($count->total_value_difference, 2) }}</div>
            </div>
        </div>

        <div class="overflow-x-auto rounded-lg bg-white dark:bg-zinc-800 border dark:border-zinc-700">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="border-t dark:border-zinc-700">
                        <th class="px-4 py-2 text-left">Producto</th>
                        <th class="px-4 py-2 text-left">Sistema</th>
                        <th class="px-4 py-2 text-left">Contado</th>
                        <th class="px-4 py-2 text-left">Diferencia</th>
                        <th class="px-4 py-2 text-left">Notas</th>
                        <th class="px-4 py-2 text-left">Capturado por</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($count->items as $item)
                        <tr class="border-t dark:border-zinc-700">
                            <td class="px-4 py-2">{{ $item->product?->name }}@if($item->variant) <span class="text-zinc-500 text-xs">- {{ $item->variant->name }}</span> @endif</td>
                            <td class="px-4 py-2">{{ $item->system_quantity }}</td>
                            <td class="px-4 py-2 font-semibold">{{ $item->counted_quantity ?? '-' }}</td>
                            <td class="px-4 py-2 font-semibold {{ $item->difference > 0 ? 'text-green-600' : ($item->difference < 0 ? 'text-red-600' : '') }}">{{ $item->difference ?? '-' }}</td>
                            <td class="px-4 py-2 text-zinc-600 dark:text-zinc-400 text-sm">{{ $item->notes ?: '-' }}</td>
                            <td class="px-4 py-2 text-zinc-600 dark:text-zinc-400 text-sm">{{ $item->counted_by_name ?? $item->countedBy?->name ?? 'N/A' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-layouts.app>
