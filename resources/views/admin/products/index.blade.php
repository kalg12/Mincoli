<x-layouts.app :title="__('Productos')">
    <div class="flex-1">
        <div class="border-b border-zinc-200 bg-white px-6 py-4 dark:border-zinc-700 dark:bg-zinc-900">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Productos</h1>
                    <p class="text-sm text-zinc-600 dark:text-zinc-400">Gestiona el catálogo completo de productos</p>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('dashboard.products.trash') }}" class="rounded-lg border border-zinc-200 px-4 py-2 text-sm font-semibold text-zinc-900 hover:bg-zinc-100/50 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-zinc-700 dark:text-white dark:hover:bg-zinc-800 dark:focus:ring-offset-zinc-900">
                        Papelera
                    </a>
                    
                    <!-- Template Download Link (invisible, used by JS) -->
                    <button onclick="window.dispatchEvent(new CustomEvent('open-import-modal'))" class="rounded-lg border border-dashed border-zinc-300 px-4 py-2 text-sm font-semibold text-zinc-700 hover:bg-zinc-50 focus:outline-none focus:ring-2 focus:ring-zinc-500 dark:border-zinc-600 dark:text-zinc-300 dark:hover:bg-zinc-800">
                        <i class="fas fa-file-import mr-2"></i> Importar
                    </button>

                    <a href="{{ route('dashboard.products.create') }}" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:bg-blue-500 dark:hover:bg-blue-600 dark:focus:ring-offset-zinc-900">
                        Nuevo producto
                    </a>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="border-b border-zinc-200 bg-white px-6 py-4 dark:border-zinc-700 dark:bg-zinc-900">
            <div class="flex gap-3 items-center">
                <input type="text" placeholder="Buscar productos..." class="flex-1 rounded-lg border border-zinc-200 bg-white px-4 py-2 text-sm text-zinc-900 placeholder-zinc-500 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:placeholder-zinc-400 dark:focus:ring-offset-zinc-900">
                <select class="rounded-lg border border-zinc-200 bg-white px-4 py-2 text-sm text-zinc-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:focus:ring-offset-zinc-900">
                    <option>Todas las categorías</option>
                    <option>Joyería</option>
                    <option>Ropa</option>
                    <option>Dulces</option>
                </select>
                <select class="rounded-lg border border-zinc-200 bg-white px-4 py-2 text-sm text-zinc-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:focus:ring-offset-zinc-900">
                    <option>Todos los estados</option>
                    <option>Publicado</option>
                    <option>Borrador</option>
                    <option>Agotado</option>
                </select>
                <button onclick="printSelectedLabels()" class="rounded-lg bg-zinc-100 px-4 py-2 text-sm font-medium text-zinc-900 hover:bg-zinc-200 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-zinc-800 dark:text-white dark:hover:bg-zinc-700">
                    <svg class="inline-block h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    Imprimir etiquetas
                </button>
            </div>
        </div>

        <!-- Products Table -->
        <div class="bg-white dark:bg-zinc-900">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="border-b border-zinc-200 text-left text-zinc-600 dark:border-zinc-700 dark:text-zinc-400">
                        <tr>
                            <th class="px-6 py-4">
                                <input type="checkbox" id="selectAll" onchange="toggleAllCheckboxes(this)" class="rounded border-zinc-300 text-pink-600 focus:ring-pink-500 dark:border-zinc-600 dark:bg-zinc-700">
                            </th>
                            <th class="px-6 py-4 font-medium">Producto</th>
                            <th class="px-6 py-4 font-medium">Categoría</th>
                            <th class="px-6 py-4 font-medium">Precio</th>
                            <th class="px-6 py-4 font-medium">Stock</th>
                            <th class="px-6 py-4 font-medium">Variantes</th>
                            <th class="px-6 py-4 font-medium">Estado</th>
                            <th class="px-6 py-4 font-medium text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800">
                        @forelse($products as $product)
                        <tr class="transition-colors hover:bg-zinc-100/50 dark:hover:bg-zinc-800/70">
                            <td class="px-6 py-4">
                                <input type="checkbox" name="product_ids[]" value="{{ $product->id }}" class="product-checkbox rounded border-zinc-300 text-pink-600 focus:ring-pink-500 dark:border-zinc-600 dark:bg-zinc-700">
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <a href="{{ route('dashboard.products.edit', $product->id) }}" class="block h-12 w-12 flex-shrink-0 rounded-lg bg-zinc-100 dark:bg-zinc-800 hover:ring-2 hover:ring-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 overflow-hidden">
                                        @if($product->images->count() > 0)
                                            <img src="{{ $product->images->first()->url }}" alt="{{ $product->name }}" class="h-full w-full object-cover">
                                        @endif
                                    </a>
                                    <div>
                                        <a href="{{ route('dashboard.products.edit', $product->id) }}" class="font-semibold text-zinc-900 dark:text-white hover:underline">{{ $product->name }}</a>
                                        <p class="text-xs text-zinc-500 dark:text-zinc-500">SKU: {{ $product->sku }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-zinc-900 dark:text-zinc-100">
                                <div class="text-sm font-medium">{{ $product->category->name ?? 'N/A' }}</div>
                                @if($product->subcategory)
                                    <div class="text-xs text-zinc-500 dark:text-zinc-500">{{ $product->subcategory->name }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-zinc-900 dark:text-zinc-100">${{ number_format($product->price, 2) }}</td>
                            <td class="px-6 py-4">
                                @php
                                    $totalStock = $product->variants->count() > 0 
                                        ? $product->variants->sum('stock') 
                                        : $product->stock;
                                @endphp
                                <div class="flex items-center gap-2">
                                    @if($totalStock == 0)
                                        <svg class="h-5 w-5 text-red-500 dark:text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                        </svg>
                                    @elseif($totalStock <= 5)
                                        <svg class="h-5 w-5 text-orange-500 dark:text-orange-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                    @else
                                        <svg class="h-5 w-5 text-emerald-500 dark:text-emerald-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                    @endif
                                    <div>
                                        <span class="font-semibold text-zinc-900 dark:text-zinc-100">{{ $totalStock }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if($product->variants->count() > 0)
                                    <span class="inline-flex items-center gap-1 rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-700 dark:bg-blue-900/30 dark:text-blue-400">
                                        <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zm0 6a1 1 0 011-1h12a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zm0-6h.01M3 10h.01M3 16h.01M7 4h.01M7 10h.01M7 16h.01M11 4h.01M11 10h.01M11 16h.01M15 4h.01M15 10h.01M15 16h.01" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $product->variants->count() }} {{ $product->variants->count() === 1 ? 'variante' : 'variantes' }}
                                    </span>
                                @else
                                    <span class="text-xs text-zinc-500 dark:text-zinc-400">Sin variantes</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $totalStock = $product->variants->count() > 0 
                                        ? $product->variants->sum('stock') 
                                        : $product->stock;
                                @endphp
                                <div class="flex items-center gap-2">
                                    <span @class([
                                        'rounded-full px-2 py-1 text-xs font-semibold',
                                        'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' => $totalStock == 0,
                                        'bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400' => ($totalStock > 0 && $totalStock <= 5),
                                        'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400' => $totalStock > 5,
                                    ]) title="Color indica nivel de stock: Rojo=Agotado | Naranja=Bajo | Verde=Disponible">
                                        @if($totalStock == 0)
                                            Agotado
                                        @elseif($totalStock <= 5)
                                            Stock Bajo ({{ $totalStock }})
                                        @else
                                            Disponible
                                        @endif
                                    </span>
                                    <span @class([
                                        'text-xs px-2 py-1 rounded-full',
                                        'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' => $product->status == 'published',
                                        'bg-gray-100 text-gray-700 dark:bg-gray-900/30 dark:text-gray-400' => $product->status == 'draft',
                                        'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400' => !in_array($product->status, ['published', 'draft'], true),
                                    ])>
                                        {{ ucfirst($product->status) }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex justify-end gap-2">
                                    <form action="{{ route('dashboard.products.toggleFeatured', $product->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="rounded-lg border border-zinc-200 bg-white px-3 py-1.5 text-xs font-medium text-zinc-900 hover:bg-zinc-100/50 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:hover:bg-zinc-700 dark:focus:ring-offset-zinc-900 transition-colors" title="{{ $product->is_featured ? 'Quitar de destacados' : 'Marcar como destacado' }}">
                                            <svg class="h-4 w-4 {{ $product->is_featured ? 'fill-yellow-400 text-yellow-400' : 'fill-none' }}" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                                        </button>
                                    </form>
                                    <form action="{{ route('dashboard.products.toggleActive', $product->id) }}" method="POST" style="display:inline;">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="rounded-lg border border-zinc-200 bg-white px-3 py-1.5 text-xs font-medium text-zinc-900 hover:bg-zinc-100/50 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:hover:bg-zinc-700 dark:focus:ring-offset-zinc-900 transition-colors" title="{{ $product->is_active ? 'Desactivar' : 'Activar' }}">
                                            <svg class="h-4 w-4 {{ $product->is_active ? 'text-emerald-500' : 'text-zinc-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                @if($product->is_active)
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                @else
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                                @endif
                                            </svg>
                                        </button>
                                    </form>
                                    <a href="{{ route('dashboard.products.edit', $product->id) }}" class="rounded-lg border border-zinc-200 bg-white px-3 py-1.5 text-xs font-medium text-zinc-900 hover:bg-zinc-100/50 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:hover:bg-zinc-700 dark:focus:ring-offset-zinc-900 transition-colors">Editar</a>
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
                            <td colspan="8" class="px-6 py-8 text-center text-zinc-500 dark:text-zinc-400">No hay productos disponibles</td>
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

    <livewire:admin.products.import-modal />

    <script>
        function toggleAllCheckboxes(source) {
            const checkboxes = document.querySelectorAll('.product-checkbox');
            checkboxes.forEach(checkbox => checkbox.checked = source.checked);
        }

        function printSelectedLabels() {
            const checkboxes = document.querySelectorAll('.product-checkbox:checked');
            const productIds = Array.from(checkboxes).map(cb => cb.value);

            if (productIds.length === 0) {
                alert('Por favor selecciona al menos un producto');
                return;
            }

            const form = document.createElement('form');
            const printRoute = '{{ route("dashboard.products.printLabels") }}';
            form.method = 'POST';
            form.action = printRoute;
            form.target = '_blank';

            const csrfInput = document.createElement('input');
            const csrfToken = '{{ csrf_token() }}';
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = csrfToken;
            form.appendChild(csrfInput);

            productIds.forEach(id => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'products[]';
                input.value = id;
                form.appendChild(input);
            });

            document.body.appendChild(form);
            form.submit();
            document.body.removeChild(form);
        }
    </script>
</x-layouts.app>
