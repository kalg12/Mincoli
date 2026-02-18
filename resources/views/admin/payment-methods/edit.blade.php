<x-layouts.app :title="__('Configurar método de pago')">
    <div class="flex-1">
        <div class="border-b border-zinc-200 bg-white px-6 py-4 dark:border-zinc-700 dark:bg-zinc-900">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Configurar: {{ $method->name }}</h1>
                    <p class="text-sm text-zinc-600 dark:text-zinc-400">Credenciales y opciones</p>
                </div>
                <a href="{{ route('dashboard.payment-methods.index') }}" class="rounded-lg border border-zinc-200 px-4 py-2 text-sm font-semibold text-zinc-900 hover:bg-zinc-100/50 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:border-zinc-700 dark:text-white dark:hover:bg-zinc-800 dark:focus:ring-offset-zinc-900">Volver</a>
            </div>
        </div>

        <form action="{{ route('dashboard.payment-methods.update', $method->id) }}" method="POST" class="bg-white dark:bg-zinc-900">
            @csrf
            @method('PUT')
            <div class="grid gap-px border-b border-zinc-200 bg-zinc-200 dark:border-zinc-700 dark:bg-zinc-700/40 md:grid-cols-2">
                <div class="space-y-4 bg-white p-6 dark:bg-zinc-900">
                    <h2 class="text-lg font-semibold text-zinc-900 dark:text-white">Información General</h2>

                    <div>
                        <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Nombre</label>
                        <input type="text" name="name" value="{{ $method->name }}" class="w-full rounded-lg border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-pink-500 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:focus:ring-offset-zinc-900" required/>
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Descripción</label>
                        <textarea name="description" rows="3" class="w-full rounded-lg border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-pink-500 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:focus:ring-offset-zinc-900">{{ $method->description }}</textarea>
                    </div>

                    <div class="flex flex-col gap-2">
                        <div class="flex items-center gap-2">
                            <input type="checkbox" name="is_active" id="is_active" value="1" {{ $method->is_active ? 'checked' : '' }} class="rounded text-pink-600 focus:ring-pink-500"/>
                            <label for="is_active" class="text-sm font-medium text-zinc-700 dark:text-zinc-300">Activo</label>
                        </div>

                        <div class="flex items-center gap-2">
                            <input type="checkbox" name="supports_card_number" id="supports_card_number" value="1" {{ $method->supports_card_number ? 'checked' : '' }} class="rounded text-pink-600 focus:ring-pink-500" onchange="toggleCardFields()"/>
                            <label for="supports_card_number" class="text-sm font-medium text-zinc-700 dark:text-zinc-300">Acepta tarjetas (mostrar mis tarjetas al cliente)</label>
                        </div>

                        <div class="flex items-center gap-2">
                            <input type="checkbox" name="requires_transfer_details" id="requires_transfer_details" value="1" {{ $method->requires_transfer_details ? 'checked' : '' }} class="rounded text-pink-600 focus:ring-pink-500"/>
                            <label for="requires_transfer_details" class="text-sm font-medium text-zinc-700 dark:text-zinc-300">
                                Pedir núm. de transferencia y línea de captura al registrar el pago
                            </label>
                        </div>
                    </div>

                    <!-- Campos de tarjeta del administrador -->
                    <div
                        id="card-fields"
                        @class([
                            'space-y-4 rounded-lg border-2 border-pink-200 bg-pink-50 p-4 dark:border-pink-800 dark:bg-pink-900/20',
                            'hidden' => ! $method->supports_card_number,
                        ])
                    >
                        <h3 class="font-semibold text-pink-900 dark:text-pink-200 flex items-center gap-2">
                            <i class="fas fa-credit-card"></i>
                            Mis Tarjetas (para recibir pagos)
                        </h3>
                        <p class="text-xs text-pink-800 dark:text-pink-300">Esta información se mostrará al cliente en las instrucciones de pago</p>

                        <div>
                            <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Número de Tarjeta (16 dígitos)</label>
                            <input type="text" name="card_number" value="{{ $method->card_number }}" maxlength="16" placeholder="1234567812345678" class="w-full rounded-lg border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-900 font-mono tracking-wider focus:border-pink-500 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white" oninput="this.value = this.value.replace(/\D/g, '').slice(0, 16)"/>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Tipo de Tarjeta</label>
                                <select name="card_type" class="w-full rounded-lg border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-pink-500 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white">
                                    <option value="">-- Seleccionar --</option>
                                    <option value="credit" {{ $method->card_type == 'credit' ? 'selected' : '' }}>💳 Crédito</option>
                                    <option value="debit" {{ $method->card_type == 'debit' ? 'selected' : '' }}>💰 Débito</option>
                                </select>
                            </div>
                            <div>
                                <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Banco</label>
                                <input type="text" name="bank_name" value="{{ $method->bank_name }}" placeholder="Ej: BBVA, Santander" class="w-full rounded-lg border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-pink-500 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white"/>
                            </div>
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Titular de la Tarjeta</label>
                            <input type="text" name="card_holder_name" value="{{ $method->card_holder_name }}" placeholder="Nombre como aparece en la tarjeta" class="w-full rounded-lg border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-pink-500 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white"/>
                        </div>
                    </div>
                </div>

                <div class="space-y-4 bg-white p-6 dark:bg-zinc-900">
                    <h2 class="text-lg font-semibold text-zinc-900 dark:text-white">Configuración Específica</h2>

                    @if($method->code == 'mercadopago')
                        <div class="space-y-4">
                            <div class="rounded-md bg-blue-50 p-4 dark:bg-blue-900/30">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-blue-800 dark:text-blue-200">Configuración Híbrida</h3>
                                        <div class="mt-2 text-sm text-blue-700 dark:text-blue-300">
                                            <p>Puedes configurar las credenciales aquí o en el archivo <code>.env</code> (recomendado).</p>
                                            <p class="mt-1">Si dejas estos campos vacíos, el sistema intentará usar las variables de entorno:</p>
                                            <ul class="list-disc pl-5 mt-1 space-y-1">
                                                <li><code>MERCADOPAGO_PUBLIC_KEY</code></li>
                                                <li><code>MERCADOPAGO_ACCESS_TOKEN</code></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Public Key</label>
                                <input type="text" name="settings[public_key]" value="{{ $method->settings['public_key'] ?? '' }}" placeholder="{{ config('services.mercadopago.public_key') ? 'Configurado en .env' : 'Ej: APP_USR-...' }}" class="w-full rounded-lg border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-pink-500 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:focus:ring-offset-zinc-900"/>
                            </div>

                            <div>
                                <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Access Token</label>
                                <div class="relative">
                                    <input type="password" name="settings[access_token]" value="{{ $method->settings['access_token'] ?? '' }}" placeholder="{{ config('services.mercadopago.access_token') ? 'Configurado en .env' : 'Ej: APP_USR-...' }}" class="w-full rounded-lg border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-pink-500 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:focus:ring-offset-zinc-900"/>
                                </div>
                            </div>
                        </div>
                    @elseif($method->code == 'transfer')
                        <div>
                             <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Instrucciones para el cliente</label>
                             <textarea name="instructions" rows="6" class="w-full rounded-lg border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-pink-500 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:focus:ring-offset-zinc-900">{{ $method->instructions }}</textarea>
                             <p class="text-xs text-zinc-500 mt-1">Esta información se mostrará al cliente al finalizar el pedido. Incluye cuenta CLABE, Banco, etc.</p>
                        </div>
                    @else
                        <div class="space-y-4">
                            <p class="text-sm text-zinc-600 dark:text-zinc-400">Configuración de este método de pago</p>
                            <div>
                                <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Instrucciones para el cliente (opcional)</label>
                                <textarea name="instructions" rows="4" class="w-full rounded-lg border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-pink-500 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:focus:ring-offset-zinc-900">{{ $method->instructions }}</textarea>
                                <p class="text-xs text-zinc-500 mt-1">Instrucciones adicionales que se mostrarán al cliente</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="sticky bottom-0 flex items-center justify-end gap-2 border-t border-zinc-200 bg-white px-6 py-4 dark:border-zinc-700 dark:bg-zinc-900">
                <a href="{{ route('dashboard.payment-methods.index') }}" class="rounded-lg border border-zinc-200 px-4 py-2 text-sm font-medium text-zinc-900 hover:bg-zinc-100/50 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:border-zinc-700 dark:text-white dark:hover:bg-zinc-800 dark:focus:ring-offset-zinc-900">Cancelar</a>
                <button type="submit" class="rounded-lg bg-pink-600 px-4 py-2 text-sm font-semibold text-white hover:bg-pink-700 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-offset-2 dark:bg-pink-500 dark:hover:bg-pink-600 dark:focus:ring-offset-zinc-900">Guardar Cambios</button>
            </div>
        </form>
    </div>

    <script>
    function toggleCardFields() {
        const checkbox = document.getElementById('supports_card_number');
        const cardFields = document.getElementById('card-fields');
        cardFields.style.display = checkbox.checked ? 'block' : 'none';
    }
    </script>
</x-layouts.app>
