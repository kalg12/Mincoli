<x-layouts.app :title="__('Editar zona de envío')">
    <div class="flex-1">
        <div class="border-b border-zinc-200 bg-white px-6 py-4 dark:border-zinc-700 dark:bg-zinc-900">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Editar zona #{{ $id ?? '—' }}</h1>
                    <p class="text-sm text-zinc-600 dark:text-zinc-400">Actualiza cobertura, costos y tiempos</p>
                </div>
                <a href="{{ route('dashboard.shipping.index') }}" class="rounded-lg border border-zinc-200 px-4 py-2 text-sm font-semibold text-zinc-900 hover:bg-zinc-100/50 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:border-zinc-700 dark:text-white dark:hover:bg-zinc-800 dark:focus:ring-offset-zinc-900">Volver</a>
            </div>
        </div>

        <form class="bg-white dark:bg-zinc-900">
            <div class="space-y-4 border-b border-zinc-200 p-6 dark:border-zinc-700">
                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Nombre de la zona</label>
                        <input type="text" value="Ciudad de México" class="w-full rounded-lg border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-pink-500 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:focus:ring-offset-zinc-900"/>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Costo base</label>
                        <input type="number" value="85" class="w-full rounded-lg border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-pink-500 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:focus:ring-offset-zinc-900"/>
                    </div>
                </div>
                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Tiempo estimado</label>
                        <input type="text" value="1-2 días" class="w-full rounded-lg border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-pink-500 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:focus:ring-offset-zinc-900"/>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Cobertura</label>
                        <select class="w-full rounded-lg border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-pink-500 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:focus:ring-offset-zinc-900">
                            <option selected>Local</option>
                            <option>Estatal</option>
                            <option>Nacional</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Paqueterías disponibles</label>
                    <div class="grid gap-2 md:grid-cols-3">
                        <label class="flex items-center gap-2 text-sm text-zinc-700 dark:text-zinc-300"><input type="checkbox" checked class="rounded border-zinc-300 text-pink-600 focus:ring-pink-500 dark:border-zinc-600"/> Estafeta</label>
                        <label class="flex items-center gap-2 text-sm text-zinc-700 dark:text-zinc-300"><input type="checkbox" class="rounded border-zinc-300 text-pink-600 focus:ring-pink-500 dark:border-zinc-600"/> DHL</label>
                        <label class="flex items-center gap-2 text-sm text-zinc-700 dark:text-zinc-300"><input type="checkbox" class="rounded border-zinc-300 text-pink-600 focus:ring-pink-500 dark:border-zinc-600"/> FedEx</label>
                    </div>
                </div>
            </div>

            <div class="sticky bottom-0 flex items-center justify-end gap-2 border-t border-zinc-200 bg-white px-6 py-4 dark:border-zinc-700 dark:bg-zinc-900">
                <a href="{{ route('dashboard.shipping.index') }}" class="rounded-lg border border-zinc-200 px-4 py-2 text-sm font-medium text-zinc-900 hover:bg-zinc-100/50 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:border-zinc-700 dark:text-white dark:hover:bg-zinc-800 dark:focus:ring-offset-zinc-900">Cancelar</a>
                <button class="rounded-lg bg-pink-600 px-4 py-2 text-sm font-semibold text-white hover:bg-pink-700 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-offset-2 dark:bg-pink-500 dark:hover:bg-pink-600 dark:focus:ring-offset-zinc-900">Guardar cambios</button>
            </div>
        </form>
    </div>
</x-layouts.app>
