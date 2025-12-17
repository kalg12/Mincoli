<x-layouts.app :title="__('Métodos de pago')">
    <div class="p-6 space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Métodos de pago</h1>
                <p class="text-sm text-zinc-600 dark:text-zinc-400">Sincroniza tarjetas, transferencia y OXXO</p>
            </div>
        </div>

        <!-- Payment Methods Grid -->
        <div class="grid gap-4 md:grid-cols-2">
            <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
                <div class="mb-4 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-blue-100 dark:bg-blue-900/30">
                            <svg class="h-6 w-6 text-blue-600 dark:text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-zinc-900 dark:text-white">Tarjeta bancaria</h3>
                            <p class="text-xs text-zinc-500 dark:text-zinc-500">Visa, Mastercard, Amex</p>
                        </div>
                    </div>
                    <label class="relative inline-flex cursor-pointer items-center">
                        <input type="checkbox" checked class="peer sr-only">
                        <div class="peer h-6 w-11 rounded-full bg-zinc-200 after:absolute after:left-[2px] after:top-0.5 after:h-5 after:w-5 after:rounded-full after:border after:border-zinc-300 after:bg-white after:transition-all after:content-[''] peer-checked:bg-pink-600 peer-checked:after:translate-x-full peer-checked:after:border-white peer-focus:ring-2 peer-focus:ring-pink-500 dark:border-zinc-600 dark:bg-zinc-700 dark:peer-focus:ring-offset-zinc-900"></div>
                    </label>
                </div>
                <p class="mb-4 text-sm text-zinc-600 dark:text-zinc-400">Procesa pagos con tarjeta de crédito y débito. Requiere configuración de Stripe o Conekta.</p>
                <a href="{{ route('dashboard.payment-methods.edit', 'card') }}" class="rounded-lg border border-zinc-200 bg-white px-4 py-2 text-sm font-medium text-zinc-900 hover:bg-zinc-100/50 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:hover:bg-zinc-700 dark:focus:ring-offset-zinc-900 transition-colors">Configurar</a>
            </div>

            <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
                <div class="mb-4 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-green-100 dark:bg-green-900/30">
                            <svg class="h-6 w-6 text-green-600 dark:text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-zinc-900 dark:text-white">Transferencia</h3>
                            <p class="text-xs text-zinc-500 dark:text-zinc-500">SPEI / Depósito</p>
                        </div>
                    </div>
                    <label class="relative inline-flex cursor-pointer items-center">
                        <input type="checkbox" checked class="peer sr-only">
                        <div class="peer h-6 w-11 rounded-full bg-zinc-200 after:absolute after:left-[2px] after:top-0.5 after:h-5 after:w-5 after:rounded-full after:border after:border-zinc-300 after:bg-white after:transition-all after:content-[''] peer-checked:bg-pink-600 peer-checked:after:translate-x-full peer-checked:after:border-white peer-focus:ring-2 peer-focus:ring-pink-500 dark:border-zinc-600 dark:bg-zinc-700 dark:peer-focus:ring-offset-zinc-900"></div>
                    </label>
                </div>
                <p class="mb-4 text-sm text-zinc-600 dark:text-zinc-400">Recibe pagos por transferencia bancaria. Proporciona tus datos bancarios a los clientes.</p>
                <a href="{{ route('dashboard.payment-methods.edit', 'transfer') }}" class="rounded-lg border border-zinc-200 bg-white px-4 py-2 text-sm font-medium text-zinc-900 hover:bg-zinc-100/50 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:hover:bg-zinc-700 dark:focus:ring-offset-zinc-900 transition-colors">Configurar</a>
            </div>

            <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
                <div class="mb-4 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-amber-100 dark:bg-amber-900/30">
                            <svg class="h-6 w-6 text-amber-600 dark:text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-zinc-900 dark:text-white">OXXO</h3>
                            <p class="text-xs text-zinc-500 dark:text-zinc-500">Pago en efectivo</p>
                        </div>
                    </div>
                    <label class="relative inline-flex cursor-pointer items-center">
                        <input type="checkbox" class="peer sr-only">
                        <div class="peer h-6 w-11 rounded-full bg-zinc-200 after:absolute after:left-[2px] after:top-0.5 after:h-5 after:w-5 after:rounded-full after:border after:border-zinc-300 after:bg-white after:transition-all after:content-[''] peer-checked:bg-pink-600 peer-checked:after:translate-x-full peer-checked:after:border-white peer-focus:ring-2 peer-focus:ring-pink-500 dark:border-zinc-600 dark:bg-zinc-700 dark:peer-focus:ring-offset-zinc-900"></div>
                    </label>
                </div>
                <p class="mb-4 text-sm text-zinc-600 dark:text-zinc-400">Permite pagos en efectivo en tiendas OXXO. Requiere integración con Conekta.</p>
                <a href="{{ route('dashboard.payment-methods.edit', 'oxxo') }}" class="rounded-lg border border-zinc-200 bg-white px-4 py-2 text-sm font-medium text-zinc-900 hover:bg-zinc-100/50 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:hover:bg-zinc-700 dark:focus:ring-offset-zinc-900 transition-colors">Configurar</a>
            </div>

            <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
                <div class="mb-4 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-indigo-100 dark:bg-indigo-900/30">
                            <svg class="h-6 w-6 text-indigo-600 dark:text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-zinc-900 dark:text-white">PayPal</h3>
                            <p class="text-xs text-zinc-500 dark:text-zinc-500">Pago en línea</p>
                        </div>
                    </div>
                    <label class="relative inline-flex cursor-pointer items-center">
                        <input type="checkbox" checked class="peer sr-only">
                        <div class="peer h-6 w-11 rounded-full bg-zinc-200 after:absolute after:left-[2px] after:top-0.5 after:h-5 after:w-5 after:rounded-full after:border after:border-zinc-300 after:bg-white after:transition-all after:content-[''] peer-checked:bg-pink-600 peer-checked:after:translate-x-full peer-checked:after:border-white peer-focus:ring-2 peer-focus:ring-pink-500 dark:border-zinc-600 dark:bg-zinc-700 dark:peer-focus:ring-offset-zinc-900"></div>
                    </label>
                </div>
                <p class="mb-4 text-sm text-zinc-600 dark:text-zinc-400">Acepta pagos con PayPal. Los clientes pueden pagar sin salir de tu sitio.</p>
                <a href="{{ route('dashboard.payment-methods.edit', 'paypal') }}" class="rounded-lg border border-zinc-200 bg-white px-4 py-2 text-sm font-medium text-zinc-900 hover:bg-zinc-100/50 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:hover:bg-zinc-700 dark:focus:ring-offset-zinc-900 transition-colors">Configurar</a>
            </div>
        </div>
    </div>
</x-layouts.app>
