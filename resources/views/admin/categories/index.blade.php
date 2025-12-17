<x-layouts.app :title="__('Categorías')">
    <div class="p-6 space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Categorías</h1>
                <p class="text-sm text-zinc-600 dark:text-zinc-400">Organiza joyería, ropa y dulces</p>
            </div>
            <a href="{{ route('dashboard.categories.create') }}" class="rounded-lg bg-pink-600 px-4 py-2 text-sm font-semibold text-white hover:bg-pink-700 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-offset-2 dark:bg-pink-500 dark:hover:bg-pink-600 dark:focus:ring-offset-zinc-900">
                Nueva categoría
            </a>
        </div>

        <!-- Categories Grid -->
        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
            <div class="rounded-xl border border-zinc-200 bg-white p-6 hover:border-pink-500 dark:border-zinc-700 dark:bg-zinc-900 dark:hover:border-pink-500">
                <div class="mb-4 flex items-center justify-between">
                    <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-pink-100 dark:bg-pink-900/30">
                        <svg class="h-6 w-6 text-pink-600 dark:text-pink-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7" />
                        </svg>
                    </div>
                    <span class="rounded-full bg-emerald-100 px-2 py-1 text-xs font-semibold text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400">Activa</span>
                </div>
                <h3 class="mb-2 text-lg font-semibold text-zinc-900 dark:text-white">Joyería</h3>
                <p class="mb-4 text-sm text-zinc-600 dark:text-zinc-400">24 productos</p>
                <div class="flex gap-2">
                    <a href="{{ route('dashboard.categories.edit', 1) }}" class="flex-1 rounded-lg border border-zinc-200 bg-white px-3 py-1.5 text-xs font-medium text-zinc-900 hover:bg-zinc-100/50 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:hover:bg-zinc-700 dark:focus:ring-offset-zinc-900 transition-colors">Editar</a>
                    <a href="{{ route('dashboard.categories.edit', 1) }}" class="flex-1 rounded-lg border border-zinc-200 bg-white px-3 py-1.5 text-xs font-medium text-zinc-900 hover:bg-zinc-100/50 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:hover:bg-zinc-700 dark:focus:ring-offset-zinc-900 transition-colors">Ver</a>
                </div>
            </div>

            <div class="rounded-xl border border-zinc-200 bg-white p-6 hover:border-pink-500 dark:border-zinc-700 dark:bg-zinc-900 dark:hover:border-pink-500">
                <div class="mb-4 flex items-center justify-between">
                    <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-purple-100 dark:bg-purple-900/30">
                        <svg class="h-6 w-6 text-purple-600 dark:text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                    </div>
                    <span class="rounded-full bg-emerald-100 px-2 py-1 text-xs font-semibold text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400">Activa</span>
                </div>
                <h3 class="mb-2 text-lg font-semibold text-zinc-900 dark:text-white">Ropa</h3>
                <p class="mb-4 text-sm text-zinc-600 dark:text-zinc-400">18 productos</p>
                <div class="flex gap-2">
                    <a href="{{ route('dashboard.categories.edit', 2) }}" class="flex-1 rounded-lg border border-zinc-200 bg-white px-3 py-1.5 text-xs font-medium text-zinc-900 hover:bg-zinc-100/50 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:hover:bg-zinc-700 dark:focus:ring-offset-zinc-900 transition-colors">Editar</a>
                    <a href="{{ route('dashboard.categories.edit', 2) }}" class="flex-1 rounded-lg border border-zinc-200 bg-white px-3 py-1.5 text-xs font-medium text-zinc-900 hover:bg-zinc-100/50 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:hover:bg-zinc-700 dark:focus:ring-offset-zinc-900 transition-colors">Ver</a>
                </div>
            </div>

            <div class="rounded-xl border border-zinc-200 bg-white p-6 hover:border-pink-500 dark:border-zinc-700 dark:bg-zinc-900 dark:hover:border-pink-500">
                <div class="mb-4 flex items-center justify-between">
                    <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-amber-100 dark:bg-amber-900/30">
                        <svg class="h-6 w-6 text-amber-600 dark:text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <span class="rounded-full bg-emerald-100 px-2 py-1 text-xs font-semibold text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400">Activa</span>
                </div>
                <h3 class="mb-2 text-lg font-semibold text-zinc-900 dark:text-white">Dulces</h3>
                <p class="mb-4 text-sm text-zinc-600 dark:text-zinc-400">56 productos</p>
                <div class="flex gap-2">
                    <a href="{{ route('dashboard.categories.edit', 3) }}" class="flex-1 rounded-lg border border-zinc-200 bg-white px-3 py-1.5 text-xs font-medium text-zinc-900 hover:bg-zinc-100/50 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:hover:bg-zinc-700 dark:focus:ring-offset-zinc-900 transition-colors">Editar</a>
                    <a href="{{ route('dashboard.categories.edit', 3) }}" class="flex-1 rounded-lg border border-zinc-200 bg-white px-3 py-1.5 text-xs font-medium text-zinc-900 hover:bg-zinc-100/50 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:hover:bg-zinc-700 dark:focus:ring-offset-zinc-900 transition-colors">Ver</a>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
