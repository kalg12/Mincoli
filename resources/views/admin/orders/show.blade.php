<x-layouts.app :title="__('Detalle del Pedido')">
    <div class="flex-1">
        <div class="border-b border-zinc-200 bg-white px-6 py-4 dark:border-zinc-700 dark:bg-zinc-900 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Pedido #{{ $order->order_number }}</h1>
                <p class="text-sm text-zinc-600 dark:text-zinc-400">{{ $order->created_at->format('d/m/Y H:i') }}</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('checkout.receipt', $order->id) }}" target="_blank" class="rounded-lg border border-pink-200 bg-pink-50 px-4 py-2 text-sm font-medium text-pink-700 hover:bg-pink-100 dark:border-pink-900 dark:bg-pink-900/30 dark:text-pink-300">
                    <i class="fas fa-print mr-2"></i> Imprimir Comprobante
                </a>
                <form action="{{ route('dashboard.orders.destroy', $order->id) }}" method="POST" onsubmit="return confirm('¿ESTÁS SEGURO? Esta acción no se puede deshacer y borrará permanentemente el pedido.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="rounded-lg border border-red-200 bg-red-50 px-4 py-2 text-sm font-medium text-red-700 hover:bg-red-100 dark:border-red-900 dark:bg-red-900/30 dark:text-red-300">
                        <i class="fas fa-trash-alt mr-2"></i> Eliminar
                    </button>
                </form>
                <a href="{{ route('dashboard.orders.index') }}" class="rounded-lg border border-zinc-200 px-4 py-2 text-sm font-medium text-zinc-900 hover:bg-zinc-100/50 dark:border-zinc-700 dark:text-white dark:hover:bg-zinc-800">Volver</a>
            </div>
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
                                <div class="ml-4 flex flex-1 flex-col">
                                    <div class="flex justify-between text-base font-medium text-zinc-900 dark:text-white">
                                        <h3>{{ $item->product->name }}</h3>
                                        <p class="ml-4">${{ number_format($item->total, 2) }}</p>
                                    </div>
                                    <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">{{ $item->variant->name ?? '' }}</p>
                                    <p class="text-sm text-zinc-500 dark:text-zinc-400">Cant: {{ $item->quantity }}</p>
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
                            <div class="mt-4 flex justify-between border-t border-zinc-200 pt-4 dark:border-zinc-700 text-lg">
                                <p class="font-bold text-zinc-900 dark:text-white">Total</p>
                                <p class="font-bold text-pink-600">${{ number_format($order->total, 2) }}</p>
                            </div>
                            <!-- Balance Info -->
                            <div class="mt-4 bg-gray-50 dark:bg-zinc-800 rounded p-4">
                                <div class="flex justify-between text-sm mb-1 text-green-700 dark:text-green-400">
                                    <span>Pagado:</span>
                                    <span>${{ number_format($order->total_paid, 2) }}</span>
                                </div>
                                <div class="flex justify-between text-base font-bold text-red-600 dark:text-red-400">
                                    <span>Restante:</span>
                                    <span>${{ number_format($order->remaining, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payments & Installments -->
                <div class="rounded-lg border border-zinc-200 bg-white shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                    <div class="border-b border-zinc-200 px-6 py-4 dark:border-zinc-700 flex justify-between items-center">
                        <h2 class="font-semibold text-zinc-900 dark:text-white">Pagos y Abonos</h2>
                    </div>
                    <div class="p-6">
                        <!-- History -->
                        <table class="w-full text-sm text-left mb-6">
                            <thead class="text-xs text-gray-500 uppercase bg-gray-50 dark:bg-zinc-800 dark:text-gray-400">
                                <tr>
                                    <th class="px-4 py-2">Fecha</th>
                                    <th class="px-4 py-2">Método</th>
                                    <th class="px-4 py-2">Ref</th>
                                    <th class="px-4 py-2 text-right">Monto</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($order->payments as $payment)
                                <tr class="border-b dark:border-gray-700">
                                    <td class="px-4 py-2">{{ $payment->created_at->format('d/m/Y') }}</td>
                                    <td class="px-4 py-2">{{ $payment->method->name }}</td>
                                    <td class="px-4 py-2 text-xs">{{ $payment->reference ?? '-' }}</td>
                                    <td class="px-4 py-2 text-right font-medium">
                                        ${{ number_format($payment->amount, 2) }}
                                        <form action="{{ route('dashboard.orders.payments.destroy', [$order->id, $payment->id]) }}" method="POST" class="inline-block ml-2" onsubmit="return confirm('¿Eliminar este pago?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-700 text-xs">
                                                <i class="fas fa-times-circle"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="4" class="px-4 py-2 text-center text-gray-500">No hay pagos registrados</td></tr>
                                @endforelse
                            </tbody>
                        </table>

                        <!-- Add Payment Form -->
                        @if($order->remaining > 0)
                        <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg border border-blue-100 dark:border-blue-800">
                            <h3 class="text-sm font-bold text-blue-900 dark:text-blue-300 mb-2">Registrar Nuevo Pago (Abono)</h3>
                            <form action="{{ route('dashboard.orders.payments.store', $order->id) }}" method="POST" class="grid gap-3 lg:grid-cols-4 items-end">
                                @csrf
                                <div class="col-span-1">
                                    <label class="block text-xs font-medium mb-1">Monto ($)</label>
                                    <input type="number" step="0.01" name="amount" value="{{ $order->remaining }}" max="{{ $order->remaining }}" class="w-full rounded border-gray-300 text-sm p-2">
                                </div>
                                <div class="col-span-1">
                                    <label class="block text-xs font-medium mb-1">Método</label>
                                    <select name="payment_method_id" class="w-full rounded border-gray-300 text-sm p-2">
                                        @foreach(\App\Models\PaymentMethod::where('is_active', true)->get() as $pm)
                                            <option value="{{ $pm->id }}">{{ $pm->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-span-1">
                                    <label class="block text-xs font-medium mb-1">Referencia</label>
                                    <input type="text" name="reference" placeholder="Ej. Voucher 123" class="w-full rounded border-gray-300 text-sm p-2">
                                </div>
                                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white rounded px-4 py-2 text-sm font-medium">Registrar</button>
                            </form>
                        </div>
                        @else
                        <div class="text-center text-green-600 font-medium py-2 bg-green-50 rounded">
                            <i class="fas fa-check-circle mr-1"></i> Pedido Pagado Totalmente
                        </div>
                        @endif
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
                        <div class="mb-4 text-center">
                            <span class="inline-block px-3 py-1 rounded-full text-sm font-bold bg-gray-100 text-gray-800 border">
                                {{ $order->status_label }}
                            </span>
                        </div>
                        <form action="{{ route('dashboard.orders.update-status', $order->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <select name="status" class="w-full rounded-lg border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-pink-500 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:focus:ring-offset-zinc-900">
                                <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pendiente</option>
                                <option value="partially_paid" {{ $order->status === 'partially_paid' ? 'selected' : '' }}>Pago Parcial</option>
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
