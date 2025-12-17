<x-layouts.app :title="__('Envíos')">
    <div class="p-6 space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Envíos</h1>
                <p class="text-sm text-zinc-600 dark:text-zinc-400">Configura paqueterías y costos</p>
            </div>
            <a href="{{ route('dashboard.shipping.zones.create') }}" class="rounded-lg bg-pink-600 px-4 py-2 text-sm font-semibold text-white hover:bg-pink-700 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-offset-2 dark:bg-pink-500 dark:hover:bg-pink-600 dark:focus:ring-offset-zinc-900">Nueva zona</a>
        </div>

        <!-- Shipping Zones -->
        <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
            <h2 class="mb-4 text-lg font-semibold text-zinc-900 dark:text-white">Zonas de envío</h2>
            <div class="space-y-3">
                <div class="flex items-center justify-between rounded-lg border border-zinc-200 bg-zinc-50 px-4 py-3 dark:border-zinc-700 dark:bg-zinc-800">
                    <div>
                        <p class="font-semibold text-zinc-900 dark:text-white">Ciudad de México</p>
                        <p class="text-sm text-zinc-600 dark:text-zinc-400">Costo: $85 • Tiempo: 1-2 días</p>
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('dashboard.shipping.zones.edit', 1) }}" class="rounded-lg border border-zinc-200 bg-white px-3 py-1.5 text-xs font-medium text-zinc-900 hover:bg-zinc-100/50 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:hover:bg-zinc-700 dark:focus:ring-offset-zinc-900 transition-colors">Editar</a>
                    </div>
                </div>
                <div class="flex items-center justify-between rounded-lg border border-zinc-200 bg-zinc-50 px-4 py-3 dark:border-zinc-700 dark:bg-zinc-800">
                    <div>
                        <p class="font-semibold text-zinc-900 dark:text-white">Estado de México</p>
                        <p class="text-sm text-zinc-600 dark:text-zinc-400">Costo: $150 • Tiempo: 2-3 días</p>
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('dashboard.shipping.zones.edit', 2) }}" class="rounded-lg border border-zinc-200 bg-white px-3 py-1.5 text-xs font-medium text-zinc-900 hover:bg-zinc-100/50 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:hover:bg-zinc-700 dark:focus:ring-offset-zinc-900 transition-colors">Editar</a>
                    </div>
                </div>
                <div class="flex items-center justify-between rounded-lg border border-zinc-200 bg-zinc-50 px-4 py-3 dark:border-zinc-700 dark:bg-zinc-800">
                    <div>
                        <p class="font-semibold text-zinc-900 dark:text-white">República Mexicana</p>
                        <p class="text-sm text-zinc-600 dark:text-zinc-400">Costo: $185+ • Tiempo: 3-5 días</p>
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('dashboard.shipping.zones.edit', 3) }}" class="rounded-lg border border-zinc-200 bg-white px-3 py-1.5 text-xs font-medium text-zinc-900 hover:bg-zinc-100/50 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:hover:bg-zinc-700 dark:focus:ring-offset-zinc-900 transition-colors">Editar</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Shipping Carriers -->
        <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
            <h2 class="mb-4 text-lg font-semibold text-zinc-900 dark:text-white">Paqueterías</h2>
            <div class="grid gap-4 md:grid-cols-2">
                <div class="rounded-lg border border-zinc-200 bg-zinc-50 p-4 dark:border-zinc-700 dark:bg-zinc-800">
                    <div class="mb-3 flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-zinc-900 dark:text-white">Estafeta</h3>
                        <span class="rounded-full bg-emerald-100 px-2 py-1 text-xs font-semibold text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400">Activo</span>
                    </div>
                    <p class="mb-3 text-sm text-zinc-600 dark:text-zinc-400">Envíos nacionales con rastreo</p>
                    <button class="rounded-lg border border-zinc-200 bg-white px-3 py-1.5 text-xs font-medium text-zinc-900 hover:bg-zinc-50 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:hover:bg-zinc-700 dark:focus:ring-offset-zinc-900 transition-colors">Configurar</button>
                </div>
                <div class="rounded-lg border border-zinc-200 bg-zinc-50 p-4 dark:border-zinc-700 dark:bg-zinc-800">
                    <div class="mb-3 flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-zinc-900 dark:text-white">DHL</h3>
                        <span class="rounded-full bg-amber-100 px-2 py-1 text-xs font-semibold text-amber-700 dark:bg-amber-900/30 dark:text-amber-400">Inactivo</span>
                    </div>
                    <p class="mb-3 text-sm text-zinc-600 dark:text-zinc-400">Envíos express nacionales</p>
                    <button class="rounded-lg border border-zinc-200 bg-white px-3 py-1.5 text-xs font-medium text-zinc-900 hover:bg-zinc-50 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:hover:bg-zinc-700 dark:focus:ring-offset-zinc-900 transition-colors">Configurar</button>
                </div>
                <div class="rounded-lg border border-zinc-200 bg-zinc-50 p-4 dark:border-zinc-700 dark:bg-zinc-800">
                    <div class="mb-3 flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-zinc-900 dark:text-white">FedEx</h3>
                        <span class="rounded-full bg-emerald-100 px-2 py-1 text-xs font-semibold text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400">Activo</span>
                    </div>
                    <p class="mb-3 text-sm text-zinc-600 dark:text-zinc-400">Envíos nacionales e internacionales</p>
                    <button class="rounded-lg border border-zinc-200 bg-white px-3 py-1.5 text-xs font-medium text-zinc-900 hover:bg-zinc-50 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:hover:bg-zinc-700 dark:focus:ring-offset-zinc-900 transition-colors">Configurar</button>
                </div>
                <div class="rounded-lg border border-zinc-200 bg-zinc-50 p-4 dark:border-zinc-700 dark:bg-zinc-800">
                    <div class="mb-3 flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-zinc-900 dark:text-white">99 Minutos</h3>
                        <span class="rounded-full bg-amber-100 px-2 py-1 text-xs font-semibold text-amber-700 dark:bg-amber-900/30 dark:text-amber-400">Inactivo</span>
                    </div>
                    <p class="mb-3 text-sm text-zinc-600 dark:text-zinc-400">Envíos el mismo día en CDMX</p>
                    <button class="rounded-lg border border-zinc-200 bg-white px-3 py-1.5 text-xs font-medium text-zinc-900 hover:bg-zinc-50 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:hover:bg-zinc-700 dark:focus:ring-offset-zinc-900 transition-colors">Configurar</button>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
