<x-layouts.app :title="__('Configuración de Tienda')">
    <div class="flex-1">
        <!-- Header -->
        <div class="border-b border-zinc-200 bg-white px-6 py-4 dark:border-zinc-700 dark:bg-zinc-900">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Configuración de Tienda</h1>
                    <p class="text-sm text-zinc-600 dark:text-zinc-400">Administra las configuraciones generales de tu tienda</p>
                </div>
            </div>
        </div>

        @if(session('success'))
        <div class="mx-6 mt-6 border-l-4 border-green-500 bg-green-50 px-4 py-3 dark:bg-green-900/20 dark:border-green-600">
            <div class="flex items-center">
                <svg class="h-5 w-5 text-green-500 dark:text-green-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <p class="text-sm font-medium text-green-700 dark:text-green-300">{{ session('success') }}</p>
            </div>
        </div>
        @endif

        <!-- Settings Form -->
        <form action="{{ route('dashboard.settings.update') }}" method="POST">
            @csrf
            @method('PUT')

            <!-- IVA Configuration -->
            <div class="mx-6 mt-6">
                <div class="rounded-lg border border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-900">
                    <div class="border-b border-zinc-200 px-6 py-4 dark:border-zinc-700">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <h3 class="text-base font-semibold text-zinc-900 dark:text-white">Mostrar IVA en el Carrito</h3>
                                <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-400">
                                    Activa esta opción si deseas mostrar el desglose del IVA (16%) en el carrito de compras.
                                </p>
                            </div>
                            <div class="ml-6">
                                <label class="relative inline-flex cursor-pointer items-center">
                                    <input type="hidden" name="show_iva" value="0">
                                    <input type="checkbox" name="show_iva" value="1"
                                           {{ $settings['show_iva'] ? 'checked' : '' }}
                                           class="peer sr-only"
                                           id="show_iva_toggle">
                                    <div class="peer h-6 w-11 rounded-full bg-zinc-200 after:absolute after:left-[2px] after:top-[2px] after:h-5 after:w-5 after:rounded-full after:border after:border-zinc-300 after:bg-white after:transition-all after:content-[''] peer-checked:bg-pink-600 peer-checked:after:translate-x-full peer-checked:after:border-white peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-pink-500 peer-focus:ring-offset-2 dark:border-zinc-600 dark:bg-zinc-700 dark:peer-focus:ring-offset-zinc-900"></div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="px-6 py-4">
                        <div class="rounded-lg border border-zinc-200 bg-zinc-50 p-4 dark:border-zinc-700 dark:bg-zinc-800">
                            <div class="mb-3 flex items-start">
                                <svg class="mr-3 mt-0.5 h-5 w-5 flex-shrink-0 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                                <div class="text-sm">
                                    <p class="font-semibold text-zinc-900 dark:text-white">Importante:</p>
                                    <ul class="mt-2 list-inside list-disc space-y-1 text-zinc-600 dark:text-zinc-400">
                                        <li>Cuando el IVA está <strong>activado</strong>: Se muestra "Subtotal" + "IVA (16%)" + "Total"</li>
                                        <li>Cuando el IVA está <strong>desactivado</strong>: Solo se muestra el "Total" (precio incluye todo)</li>
                                    </ul>
                                </div>
                            </div>

                            <div class="mt-4 border-t border-zinc-200 pt-4 dark:border-zinc-700">
                                <p class="mb-3 text-xs font-semibold uppercase text-zinc-500 dark:text-zinc-400">Vista Previa del Carrito:</p>

                                <div id="preview-with-iva" class="{{ $settings['show_iva'] ? '' : 'hidden' }}">
                                    <div class="space-y-2 text-sm text-zinc-900 dark:text-white">
                                        <div class="flex justify-between">
                                            <span class="text-zinc-600 dark:text-zinc-400">Subtotal:</span>
                                            <span class="font-medium">$100.00</span>
                                        </div>
                                        <div class="flex justify-between text-purple-600 dark:text-purple-400">
                                            <span>IVA (16%):</span>
                                            <span class="font-medium">$16.00</span>
                                        </div>
                                        <div class="flex justify-between border-t border-zinc-200 pt-2 text-base font-bold dark:border-zinc-700">
                                            <span>Total:</span>
                                            <span class="text-pink-600 dark:text-pink-500">$116.00</span>
                                        </div>
                                    </div>
                                </div>

                                <div id="preview-without-iva" class="{{ $settings['show_iva'] ? 'hidden' : '' }}">
                                    <div class="space-y-2 text-sm">
                                        <div class="flex justify-between text-base font-bold text-zinc-900 dark:text-white">
                                            <span>Total:</span>
                                            <span class="text-pink-600 dark:text-pink-500">$116.00</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="border-t border-zinc-200 bg-zinc-50 px-6 py-4 dark:border-zinc-700 dark:bg-zinc-800">
                        <div class="flex items-center justify-end gap-3">
                            <a href="{{ route('dashboard') }}" class="rounded-lg border border-zinc-300 bg-white px-4 py-2 text-sm font-medium text-zinc-700 hover:bg-zinc-50 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-offset-2 dark:border-zinc-600 dark:bg-zinc-700 dark:text-zinc-200 dark:hover:bg-zinc-600 dark:focus:ring-offset-zinc-900">
                                Cancelar
                            </a>
                            <button type="submit" class="rounded-lg bg-pink-600 px-4 py-2 text-sm font-semibold text-white hover:bg-pink-700 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-offset-2 dark:bg-pink-500 dark:hover:bg-pink-600 dark:focus:ring-offset-zinc-900">
                                Guardar Configuración
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        document.getElementById('show_iva_toggle').addEventListener('change', function() {
            const withIva = document.getElementById('preview-with-iva');
            const withoutIva = document.getElementById('preview-without-iva');

            if (this.checked) {
                withIva.classList.remove('hidden');
                withoutIva.classList.add('hidden');
            } else {
                withIva.classList.add('hidden');
                withoutIva.classList.remove('hidden');
            }
        });
    </script>
    @endpush
</x-layouts.app>
