<x-layouts.app :title="__('Clientes')">
    <div class="p-6 space-y-6">
        <div class="flex items-center justify-between gap-3 flex-wrap">
            <div>
                <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Clientes</h1>
                <p class="text-sm text-zinc-600 dark:text-zinc-400">Usuarios con cuenta y pedidos asociados</p>
            </div>
            <a href="{{ route('dashboard.customers.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-offset-zinc-900">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Agregar Cliente
            </a>
        </div>

        <div class="grid gap-4 md:grid-cols-2">
            <div class="rounded-xl border border-zinc-200 bg-white p-4 dark:border-zinc-700 dark:bg-zinc-900">
                <p class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Total clientes</p>
                <p class="mt-1 text-2xl font-bold text-zinc-900 dark:text-white">{{ number_format($stats['total']) }}</p>
            </div>
            <div class="rounded-xl border border-zinc-200 bg-white p-4 dark:border-zinc-700 dark:bg-zinc-900">
                <p class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Nuevos este mes</p>
                <p class="mt-1 text-2xl font-bold text-zinc-900 dark:text-white">{{ number_format($stats['new_this_month']) }}</p>
            </div>
        </div>

        <div class="rounded-xl border border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-900">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="border-b border-zinc-200 text-left text-zinc-600 dark:border-zinc-700 dark:text-zinc-400">
                        <tr>
                            <th class="px-6 py-4 font-medium">Cliente</th>
                            <th class="px-6 py-4 font-medium">Email</th>
                            <th class="px-6 py-4 font-medium">Teléfono</th>
                            <th class="px-6 py-4 font-medium">Pedidos</th>
                            <th class="px-6 py-4 font-medium">Total gastado</th>
                            <th class="px-6 py-4 font-medium">Registro</th>
                            <th class="px-6 py-4 font-medium">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800">
                        @forelse($customers as $customer)
                            <tr class="transition-colors hover:bg-zinc-100/50 dark:hover:bg-zinc-800/70">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-pink-100 dark:bg-pink-900/30">
                                            <span class="text-sm font-semibold text-pink-600 dark:text-pink-500">{{ strtoupper(\Illuminate\Support\Str::substr($customer->name, 0, 2)) }}</span>
                                        </div>
                                        <p class="font-medium text-zinc-900 dark:text-white">{{ $customer->name }}</p>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-zinc-900 dark:text-zinc-100">{{ $customer->email ?? '—' }}</td>
                                <td class="px-6 py-4 text-zinc-900 dark:text-zinc-100">{{ $customer->phone }}</td>
                                <td class="px-6 py-4 text-zinc-900 dark:text-zinc-100">{{ $customer->orders_count }}</td>
                                <td class="px-6 py-4 font-semibold text-zinc-900 dark:text-white">${{ number_format($customer->total_spent ?? 0, 2) }}</td>
                                <td class="px-6 py-4 text-zinc-900 dark:text-zinc-100">{{ optional($customer->created_at)->format('d/m/Y') }}</td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('dashboard.customers.show', $customer->id) }}" class="transition-colors rounded-lg border border-zinc-200 bg-white px-3 py-1.5 text-xs font-medium text-zinc-900 hover:bg-zinc-100/50 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:hover:bg-zinc-700 dark:focus:ring-offset-zinc-900">Ver perfil</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-6 text-center text-sm text-zinc-500 dark:text-zinc-400">Aún no hay clientes.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="flex items-center justify-between border-t border-zinc-200 px-6 py-4 dark:border-zinc-700">
                <p class="text-sm text-zinc-600 dark:text-zinc-400">Mostrando {{ $customers->count() }} de {{ $customers->total() }} clientes</p>
                {{ $customers->links() }}
            </div>
        </div>
    </div>
</x-layouts.app>
