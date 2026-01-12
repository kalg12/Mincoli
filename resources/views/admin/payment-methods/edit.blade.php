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

                    <div class="flex items-center gap-2">
                         <input type="checkbox" name="is_active" id="is_active" value="1" {{ $method->is_active ? 'checked' : '' }} class="rounded text-pink-600 focus:ring-pink-500"/>
                         <label for="is_active" class="text-sm font-medium text-zinc-700 dark:text-zinc-300">Activo</label>
                    </div>
                </div>

                <div class="space-y-4 bg-white p-6 dark:bg-zinc-900">
                    <h2 class="text-lg font-semibold text-zinc-900 dark:text-white">Configuración Específica</h2>
                    
                    @if($method->code == 'mercadopago')
                        <div class="space-y-4">
                            <div>
                                <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Access Token</label>
                                <input type="password" name="settings[access_token]" value="{{ $method->settings['access_token'] ?? '' }}" class="w-full rounded-lg border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-pink-500 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:focus:ring-offset-zinc-900"/>
                            </div>
                            <div>
                                <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Public Key</label>
                                <input type="text" name="settings[public_key]" value="{{ $method->settings['public_key'] ?? '' }}" class="w-full rounded-lg border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-pink-500 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:focus:ring-offset-zinc-900"/>
                            </div>
                            <div>
                                <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Entorno</label>
                                <select name="settings[environment]" class="w-full rounded-lg border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-pink-500 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:focus:ring-offset-zinc-900">
                                    <option value="sandbox" {{ ($method->settings['environment'] ?? '') == 'sandbox' ? 'selected' : '' }}>Sandbox (Pruebas)</option>
                                    <option value="production" {{ ($method->settings['environment'] ?? '') == 'production' ? 'selected' : '' }}>Producción</option>
                                </select>
                            </div>
                        </div>
                    @elseif($method->code == 'transfer')
                        <div>
                             <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Instrucciones para el cliente</label>
                             <textarea name="instructions" rows="6" class="w-full rounded-lg border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-pink-500 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:focus:ring-offset-zinc-900">{{ $method->instructions }}</textarea>
                             <p class="text-xs text-zinc-500 mt-1">Esta información se mostrará al cliente al finalizar el pedido. Incluye cuenta CLABE, Banco, etc.</p>
                        </div>
                    @else
                        <p class="text-sm text-zinc-500">No hay configuraciones adicionales disponibles para este método.</p>
                    @endif
                </div>
            </div>

            <div class="sticky bottom-0 flex items-center justify-end gap-2 border-t border-zinc-200 bg-white px-6 py-4 dark:border-zinc-700 dark:bg-zinc-900">
                <a href="{{ route('dashboard.payment-methods.index') }}" class="rounded-lg border border-zinc-200 px-4 py-2 text-sm font-medium text-zinc-900 hover:bg-zinc-100/50 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:border-zinc-700 dark:text-white dark:hover:bg-zinc-800 dark:focus:ring-offset-zinc-900">Cancelar</a>
                <button type="submit" class="rounded-lg bg-pink-600 px-4 py-2 text-sm font-semibold text-white hover:bg-pink-700 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-offset-2 dark:bg-pink-500 dark:hover:bg-pink-600 dark:focus:ring-offset-zinc-900">Guardar Cambios</button>
            </div>
        </form>
    </div>
</x-layouts.app>
