<x-layouts.app :title="__('Pedidos')">
    <div class="p-6 space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Pedidos</h1>
                <p class="text-sm text-zinc-600 dark:text-zinc-400">Gestiona pagos y envíos</p>
            </div>
        </div>

        <!-- Filters -->
        <div class="flex gap-3">
            <input type="text" placeholder="Buscar por número o cliente..." class="flex-1 rounded-lg border border-zinc-200 bg-white px-4 py-2 text-sm text-zinc-900 placeholder-zinc-500 focus:border-pink-500 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:placeholder-zinc-400 dark:focus:ring-offset-zinc-900">
            <select class="rounded-lg border border-zinc-200 bg-white px-4 py-2 text-sm text-zinc-900 focus:border-pink-500 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:focus:ring-offset-zinc-900">
                <option>Todos los estados</option>
                <option>Pagado</option>
                <option>Pendiente</option>
                <option>Enviado</option>
                <option>Entregado</option>
                <option>Cancelado</option>
            </select>
        </div>

        <!-- Orders Table -->
        <div class="rounded-xl border border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-900">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="border-b border-zinc-200 text-left text-zinc-600 dark:border-zinc-700 dark:text-zinc-400">
                        <tr>
                            <th class="px-6 py-4 font-medium">#</th>
                            <th class="px-6 py-4 font-medium">Cliente</th>
                            <th class="px-6 py-4 font-medium">Productos</th>
                            <th class="px-6 py-4 font-medium">Total</th>
                            <th class="px-6 py-4 font-medium">Estado</th>
                            <th class="px-6 py-4 font-medium">Fecha</th>
                            <th class="px-6 py-4 font-medium">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800">
                        <tr class="transition-colors hover:bg-zinc-100/50 dark:hover:bg-zinc-800/70">
                            <td class="px-6 py-4 font-semibold text-zinc-900 dark:text-white">#10234</td>
                            <td class="px-6 py-4">
                                <div>
                                    <p class="font-medium text-zinc-900 dark:text-white">Ana López</p>
                                    <p class="text-xs text-zinc-500 dark:text-zinc-500">ana@example.com</p>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-zinc-900 dark:text-zinc-100">3 productos</td>
                            <td class="px-6 py-4 font-semibold text-zinc-900 dark:text-white">$1,250</td>
                            <td class="px-6 py-4">
                                <span class="rounded-full bg-emerald-100 px-2 py-1 text-xs font-semibold text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400">Pagado</span>
                            </td>
                            <td class="px-6 py-4 text-zinc-900 dark:text-zinc-100">16/12/2025</td>
                            <td class="px-6 py-4">
                                <a href="{{ route('dashboard.orders.show', 10234) }}" class="transition-colors rounded-lg border border-zinc-200 bg-white px-3 py-1.5 text-xs font-medium text-zinc-900 hover:bg-zinc-100/50 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:hover:bg-zinc-700 dark:focus:ring-offset-zinc-900">Ver detalle</a>
                            </td>
                        </tr>
                        <tr class="transition-colors hover:bg-zinc-100/50 dark:hover:bg-zinc-800/70">
                            <td class="px-6 py-4 font-semibold text-zinc-900 dark:text-white">#10233</td>
                            <td class="px-6 py-4">
                                <div>
                                    <p class="font-medium text-zinc-900 dark:text-white">María Ruiz</p>
                                    <p class="text-xs text-zinc-500 dark:text-zinc-500">maria@example.com</p>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-zinc-900 dark:text-zinc-100">2 productos</td>
                            <td class="px-6 py-4 font-semibold text-zinc-900 dark:text-white">$980</td>
                            <td class="px-6 py-4">
                                <span class="rounded-full bg-amber-100 px-2 py-1 text-xs font-semibold text-amber-700 dark:bg-amber-900/30 dark:text-amber-400">Pendiente</span>
                            </td>
                            <td class="px-6 py-4 text-zinc-900 dark:text-zinc-100">16/12/2025</td>
                            <td class="px-6 py-4">
                                <a href="{{ route('dashboard.orders.show', 10233) }}" class="transition-colors rounded-lg border border-zinc-200 bg-white px-3 py-1.5 text-xs font-medium text-zinc-900 hover:bg-zinc-100/50 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:hover:bg-zinc-700 dark:focus:ring-offset-zinc-900">Ver detalle</a>
                            </td>
                        </tr>
                        <tr class="transition-colors hover:bg-zinc-100/50 dark:hover:bg-zinc-800/70">
                            <td class="px-6 py-4 font-semibold text-zinc-900 dark:text-white">#10232</td>
                            <td class="px-6 py-4">
                                <div>
                                    <p class="font-medium text-zinc-900 dark:text-white">Sandra Díaz</p>
                                    <p class="text-xs text-zinc-500 dark:text-zinc-500">sandra@example.com</p>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-zinc-900 dark:text-zinc-100">5 productos</td>
                            <td class="px-6 py-4 font-semibold text-zinc-900 dark:text-white">$2,150</td>
                            <td class="px-6 py-4">
                                <span class="rounded-full bg-sky-100 px-2 py-1 text-xs font-semibold text-sky-700 dark:bg-sky-900/30 dark:text-sky-400">Enviado</span>
                            </td>
                            <td class="px-6 py-4 text-zinc-900 dark:text-zinc-100">15/12/2025</td>
                            <td class="px-6 py-4">
                                <a href="{{ route('dashboard.orders.show', 10232) }}" class="transition-colors rounded-lg border border-zinc-200 bg-white px-3 py-1.5 text-xs font-medium text-zinc-900 hover:bg-zinc-100/50 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:hover:bg-zinc-700 dark:focus:ring-offset-zinc-900">Ver detalle</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="flex items-center justify-between border-t border-zinc-200 px-6 py-4 dark:border-zinc-700">
                <p class="text-sm text-zinc-600 dark:text-zinc-400">Mostrando 3 de 28 pedidos</p>
                <div class="flex gap-2">
                    <button class="transition-colors rounded-lg border border-zinc-200 bg-white px-3 py-1.5 text-xs font-medium text-zinc-900 hover:bg-zinc-100/50 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:hover:bg-zinc-700 dark:focus:ring-offset-zinc-900">Anterior</button>
                    <button class="transition-colors rounded-lg border border-zinc-200 bg-white px-3 py-1.5 text-xs font-medium text-zinc-900 hover:bg-zinc-100/50 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:hover:bg-zinc-700 dark:focus:ring-offset-zinc-900">Siguiente</button>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
