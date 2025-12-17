<x-layouts.app :title="__('Perfil de cliente')">
    <div class="flex-1">
        <div class="border-b border-zinc-200 bg-white px-6 py-4 dark:border-zinc-700 dark:bg-zinc-900">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Cliente #{{ $id ?? '—' }}</h1>
                    <p class="text-sm text-zinc-600 dark:text-zinc-400">Edita datos y consulta actividad</p>
                </div>
                <a href="{{ route('dashboard.customers.index') }}" class="rounded-lg border border-zinc-200 px-4 py-2 text-sm font-semibold text-zinc-900 hover:bg-zinc-100/50 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:border-zinc-700 dark:text-white dark:hover:bg-zinc-800 dark:focus:ring-offset-zinc-900">Volver</a>
            </div>
        </div>

        <div class="grid gap-6 p-6 md:grid-cols-3">
            <form class="space-y-4 rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900 md:col-span-2">
                <h2 class="text-lg font-semibold text-zinc-900 dark:text-white">Información del cliente</h2>
                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Nombre</label>
                        <input type="text" value="Ana López" class="w-full rounded-lg border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-pink-500 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:focus:ring-offset-zinc-900" />
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Email</label>
                        <input type="email" value="ana@example.com" class="w-full rounded-lg border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-pink-500 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:focus:ring-offset-zinc-900" />
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Teléfono</label>
                        <input type="tel" value="55 1234 5678" class="w-full rounded-lg border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-pink-500 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:focus:ring-offset-zinc-900" />
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Estado</label>
                        <select class="w-full rounded-lg border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-pink-500 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:focus:ring-offset-zinc-900">
                            <option selected>Activo</option>
                            <option>Inactivo</option>
                            <option>Bloqueado</option>
                        </select>
                    </div>
                </div>
                <div class="flex justify-end gap-2">
                    <a href="{{ route('dashboard.customers.index') }}" class="rounded-lg border border-zinc-200 px-4 py-2 text-sm font-medium text-zinc-900 hover:bg-zinc-100/50 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:border-zinc-700 dark:text-white dark:hover:bg-zinc-800 dark:focus:ring-offset-zinc-900">Cancelar</a>
                    <button class="rounded-lg bg-pink-600 px-4 py-2 text-sm font-semibold text-white hover:bg-pink-700 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-offset-2 dark:bg-pink-500 dark:hover:bg-pink-600 dark:focus:ring-offset-zinc-900">Guardar</button>
                </div>
            </form>

            <div class="space-y-4">
                <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
                    <h3 class="mb-2 text-lg font-semibold text-zinc-900 dark:text-white">Resumen</h3>
                    <p class="text-sm text-zinc-600 dark:text-zinc-400">Pedidos: 12</p>
                    <p class="text-sm text-zinc-600 dark:text-zinc-400">Gastado: $8,450</p>
                </div>
                <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
                    <h3 class="mb-2 text-lg font-semibold text-zinc-900 dark:text-white">Dirección principal</h3>
                    <p class="text-sm text-zinc-600 dark:text-zinc-400">Av. Reforma 123, CDMX</p>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
