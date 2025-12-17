<x-layouts.app :title="__('Banners')">
    <div class="p-6 space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Banners</h1>
                <p class="text-sm text-zinc-600 dark:text-zinc-400">Actualiza el carrusel de inicio</p>
            </div>
            <a href="{{ route('dashboard.banners.create') }}" class="rounded-lg bg-pink-600 px-4 py-2 text-sm font-semibold text-white hover:bg-pink-700 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-offset-2 dark:bg-pink-500 dark:hover:bg-pink-600 dark:focus:ring-offset-zinc-900">
                Nuevo banner
            </a>
        </div>

        <!-- Banners List -->
        <div class="space-y-4">
            <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
                <div class="flex items-center gap-4">
                    <div class="h-32 w-48 flex-shrink-0 overflow-hidden rounded-lg bg-zinc-100 dark:bg-zinc-800">
                        <div class="flex h-full items-center justify-center text-zinc-400 dark:text-zinc-500">
                            <svg class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1">
                        <div class="mb-2 flex items-center gap-2">
                            <h3 class="text-lg font-semibold text-zinc-900 dark:text-white">Rebajas Joyas</h3>
                            <span class="rounded-full bg-emerald-100 px-2 py-1 text-xs font-semibold text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400">Activo</span>
                        </div>
                        <p class="mb-2 text-sm text-zinc-600 dark:text-zinc-400">Banner principal con descuentos en joyería</p>
                        <div class="flex items-center gap-4 text-xs text-zinc-500 dark:text-zinc-500">
                            <span>Posición: 1</span>
                            <span>Creado: 10/12/2025</span>
                            <span>Clicks: 234</span>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <button class="transition-colors rounded-lg border border-zinc-200 bg-white px-3 py-1.5 text-xs font-medium text-zinc-900 hover:bg-zinc-50 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:hover:bg-zinc-700 dark:focus:ring-offset-zinc-900">Editar</button>
                        <button class="transition-colors rounded-lg border border-zinc-200 bg-white px-3 py-1.5 text-xs font-medium text-zinc-900 hover:bg-zinc-50 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:hover:bg-zinc-700 dark:focus:ring-offset-zinc-900">Desactivar</button>
                    </div>
                </div>
            </div>

            <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
                <div class="flex items-center gap-4">
                    <div class="h-32 w-48 flex-shrink-0 overflow-hidden rounded-lg bg-zinc-100 dark:bg-zinc-800">
                        <div class="flex h-full items-center justify-center text-zinc-400 dark:text-zinc-500">
                            <svg class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1">
                        <div class="mb-2 flex items-center gap-2">
                            <h3 class="text-lg font-semibold text-zinc-900 dark:text-white">Lanzamiento Bolsas</h3>
                            <span class="rounded-full bg-amber-100 px-2 py-1 text-xs font-semibold text-amber-700 dark:bg-amber-900/30 dark:text-amber-400">Programado</span>
                        </div>
                        <p class="mb-2 text-sm text-zinc-600 dark:text-zinc-400">Nueva colección de bolsas y accesorios</p>
                        <div class="flex items-center gap-4 text-xs text-zinc-500 dark:text-zinc-500">
                            <span>Posición: 2</span>
                            <span>Publicar: 20/12/2025</span>
                            <span>Clicks: 0</span>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <button class="transition-colors rounded-lg border border-zinc-200 bg-white px-3 py-1.5 text-xs font-medium text-zinc-900 hover:bg-zinc-50 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:hover:bg-zinc-700 dark:focus:ring-offset-zinc-900">Editar</button>
                        <button class="transition-colors rounded-lg border border-zinc-200 bg-white px-3 py-1.5 text-xs font-medium text-zinc-900 hover:bg-zinc-50 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:hover:bg-zinc-700 dark:focus:ring-offset-zinc-900">Activar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
