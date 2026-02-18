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
                        @if($order->payments->count() > 0)
                        <a href="{{ route('dashboard.orders.payments-pdf', $order->id) }}" target="_blank" class="text-xs font-black uppercase text-pink-600 hover:text-pink-700 flex items-center gap-2">
                            <i class="fas fa-file-pdf"></i> Exportar Abonos (PDF)
                        </a>
                        @endif
                    </div>
                    <div class="p-6">
                        <!-- History -->
                        <table class="w-full text-sm text-left mb-6">
                            <thead class="text-xs text-gray-500 uppercase bg-gray-50 dark:bg-zinc-800 dark:text-gray-400">
                                <tr>
                                    <th class="px-4 py-2">Fecha</th>
                                    <th class="px-4 py-2">Método</th>
                                    <th class="px-4 py-2">Detalles</th>
                                    <th class="px-4 py-2 text-right">Monto</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($order->payments as $payment)
                                <tr class="border-b dark:border-gray-700 align-top">
                                    <td class="px-4 py-2 whitespace-nowrap">{{ $payment->created_at->format('d/m/Y') }}</td>
                                    <td class="px-4 py-2 whitespace-nowrap">
                                        {{ $payment->method->name }}
                                        <div class="text-[11px] text-gray-500">
                                            {{ ucfirst($payment->status) }}
                                        </div>
                                    </td>
                                    <td class="px-4 py-2 text-xs">
                                        @if($payment->transfer_number || $payment->capture_line)
                                            @if($payment->transfer_number)
                                                <div><span class="font-semibold">Núm. transferencia:</span> {{ $payment->transfer_number }}</div>
                                            @endif
                                            @if($payment->capture_line)
                                                <div><span class="font-semibold">Línea de captura:</span> {{ $payment->capture_line }}</div>
                                            @endif
                                            @if($payment->reference)
                                                <div class="mt-1 text-[11px] text-gray-500">Ref: {{ $payment->reference }}</div>
                                            @endif
                                        @else
                                            {{ $payment->reference ?? '-' }}
                                        @endif
                                    </td>
                                    <td class="px-4 py-2 text-right font-medium whitespace-nowrap">
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
                                <div class="col-span-1">
                                    <label class="block text-xs font-medium mb-1">Núm. transferencia</label>
                                    <input type="text" name="transfer_number" placeholder="Folio / Núm. transferencia" class="w-full rounded border-gray-300 text-sm p-2">
                                </div>
                                <div class="col-span-2">
                                    <label class="block text-xs font-medium mb-1">Línea de captura</label>
                                    <input type="text" name="capture_line" placeholder="Línea de captura / referencia bancaria" class="w-full rounded border-gray-300 text-sm p-2">
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
                        <form action="{{ route('dashboard.orders.update-status', $order->id) }}" method="POST" x-data="statusManager">
                            @csrf
                            @method('PUT')
                            <select name="status" x-model="selectedStatus" class="w-full rounded-lg border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-pink-500 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:focus:ring-offset-zinc-900">
                                <option value="pending">Pendiente</option>
                                <option value="partially_paid">Pago Parcial</option>
                                <option value="paid">Pagado</option>
                                <option value="shipped">Enviado</option>
                                <option value="delivered">Entregado</option>
                                <option value="cancelled">Cancelado</option>
                            </select>

                            <div x-show="selectedStatus === 'cancelled'" x-transition class="mt-4 rounded-md bg-yellow-50 p-3 border border-yellow-200 dark:bg-yellow-900/20 dark:border-yellow-900/50">
                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input id="restore_stock" name="restore_stock" type="checkbox" value="1" checked class="focus:ring-pink-500 h-4 w-4 text-pink-600 border-gray-300 rounded">
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="restore_stock" class="font-medium text-yellow-800 dark:text-yellow-400">Restaurar Stock</label>
                                        <p class="text-yellow-700 dark:text-yellow-500 text-xs mt-1">Si se marca, se devolverá el stock de todos los productos de este pedido.</p>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="mt-4 w-full rounded-lg bg-pink-600 px-4 py-2 text-sm font-semibold text-white hover:bg-pink-700">Actualizar Estado</button>
                        </form>
                    </div>
                </div>
                
                <!-- Helper for AlpineJS status -->
                <script>
                    document.addEventListener('alpine:init', () => {
                        Alpine.data('statusManager', () => ({
                            selectedStatus: '{{ $order->status }}',
                        }))
                    })
                </script>
                    </div>
                </div>

                <!-- Customer Info -->
                <div class="rounded-lg border border-zinc-200 bg-white shadow-sm dark:border-zinc-700 dark:bg-zinc-900" x-data="customerLink()">
                    <div class="border-b border-zinc-200 px-6 py-4 dark:border-zinc-700 flex justify-between items-center">
                        <h2 class="font-semibold text-zinc-900 dark:text-white">Cliente</h2>
                        @if(!$order->customer_id)
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400">
                            Invitado
                        </span>
                        @endif
                    </div>
                    <div class="p-6">
                        <!-- Search Section -->
                        <div class="mb-6 pb-6 border-b border-zinc-100 dark:border-zinc-800">
                            <label class="block text-xs font-bold uppercase tracking-wider text-zinc-500 mb-2">Vincular con base de datos</label>
                            <div class="relative">
                                <input type="text" x-model="search" @input.debounce.300ms="performSearch()"
                                    placeholder="Buscar por nombre o teléfono..." 
                                    class="w-full rounded-lg border-zinc-300 bg-white px-3 py-2 pl-10 text-sm shadow-sm focus:border-pink-500 focus:ring-pink-500 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3">
                                    <i class="fas fa-search text-zinc-400"></i>
                                </div>
                            </div>

                            <!-- Search Results -->
                            <div x-show="results.length > 0" class="absolute z-10 mt-2 w-full rounded-lg border border-zinc-200 bg-white shadow-xl dark:border-zinc-700 dark:bg-zinc-800 overflow-hidden" x-cloak>
                                <ul class="divide-y divide-zinc-100 dark:divide-zinc-700 max-h-60 overflow-y-auto">
                                    <template x-for="customer in results" :key="customer.id">
                                        <li>
                                            <button @click="selectCustomer(customer)" class="w-full text-left px-4 py-3 hover:bg-zinc-50 dark:hover:bg-zinc-700/50 transition-colors">
                                                <div class="font-bold text-sm text-zinc-900 dark:text-white" x-text="customer.name"></div>
                                                <div class="text-xs text-zinc-500" x-text="customer.phone + ' • ' + customer.email"></div>
                                            </button>
                                        </li>
                                    </template>
                                </ul>
                            </div>
                        </div>

                        <!-- Customer Details Form -->
                        <form action="{{ route('dashboard.orders.update-customer', $order->id) }}" method="POST" class="space-y-4">
                            @csrf
                            @method('PUT')
                            <div>
                                <label class="block text-xs font-medium text-zinc-500 dark:text-zinc-400 mb-1">Nombre Completo</label>
                                <input type="text" name="customer_name" id="field_name" value="{{ $order->customer_name }}" 
                                    class="w-full rounded-lg border-zinc-300 bg-white px-3 py-2 text-sm shadow-sm focus:border-pink-500 focus:ring-pink-500 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-zinc-500 dark:text-zinc-400 mb-1">Correo Electrónico</label>
                                <input type="email" name="customer_email" id="field_email" value="{{ $order->customer_email }}" 
                                    class="w-full rounded-lg border-zinc-300 bg-white px-3 py-2 text-sm shadow-sm focus:border-pink-500 focus:ring-pink-500 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-zinc-500 dark:text-zinc-400 mb-1">Teléfono</label>
                                <input type="text" name="customer_phone" id="field_phone" value="{{ $order->customer_phone }}" 
                                    class="w-full rounded-lg border-zinc-300 bg-white px-3 py-2 text-sm shadow-sm focus:border-pink-500 focus:ring-pink-500 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white">
                            </div>
                            <div class="flex gap-2 pt-2">
                                <button type="submit" class="flex-1 rounded-lg bg-zinc-900 px-4 py-2 text-xs font-black uppercase text-white hover:bg-zinc-800 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-100 transition-colors">
                                    <i class="fas fa-save mr-2"></i> Guardar Solo Info
                                </button>
                                <button type="button" @click="confirmLink()" x-show="selectedCustomerId" 
                                    class="flex-1 rounded-lg bg-pink-600 px-4 py-2 text-xs font-black uppercase text-white hover:bg-pink-700 shadow-lg shadow-pink-500/20 transition-all">
                                    <i class="fas fa-link mr-2"></i> Vincular Cliente
                                </button>
                            </div>
                        </form>
                        
                        @if(!$order->customer_id)
                        <div class="mt-4 pt-4 border-t border-zinc-100 dark:border-zinc-800">
                            <form action="{{ route('dashboard.orders.register-customer', $order->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full rounded-lg bg-green-600 px-4 py-2 text-xs font-black uppercase text-white hover:bg-green-700 shadow-lg shadow-green-500/10 transition-all">
                                    <i class="fas fa-user-plus mr-2"></i> Registrar como Nuevo Cliente
                                </button>
                            </form>
                            <p class="mt-2 text-[10px] text-zinc-500 text-center italic">Crea un registro permanente en tu base de datos de clientes.</p>
                        </div>
                        @else
                        <div class="mt-4 pt-4 border-t border-zinc-100 dark:border-zinc-800">
                            <a href="{{ route('dashboard.customers.show', $order->customer_id) }}" 
                                class="inline-flex items-center justify-center w-full px-4 py-2 rounded-lg border border-zinc-200 bg-zinc-50 text-xs font-bold text-zinc-700 hover:bg-zinc-100 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-300 dark:hover:bg-zinc-700 transition-colors">
                                <i class="fas fa-external-link-alt mr-2"></i> Ver Perfil Permanente
                            </a>
                        </div>
                        @endif
                        
                        <form id="link-form" action="{{ route('dashboard.orders.link-customer', $order->id) }}" method="POST" class="hidden">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="customer_id" :value="selectedCustomerId">
                        </form>
                    </div>
                </div>

                <script>
                    function customerLink() {
                        return {
                            search: '',
                            results: [],
                            selectedCustomerId: null,
                            async performSearch() {
                                if (this.search.length < 3) {
                                    this.results = [];
                                    return;
                                }
                                try {
                                    const response = await fetch(`{{ route('dashboard.pos.customers.search') }}?q=${this.search}`);
                                    this.results = await response.json();
                                } catch (e) {
                                    console.error('Search error', e);
                                }
                            },
                            selectCustomer(customer) {
                                document.getElementById('field_name').value = customer.name;
                                document.getElementById('field_email').value = customer.email;
                                document.getElementById('field_phone').value = customer.phone;
                                this.selectedCustomerId = customer.id;
                                this.results = [];
                                this.search = customer.name;
                            },
                            confirmLink() {
                                if (confirm('¿Quieres vincular este pedido oficialmente con este cliente?')) {
                                    document.getElementById('link-form').submit();
                                }
                            }
                        }
                    }
                </script>
            </div>
        </div>
    </div>
</x-layouts.app>
