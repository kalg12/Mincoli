<x-layouts.app :title="__('Clientes')">
    <div class="p-6 space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Clientes</h1>
                <p class="text-sm text-zinc-600 dark:text-zinc-400">Usuarios con cuenta y pedidos asociados</p>
            </div>
        </div>

        <!-- Stats -->
        <div class="grid gap-4 md:grid-cols-3">
            <div class="rounded-xl border border-zinc-200 bg-white p-4 dark:border-zinc-700 dark:bg-zinc-900">
                <p class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Total clientes</p>
                <p class="mt-1 text-2xl font-bold text-zinc-900 dark:text-white">4,812</p>
            </div>
            <div class="rounded-xl border border-zinc-200 bg-white p-4 dark:border-zinc-700 dark:bg-zinc-900">
                <p class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Nuevos este mes</p>
                <p class="mt-1 text-2xl font-bold text-zinc-900 dark:text-white">120</p>
            </div>
            <div class="rounded-xl border border-zinc-200 bg-white p-4 dark:border-zinc-700 dark:bg-zinc-900">
                <p class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Clientes activos</p>
                <p class="mt-1 text-2xl font-bold text-zinc-900 dark:text-white">3,245</p>
            </div>
        </div>

        <!-- Filters -->
        <div class="flex gap-3">
            <input type="text" placeholder="Buscar por nombre o email..." class="flex-1 rounded-lg border border-zinc-200 bg-white px-4 py-2 text-sm text-zinc-900 placeholder-zinc-500 focus:border-pink-500 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:placeholder-zinc-400 dark:focus:ring-offset-zinc-900">
            <select class="rounded-lg border border-zinc-200 bg-white px-4 py-2 text-sm text-zinc-900 focus:border-pink-500 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:focus:ring-offset-zinc-900">
                <option>Todos</option>
                <option>Con pedidos</option>
                <option>Sin pedidos</option>
            </select>
        </div>

        <!-- Customers Table -->
        <div class="rounded-xl border border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-900">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="border-b border-zinc-200 text-left text-zinc-600 dark:border-zinc-700 dark:text-zinc-400">
                        <tr>
                            <th class="px-6 py-4 font-medium">Cliente</th>
                            <th class="px-6 py-4 font-medium">Email</th>
                            <th class="px-6 py-4 font-medium">Pedidos</th>
                            <th class="px-6 py-4 font-medium">Total gastado</th>
                            <th class="px-6 py-4 font-medium">Registro</th>
                            <th class="px-6 py-4 font-medium">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800">
                        <tr class="transition-colors hover:bg-zinc-100/50 dark:hover:bg-zinc-800/70">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-pink-100 dark:bg-pink-900/30">
                                        <span class="text-sm font-semibold text-pink-600 dark:text-pink-500">AL</span>
                                    </div>
                                    <p class="font-medium text-zinc-900 dark:text-white">Ana López</p>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-zinc-900 dark:text-zinc-100">ana@example.com</td>
                            <td class="px-6 py-4 text-zinc-900 dark:text-zinc-100">8</td>
                            <td class="px-6 py-4 font-semibold text-zinc-900 dark:text-white">$6,240</td>
                            <td class="px-6 py-4 text-zinc-900 dark:text-zinc-100">10/08/2025</td>
                            <td class="px-6 py-4">
                                <a href="{{ route('dashboard.customers.show', 1) }}" class="transition-colors rounded-lg border border-zinc-200 bg-white px-3 py-1.5 text-xs font-medium text-zinc-900 hover:bg-zinc-100/50 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:hover:bg-zinc-700 dark:focus:ring-offset-zinc-900">Ver perfil</a>
                            </td>
                        </tr>
                        <tr class="transition-colors hover:bg-zinc-100/50 dark:hover:bg-zinc-800/70">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-purple-100 dark:bg-purple-900/30">
                                        <span class="text-sm font-semibold text-purple-600 dark:text-purple-500">MR</span>
                                    </div>
                                    <p class="font-medium text-zinc-900 dark:text-white">María Ruiz</p>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-zinc-900 dark:text-zinc-100">maria@example.com</td>
                            <td class="px-6 py-4 text-zinc-900 dark:text-zinc-100">3</td>
                            <td class="px-6 py-4 font-semibold text-zinc-900 dark:text-white">$2,150</td>
                            <td class="px-6 py-4 text-zinc-900 dark:text-zinc-100">15/11/2025</td>
                            <td class="px-6 py-4">
                                <a href="{{ route('dashboard.customers.show', 2) }}" class="transition-colors rounded-lg border border-zinc-200 bg-white px-3 py-1.5 text-xs font-medium text-zinc-900 hover:bg-zinc-100/50 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:hover:bg-zinc-700 dark:focus:ring-offset-zinc-900">Ver perfil</a>
                            </td>
                        </tr>
                        <tr class="transition-colors hover:bg-zinc-100/50 dark:hover:bg-zinc-800/70">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-sky-100 dark:bg-sky-900/30">
                                        <span class="text-sm font-semibold text-sky-600 dark:text-sky-500">SD</span>
                                    </div>
                                    <p class="font-medium text-zinc-900 dark:text-white">Sandra Díaz</p>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-zinc-900 dark:text-zinc-100">sandra@example.com</td>
                            <td class="px-6 py-4 text-zinc-900 dark:text-zinc-100">12</td>
                            <td class="px-6 py-4 font-semibold text-zinc-900 dark:text-white">$9,820</td>
                            <td class="px-6 py-4 text-zinc-900 dark:text-zinc-100">05/07/2025</td>
                            <td class="px-6 py-4">
                                <a href="{{ route('dashboard.customers.show', 3) }}" class="transition-colors rounded-lg border border-zinc-200 bg-white px-3 py-1.5 text-xs font-medium text-zinc-900 hover:bg-zinc-100/50 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:hover:bg-zinc-700 dark:focus:ring-offset-zinc-900">Ver perfil</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="flex items-center justify-between border-t border-zinc-200 px-6 py-4 dark:border-zinc-700">
                <p class="text-sm text-zinc-600 dark:text-zinc-400">Mostrando 3 de 4,812 clientes</p>
                <div class="flex gap-2">
                    <button class="transition-colors rounded-lg border border-zinc-200 bg-white px-3 py-1.5 text-xs font-medium text-zinc-900 hover:bg-zinc-100/50 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:hover:bg-zinc-700 dark:focus:ring-offset-zinc-900">Anterior</button>
                    <button class="transition-colors rounded-lg border border-zinc-200 bg-white px-3 py-1.5 text-xs font-medium text-zinc-900 hover:bg-zinc-100/50 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:hover:bg-zinc-700 dark:focus:ring-offset-zinc-900">Siguiente</button>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
