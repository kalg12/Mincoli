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

                <div class="mb-8 rounded-xl border border-zinc-200 dark:border-zinc-700 p-5 bg-white dark:bg-zinc-900 shadow-sm">
                    <h3 class="text-base font-semibold text-zinc-900 dark:text-white mb-1">Agregar desde clientes</h3>
                    <p class="text-xs text-zinc-500 dark:text-zinc-400 mb-4">Clientes con teléfono en <a href="{{ route('dashboard.customers.index') }}" class="text-pink-600 hover:text-pink-500 font-medium">Clientes</a>. Agrega su número a autorizados para la landing exclusiva.</p>
                    <form action="{{ route('dashboard.exclusive-landing.phones.add-all-from-customers') }}" method="POST" class="mb-4 inline">
                        @csrf
                        <button type="submit" class="rounded-lg bg-pink-600 hover:bg-pink-700 text-white text-sm font-medium px-4 py-2 shadow-sm transition">Agregar todos los clientes con teléfono</button>
                    </form>
                    <form id="customer-filter-form" method="GET" action="{{ route('dashboard.exclusive-landing.phones.index') }}" class="flex flex-wrap items-end gap-3 mb-4">
                        @if(request('q'))<input type="hidden" name="q" value="{{ request('q') }}">@endif
                        @if(request('page'))<input type="hidden" name="page" value="{{ request('page') }}">@endif
                        <div class="flex-1 min-w-[200px] max-w-sm">
                            <label class="block text-xs font-medium text-zinc-600 dark:text-zinc-400 mb-1">Buscar cliente</label>
                            <div class="relative">
                                <input type="text" id="customer-q-input" name="customer_q" value="{{ request('customer_q') }}" placeholder="Nombre, teléfono o email..." autocomplete="off"
                                    class="w-full rounded-lg border border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white text-sm py-2 pl-3 pr-9 focus:ring-2 focus:ring-pink-500 focus:border-pink-500 transition">
                                @if(request('customer_q'))
                                <a href="{{ route('dashboard.exclusive-landing.phones.index', array_filter(['q' => request('q'), 'page' => request('page'), 'customer_per_page' => request('customer_per_page', 10)])) }}" class="absolute right-2 top-1/2 -translate-y-1/2 p-1 rounded text-zinc-400 hover:text-zinc-600 hover:bg-zinc-200 dark:hover:bg-zinc-600 dark:hover:text-white transition" title="Limpiar filtro">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </a>
                                @endif
                            </div>
                        </div>
                        <div class="w-24">
                            <label class="block text-xs font-medium text-zinc-600 dark:text-zinc-400 mb-1">Por página</label>
                            <select name="customer_per_page" onchange="this.form.submit()" class="w-full rounded-lg border border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white text-sm py-2 focus:ring-2 focus:ring-pink-500">
                                @foreach($customerPerPageOptions as $opt)
                                    <option value="{{ $opt }}" {{ $customerPerPage === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                                @endforeach
                            </select>
                        </div>
                        @if(request('customer_q'))
                        <a href="{{ route('dashboard.exclusive-landing.phones.index', array_filter(['q' => request('q'), 'page' => request('page'), 'customer_per_page' => request('customer_per_page', 10)])) }}" class="rounded-lg border border-zinc-300 dark:border-zinc-600 px-3 py-2 text-sm text-zinc-600 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-700 transition">
                            Limpiar filtro
                        </a>
                        @endif
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
                                            <form action="{{ route('dashboard.exclusive-landing.phones.remove-from-customer', $row->customer) }}" method="POST" class="inline" onsubmit="return confirm('¿Desautorizar este número? Dejará de acceder a la landing exclusiva.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-amber-600 hover:text-amber-700 dark:text-amber-400 dark:hover:text-amber-300 text-sm font-medium">Desautorizar</button>
                                            </form>
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

                <form method="GET" class="mb-4 flex flex-wrap items-center gap-3">
                    @if(request('customer_q'))<input type="hidden" name="customer_q" value="{{ request('customer_q') }}">@endif
                    @if(request('customer_per_page'))<input type="hidden" name="customer_per_page" value="{{ request('customer_per_page') }}">@endif
                    @if(request('customer_page'))<input type="hidden" name="customer_page" value="{{ request('customer_page') }}">@endif
                    <label class="text-sm font-medium text-zinc-700 dark:text-zinc-300">Buscar en autorizados</label>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Por número..." class="rounded-lg border border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white w-56 text-sm py-2">
                    <button type="submit" class="rounded-lg bg-zinc-200 px-3 py-2 text-sm dark:bg-zinc-700 dark:text-zinc-200">Buscar</button>
                    @if(request('q'))
                    <a href="{{ route('dashboard.exclusive-landing.phones.index', array_filter(['customer_q' => request('customer_q'), 'customer_per_page' => request('customer_per_page'), 'customer_page' => request('customer_page')])) }}" class="text-sm text-zinc-500 hover:text-zinc-700 dark:hover:text-zinc-300">Limpiar</a>
                    @endif
                </form>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                        <thead>
                            <tr>
                                <th class="py-3 text-left text-sm font-semibold text-zinc-900 dark:text-white">Teléfono</th>
                                <th class="py-3 text-left text-sm font-semibold text-zinc-900 dark:text-white">Cliente</th>
                                <th class="py-3 text-left text-sm font-semibold text-zinc-900 dark:text-white">Estado</th>
                                <th class="py-3 text-left text-sm font-semibold text-zinc-900 dark:text-white">Alta</th>
                                <th class="py-3 text-right text-sm font-semibold text-zinc-900 dark:text-white">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                            @forelse($phones as $phone)
                                <tr>
                                    <td class="py-3 text-zinc-700 dark:text-zinc-300">{{ $phone->phone }}</td>
                                    <td class="py-3 text-zinc-600 dark:text-zinc-400 text-sm">{{ $customerNamesByPhone[$phone->phone] ?? '—' }}</td>
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
                                    <td colspan="5" class="py-8 text-center text-zinc-500 dark:text-zinc-400">No hay números autorizados. Agrega al menos uno para que las clientas puedan acceder a la landing.</td>
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
    </div>
    <script>
    (function() {
        var form = document.getElementById('customer-filter-form');
        var input = document.getElementById('customer-q-input');
        if (!form || !input) return;
        var debounceTimer;
        input.addEventListener('input', function() {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(function() {
                var action = form.getAttribute('action') || '';
                form.setAttribute('action', action.split('#')[0] + '#customer-search');
                form.submit();
            }, 380);
        });
        if (window.location.hash === '#customer-search') {
            input.focus();
            input.setSelectionRange(input.value.length, input.value.length);
        }
    })();
    </script>
</x-layouts.app>
