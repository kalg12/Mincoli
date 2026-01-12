<x-layouts.app :title="__('Pedidos')">
    <div class="flex-1">
        <div class="border-b border-zinc-200 bg-white px-6 py-4 dark:border-zinc-700 dark:bg-zinc-900">
            <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Pedidos</h1>
            <p class="text-sm text-zinc-600 dark:text-zinc-400">Gestiona las órdenes de compra</p>
        </div>

        <div class="p-6">
            <div class="overflow-hidden rounded-lg border border-zinc-200 bg-white shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                <table class="w-full text-left text-sm text-zinc-600 dark:text-zinc-400">
                    <thead class="bg-zinc-50 text-xs uppercase text-zinc-500 dark:bg-zinc-800 dark:text-zinc-400">
                        <tr>
                            <th class="px-6 py-3">Pedido</th>
                            <th class="px-6 py-3">Cliente</th>
                            <th class="px-6 py-3">Total</th>
                            <th class="px-6 py-3">Fecha</th>
                            <th class="px-6 py-3">Estado</th>
                            <th class="px-6 py-3 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                        @foreach($orders as $order)
                        <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50">
                            <td class="px-6 py-4 font-medium text-zinc-900 dark:text-white">
                                {{ $order->order_number }}
                                <div class="text-xs text-zinc-500">{{ $order->payment_method_name }}</div>
                            </td>
                            <td class="px-6 py-4">
                                {{ $order->customer_name ?? 'Invitado' }}
                                <div class="text-xs text-zinc-500">{{ $order->customer_email }}</div>
                            </td>
                            <td class="px-6 py-4">
                                ${{ number_format($order->total, 2) }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $order->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex rounded-full px-2 py-1 text-xs font-medium 
                                    {{ $order->status === 'paid' ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 
                                       ($order->status === 'partially_paid' ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' : 
                                       ($order->status === 'pending' ? 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400' : 
                                       'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-400')) }}">
                                    {{ $order->status_label }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right flex justify-end gap-2">
                                <a href="{{ route('dashboard.orders.show', $order->id) }}" class="font-medium text-pink-600 hover:text-pink-500">Ver</a>
                                <form action="{{ route('dashboard.orders.destroy', $order->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este pedido?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="font-medium text-red-600 hover:text-red-500">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="p-4">
                    {{ $orders->links() }}
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
