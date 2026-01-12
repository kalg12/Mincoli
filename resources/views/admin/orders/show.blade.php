<x-layouts.app :title="__('Detalle del Pedido')">
    <div class="flex-1">
        <div class="border-b border-zinc-200 bg-white px-6 py-4 dark:border-zinc-700 dark:bg-zinc-900 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Pedido #{{ $order->order_number }}</h1>
                <p class="text-sm text-zinc-600 dark:text-zinc-400">{{ $order->created_at->format('d/m/Y H:i') }}</p>
            </div>
            <a href="{{ route('dashboard.orders.index') }}" class="rounded-lg border border-zinc-200 px-4 py-2 text-sm font-medium text-zinc-900 hover:bg-zinc-100/50 dark:border-zinc-700 dark:text-white dark:hover:bg-zinc-800">Volver</a>
        </div>

        <div class="grid gap-6 p-6 lg:grid-cols-3">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Items -->
                <div class="rounded-lg border border-zinc-200 bg-white shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                    <div class="border-b border-zinc-200 px-6 py-4 dark:border-zinc-700">
                        <h2 class="font-semibold text-zinc-900 dark:text-white">Productos</h2>
                    </div>
                    <div class="p-6">
                        <ul class="divide-y divide-zinc-200 dark:divide-zinc-700">
                            @foreach($order->items as $item)
                            <li class="flex py-4">
                                <div class="h-16 w-16 flex-shrink-0 overflow-hidden rounded-md border border-zinc-200 dark:border-zinc-700 bg-gray-100">
                                     <!-- Placeholder or logic for image -->
                                </div>
                                <div class="ml-4 flex flex-1 flex-col">
                                    <div>
                                        <div class="flex justify-between text-base font-medium text-zinc-900 dark:text-white">
                                            <h3>{{ $item->product->name }}</h3>
                                            <p class="ml-4">${{ number_format($item->total, 2) }}</p>
                                        </div>
                                        <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">{{ $item->variant->name ?? '' }}</p>
                                    </div>
                                    <div class="flex flex-1 items-end justify-between text-sm">
                                        <p class="text-zinc-500 dark:text-zinc-400">Cant: {{ $item->quantity }}</p>
                                    </div>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                        <div class="mt-6 border-t border-zinc-200 pt-6 dark:border-zinc-700">
                            <div class="flex justify-between text-sm">
                                <p class="text-zinc-600 dark:text-zinc-400">Subtotal</p>
                                <p class="font-medium text-zinc-900 dark:text-white">${{ number_format($order->subtotal, 2) }}</p>
                            </div>
                            <div class="mt-2 flex justify-between text-sm">
                                <p class="text-zinc-600 dark:text-zinc-400">IVA</p>
                                <p class="font-medium text-zinc-900 dark:text-white">${{ number_format($order->iva_total, 2) }}</p>
                            </div>
                            <div class="mt-4 flex justify-between border-t border-zinc-200 pt-4 dark:border-zinc-700">
                                <p class="text-base font-bold text-zinc-900 dark:text-white">Total</p>
                                <p class="text-base font-bold text-pink-600">${{ number_format($order->total, 2) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Status Management -->
                <div class="rounded-lg border border-zinc-200 bg-white shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                    <div class="border-b border-zinc-200 px-6 py-4 dark:border-zinc-700">
                        <h2 class="font-semibold text-zinc-900 dark:text-white">Estado del Pedido</h2>
                    </div>
                    <div class="p-6">
                        <form action="{{ route('dashboard.orders.update-status', $order->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <select name="status" class="w-full rounded-lg border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-pink-500 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:focus:ring-offset-zinc-900">
                                <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pendiente</option>
                                <option value="paid" {{ $order->status === 'paid' ? 'selected' : '' }}>Pagado</option>
                                <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Enviado</option>
                                <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Entregado</option>
                                <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelado</option>
                            </select>
                            <button type="submit" class="mt-4 w-full rounded-lg bg-pink-600 px-4 py-2 text-sm font-semibold text-white hover:bg-pink-700">Actualizar Estado</button>
                        </form>
                    </div>
                </div>

                <!-- Customer Info -->
                <div class="rounded-lg border border-zinc-200 bg-white shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                    <div class="border-b border-zinc-200 px-6 py-4 dark:border-zinc-700">
                        <h2 class="font-semibold text-zinc-900 dark:text-white">Cliente</h2>
                    </div>
                    <div class="p-6 text-sm text-zinc-600 dark:text-zinc-400 space-y-2">
                        <p><span class="font-medium text-zinc-900 dark:text-white">Nombre:</span> {{ $order->customer_name }}</p>
                        <p><span class="font-medium text-zinc-900 dark:text-white">Email:</span> {{ $order->customer_email }}</p>
                        <p><span class="font-medium text-zinc-900 dark:text-white">Tel:</span> {{ $order->customer_phone }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
