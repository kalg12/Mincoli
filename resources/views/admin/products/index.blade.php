<x-layouts.app :title="__('Productos')">
    <div class="flex-1">
        <div class="border-b border-zinc-200 bg-white px-6 py-4 dark:border-zinc-700 dark:bg-zinc-900">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Productos</h1>
                    <p class="text-sm text-zinc-600 dark:text-zinc-400">Gestiona el catálogo completo de productos</p>
                </div>
                <a href="{{ route('dashboard.products.create') }}" class="rounded-lg bg-pink-600 px-4 py-2 text-sm font-semibold text-white hover:bg-pink-700 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-offset-2 dark:bg-pink-500 dark:hover:bg-pink-600 dark:focus:ring-offset-zinc-900">
                    Nuevo producto
                </a>
            </div>
        </div>

        <!-- Filters -->
        <div class="border-b border-zinc-200 bg-white px-6 py-4 dark:border-zinc-700 dark:bg-zinc-900">
            <div class="flex gap-3">
                <input type="text" placeholder="Buscar productos..." class="flex-1 rounded-lg border border-zinc-200 bg-white px-4 py-2 text-sm text-zinc-900 placeholder-zinc-500 focus:border-pink-500 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:placeholder-zinc-400 dark:focus:ring-offset-zinc-900">
                <select class="rounded-lg border border-zinc-200 bg-white px-4 py-2 text-sm text-zinc-900 focus:border-pink-500 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:focus:ring-offset-zinc-900">
                    <option>Todas las categorías</option>
                    <option>Joyería</option>
                    <option>Ropa</option>
                    <option>Dulces</option>
                </select>
                <select class="rounded-lg border border-zinc-200 bg-white px-4 py-2 text-sm text-zinc-900 focus:border-pink-500 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:focus:ring-offset-zinc-900">
                    <option>Todos los estados</option>
                    <option>Publicado</option>
                    <option>Borrador</option>
                    <option>Agotado</option>
                </select>
            </div>
        </div>

        <!-- Products Table -->
        <div class="bg-white dark:bg-zinc-900">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="border-b border-zinc-200 text-left text-zinc-600 dark:border-zinc-700 dark:text-zinc-400">
                        <tr>
                            <th class="px-6 py-4 font-medium">Producto</th>
                            <th class="px-6 py-4 font-medium">Categoría</th>
                            <th class="px-6 py-4 font-medium">Precio</th>
                            <th class="px-6 py-4 font-medium">Stock</th>
                            <th class="px-6 py-4 font-medium">Estado</th>
                            <th class="px-6 py-4 font-medium text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800">
                        @forelse($products as $product)
                        <tr class="transition-colors hover:bg-zinc-100/50 dark:hover:bg-zinc-800/70">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="h-12 w-12 flex-shrink-0 rounded-lg bg-zinc-100 dark:bg-zinc-800"></div>
                                    <div>
                                        <p class="font-semibold text-zinc-900 dark:text-white">{{ $product->name }}</p>
                                        <p class="text-xs text-zinc-500 dark:text-zinc-500">SKU: {{ $product->sku }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-zinc-900 dark:text-zinc-100">{{ $product->category->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-zinc-900 dark:text-zinc-100">${{ number_format($product->price, 2) }}</td>
                            <td class="px-6 py-4 text-zinc-900 dark:text-zinc-100">{{ $product->stock }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <span class="@if($product->stock == 0) rounded-full bg-red-100 px-2 py-1 text-xs font-semibold text-red-700 dark:bg-red-900/30 dark:text-red-400 @elseif($product->stock <= 5) rounded-full bg-orange-100 px-2 py-1 text-xs font-semibold text-orange-700 dark:bg-orange-900/30 dark:text-orange-400 @else rounded-full bg-emerald-100 px-2 py-1 text-xs font-semibold text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400 @endif" title="Color indica nivel de stock: Rojo=Crítico | Naranja=Bajo | Verde=Disponible">
                                        @if($product->stock == 0)
                                            Agotado
                                        @elseif($product->stock <= 5)
                                            Stock Bajo ({{ $product->stock }})
                                        @else
                                            Disponible
                                        @endif
                                    </span>
                                    <span class="text-xs px-2 py-1 rounded-full @if($product->status == 'published') bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400 @elseif($product->status == 'draft') bg-gray-100 text-gray-700 dark:bg-gray-900/30 dark:text-gray-400 @else bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400 @endif">
                                        {{ ucfirst($product->status) }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('dashboard.products.edit', $product->id) }}" class="rounded-lg border border-zinc-200 bg-white px-3 py-1.5 text-xs font-medium text-zinc-900 hover:bg-zinc-100/50 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:hover:bg-zinc-700 dark:focus:ring-offset-zinc-900 transition-colors">Editar</a>
                                    <form action="{{ route('dashboard.products.destroy', $product->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Eliminar este producto?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-lg border border-red-200 bg-white px-3 py-1.5 text-xs font-medium text-red-900 hover:bg-red-100/50 focus:outline-none focus:ring-2 focus:ring-red-500 dark:border-red-700 dark:bg-red-900/20 dark:text-red-400 dark:hover:bg-red-900/40 dark:focus:ring-offset-zinc-900 transition-colors">Eliminar</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-zinc-500 dark:text-zinc-400">No hay productos disponibles</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="flex items-center justify-between border-t border-zinc-200 px-6 py-4 dark:border-zinc-700">
                <p class="text-sm text-zinc-600 dark:text-zinc-400">
                    @if($products->total() > 0)
                        Mostrando {{ $products->firstItem() }} de {{ $products->total() }} productos
                    @else
                        Sin productos
                    @endif
                </p>
                <div class="flex gap-2">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
