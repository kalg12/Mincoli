<x-layouts.app :title="__('Papelera de productos')">
    <div class="flex-1">
        <div class="border-b border-zinc-200 bg-white px-6 py-4 dark:border-zinc-700 dark:bg-zinc-900">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Papelera</h1>
                    <p class="text-sm text-zinc-600 dark:text-zinc-400">Productos eliminados (soft delete). Puedes restaurar o eliminar definitivamente.</p>
                </div>
                <a href="{{ route('dashboard.products.index') }}" class="rounded-lg border border-zinc-200 px-4 py-2 text-sm font-semibold text-zinc-900 hover:bg-zinc-100/50 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-zinc-700 dark:text-white dark:hover:bg-zinc-800 dark:focus:ring-offset-zinc-900">
                    Volver a productos
                </a>
            </div>
        </div>

        <div class="bg-white dark:bg-zinc-900">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="border-b border-zinc-200 text-left text-zinc-600 dark:border-zinc-700 dark:text-zinc-400">
                        <tr>
                            <th class="px-6 py-4 font-medium">Producto</th>
                            <th class="px-6 py-4 font-medium">Categoría</th>
                            <th class="px-6 py-4 font-medium">SKU</th>
                            <th class="px-6 py-4 font-medium">Eliminado</th>
                            <th class="px-6 py-4 font-medium text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800">
                        @forelse($products as $product)
                        <tr class="transition-colors hover:bg-zinc-100/50 dark:hover:bg-zinc-800/70">
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <span class="font-semibold text-zinc-900 dark:text-white">{{ $product->name }}</span>
                                    <span class="text-xs text-zinc-500 dark:text-zinc-500">ID: {{ $product->id }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-zinc-900 dark:text-zinc-100">{{ $product->category->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-zinc-900 dark:text-zinc-100">{{ $product->sku }}</td>
                            <td class="px-6 py-4 text-zinc-900 dark:text-zinc-100">{{ optional($product->deleted_at)->format('Y-m-d H:i') }}</td>
                            <td class="px-6 py-4">
                                <div class="flex justify-end gap-2">
                                    <form action="{{ route('dashboard.products.restore', $product->id) }}" method="POST" onsubmit="return confirm('¿Restaurar este producto?');">
                                        @csrf
                                        <button type="submit" class="rounded-lg border border-emerald-200 bg-white px-3 py-1.5 text-xs font-medium text-emerald-700 hover:bg-emerald-50 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:border-emerald-700 dark:bg-emerald-900/20 dark:text-emerald-300 dark:hover:bg-emerald-900/40 dark:focus:ring-offset-zinc-900 transition-colors">Restaurar</button>
                                    </form>
                                    <form action="{{ route('dashboard.products.forceDelete', $product->id) }}" method="POST" onsubmit="return confirm('Esto eliminará definitivamente el producto y sus imágenes/variantes. ¿Continuar?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-lg border border-red-200 bg-white px-3 py-1.5 text-xs font-medium text-red-900 hover:bg-red-100/50 focus:outline-none focus:ring-2 focus:ring-red-500 dark:border-red-700 dark:bg-red-900/20 dark:text-red-400 dark:hover:bg-red-900/40 dark:focus:ring-offset-zinc-900 transition-colors">Eliminar definitivamente</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-zinc-500 dark:text-zinc-400">No hay productos en la papelera</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="flex items-center justify-between border-t border-zinc-200 px-6 py-4 dark:border-zinc-700">
                <p class="text-sm text-zinc-600 dark:text-zinc-400">
                    @if($products->total() > 0)
                        Mostrando {{ $products->firstItem() }} de {{ $products->total() }} productos eliminados
                    @else
                        Sin productos eliminados
                    @endif
                </p>
                <div class="flex gap-2">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
