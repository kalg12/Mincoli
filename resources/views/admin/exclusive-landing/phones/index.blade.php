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

                <div class="mb-8 rounded-lg border border-zinc-200 dark:border-zinc-700 p-4 bg-zinc-50 dark:bg-zinc-800/50">
                    <h3 class="text-sm font-semibold text-zinc-900 dark:text-white mb-2">Agregar desde clientes</h3>
                    <p class="text-xs text-zinc-600 dark:text-zinc-400 mb-3">Clientes con teléfono registrado en <a href="{{ route('dashboard.customers.index') }}" class="text-pink-600 hover:underline">Clientes</a>. Agrega su número a la lista de autorizados para la landing exclusiva.</p>
                    <form action="{{ route('dashboard.exclusive-landing.phones.add-all-from-customers') }}" method="POST" class="mb-4 inline">
                        @csrf
                        <button type="submit" class="rounded-lg bg-pink-600 hover:bg-pink-700 text-white text-sm font-medium px-3 py-1.5">Agregar todos los clientes con teléfono</button>
                    </form>
                    <form method="GET" class="flex flex-wrap items-end gap-3 mb-4">
                        @if(request('q'))<input type="hidden" name="q" value="{{ request('q') }}">@endif
                        @if(request('page'))<input type="hidden" name="page" value="{{ request('page') }}">@endif
                        <div>
                            <label class="block text-xs font-medium text-zinc-600 dark:text-zinc-400 mb-1">Buscar cliente</label>
                            <input type="search" name="customer_q" value="{{ request('customer_q') }}" placeholder="Nombre, teléfono o email..." class="rounded-lg border border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white w-56 text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-zinc-600 dark:text-zinc-400 mb-1">Por página</label>
                            <select name="customer_per_page" onchange="this.form.submit()" class="rounded-lg border border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white text-sm">
                                @foreach($customerPerPageOptions as $opt)
                                    <option value="{{ $opt }}" {{ $customerPerPage === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="rounded-lg bg-zinc-200 dark:bg-zinc-700 px-3 py-2 text-sm dark:text-zinc-200">Buscar</button>
                    </form>
                    @if($customersWithPhone->isNotEmpty())
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700 text-sm">
                            <thead>
                                <tr>
                                    <th class="py-2 text-left font-semibold text-zinc-700 dark:text-zinc-300">Cliente</th>
                                    <th class="py-2 text-left font-semibold text-zinc-700 dark:text-zinc-300">Teléfono</th>
                                    <th class="py-2 text-left font-semibold text-zinc-700 dark:text-zinc-300">Estado</th>
                                    <th class="py-2 text-right font-semibold text-zinc-700 dark:text-zinc-300">Acción</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                                @foreach($customersWithPhone as $row)
                                <tr>
                                    <td class="py-2 text-zinc-700 dark:text-zinc-300">{{ $row->customer->name }}</td>
                                    <td class="py-2 text-zinc-600 dark:text-zinc-400">{{ $row->customer->phone }}</td>
                                    <td class="py-2">
                                        @if(!$row->valid_phone)
                                            <span class="text-amber-600 dark:text-amber-400 text-xs">Número inválido</span>
                                        @elseif($row->already_authorized)
                                            <span class="text-green-600 dark:text-green-400 text-xs">Ya autorizado</span>
                                        @else
                                            <span class="text-zinc-500 dark:text-zinc-400 text-xs">No autorizado</span>
                                        @endif
                                    </td>
                                    <td class="py-2 text-right">
                                        @if(!$row->valid_phone)
                                            —
                                        @elseif($row->already_authorized)
                                            —
                                        @else
                                            <form action="{{ route('dashboard.exclusive-landing.phones.add-from-customer', $row->customer) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="text-pink-600 hover:text-pink-700 text-sm font-medium">Agregar</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3 flex flex-wrap items-center justify-between gap-2">
                        <p class="text-xs text-zinc-500 dark:text-zinc-400">
                            Mostrando {{ $customersWithPhonePaginator->firstItem() ?? 0 }}–{{ $customersWithPhonePaginator->lastItem() ?? 0 }} de {{ $customersWithPhonePaginator->total() }} clientes
                        </p>
                        <div class="customers-pagination">
                            {{ $customersWithPhonePaginator->links() }}
                        </div>
                    </div>
                    @else
                    <p class="py-4 text-sm text-zinc-500 dark:text-zinc-400 text-center">
                        @if(request('customer_q'))
                            No hay clientes con teléfono que coincidan con "{{ request('customer_q') }}".
                        @else
                            No hay clientes con teléfono registrado.
                        @endif
                    </p>
                    @endif
                </div>

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
