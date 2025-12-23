<x-layouts.app :title="__('Movimientos de Inventario')">
    <div class="p-6 grid gap-6">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-semibold">Movimientos</h1>
            <a href="{{ route('dashboard.inventory.movements.create') }}" class="px-4 py-2 rounded bg-primary-600 text-white">Nuevo movimiento</a>
        </div>

        <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-3">
            <select name="type" class="border rounded px-3 py-2 dark:bg-zinc-900 dark:border-zinc-700">
                <option value="">Todos</option>
                <option value="in" @selected(request('type')==='in')>Entrada</option>
                <option value="out" @selected(request('type')==='out')>Salida</option>
                <option value="adjust" @selected(request('type')==='adjust')>Ajuste</option>
            </select>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar producto" class="border rounded px-3 py-2 dark:bg-zinc-900 dark:border-zinc-700" />
            <button class="px-4 py-2 rounded bg-zinc-800 text-white">Filtrar</button>
        </form>

        <div class="overflow-x-auto rounded-lg bg-white dark:bg-zinc-800 border dark:border-zinc-700">
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
                    @foreach($movements as $mv)
                        <tr class="border-t dark:border-zinc-700">
                            <td class="px-4 py-2 text-zinc-700 dark:text-zinc-300">{{ $mv->created_at?->format('d/m/Y H:i') }}</td>
                            <td class="px-4 py-2 text-zinc-900 dark:text-white">{{ $mv->product?->name }} @if($mv->variant) <span class="text-zinc-500">- {{ $mv->variant->name }}</span> @endif</td>
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
                            <td class="px-4 py-2 text-zinc-700 dark:text-zinc-300">{{ ucfirst($mv->reason) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div>{{ $movements->links() }}</div>
    </div>
</x-layouts.app>
