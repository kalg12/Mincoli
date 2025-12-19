<x-layouts.app :title="__('Editar Producto')">
    <div class="space-y-6">
        <!-- Header -->
        <div class="border-b border-zinc-200 bg-white px-6 py-4 dark:border-zinc-700 dark:bg-zinc-900">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">{{ $product->name }}</h1>
                    <p class="text-sm text-zinc-600 dark:text-zinc-400">SKU: {{ $product->sku }}</p>
                </div>
                <a href="{{ route('dashboard.products.index') }}" class="rounded-lg border border-zinc-200 px-4 py-2 text-sm font-semibold text-zinc-900 hover:bg-zinc-100/50 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-zinc-700 dark:text-white dark:hover:bg-zinc-800 dark:focus:ring-offset-zinc-900">Volver</a>
            </div>
        </div>

        <!-- Tabs Navigation -->
        <div class="border-b border-zinc-200 bg-white px-6 dark:border-zinc-700 dark:bg-zinc-900">
            <div class="flex gap-8">
                <button onclick="switchTab('general')" class="tab-btn py-4 px-1 border-b-2 border-blue-600 text-blue-600 font-medium dark:text-blue-400 dark:border-blue-400">
                    Información General
                </button>
                <button onclick="switchTab('variants')" class="tab-btn py-4 px-1 border-b-2 border-transparent text-zinc-600 font-medium hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-white">
                    Variantes ({{ $product->variants->count() }})
                </button>
                <button onclick="switchTab('inventory')" class="tab-btn py-4 px-1 border-b-2 border-transparent text-zinc-600 font-medium hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-white">
                    Inventario
                </button>
            </div>
        </div>

        <div class="px-6 pb-6">
            <div id="general" class="tab-content">
                <form method="POST" action="{{ route('dashboard.products.update', $product->id) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
                        <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-4">Información del Producto</h3>
                        
                        <div class="grid gap-4 md:grid-cols-2">
                            <div>
                                <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Nombre <span class="text-red-500">*</span></label>
                                <input type="text" name="name" value="{{ old('name', $product->name) }}" class="w-full rounded-lg border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:focus:ring-offset-zinc-900" required />
                                @error('name')<p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">SKU <span class="text-red-500">*</span></label>
                                <input type="text" name="sku" value="{{ old('sku', $product->sku) }}" class="w-full rounded-lg border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:focus:ring-offset-zinc-900" required />
                                @error('sku')<p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Categoría <span class="text-red-500">*</span></label>
                                <select name="category_id" class="w-full rounded-lg border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:focus:ring-offset-zinc-900" required>
                                    @foreach($categories ?? [] as $category)
                                        <option value="{{ $category->id }}" @if($product->category_id == $category->id) selected @endif>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                @error('category_id')<p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Código de Barras</label>
                                <input type="text" name="barcode" value="{{ old('barcode', $product->barcode) }}" class="w-full rounded-lg border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:focus:ring-offset-zinc-900" />
                                @error('barcode')<p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <div class="mt-4">
                            <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Descripción</label>
                            <textarea name="description" rows="4" class="w-full rounded-lg border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:focus:ring-offset-zinc-900">{{ old('description', $product->description) }}</textarea>
                            @error('description')<p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="grid gap-6 md:grid-cols-2">
                        <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
                            <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-4">Precios</h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Precio de Venta <span class="text-red-500">*</span></label>
                                    <input type="number" name="price" value="{{ old('price', $product->price) }}" step="0.01" class="w-full rounded-lg border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:focus:ring-offset-zinc-900" required />
                                    @error('price')<p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Precio de Costo</label>
                                    <input type="number" name="cost" value="{{ old('cost', $product->cost) }}" step="0.01" class="w-full rounded-lg border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:focus:ring-offset-zinc-900" />
                                </div>
                                <div>
                                    <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Precio en Oferta</label>
                                    <input type="number" name="sale_price" value="{{ old('sale_price', $product->sale_price) }}" step="0.01" class="w-full rounded-lg border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:focus:ring-offset-zinc-900" />
                                </div>
                            </div>
                        </div>

                        <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
                            <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-4">Configuración</h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Estado <span class="text-red-500">*</span></label>
                                    <select name="status" class="w-full rounded-lg border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:focus:ring-offset-zinc-900" required>
                                        <option value="published" @if($product->status == 'published') selected @endif>Publicado</option>
                                        <option value="draft" @if($product->status == 'draft') selected @endif>Borrador</option>
                                        <option value="out_of_stock" @if($product->status == 'out_of_stock') selected @endif>Agotado</option>
                                    </select>
                                    @error('status')<p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">IVA (%)</label>
                                    <input type="number" name="iva_rate" value="{{ old('iva_rate', $product->iva_rate ?? 0) }}" step="0.01" class="w-full rounded-lg border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:focus:ring-offset-zinc-900" />
                                </div>
                                <div class="flex items-center gap-3 pt-2">
                                    <input type="checkbox" name="is_featured" id="is_featured" value="1" @if($product->is_featured) checked @endif class="rounded border-zinc-300 text-blue-600 focus:ring-blue-500 dark:border-zinc-600 dark:bg-zinc-700" />
                                    <label for="is_featured" class="text-sm font-medium text-zinc-700 dark:text-zinc-300">Destacar producto</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3">
                        <a href="{{ route('dashboard.products.index') }}" class="px-6 py-2.5 bg-zinc-200 hover:bg-zinc-300 text-zinc-900 rounded-lg font-medium transition dark:bg-zinc-700 dark:hover:bg-zinc-600 dark:text-white">Cancelar</a>
                        <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition">Guardar cambios</button>
                    </div>
                </form>
            </div>

            <!-- TAB: Variants -->
            <div id="variants" class="tab-content hidden">
                <div class="space-y-6">
                    <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-semibold text-zinc-900 dark:text-white">Variantes del Producto</h3>
                            <button onclick="openAddVariantModal()" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition">
                                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Agregar Variante
                            </button>
                        </div>

                        @if($product->variants->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead class="border-b border-zinc-200 dark:border-zinc-700">
                                    <tr>
                                        <th class="px-4 py-3 text-left font-medium text-zinc-700 dark:text-zinc-300">Nombre</th>
                                        <th class="px-4 py-3 text-left font-medium text-zinc-700 dark:text-zinc-300">Color</th>
                                        <th class="px-4 py-3 text-left font-medium text-zinc-700 dark:text-zinc-300">Talla</th>
                                        <th class="px-4 py-3 text-left font-medium text-zinc-700 dark:text-zinc-300">SKU</th>
                                        <th class="px-4 py-3 text-left font-medium text-zinc-700 dark:text-zinc-300">Precio</th>
                                        <th class="px-4 py-3 text-left font-medium text-zinc-700 dark:text-zinc-300">Stock</th>
                                        <th class="px-4 py-3 text-right font-medium text-zinc-700 dark:text-zinc-300">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800">
                                    @foreach($product->variants as $variant)
                                    <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50">
                                        <td class="px-4 py-3 text-zinc-900 dark:text-white">{{ $variant->name }}</td>
                                        <td class="px-4 py-3 text-zinc-900 dark:text-white">
                                            @if($variant->color)
                                                <span class="inline-flex items-center gap-2">
                                                    <span class="w-4 h-4 rounded border border-zinc-300" style="background-color: {{ $variant->color }};"></span>
                                                    {{ $variant->color }}
                                                </span>
                                            @else
                                                <span class="text-zinc-400 dark:text-zinc-500">—</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-zinc-900 dark:text-white">{{ $variant->size ?? '—' }}</td>
                                        <td class="px-4 py-3 text-zinc-900 dark:text-white">{{ $variant->sku }}</td>
                                        <td class="px-4 py-3 text-zinc-900 dark:text-white">${{ number_format($variant->price ?? $product->price, 2) }}</td>
                                        <td class="px-4 py-3">
                                            <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-medium {{ $variant->stock == 0 ? 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300' : ($variant->stock <= 5 ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300' : 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300') }}">
                                                {{ $variant->stock }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-right">
                                            <button onclick="editVariant({{ $variant->id }})" class="text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 text-sm font-medium">Editar</button>
                                            <button onclick="deleteVariant({{ $variant->id }})" class="text-red-600 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 text-sm font-medium ml-3">Eliminar</button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="text-center py-12">
                            <svg class="w-12 h-12 text-zinc-400 dark:text-zinc-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                            </svg>
                            <p class="text-zinc-600 dark:text-zinc-400 mb-4">No hay variantes creadas aún</p>
                            <button onclick="openAddVariantModal()" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition">
                                Crear primera variante
                            </button>
                        </div>
                        @endif
                    </div>

                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 dark:bg-blue-900/20 dark:border-blue-800">
                        <p class="text-sm text-blue-800 dark:text-blue-300"><strong>Consejo:</strong> Las variantes permiten gestionar diferentes opciones del mismo producto (colores, tallas, etc.) con inventario independiente. Cada variante puede tener su propio SKU y precio.</p>
                    </div>
                </div>
            </div>

            <!-- TAB: Inventory -->
            <div id="inventory" class="tab-content hidden">
                <div class="space-y-6">
                    <!-- Ajuste de stock -->
                    <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
                        <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-6">Ajustar Stock</h3>
                        
                        <form method="POST" action="{{ route('dashboard.products.adjustStock', $product->id) }}" class="space-y-4 max-w-lg">
                            @csrf
                            
                            @if($product->variants->count() > 0)
                            <div>
                                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Variante <span class="text-red-500">*</span></label>
                                <select name="variant_id" class="w-full rounded-lg border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white" required>
                                    <option value="">Selecciona una variante</option>
                                    @foreach($product->variants as $variant)
                                        <option value="{{ $variant->id }}">{{ $variant->name }} (Stock: {{ $variant->stock }})</option>
                                    @endforeach
                                </select>
                            </div>
                            @else
                            <div class="p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800 text-sm text-blue-800 dark:text-blue-300">
                                <p>Sin variantes. El ajuste se aplicará al stock del producto.</p>
                            </div>
                            @endif

                            <div class="grid gap-4 md:grid-cols-2">
                                <div>
                                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Tipo <span class="text-red-500">*</span></label>
                                    <select name="type" class="w-full rounded-lg border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white" required>
                                        <option value="entrada">Entrada (compra/devolución)</option>
                                        <option value="salida">Salida (venta/daño)</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Cantidad <span class="text-red-500">*</span></label>
                                    <input type="number" name="quantity" min="1" required class="w-full rounded-lg border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white" />
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Razón <span class="text-red-500">*</span></label>
                                <select name="reason" class="w-full rounded-lg border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white" required>
                                    <option value="">Selecciona una razón</option>
                                    <option value="compra">Compra a proveedor</option>
                                    <option value="devolución">Devolución cliente</option>
                                    <option value="daño">Producto dañado</option>
                                    <option value="pérdida">Pérdida/Robo</option>
                                    <option value="ajuste">Ajuste de inventario</option>
                                    <option value="otro">Otro</option>
                                </select>
                            </div>

                            <div>
                                <button type="submit" class="px-6 py-2.5 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium transition">
                                    Registrar Movimiento
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Historial de movimientos -->
                    <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
                        <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-6">Movimientos de Inventario</h3>

                        <div class="grid gap-6 md:grid-cols-3 mb-8">
                            <div class="rounded-lg border border-zinc-200 p-4 dark:border-zinc-700">
                                <p class="text-sm text-zinc-600 dark:text-zinc-400 mb-2">Stock Total</p>
                                <p class="text-3xl font-bold text-zinc-900 dark:text-white">{{ $product->total_stock ?? 0 }}</p>
                            </div>
                            <div class="rounded-lg border border-zinc-200 p-4 dark:border-zinc-700">
                                <p class="text-sm text-zinc-600 dark:text-zinc-400 mb-2">Movimientos Totales</p>
                                <p class="text-3xl font-bold text-zinc-900 dark:text-white">{{ $product->inventoryMovements->count() }}</p>
                            </div>
                            <div class="rounded-lg border border-zinc-200 p-4 dark:border-zinc-700">
                                <p class="text-sm text-zinc-600 dark:text-zinc-400 mb-2">Último Movimiento</p>
                                <p class="text-sm text-zinc-900 dark:text-white font-medium">
                                    @if($product->inventoryMovements->latest()->first())
                                        {{ optional($product->inventoryMovements->latest()->first()->created_at)->format('d/m/Y H:i') }}
                                    @else
                                        Sin movimientos
                                    @endif
                                </p>
                            </div>
                        </div>

                        <h4 class="text-md font-semibold text-zinc-900 dark:text-white mb-4">Historial (Últimos 20)</h4>

                        @php
                            $movements = $product->inventoryMovements()->with(['variant', 'createdBy'])->latest()->limit(20)->get();
                        @endphp

                        @if($movements->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead class="border-b border-zinc-200 dark:border-zinc-700">
                                    <tr>
                                        <th class="px-4 py-3 text-left font-medium text-zinc-700 dark:text-zinc-300">Fecha</th>
                                        <th class="px-4 py-3 text-left font-medium text-zinc-700 dark:text-zinc-300">Tipo</th>
                                        <th class="px-4 py-3 text-left font-medium text-zinc-700 dark:text-zinc-300">Cantidad</th>
                                        <th class="px-4 py-3 text-left font-medium text-zinc-700 dark:text-zinc-300">Razón</th>
                                        <th class="px-4 py-3 text-left font-medium text-zinc-700 dark:text-zinc-300">Variante</th>
                                        <th class="px-4 py-3 text-left font-medium text-zinc-700 dark:text-zinc-300">Usuario</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800">
                                    @foreach($movements as $movement)
                                    <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50">
                                        <td class="px-4 py-3 text-zinc-900 dark:text-white text-xs">{{ optional($movement->created_at)->format('d/m/Y H:i') }}</td>
                                        <td class="px-4 py-3">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $movement->type === 'entrada' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300' }}">
                                                {{ ucfirst($movement->type) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 font-semibold text-zinc-900 dark:text-white">
                                            {{ $movement->type === 'entrada' ? '+' : '-' }}{{ $movement->quantity }}
                                        </td>
                                        <td class="px-4 py-3 text-zinc-700 dark:text-zinc-300 text-xs">{{ ucfirst($movement->reason ?? '—') }}</td>
                                        <td class="px-4 py-3 text-zinc-700 dark:text-zinc-300 text-xs">{{ $movement->variant?->name ?? 'Producto' }}</td>
                                        <td class="px-4 py-3 text-zinc-700 dark:text-zinc-300 text-xs">{{ $movement->createdBy?->name ?? 'Sistema' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="text-center py-8">
                            <p class="text-zinc-600 dark:text-zinc-400">Sin movimientos de inventario registrados</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('admin.products.variant-modal')

    <script>
        function switchTab(tabName) {
            // Hide all tabs
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.add('hidden');
            });
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('border-blue-600', 'text-blue-600', 'dark:text-blue-400', 'dark:border-blue-400');
                btn.classList.add('border-transparent', 'text-zinc-600', 'dark:text-zinc-400');
            });

            // Show selected tab
            document.getElementById(tabName).classList.remove('hidden');
            event.target.classList.remove('border-transparent', 'text-zinc-600', 'dark:text-zinc-400');
            event.target.classList.add('border-blue-600', 'text-blue-600', 'dark:text-blue-400', 'dark:border-blue-400');
        }

        function openAddVariantModal() {
            alert('Modal para agregar variante - En desarrollo');
        }

        function editVariant(variantId) {
            alert('Editar variante ' + variantId + ' - En desarrollo');
        }

        function deleteVariant(variantId) {
            if (confirm('¿Eliminar esta variante?')) {
                alert('Eliminando variante ' + variantId + ' - En desarrollo');
            }
        }
    </script>
</x-layouts.app>
