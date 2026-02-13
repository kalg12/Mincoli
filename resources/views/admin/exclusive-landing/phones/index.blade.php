<x-layouts.app title="Números autorizados - Landing Exclusiva">
    <div class="flex-1">
        <div class="border-b border-zinc-200 bg-white px-6 py-4 dark:border-zinc-700 dark:bg-zinc-900">
            <div class="flex items-center justify-between flex-wrap gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Números autorizados</h1>
                    <p class="text-sm text-zinc-600 dark:text-zinc-400">Teléfonos que pueden acceder a la landing exclusiva</p>
                </div>
                <a href="{{ route('dashboard.exclusive-landing.config') }}" class="rounded-lg border border-zinc-300 px-4 py-2 text-sm font-medium text-zinc-700 hover:bg-zinc-50 dark:border-zinc-600 dark:text-zinc-300 dark:hover:bg-zinc-800">Configuración landing</a>
            </div>
        </div>

        @if(session('success'))
            <div class="mx-6 mt-6 border-l-4 border-green-500 bg-green-50 px-4 py-3 dark:bg-green-900/20 dark:border-green-600">
                <p class="text-sm font-medium text-green-700 dark:text-green-300">{{ session('success') }}</p>
            </div>
        @endif
        @if(session('error'))
            <div class="mx-6 mt-6 border-l-4 border-red-500 bg-red-50 px-4 py-3 dark:bg-red-900/20 dark:border-red-600">
                <p class="text-sm font-medium text-red-700 dark:text-red-300">{{ session('error') }}</p>
            </div>
        @endif

        <div class="mx-6 mt-6">
            <div class="rounded-lg border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
                <form action="{{ route('dashboard.exclusive-landing.phones.store') }}" method="POST" class="flex flex-wrap items-end gap-3 mb-6">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Agregar número (con lada)</label>
                        <input type="tel" name="phone" value="{{ old('phone') }}" placeholder="55 1234 5678" class="rounded-lg border border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white w-48">
                    </div>
                    <button type="submit" class="rounded-lg bg-pink-600 px-4 py-2 text-sm font-medium text-white hover:bg-pink-700">Agregar</button>
                </form>

                <form method="GET" class="mb-4">
                    <input type="search" name="q" value="{{ request('q') }}" placeholder="Buscar por número..." class="rounded-lg border border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white w-64">
                    <button type="submit" class="ml-2 rounded-lg bg-zinc-200 px-3 py-2 text-sm dark:bg-zinc-700 dark:text-zinc-200">Buscar</button>
                </form>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                        <thead>
                            <tr>
                                <th class="py-3 text-left text-sm font-semibold text-zinc-900 dark:text-white">Teléfono</th>
                                <th class="py-3 text-left text-sm font-semibold text-zinc-900 dark:text-white">Estado</th>
                                <th class="py-3 text-left text-sm font-semibold text-zinc-900 dark:text-white">Alta</th>
                                <th class="py-3 text-right text-sm font-semibold text-zinc-900 dark:text-white">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                            @forelse($phones as $phone)
                                <tr>
                                    <td class="py-3 text-zinc-700 dark:text-zinc-300">{{ $phone->phone }}</td>
                                    <td class="py-3">
                                        @if($phone->is_active)
                                            <span class="rounded-full bg-green-100 px-2 py-1 text-xs font-medium text-green-800 dark:bg-green-900/30 dark:text-green-400">Activo</span>
                                        @else
                                            <span class="rounded-full bg-zinc-100 px-2 py-1 text-xs font-medium text-zinc-600 dark:bg-zinc-700 dark:text-zinc-400">Inactivo</span>
                                        @endif
                                    </td>
                                    <td class="py-3 text-sm text-zinc-500 dark:text-zinc-400">{{ $phone->registered_at?->format('d/m/Y') ?? $phone->created_at->format('d/m/Y') }}</td>
                                    <td class="py-3 text-right">
                                        <form action="{{ route('dashboard.exclusive-landing.phones.toggle', $phone) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-sm text-pink-600 hover:text-pink-700 mr-2">{{ $phone->is_active ? 'Desactivar' : 'Activar' }}</button>
                                        </form>
                                        <form action="{{ route('dashboard.exclusive-landing.phones.destroy', $phone) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar este número?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-sm text-red-600 hover:text-red-700">Eliminar</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-8 text-center text-zinc-500 dark:text-zinc-400">No hay números autorizados. Agrega al menos uno para que las clientas puedan acceder a la landing.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $phones->links() }}
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
