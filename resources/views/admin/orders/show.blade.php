<x-layouts.app :title="__('Detalle de orden')">
    <div class="flex-1">
        <div class="border-b border-zinc-200 bg-white px-6 py-4 dark:border-zinc-700 dark:bg-zinc-900">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Orden #{{ $id ?? '—' }}</h1>
                    <p class="text-sm text-zinc-600 dark:text-zinc-400">Consulta y actualiza el estado</p>
                </div>
                <a href="{{ route('dashboard.orders.index') }}" class="rounded-lg border border-zinc-200 px-4 py-2 text-sm font-semibold text-zinc-900 hover:bg-zinc-100/50 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:border-zinc-700 dark:text-white dark:hover:bg-zinc-800 dark:focus:ring-offset-zinc-900">Volver</a>
            </div>
        </div>

        <div class="grid gap-6 p-6 md:grid-cols-3">
            <div class="space-y-6 md:col-span-2">
                <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
                    <h2 class="mb-4 text-lg font-semibold text-zinc-900 dark:text-white">Productos</h2>
                    <div class="divide-y divide-zinc-200 dark:divide-zinc-800">
                        <div class="flex items-center justify-between py-3">
                            <div>
                                <p class="font-medium text-zinc-900 dark:text-white">Collar Aura</p>
                                <p class="text-sm text-zinc-600 dark:text-zinc-400">Cantidad: 1</p>
                            </div>
                            <p class="font-semibold text-zinc-900 dark:text-white">$820</p>
                        </div>
                        <div class="flex items-center justify-between py-3">
                            <div>
                                <p class="font-medium text-zinc-900 dark:text-white">Blusa Jade</p>
                                <p class="text-sm text-zinc-600 dark:text-zinc-400">Cantidad: 2</p>
                            </div>
                            <p class="font-semibold text-zinc-900 dark:text-white">$1,280</p>
                        </div>
                    </div>
                    <div class="mt-4 flex justify-end gap-8 text-sm">
                        <p class="text-zinc-600 dark:text-zinc-400">Subtotal: <span class="font-semibold text-zinc-900 dark:text-white">$2,100</span></p>
                        <p class="text-zinc-600 dark:text-zinc-400">Envío: <span class="font-semibold text-zinc-900 dark:text-white">$85</span></p>
                        <p class="text-zinc-600 dark:text-zinc-400">Total: <span class="font-semibold text-zinc-900 dark:text-white">$2,185</span></p>
                    </div>
                </div>

                <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
                    <h2 class="mb-4 text-lg font-semibold text-zinc-900 dark:text-white">Dirección de envío</h2>
                    <p class="text-sm text-zinc-600 dark:text-zinc-400">Ana López, Av. Reforma 123, CDMX</p>
                </div>
            </div>

            <div class="space-y-6">
                <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
                    <h2 class="mb-4 text-lg font-semibold text-zinc-900 dark:text-white">Estado de la orden</h2>
                    <div class="space-y-3">
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Actualizar estado</label>
                        <select class="w-full rounded-lg border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-pink-500 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:focus:ring-offset-zinc-900">
                            <option>Pendiente</option>
                            <option selected>Pagado</option>
                            <option>Enviado</option>
                            <option>Entregado</option>
                            <option>Cancelado</option>
                        </select>
                        <button class="w-full rounded-lg bg-pink-600 px-4 py-2 text-sm font-semibold text-white hover:bg-pink-700 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-offset-2 dark:bg-pink-500 dark:hover:bg-pink-600 dark:focus:ring-offset-zinc-900">Guardar estado</button>
                    </div>
                </div>

                <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
                    <h2 class="mb-4 text-lg font-semibold text-zinc-900 dark:text-white">Cliente</h2>
                    <p class="text-sm text-zinc-600 dark:text-zinc-400">Ana López • ana@example.com</p>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
