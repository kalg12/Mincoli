<x-layouts.app :title="__('POS - Pendientes por Enviar')">
<div class="p-6 space-y-6">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold">Productos Pendientes por Enviar</h1>
        <a href="{{ route('dashboard.pos.index') }}" class="text-blue-600 hover:text-blue-800 font-semibold">
            ← Volver al POS
        </a>
    </div>

    @if($items->count())
        <div class="overflow-x-auto rounded-xl border border-zinc-200 bg-white shadow dark:border-zinc-700 dark:bg-zinc-900">
            <table class="w-full">
                <thead>
                    <tr class="border-b bg-gray-50">
                        <th class="text-left py-4 px-6 font-semibold">Transacción</th>
                        <th class="text-left py-4 px-6 font-semibold">Cliente</th>
                        <th class="text-left py-4 px-6 font-semibold">Teléfono</th>
                        <th class="text-left py-4 px-6 font-semibold">Producto</th>
                        <th class="text-left py-4 px-6 font-semibold">SKU/Barcode</th>
                        <th class="text-center py-4 px-6 font-semibold">Cantidad</th>
                        <th class="text-left py-4 px-6 font-semibold">Estado</th>
                        <th class="text-left py-4 px-6 font-semibold">Fecha</th>
                        <th class="text-center py-4 px-6 font-semibold">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $item)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="py-4 px-6 font-mono text-sm font-semibold">
                                <a href="{{ route('dashboard.pos.transaction.edit', $item->posTransaction) }}" class="text-blue-600 hover:text-blue-800">
                                    {{ $item->posTransaction->transaction_number }}
                                </a>
                            </td>
                            <td class="py-4 px-6">{{ $item->posTransaction->customer?->name ?? 'Sin cliente' }}</td>
                            <td class="py-4 px-6">{{ $item->posTransaction->customer?->phone }}</td>
                            <td class="py-4 px-6">
                                <div class="font-semibold">{{ $item->product_name }}</div>
                                <div class="text-xs text-gray-500">{{ $item->product->category?->name }}</div>
                            </td>
                            <td class="py-4 px-6 font-mono text-sm">
                                <div>{{ $item->product_sku }}</div>
                                @if($item->product_barcode)
                                    <div class="text-xs text-gray-500">{{ $item->product_barcode }}</div>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-center font-semibold">{{ $item->quantity }}</td>
                            <td class="py-4 px-6">
                                <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm font-semibold">
                                    {{ ucfirst(str_replace('_', ' ', $item->status)) }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-sm text-gray-600">
                                {{ $item->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="py-4 px-6 text-center">
                                <div class="flex gap-2 justify-center">
                                    <form action="{{ route('dashboard.pos.item.shipped', $item) }}" method="POST" class="inline">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm font-semibold transition">
                                            Enviado
                                        </button>
                                    </form>
                                    <form action="{{ route('dashboard.pos.item.completed', $item) }}" method="POST" class="inline">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700 text-sm font-semibold transition">
                                            Completado
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $items->links() }}
        </div>
    @else
        <div class="bg-white rounded-lg shadow p-12 text-center">
            <div class="text-6xl mb-4">📦</div>
            <h2 class="text-2xl font-bold mb-2">No hay productos pendientes</h2>
            <p class="text-gray-600 mb-6">Todos los productos han sido enviados o completados</p>
            <a href="{{ route('dashboard.pos.index') }}" class="inline-block px-6 py-2 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700">
                Volver al POS
            </a>
        </div>
    @endif
</div>
</x-layouts.app>
