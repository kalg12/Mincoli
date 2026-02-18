<x-layouts.app :title="__('Perfil de cliente')">
    <div class="flex-1">
        <div class="border-b border-zinc-200 bg-white px-6 py-4 dark:border-zinc-700 dark:bg-zinc-900">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">{{ $customer->name }}</h1>
                    <p class="text-sm text-zinc-600 dark:text-zinc-400">Desde {{ optional($customer->created_at)->format('d/m/Y') }}</p>
                </div>
                <a href="{{ route('dashboard.customers.index') }}" class="rounded-lg border border-zinc-200 px-4 py-2 text-sm font-semibold text-zinc-900 hover:bg-zinc-100/50 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-zinc-700 dark:text-white dark:hover:bg-zinc-800 dark:focus:ring-offset-zinc-900">Volver</a>
            </div>
        </div>

        <div class="grid gap-6 p-6 md:grid-cols-3">
            <!-- Formulario de edición -->
            <form method="POST" action="{{ route('dashboard.customers.update', $customer->id) }}" class="space-y-4 rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900 md:col-span-2">
                @csrf
                @method('PUT')

                <h2 class="text-lg font-semibold text-zinc-900 dark:text-white">Información del cliente</h2>

                @if ($errors->any())
                    <div class="rounded-lg bg-red-50 border border-red-200 p-4 dark:bg-red-900/20 dark:border-red-800">
                        <p class="text-sm font-medium text-red-800 dark:text-red-300">Hay errores en el formulario</p>
                        <ul class="mt-2 list-inside list-disc text-sm text-red-700 dark:text-red-400">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="grid gap-4 md:grid-cols-2 pb-4 border-b border-zinc-200 dark:border-zinc-700">
                    <div>
                        <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Nombre <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $customer->name) }}" class="w-full rounded-lg border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:focus:ring-offset-zinc-900" required />
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Email <span class="text-red-500">*</span></label>
                        <input type="email" name="email" value="{{ old('email', $customer->email) }}" class="w-full rounded-lg border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:focus:ring-offset-zinc-900" required />
                    </div>
                    <div class="md:col-span-2">
                        <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Teléfono <span class="text-red-500">*</span></label>
                        <input type="tel" name="phone" value="{{ old('phone', $customer->phone) }}" class="w-full rounded-lg border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:focus:ring-offset-zinc-900" required />
                    </div>
                </div>

                <div class="flex justify-end gap-2 pt-4">
                    <a href="{{ route('dashboard.customers.index') }}" class="rounded-lg border border-zinc-200 px-4 py-2 text-sm font-medium text-zinc-900 hover:bg-zinc-100/50 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-zinc-700 dark:text-white dark:hover:bg-zinc-800 dark:focus:ring-offset-zinc-900">Cancelar</a>
                    <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:hover:bg-blue-700 dark:focus:ring-offset-zinc-900">Guardar cambios</button>
                </div>
            </form>

            <!-- Sección de Direcciones de Envío -->
            <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900 md:col-span-2">
                <div class="mb-4 flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-zinc-900 dark:text-white">Direcciones de Envío</h2>
                    <button onclick="document.getElementById('add-address-modal').showModal()" class="rounded-lg bg-green-600 px-3 py-1.5 text-sm font-semibold text-white hover:bg-green-700">
                        + Agregar Dirección
                    </button>
                </div>

                @if($customer->addresses->count() > 0)
                    <div class="grid gap-4 md:grid-cols-2">
                        @foreach($customer->addresses as $address)
                            <div class="relative rounded-lg border {{ $address->is_default ? 'border-green-500 bg-green-50 dark:bg-green-900/20' : 'border-zinc-200 dark:border-zinc-700' }} p-4">
                                @if($address->is_default)
                                    <span class="absolute -top-2 -right-2 rounded-full bg-green-500 px-2 py-0.5 text-xs font-semibold text-white">Predeterminada</span>
                                @endif
                                <div class="flex items-start justify-between">
                                    <div>
                                        <p class="font-semibold text-zinc-900 dark:text-white">{{ $address->label }}</p>
                                        <p class="text-sm text-zinc-600 dark:text-zinc-400 mt-1">
                                            {{ $address->street }} {{ $address->ext_number }}@if($address->int_number), Int. {{ $address->int_number }}@endif
                                        </p>
                                        <p class="text-sm text-zinc-600 dark:text-zinc-400">{{ $address->colony }}, {{ $address->city }}, {{ $address->state }}</p>
                                        <p class="text-sm text-zinc-600 dark:text-zinc-400">C.P. {{ $address->zip }}</p>
                                        @if($address->references)
                                            <p class="text-xs text-zinc-500 dark:text-zinc-500 mt-1">Ref: {{ $address->references }}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="mt-3 flex gap-2">
                                    @if(!$address->is_default)
                                        <form method="POST" action="{{ route('dashboard.customers.addresses.set-default', [$customer->id, $address->id]) }}">
                                            @csrf
                                            <button type="submit" class="rounded border border-zinc-200 px-2 py-1 text-xs font-medium text-zinc-700 hover:bg-zinc-100 dark:border-zinc-600 dark:text-zinc-300 dark:hover:bg-zinc-800">Establecer predeterminada</button>
                                        </form>
                                    @endif
                                    <button onclick="document.getElementById('edit-address-{{ $address->id }}').showModal()" class="rounded border border-zinc-200 px-2 py-1 text-xs font-medium text-blue-700 hover:bg-blue-50 dark:border-zinc-600 dark:text-blue-300 dark:hover:bg-blue-900/20">Editar</button>
                                    <form method="POST" action="{{ route('dashboard.customers.addresses.destroy', [$customer->id, $address->id]) }}" onsubmit="return confirm('¿Eliminar esta dirección?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded border border-red-200 px-2 py-1 text-xs font-medium text-red-700 hover:bg-red-50 dark:border-red-800 dark:text-red-400 dark:hover:bg-red-900/20">Eliminar</button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-zinc-500 dark:text-zinc-400">No hay direcciones agregadas. Agrega una dirección para facilitar el envío de pedidos.</p>
                @endif
            </div>

            <!-- Modal para agregar dirección -->
            <dialog id="add-address-modal" class="rounded-lg p-6 dark:bg-zinc-900 w-full max-w-lg">
                <h3 class="mb-4 text-lg font-semibold text-zinc-900 dark:text-white">Agregar Dirección</h3>
                <form method="POST" action="{{ route('dashboard.customers.addresses.store', $customer->id) }}" class="space-y-4">
                    @csrf
                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Etiqueta <span class="text-red-500">*</span></label>
                            <input type="text" name="label" placeholder="Casa, Oficina, etc." class="w-full rounded-lg border border-zinc-200 bg-white px-3 py-2 text-sm dark:border-zinc-700 dark:bg-zinc-800 dark:text-white" required />
                        </div>
                        <div class="flex items-end">
                            <label class="flex items-center gap-2 text-sm text-zinc-700 dark:text-zinc-300">
                                <input type="checkbox" name="is_default" class="rounded border-zinc-300" />
                                Establecer como predeterminada
                            </label>
                        </div>
                        <div class="md:col-span-2">
                            <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Calle <span class="text-red-500">*</span></label>
                            <input type="text" name="street" placeholder="Calle principal" class="w-full rounded-lg border border-zinc-200 bg-white px-3 py-2 text-sm dark:border-zinc-700 dark:bg-zinc-800 dark:text-white" required />
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">No. Exterior</label>
                            <input type="text" name="ext_number" placeholder="123" class="w-full rounded-lg border border-zinc-200 bg-white px-3 py-2 text-sm dark:border-zinc-700 dark:bg-zinc-800 dark:text-white" />
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">No. Interior</label>
                            <input type="text" name="int_number" placeholder="Apt 4" class="w-full rounded-lg border border-zinc-200 bg-white px-3 py-2 text-sm dark:border-zinc-700 dark:bg-zinc-800 dark:text-white" />
                        </div>
                        <div class="md:col-span-2">
                            <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Colonia <span class="text-red-500">*</span></label>
                            <input type="text" name="colony" placeholder="Colonia" class="w-full rounded-lg border border-zinc-200 bg-white px-3 py-2 text-sm dark:border-zinc-700 dark:bg-zinc-800 dark:text-white" required />
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Ciudad <span class="text-red-500">*</span></label>
                            <input type="text" name="city" placeholder="Ciudad" class="w-full rounded-lg border border-zinc-200 bg-white px-3 py-2 text-sm dark:border-zinc-700 dark:bg-zinc-800 dark:text-white" required />
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Estado <span class="text-red-500">*</span></label>
                            <input type="text" name="state" placeholder="Estado" class="w-full rounded-lg border border-zinc-200 bg-white px-3 py-2 text-sm dark:border-zinc-700 dark:bg-zinc-800 dark:text-white" required />
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Código Postal <span class="text-red-500">*</span></label>
                            <input type="text" name="zip" placeholder="CP" class="w-full rounded-lg border border-zinc-200 bg-white px-3 py-2 text-sm dark:border-zinc-700 dark:bg-zinc-800 dark:text-white" required />
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Referencias</label>
                            <input type="text" name="references" placeholder="Entre calles, señas, etc." class="w-full rounded-lg border border-zinc-200 bg-white px-3 py-2 text-sm dark:border-zinc-700 dark:bg-zinc-800 dark:text-white" />
                        </div>
                    </div>
                    <div class="flex justify-end gap-2 pt-4">
                        <button type="button" onclick="document.getElementById('add-address-modal').close()" class="rounded-lg border border-zinc-200 px-4 py-2 text-sm font-medium text-zinc-900 hover:bg-zinc-100 dark:border-zinc-700 dark:text-white dark:hover:bg-zinc-800">Cancelar</button>
                        <button type="submit" class="rounded-lg bg-green-600 px-4 py-2 text-sm font-semibold text-white hover:bg-green-700">Guardar Dirección</button>
                    </div>
                </form>
            </dialog>

            <!-- Modales para editar direcciones -->
            @foreach($customer->addresses as $address)
            <dialog id="edit-address-{{ $address->id }}" class="rounded-lg p-6 dark:bg-zinc-900 w-full max-w-lg">
                <h3 class="mb-4 text-lg font-semibold text-zinc-900 dark:text-white">Editar Dirección</h3>
                <form method="POST" action="{{ route('dashboard.customers.addresses.update', [$customer->id, $address->id]) }}" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Etiqueta <span class="text-red-500">*</span></label>
                            <input type="text" name="label" value="{{ $address->label }}" class="w-full rounded-lg border border-zinc-200 bg-white px-3 py-2 text-sm dark:border-zinc-700 dark:bg-zinc-800 dark:text-white" required />
                        </div>
                        <div class="flex items-end">
                            <label class="flex items-center gap-2 text-sm text-zinc-700 dark:text-zinc-300">
                                <input type="checkbox" name="is_default" class="rounded border-zinc-300" {{ $address->is_default ? 'checked' : '' }} />
                                Predeterminada
                            </label>
                        </div>
                        <div class="md:col-span-2">
                            <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Calle <span class="text-red-500">*</span></label>
                            <input type="text" name="street" value="{{ $address->street }}" class="w-full rounded-lg border border-zinc-200 bg-white px-3 py-2 text-sm dark:border-zinc-700 dark:bg-zinc-800 dark:text-white" required />
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">No. Exterior</label>
                            <input type="text" name="ext_number" value="{{ $address->ext_number }}" class="w-full rounded-lg border border-zinc-200 bg-white px-3 py-2 text-sm dark:border-zinc-700 dark:bg-zinc-800 dark:text-white" />
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">No. Interior</label>
                            <input type="text" name="int_number" value="{{ $address->int_number }}" class="w-full rounded-lg border border-zinc-200 bg-white px-3 py-2 text-sm dark:border-zinc-700 dark:bg-zinc-800 dark:text-white" />
                        </div>
                        <div class="md:col-span-2">
                            <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Colonia <span class="text-red-500">*</span></label>
                            <input type="text" name="colony" value="{{ $address->colony }}" class="w-full rounded-lg border border-zinc-200 bg-white px-3 py-2 text-sm dark:border-zinc-700 dark:bg-zinc-800 dark:text-white" required />
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Ciudad <span class="text-red-500">*</span></label>
                            <input type="text" name="city" value="{{ $address->city }}" class="w-full rounded-lg border border-zinc-200 bg-white px-3 py-2 text-sm dark:border-zinc-700 dark:bg-zinc-800 dark:text-white" required />
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Estado <span class="text-red-500">*</span></label>
                            <input type="text" name="state" value="{{ $address->state }}" class="w-full rounded-lg border border-zinc-200 bg-white px-3 py-2 text-sm dark:border-zinc-700 dark:bg-zinc-800 dark:text-white" required />
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Código Postal <span class="text-red-500">*</span></label>
                            <input type="text" name="zip" value="{{ $address->zip }}" class="w-full rounded-lg border border-zinc-200 bg-white px-3 py-2 text-sm dark:border-zinc-700 dark:bg-zinc-800 dark:text-white" required />
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Referencias</label>
                            <input type="text" name="references" value="{{ $address->references }}" class="w-full rounded-lg border border-zinc-200 bg-white px-3 py-2 text-sm dark:border-zinc-700 dark:bg-zinc-800 dark:text-white" />
                        </div>
                    </div>
                    <div class="flex justify-end gap-2 pt-4">
                        <button type="button" onclick="document.getElementById('edit-address-{{ $address->id }}').close()" class="rounded-lg border border-zinc-200 px-4 py-2 text-sm font-medium text-zinc-900 hover:bg-zinc-100 dark:border-zinc-700 dark:text-white dark:hover:bg-zinc-800">Cancelar</button>
                        <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">Actualizar Dirección</button>
                    </div>
                </form>
            </dialog>
            @endforeach

            <!-- Panel lateral -->
            <div class="space-y-4">
                <!-- Resumen -->
                <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
                    <h3 class="mb-4 text-lg font-semibold text-zinc-900 dark:text-white">Resumen</h3>
                    <div class="space-y-4">
                        <div>
                            <p class="text-xs font-medium text-zinc-500 dark:text-zinc-400">PEDIDOS</p>
                            <p class="text-2xl font-bold text-zinc-900 dark:text-white">{{ $customer->orders_count ?? 0 }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-zinc-500 dark:text-zinc-400">TOTAL GASTADO</p>
                            <p class="text-2xl font-bold text-zinc-900 dark:text-white">${{ number_format($customer->total_spent ?? 0, 2) }}</p>
                        </div>
                        <div class="rounded-lg border-l-4 border-l-yellow-500 bg-yellow-50 p-3 dark:bg-yellow-900/20">
                            <p class="text-xs font-medium text-yellow-700 dark:text-yellow-300">PEDIDOS PENDIENTES</p>
                            <p class="text-lg font-bold text-yellow-900 dark:text-yellow-100 mt-1">{{ $customer->pending_orders ?? 0 }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-zinc-500 dark:text-zinc-400">REGISTRADO</p>
                            <p class="text-sm text-zinc-900 dark:text-zinc-100">{{ optional($customer->created_at)->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sección de Pedidos -->
        @php
            $ordersCollection = $customer->orders ?? collect([]);
        @endphp
        @if($ordersCollection->count() > 0)
        <div class="border-t border-zinc-200 dark:border-zinc-700">
            <div class="px-6 py-4">
                <h2 class="text-lg font-semibold text-zinc-900 dark:text-white">Historial de pedidos</h2>
                <p class="text-sm text-zinc-600 dark:text-zinc-400">Últimos 20 pedidos del cliente</p>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="border-t border-b border-zinc-200 bg-zinc-50 text-left text-zinc-600 dark:border-zinc-700 dark:bg-zinc-800/50 dark:text-zinc-400">
                        <tr>
                            <th class="px-6 py-4 font-medium">Número</th>
                            <th class="px-6 py-4 font-medium">Estado</th>
                            <th class="px-6 py-4 font-medium">Canal</th>
                            <th class="px-6 py-4 font-medium">Total</th>
                            <th class="px-6 py-4 font-medium">Fecha</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800">
                        @foreach($ordersCollection as $order)
                        <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50">
                            <td class="px-6 py-4 font-medium text-zinc-900 dark:text-white">#{{ $order->order_number }}</td>
                            <td class="px-6 py-4">
                                @php
                                    $statusMap = [
                                        'pending' => ['Pendiente', 'yellow'],
                                        'completed' => ['Completado', 'green'],
                                        'paid' => ['Pagado', 'green'],
                                        'delivered' => ['Entregado', 'green'],
                                        'cancelled' => ['Cancelado', 'red'],
                                        'processing' => ['Procesando', 'blue'],
                                        'shipped' => ['Enviado', 'blue'],
                                    ];
                                    $statusData = $statusMap[$order->status] ?? [ucfirst($order->status), 'gray'];
                                @endphp
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{
                                    $statusData[1] === 'green' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300' :
                                    ($statusData[1] === 'yellow' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300' :
                                    ($statusData[1] === 'red' ? 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300' :
                                    ($statusData[1] === 'blue' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300' :
                                    'bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-300')))
                                }}">
                                    {{ $statusData[0] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-zinc-600 dark:text-zinc-400">
                                @php
                                    $channelMap = [
                                        'web' => 'Tienda Online',
                                        'phone' => 'Teléfono',
                                        'whatsapp' => 'WhatsApp',
                                        'social' => 'Redes Sociales',
                                        'store' => 'Tienda Física',
                                    ];
                                @endphp
                                {{ $channelMap[$order->channel] ?? ucfirst($order->channel ?? '—') }}
                            </td>
                            <td class="px-6 py-4 font-semibold text-zinc-900 dark:text-white">${{ number_format($order->total, 2) }}</td>
                            <td class="px-6 py-4 text-zinc-600 dark:text-zinc-400">{{ optional($order->placed_at)->format('d/m/Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <!-- Sección de Notas -->
        <div class="border-t border-zinc-200 dark:border-zinc-700">
            <div class="px-6 py-4">
                <h2 class="text-lg font-semibold text-zinc-900 dark:text-white">Notas del cliente</h2>
                <p class="text-sm text-zinc-600 dark:text-zinc-400">Observaciones y anotaciones internas</p>
            </div>

            <!-- Formulario para agregar nota -->
            <div class="border-t border-zinc-200 dark:border-zinc-700 px-6 py-4">
                <form method="POST" action="{{ route('dashboard.customers.notes.store', $customer->id) }}" class="space-y-3">
                    @csrf
                    <textarea name="note" placeholder="Agregar una nota..." rows="3" class="w-full rounded-lg border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-900 placeholder-zinc-500 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:placeholder-zinc-400 dark:focus:ring-offset-zinc-900" required></textarea>
                    <div class="flex justify-end">
                        <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">Guardar nota</button>
                    </div>
                </form>
            </div>

            <!-- Historial de notas -->
            @php
                $notesCollection = $customer->notes ?? collect([]);
            @endphp
            @if($notesCollection->count() > 0)
            <div class="border-t border-zinc-200 dark:border-zinc-700 divide-y divide-zinc-200 dark:divide-zinc-800">
                @foreach($notesCollection->sortByDesc('created_at') as $note)
                <div class="px-6 py-4">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-sm font-medium text-zinc-900 dark:text-white">{{ $note->user?->name ?? 'Sistema' }}</p>
                            <p class="text-xs text-zinc-500 dark:text-zinc-400">{{ optional($note->created_at)->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                    <p class="mt-2 text-sm text-zinc-700 dark:text-zinc-300">{{ $note->note }}</p>
                </div>
                @endforeach
            </div>
            @else
            <div class="border-t border-zinc-200 dark:border-zinc-700 px-6 py-8 text-center">
                <p class="text-sm text-zinc-600 dark:text-zinc-400">No hay notas aún</p>
            </div>
            @endif
        </div>
    </div>
</x-layouts.app>
