<div class="p-6 space-y-6" wire:poll.30s>
    <!-- Header -->
    <div class="flex flex-col gap-2">
        <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Panel de administración</h1>
        <p class="text-sm text-zinc-600 dark:text-zinc-400">Resumen del estado de la tienda y accesos para gestionar inventario, banners y pedidos.</p>
    </div>

    <!-- KPI cards -->
    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
            <div class="flex items-center justify-between">
                <p class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Ventas de hoy</p>
            </div>
            <p class="mt-2 text-3xl font-bold text-zinc-900 dark:text-white">${{ number_format($salesToday, 2) }}</p>
            <p class="text-xs text-zinc-500 dark:text-zinc-500">Incluye pedidos pagados</p>
        </div>
        <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
            <div class="flex items-center justify-between">
                <p class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Pedidos activos</p>
            </div>
            <p class="mt-2 text-3xl font-bold text-zinc-900 dark:text-white">{{ $activeOrdersCount }}</p>
            <p class="text-xs text-zinc-500 dark:text-zinc-500">Sincroniza estados con paquetería</p>
        </div>
        <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
            <div class="flex items-center justify-between">
                <p class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Stock bajo</p>
            </div>
            <p class="mt-2 text-3xl font-bold text-zinc-900 dark:text-white">{{ $lowStockVariantsCount }}</p>
            <p class="text-xs text-zinc-500 dark:text-zinc-500">Variantes con existencias menores a 5</p>
        </div>
        <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
            <div class="flex items-center justify-between">
                <p class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Clientes registrados</p>
            </div>
            <p class="mt-2 text-3xl font-bold text-zinc-900 dark:text-white">{{ number_format($customersCount) }}</p>
            <p class="text-xs text-zinc-500 dark:text-zinc-500">Usuarios con cuenta y pedidos asociados</p>
        </div>
    </div>

    <!-- Quick actions -->
    <div class="grid gap-4 rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900 md:grid-cols-2 xl:grid-cols-4">
        <a href="{{ route('dashboard.products.create') }}" class="flex items-center justify-between rounded-lg border border-zinc-200 bg-zinc-50 px-4 py-3 transition hover:border-pink-500 dark:border-zinc-700 dark:bg-zinc-800 dark:hover:border-pink-500 hover:bg-zinc-100 dark:hover:bg-zinc-700">
            <div>
                <p class="text-sm font-semibold text-zinc-900 dark:text-white">Nuevo producto</p>
                <p class="text-xs text-zinc-600 dark:text-zinc-400">Agrega variantes, precios e imágenes</p>
            </div>
            <span class="text-xl text-pink-600 dark:text-pink-500">＋</span>
        </a>
        <a href="{{ route('dashboard.categories.create') }}" class="flex items-center justify-between rounded-lg border border-zinc-200 bg-zinc-50 px-4 py-3 transition hover:border-pink-500 dark:border-zinc-700 dark:bg-zinc-800 dark:hover:border-pink-500 hover:bg-zinc-100 dark:hover:bg-zinc-700">
            <div>
                <p class="text-sm font-semibold text-zinc-900 dark:text-white">Nueva categoría</p>
                <p class="text-xs text-zinc-600 dark:text-zinc-400">Organiza joyería, ropa y dulces</p>
            </div>
            <span class="text-xl text-pink-600 dark:text-pink-500">＋</span>
        </a>
        <a href="{{ route('dashboard.banners.create') }}" class="flex items-center justify-between rounded-lg border border-zinc-200 bg-zinc-50 px-4 py-3 transition hover:border-pink-500 dark:border-zinc-700 dark:bg-zinc-800 dark:hover:border-pink-500 hover:bg-zinc-100 dark:hover:bg-zinc-700">
            <div>
                <p class="text-sm font-semibold text-zinc-900 dark:text-white">Nuevo banner</p>
                <p class="text-xs text-zinc-600 dark:text-zinc-400">Actualiza el carrusel de inicio</p>
            </div>
            <span class="text-xl text-pink-600 dark:text-pink-500">＋</span>
        </a>
        <a href="{{ route('dashboard.orders.index') }}" class="flex items-center justify-between rounded-lg border border-zinc-200 bg-zinc-50 px-4 py-3 transition hover:border-pink-500 dark:border-zinc-700 dark:bg-zinc-800 dark:hover:border-pink-500 hover:bg-zinc-100 dark:hover:bg-zinc-700">
            <div>
                <p class="text-sm font-semibold text-zinc-900 dark:text-white">Ver pedidos</p>
                <p class="text-xs text-zinc-600 dark:text-zinc-400">Gestiona pagos y envíos</p>
            </div>
            <span class="text-xl text-pink-600 dark:text-pink-500">→</span>
        </a>
    </div>

    <!-- Content grids -->
    <div class="grid grid-cols-1 gap-4 xl:grid-cols-3">
        <!-- Orders table -->
        <div class="xl:col-span-2 rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-zinc-900 dark:text-white">Últimos pedidos</h3>
                <a href="{{ route('dashboard.orders.index') }}" class="text-sm text-pink-600 hover:text-pink-700 dark:text-pink-500 dark:hover:text-pink-400">Ver todos</a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="text-left text-zinc-600 dark:text-zinc-400 border-b border-zinc-200 dark:border-zinc-700">
                        <tr>
                            <th class="pb-3 pr-4 font-medium">#</th>
                            <th class="pb-3 pr-4 font-medium">Cliente</th>
                            <th class="pb-3 pr-4 font-medium">Estado</th>
                            <th class="pb-3 pr-4 font-medium">Total</th>
                            <th class="pb-3 pr-4 font-medium">Fecha</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800">
                        @forelse($latestOrders as $order)
                            <tr class="text-zinc-900 dark:text-zinc-100 hover:bg-zinc-100 dark:hover:bg-zinc-800">
                                <td class="py-3 pr-4 font-semibold">#{{ $order->order_number }}</td>
                                <td class="py-3 pr-4">{{ $order->customer?->name ?? 'N/A' }}</td>
                                <td class="py-3 pr-4">
                                    <span class="rounded-full px-2 py-1 text-xs font-semibold 
                                        @switch($order->status)
                                            @case('paid')
                                                bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400
                                                @break
                                            @case('pending')
                                                bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400
                                                @break
                                            @case('shipped')
                                                bg-sky-100 text-sky-700 dark:bg-sky-900/30 dark:text-sky-400
                                                @break
                                            @default
                                                bg-zinc-100 text-zinc-700 dark:bg-zinc-900/30 dark:text-zinc-400
                                        @endswitch
                                    ">{{ Str::title($order->status) }}</span>
                                </td>
                                <td class="py-3 pr-4">${{ number_format($order->total, 2) }}</td>
                                <td class="py-3 pr-4">{{ $order->placed_at?->format('d/m/Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-3 pr-4 text-center text-zinc-500 dark:text-zinc-400">No hay pedidos recientes.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Banners -->
        <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-zinc-900 dark:text-white">Banners activos</h3>
                <a href="{{ route('dashboard.banners.index') }}" class="text-sm text-pink-600 hover:text-pink-700 dark:text-pink-500 dark:hover:text-pink-400">Gestionar</a>
            </div>
            <div class="space-y-3">
                @forelse($activeBanners as $banner)
                    <div class="rounded-lg border border-zinc-200 bg-zinc-50 p-4 text-sm dark:border-zinc-700 dark:bg-zinc-800">
                        <p class="font-semibold text-zinc-900 dark:text-white">{{ $banner->title }}</p>
                        <p class="text-xs text-zinc-600 dark:text-zinc-400">{{ $banner->text }}</p>
                    </div>
                @empty
                    <div class="rounded-lg border border-zinc-200 bg-zinc-50 p-4 text-sm dark:border-zinc-700 dark:bg-zinc-800 text-center text-zinc-500 dark:text-zinc-400">
                        No hay banners activos.
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Bottom grids -->
    <div class="grid grid-cols-1 gap-4 xl:grid-cols-3">
        <!-- Top products -->
        <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-zinc-900 dark:text-white">Productos destacados</h3>
                <a href="{{ route('dashboard.products.index') }}" class="text-sm text-pink-600 hover:text-pink-700 dark:text-pink-500 dark:hover:text-pink-400">Ver catálogo</a>
            </div>
            <div class="space-y-3 text-sm">
                @forelse($featuredProducts as $product)
                    <div class="flex items-center justify-between border-b border-zinc-100 pb-3 dark:border-zinc-800">
                        <div>
                            <p class="font-semibold text-zinc-900 dark:text-white">{{ $product->name }}</p>
                            <p class="text-xs text-zinc-600 dark:text-zinc-400">Stock: {{ $product->variants->sum('stock') }} • ${{ number_format($product->price, 2) }}</p>
                        </div>
                    </div>
                @empty
                     <div class="text-center text-zinc-500 dark:text-zinc-400">
                        No hay productos destacados.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Payment methods -->
        <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-zinc-900 dark:text-white">Métodos de pago</h3>
                <a href="{{ route('dashboard.payment-methods.index') }}" class="text-sm text-pink-600 hover:text-pink-700 dark:text-pink-500 dark:hover:text-pink-400">Configurar</a>
            </div>
            <div class="space-y-3 text-sm">
                @forelse($paymentMethods as $method)
                    <div class="flex items-center justify-between rounded-lg border border-zinc-200 bg-zinc-50 px-3 py-2 dark:border-zinc-700 dark:bg-zinc-800">
                        <span class="text-zinc-900 dark:text-zinc-100">{{ $method->name }}</span>
                        @if($method->is_active)
                            <span class="text-emerald-600 text-xs font-semibold dark:text-emerald-400">Activo</span>
                        @else
                            <span class="text-amber-600 text-xs font-semibold dark:text-amber-400">Inactivo</span>
                        @endif
                    </div>
                @empty
                    <div class="rounded-lg border border-zinc-200 bg-zinc-50 px-3 py-2 dark:border-zinc-700 dark:bg-zinc-800 text-center text-zinc-500 dark:text-zinc-400">
                        No hay métodos de pago configurados.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Help -->
        <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
            <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-4">Guías rápidas</h3>
            <ul class="space-y-3 text-sm">
                <li class="flex items-start gap-3 border-b border-zinc-100 pb-3 dark:border-zinc-800">
                    <span class="text-pink-600 dark:text-pink-500 mt-0.5">•</span>
                    <div>
                        <p class="font-semibold text-zinc-900 dark:text-white">Cómo subir banners</p>
                        <p class="text-xs text-zinc-600 dark:text-zinc-400">Recomendado: 1920x600, JPG/WEBP</p>
                    </div>
                </li>
                <li class="flex items-start gap-3 border-b border-zinc-100 pb-3 dark:border-zinc-800">
                    <span class="text-pink-600 dark:text-pink-500 mt-0.5">•</span>
                    <div>
                        <p class="font-semibold text-zinc-900 dark:text-white">Cargar catálogo</p>
                        <p class="text-xs text-zinc-600 dark:text-zinc-400">Incluye variantes, precios y stock</p>
                    </div>
                </li>
                <li class="flex items-start gap-3">
                    <span class="text-pink-600 dark:text-pink-500 mt-0.5">•</span>
                    <div>
                        <p class="font-semibold text-zinc-900 dark:text-white">Activar métodos de pago</p>
                        <p class="text-xs text-zinc-600 dark:text-zinc-400">Sincroniza tarjetas, transferencia y OXXO</p>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>