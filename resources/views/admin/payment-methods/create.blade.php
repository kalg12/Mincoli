<x-layouts.app :title="__('Crear método de pago')">
    <div class="flex-1">
        <div class="border-b border-zinc-200 bg-white px-6 py-4 dark:border-zinc-700 dark:bg-zinc-900">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Crear Método de Pago</h1>
                    <p class="text-sm text-zinc-600 dark:text-zinc-400">Agrega un nuevo método de pago para tus clientes</p>
                </div>
                <a href="{{ route('dashboard.payment-methods.index') }}" class="rounded-lg border border-zinc-200 px-4 py-2 text-sm font-semibold text-zinc-900 hover:bg-zinc-100/50 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:border-zinc-700 dark:text-white dark:hover:bg-zinc-800 dark:focus:ring-offset-zinc-900">Volver</a>
            </div>
        </div>

        <form action="{{ route('dashboard.payment-methods.store') }}" method="POST" class="bg-white dark:bg-zinc-900">
            @csrf
            <div class="grid gap-px border-b border-zinc-200 bg-zinc-200 dark:border-zinc-700 dark:bg-zinc-700/40 md:grid-cols-2">
                <div class="space-y-4 bg-white p-6 dark:bg-zinc-900">
                    <h2 class="text-lg font-semibold text-zinc-900 dark:text-white">Información General</h2>

                    <div>
                        <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Nombre</label>
                        <input
                            type="text"
                            name="name"
                            value="{{ old('name') }}"
                            placeholder="Ej: Pago con tarjeta"
                            @class([
                                'w-full rounded-lg border bg-white px-3 py-2 text-sm text-zinc-900 focus:border-pink-500 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:bg-zinc-800 dark:text-white dark:focus:ring-offset-zinc-900',
                                'border-zinc-200 dark:border-zinc-700' => ! $errors->has('name'),
                                'border-red-500' => $errors->has('name'),
                            ])
                            required
                        />
                        @error('name')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Código (único)</label>
                        <input
                            type="text"
                            name="code"
                            value="{{ old('code') }}"
                            placeholder="Ej: credit-card, oxxo, transfer"
                            @class([
                                'w-full rounded-lg border bg-white px-3 py-2 text-sm text-zinc-900 focus:border-pink-500 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:bg-zinc-800 dark:text-white dark:focus:ring-offset-zinc-900',
                                'border-zinc-200 dark:border-zinc-700' => ! $errors->has('code'),
                                'border-red-500' => $errors->has('code'),
                            ])
                            required
                        />
                        <p class="text-xs text-zinc-500 mt-1">Usa letras minúsculas, números y guiones. Ej: mi-metodo-pago</p>
                        @error('code')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Descripción</label>
                        <textarea name="description" rows="3" placeholder="Describe este método de pago..." class="w-full rounded-lg border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-pink-500 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:focus:ring-offset-zinc-900">{{ old('description') }}</textarea>
                    </div>

                    <div class="flex flex-col gap-2">
                        <div class="flex items-center gap-2">
                            <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="rounded text-pink-600 focus:ring-pink-500"/>
                            <label for="is_active" class="text-sm font-medium text-zinc-700 dark:text-zinc-300">Activo</label>
                        </div>

                        <div class="flex items-center gap-2">
                            <input type="checkbox" name="supports_card_number" id="supports_card_number" value="1" {{ old('supports_card_number') ? 'checked' : '' }} class="rounded text-pink-600 focus:ring-pink-500" onchange="toggleCardFields()"/>
                            <label for="supports_card_number" class="text-sm font-medium text-zinc-700 dark:text-zinc-300">Acepta tarjetas (mostrar mis tarjetas al cliente)</label>
                        </div>

                        <div class="flex items-center gap-2">
                            <input type="checkbox" name="requires_transfer_details" id="requires_transfer_details" value="1" {{ old('requires_transfer_details') ? 'checked' : '' }} class="rounded text-pink-600 focus:ring-pink-500"/>
                            <label for="requires_transfer_details" class="text-sm font-medium text-zinc-700 dark:text-zinc-300">
                                Pedir núm. de transferencia y línea de captura al registrar el pago
                            </label>
                        </div>
                    </div>

                    <!-- Campos de tarjeta del administrador -->
                    <div id="card-fields" class="space-y-4 rounded-lg border-2 border-pink-200 bg-pink-50 p-4 dark:border-pink-800 dark:bg-pink-900/20" style="display: none;">
                        <h3 class="font-semibold text-pink-900 dark:text-pink-200 flex items-center gap-2">
                            <i class="fas fa-credit-card"></i>
                            Mis Tarjetas (para recibir pagos)
                        </h3>
                        <p class="text-xs text-pink-800 dark:text-pink-300">Esta información se mostrará al cliente en las instrucciones de pago</p>

                        <div>
                            <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Número de Tarjeta (16 dígitos)</label>
                            <input type="text" name="card_number" value="{{ old('card_number') }}" maxlength="16" placeholder="1234567812345678" class="w-full rounded-lg border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-900 font-mono tracking-wider focus:border-pink-500 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white" oninput="this.value = this.value.replace(/\D/g, '').slice(0, 16)"/>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Tipo de Tarjeta</label>
                                <select name="card_type" class="w-full rounded-lg border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-pink-500 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white">
                                    <option value="">-- Seleccionar --</option>
                                    <option value="credit" {{ old('card_type') == 'credit' ? 'selected' : '' }}>💳 Crédito</option>
                                    <option value="debit" {{ old('card_type') == 'debit' ? 'selected' : '' }}>💰 Débito</option>
                                </select>
                            </div>
                            <div>
                                <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Banco</label>
                                <input type="text" name="bank_name" value="{{ old('bank_name') }}" placeholder="Ej: BBVA, Santander" class="w-full rounded-lg border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-pink-500 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white"/>
                            </div>
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Titular de la Tarjeta</label>
                            <input type="text" name="card_holder_name" value="{{ old('card_holder_name') }}" placeholder="Nombre como aparece en la tarjeta" class="w-full rounded-lg border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-pink-500 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white"/>
                        </div>
                    </div>
                </div>

                <div class="space-y-4 bg-white p-6 dark:bg-zinc-900">
                    <h2 class="text-lg font-semibold text-zinc-900 dark:text-white">Configuración Adicional</h2>

                    <div>
                        <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Instrucciones para el cliente (opcional)</label>
                        <textarea name="instructions" rows="6" placeholder="Instrucciones que se mostrarán al cliente al seleccionar este método de pago..." class="w-full rounded-lg border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-pink-500 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:focus:ring-offset-zinc-900">{{ old('instructions') }}</textarea>
                        <p class="text-xs text-zinc-500 mt-1">Esta información se mostrará al cliente en el comprobante y en las instrucciones de pago</p>
                    </div>

                    <div class="rounded-md bg-blue-50 p-4 dark:bg-blue-900/30">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-blue-800 dark:text-blue-200">Información</h3>
                                <div class="mt-2 text-sm text-blue-700 dark:text-blue-300">
                                    <p>Después de crear el método de pago, podrás configurar opciones específicas como credenciales de API, webhooks, etc.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="sticky bottom-0 flex items-center justify-end gap-2 border-t border-zinc-200 bg-white px-6 py-4 dark:border-zinc-700 dark:bg-zinc-900">
                <a href="{{ route('dashboard.payment-methods.index') }}" class="rounded-lg border border-zinc-200 px-4 py-2 text-sm font-medium text-zinc-900 hover:bg-zinc-100/50 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:border-zinc-700 dark:text-white dark:hover:bg-zinc-800 dark:focus:ring-offset-zinc-900">Cancelar</a>
                <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-pink-600 px-4 py-2 text-sm font-semibold text-white hover:bg-pink-700 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-offset-2 dark:bg-pink-500 dark:hover:bg-pink-600 dark:focus:ring-offset-zinc-900">
                    <i class="fas fa-save"></i>
                    Crear Método de Pago
                </button>
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
