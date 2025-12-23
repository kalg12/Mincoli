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
                <button type="button" onclick="switchTab(event, 'general')" class="tab-btn py-4 px-1 border-b-2 border-blue-600 text-blue-600 font-medium dark:text-blue-400 dark:border-blue-400">
                    Información General
                </button>
                <button type="button" onclick="switchTab(event, 'variants')" class="tab-btn py-4 px-1 border-b-2 border-transparent text-zinc-600 font-medium hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-white">
                    Variantes ({{ $product->variants->count() }})
                </button>
                <button type="button" onclick="switchTab(event, 'inventory')" class="tab-btn py-4 px-1 border-b-2 border-transparent text-zinc-600 font-medium hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-white">
                    Inventario
                </button>
            </div>
        </div>

        <!-- Modal editar variante (ÚNICO) -->
        <div id="variantEditModal" class="hidden fixed inset-0 z-50 items-center justify-center">
            <div class="absolute inset-0 bg-black/60" onclick="closeVariantEditModal()"></div>
            <div class="relative bg-white dark:bg-zinc-900 rounded-xl shadow-xl w-full max-w-2xl mx-4 p-6 border border-zinc-200 dark:border-zinc-700">
                <div class="flex items-center justify-between mb-4">
                    <h4 class="text-lg font-semibold text-zinc-900 dark:text-white">Editar Variante</h4>
                    <button type="button" class="text-zinc-500 hover:text-zinc-700 dark:hover:text-zinc-300" onclick="closeVariantEditModal()">✕</button>
                </div>
                <form id="variantEditForm" method="POST" action="#" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Nombre <span class="text-red-500">*</span></label>
                            <input type="text" name="name" id="vef_name" class="w-full rounded-lg border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white" required>
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Talla</label>
                            <input type="text" name="size" id="vef_size" class="w-full rounded-lg border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white">
                        </div>
                    </div>
                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Color</label>
                            <div class="flex gap-2">
                                <input type="color" id="vef_color_picker" class="h-10 w-16 rounded border border-zinc-200 dark:border-zinc-700 cursor-pointer">
                                <input type="text" name="color" id="vef_color_text" placeholder="#ffffff" class="flex-1 rounded-lg border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white">
                            </div>
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Código de Barras</label>
                            <input type="text" name="barcode" id="vef_barcode" class="w-full rounded-lg border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white">
                        </div>
                    </div>
                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">SKU <span class="text-red-500">*</span></label>
                            <input type="text" name="sku" id="vef_sku" class="w-full rounded-lg border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white" required>
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Precio</label>
                            <input type="number" name="price" id="vef_price" step="0.01" class="w-full rounded-lg border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white">
                        </div>
                    </div>
                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Stock <span class="text-red-500">*</span></label>
                            <input type="number" name="stock" id="vef_stock" min="0" class="w-full rounded-lg border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white" required>
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Imagen (opcional)</label>
                            <input type="file" name="image" id="vef_image" accept="image/*" class="w-full rounded-lg border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white">
                        </div>
                    </div>
                    <div class="flex justify-end gap-2">
                        <button type="button" onclick="closeVariantEditModal()" class="rounded-lg border border-zinc-300 dark:border-zinc-600 px-4 py-2 text-sm text-zinc-700 dark:text-zinc-300 bg-white dark:bg-zinc-800 hover:bg-zinc-50 dark:hover:bg-zinc-700">Cancelar</button>
                        <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">Guardar cambios</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="px-6 pb-6">
            <div id="general" class="tab-content">
                <form id="productEditForm" method="POST" action="{{ route('dashboard.products.update', $product->id) }}" class="space-y-6" enctype="multipart/form-data">
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

                    <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
                        <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-4">Imágenes</h3>

                        <div class="grid md:grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <label class="mb-1.5 block text-sm font-medium text-zinc-900 dark:text-white">Subir imagen</label>

                                <input id="image_file_input" type="file" name="image" accept="image/*" @class([
                                    'w-full rounded-lg border bg-white px-4 py-2 text-sm text-zinc-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-zinc-800 dark:text-white dark:focus:ring-offset-zinc-900',
                                    'border-zinc-200 dark:border-zinc-700' => !$errors->has('image'),
                                    'border-red-500 dark:border-red-500' => $errors->has('image'),
                                ])>

                                @error('image')<p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                                <p class="text-xs text-zinc-500 dark:text-zinc-500">PNG, JPG, WEBP hasta 5MB.</p>
                                <div id="imageLocalPreviewCard" class="mt-2 hidden rounded-lg border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-3">
                                    <p class="text-xs text-zinc-600 dark:text-zinc-400 mb-2">Vista previa (no guardada)</p>
                                    <img id="imageLocalPreviewImg" alt="Vista previa" class="w-full h-40 object-contain">
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label class="mb-1.5 block text-sm font-medium text-zinc-900 dark:text-white">Enlace público (Google Drive)</label>

                                <input id="image_url" type="url" name="image_url" value="{{ old('image_url') }}" placeholder="https://drive.google.com/file/d/ID/view?usp=sharing" @class([
                                    'w-full rounded-lg border bg-white px-4 py-2 text-sm text-zinc-900 placeholder-zinc-500 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-zinc-800 dark:text-white dark:placeholder-zinc-400 dark:focus:ring-offset-zinc-900',
                                    'border-zinc-200 dark:border-zinc-700' => !$errors->has('image_url'),
                                    'border-red-500 dark:border-red-500' => $errors->has('image_url'),
                                ])>

                                @error('image_url')<p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                                <p class="text-xs text-zinc-500 dark:text-zinc-500">Pega el enlace público de Drive; se normaliza automáticamente.</p>
                                <p class="text-xs text-zinc-500 dark:text-zinc-500">Ejemplo: https://drive.google.com/file/d/abcdef123/view?usp=sharing</p>
                                <div id="imageUrlPreviewCard" class="mt-2 hidden rounded-lg border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-3">
                                    <div class="flex items-center gap-2 text-xs text-zinc-600 dark:text-zinc-400 mb-2">
                                        <span>Vista previa del enlace</span>
                                        <span id="imageUrlStatus" class="hidden animate-pulse text-blue-600 dark:text-blue-400">Cargando...</span>
                                    </div>
                                    <p id="imageUrlError" class="hidden text-xs text-red-600 dark:text-red-400 mb-2">No pudimos cargar la imagen. Verifica el enlace.</p>
                                    <img id="imageUrlPreviewImg" alt="Vista previa URL" class="w-full h-40 object-contain">
                                </div>
                            </div>
                        </div>

                        <p class="mt-3 text-xs text-zinc-500 dark:text-zinc-500">Las imágenes nuevas se añaden a las existentes (no se eliminan). La subida se coloca primero.</p>

                        @if($product->images->count() > 0)
                        <div id="currentImagesSection" class="mt-6">
                            <div class="flex items-center justify-between mb-3 gap-2">
                                <h4 class="text-sm font-semibold text-zinc-900 dark:text-white">Imágenes actuales</h4>
                                <div class="flex items-center gap-3 text-xs text-zinc-600 dark:text-zinc-400">
                                    <span>Arrastra para ordenar</span>
                                    <button type="button" id="saveImagesOrderBtn" class="hidden rounded-md bg-blue-600 text-white px-3 py-1.5 hover:bg-blue-700 text-xs font-semibold">Guardar orden</button>
                                </div>
                            </div>
                            <div id="currentImagesGrid" class="grid sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                                @foreach($product->images as $image)
                                <div data-image-card="1" data-image-id="{{ $image->id }}" draggable="true" class="rounded-lg border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 overflow-hidden flex flex-col transition-shadow hover:shadow-md">
                                    <div class="aspect-square bg-zinc-50 dark:bg-zinc-800 flex items-center justify-center p-4 cursor-move">
                                        <img src="{{ $image->url }}" alt="{{ $product->name }}" class="max-w-full max-h-full object-contain pointer-events-none select-none">
                                    </div>
                                    <div class="p-3 border-t border-zinc-200 dark:border-zinc-700 flex items-center justify-between gap-2">
                                        <span class="text-[11px] text-zinc-500 dark:text-zinc-400">ID: {{ $image->id }}</span>
                                        <button type="button"
                                                class="w-full px-3 py-2 text-xs font-medium rounded-lg bg-red-600 hover:bg-red-700 text-white transition-colors"
                                                data-delete-image-action="{{ route('dashboard.products.images.destroy', ['id' => $product->id, 'imageId' => $image->id]) }}"
                                                onclick="submitDeleteImage(this)">
                                            Eliminar
                                        </button>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
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
                            <button onclick="openAddVariantPanel()" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition">
                                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Agregar Variante
                            </button>
                        </div>

                        <!-- Panel inline para crear variante -->
                        <div id="variantCreatePanel" class="hidden mb-6 rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
                            <h4 class="text-md font-semibold text-zinc-900 dark:text-white mb-4">Crear Variante</h4>
                            <form id="variantInlineForm" method="POST" action="/dashboard/products/{{ $product->id }}/variants" class="space-y-4" enctype="multipart/form-data">
                                @csrf
                                <div class="grid gap-4 md:grid-cols-2">
                                    <div>
                                        <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Nombre <span class="text-red-500">*</span></label>
                                        <input type="text" name="name" class="w-full rounded-lg border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white" required>
                                    </div>
                                    <div>
                                        <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Talla</label>
                                        <input type="text" name="size" placeholder="XS, S, M, L, XL..." class="w-full rounded-lg border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white">
                                    </div>
                                </div>

                                <div class="grid gap-4 md:grid-cols-2">
                                    <div>
                                        <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Color</label>
                                        <div class="flex gap-2">
                                            <input type="color" id="variantColorPicker" name="color" class="h-10 w-16 rounded border border-zinc-200 dark:border-zinc-700 cursor-pointer">
                                            <input type="text" id="variantColorText" name="color_text" placeholder="#ffffff" class="flex-1 rounded-lg border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white">
                                        </div>
                                    </div>
                                    <div>
                                        <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Código de Barras</label>
                                        <input type="text" name="barcode" class="w-full rounded-lg border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white">
                                    </div>
                                </div>

                                <div class="grid gap-4 md:grid-cols-2">
                                    <div>
                                        <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">SKU <span class="text-red-500">*</span></label>
                                        <input type="text" name="sku" class="w-full rounded-lg border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white" required>
                                    </div>
                                    <div>
                                        <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Precio</label>
                                        <input type="number" name="price" step="0.01" placeholder="Usa el precio del producto si está vacío" class="w-full rounded-lg border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white">
                                    </div>
                                </div>

                                <div class="grid gap-4 md:grid-cols-2">
                                    <div>
                                        <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Stock <span class="text-red-500">*</span></label>
                                        <input type="number" name="stock" min="0" class="w-full rounded-lg border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white" required>
                                    </div>
                                    <div>
                                        <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Imagen de la variante (opcional)</label>
                                        <input type="file" name="image" accept="image/*" class="w-full rounded-lg border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white">
                                    </div>
                                </div>

                                <div class="flex justify-end gap-2">
                                    <button type="button" onclick="closeAddVariantPanel()" class="rounded-lg border border-zinc-300 dark:border-zinc-600 px-4 py-2 text-sm text-zinc-700 dark:text-zinc-300 bg-white dark:bg-zinc-800 hover:bg-zinc-50 dark:hover:bg-zinc-700">Cancelar</button>
                                    <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">Guardar Variante</button>
                                </div>
                            </form>
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
                                                    <span class="w-4 h-4 rounded border border-zinc-300 js-variant-color" data-color="{{ $variant->color }}"></span>
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
                                            <button type="button"
                                                data-variant-id="{{ $variant->id }}"
                                                data-name="{{ $variant->name }}"
                                                data-size="{{ $variant->size }}"
                                                data-color="{{ $variant->color }}"
                                                data-sku="{{ $variant->sku }}"
                                                data-barcode="{{ $variant->barcode }}"
                                                data-price="{{ $variant->price ?? '' }}"
                                                data-stock="{{ $variant->stock }}"
                                                class="js-edit-variant text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 text-sm font-medium">Editar</button>
                                            <button type="button" data-variant-id="{{ $variant->id }}" class="js-delete-variant text-red-600 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 text-sm font-medium ml-3">Eliminar</button>
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
                            <button onclick="openAddVariantPanel()" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition">
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
                                        <option value="in">Entrada (compra/devolución)</option>
                                        <option value="out">Salida (venta/daño)</option>
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
                                    @if($product->inventoryMovements->first())
                                        {{ optional($product->inventoryMovements->first()->created_at)->format('d/m/Y H:i') }}
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
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $movement->type === 'in' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300' : ($movement->type === 'out' ? 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300' : 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300') }}">
                                                {{ $movement->type === 'in' ? 'Entrada' : ($movement->type === 'out' ? 'Salida' : 'Ajuste') }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 font-semibold text-zinc-900 dark:text-white">
                                            {{ $movement->type === 'in' ? '+' : '-' }}{{ $movement->quantity }}
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

    <script>
        async function submitDeleteImage(button) {
            var action = button.getAttribute('data-delete-image-action');
            if (!action) return;
            if (!confirm('¿Eliminar esta imagen?')) return;

            button.disabled = true;
            button.classList.add('opacity-60', 'cursor-not-allowed');

            try {
                var fd = new FormData();
                fd.append('_token', '{{ csrf_token() }}');
                fd.append('_method', 'DELETE');

                var res = await fetch(action, {
                    method: 'POST',
                    body: fd,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json, text/plain, */*'
                    },
                    redirect: 'follow'
                });

                if (!res.ok) {
                    throw new Error('Delete failed: ' + res.status);
                }

                var card = button.closest('[data-image-card]');
                if (card) {
                    card.remove();
                }

                var grid = document.getElementById('currentImagesGrid');
                var section = document.getElementById('currentImagesSection');
                if (grid && section) {
                    var remaining = grid.querySelectorAll('[data-image-card]').length;
                    if (remaining === 0) {
                        section.classList.add('hidden');
                    }
                }
            } catch (err) {
                console.error(err);

                var form = document.createElement('form');
                form.method = 'POST';
                form.action = action;
                form.style.display = 'none';

                var token = document.createElement('input');
                token.type = 'hidden';
                token.name = '_token';
                token.value = '{{ csrf_token() }}';

                var method = document.createElement('input');
                method.type = 'hidden';
                method.name = '_method';
                method.value = 'DELETE';

                form.appendChild(token);
                form.appendChild(method);
                document.body.appendChild(form);
                form.submit();
            } finally {
                if (button && button.classList) {
                    button.disabled = false;
                    button.classList.remove('opacity-60', 'cursor-not-allowed');
                }
            }
        }

        function switchTab(e, tabName) {
            document.querySelectorAll('.tab-content').forEach(tab => tab.classList.add('hidden'));
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('border-blue-600', 'text-blue-600', 'dark:text-blue-400', 'dark:border-blue-400');
                btn.classList.add('border-transparent', 'text-zinc-600', 'dark:text-zinc-400');
            });

            document.getElementById(tabName).classList.remove('hidden');
            const activeBtn = e.currentTarget;
            if (activeBtn) {
                activeBtn.classList.remove('border-transparent', 'text-zinc-600', 'dark:text-zinc-400');
                activeBtn.classList.add('border-blue-600', 'text-blue-600', 'dark:text-blue-400', 'dark:border-blue-400');
            }
        }

        function openAddVariantPanel() {
            const panel = document.getElementById('variantCreatePanel');
            if (panel) {
                panel.classList.remove('hidden');

                const picker = document.getElementById('variantColorPicker');
                const text = document.getElementById('variantColorText');
                if (picker && text) {
                    if (!panel.dataset.bound) {
                        panel.dataset.bound = '1';
                        picker.addEventListener('input', function (e) {
                            const target = e.target;
                            if (target && 'value' in target) {
                                text.value = target.value;
                            }
                        });
                        text.addEventListener('input', function (e) {
                            const target = e.target;
                            if (target && 'value' in target) {
                                if (/^#[0-9A-F]{6}$/i.test(target.value)) {
                                    picker.value = target.value;
                                }
                            }
                        });
                    }
                }

                panel.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        }

        function closeAddVariantPanel() {
            const panel = document.getElementById('variantCreatePanel');
            if (panel) panel.classList.add('hidden');
        }

        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.js-variant-color').forEach(function(el){
                var c = el.getAttribute('data-color');
                if (c) { el.style.backgroundColor = c; }
            });

            var baseVariantsUrl = '/dashboard/products/{{ $product->id }}/variants';

            document.querySelectorAll('.js-delete-variant').forEach(function(btn){
                btn.addEventListener('click', function(){
                    var id = this.getAttribute('data-variant-id');
                    if (!id) return;
                    if (!confirm('¿Eliminar esta variante?')) return;
                    var form = document.createElement('form');
                    form.method = 'POST';
                    form.action = baseVariantsUrl + '/' + id;
                    form.style.display = 'none';
                    var token = document.createElement('input');
                    token.type = 'hidden'; token.name = '_token'; token.value = '{{ csrf_token() }}';
                    var method = document.createElement('input');
                    method.type = 'hidden'; method.name = '_method'; method.value = 'DELETE';
                    form.appendChild(token); form.appendChild(method);
                    document.body.appendChild(form);
                    form.submit();
                });
            });

            document.querySelectorAll('.js-edit-variant').forEach(function(btn){
                btn.addEventListener('click', function(){ editVariant(this); });
            });

            // Vista previa de imagen local
            var fileInput = document.getElementById('image_file_input');
            var fileCard = document.getElementById('imageLocalPreviewCard');
            var fileImg = document.getElementById('imageLocalPreviewImg');
            if (fileInput && fileCard && fileImg && !fileInput.dataset.bound) {
                fileInput.dataset.bound = '1';
                fileInput.addEventListener('change', function(){
                    var f = fileInput.files && fileInput.files[0] ? fileInput.files[0] : null;
                    if (!f) {
                        fileCard.classList.add('hidden');
                        return;
                    }
                    var url = URL.createObjectURL(f);
                    fileImg.src = url;
                    fileCard.classList.remove('hidden');
                });
            }

            // Bind color sync for the Variant Edit modal ONCE (avoid stacking listeners)
            var vefPicker = document.getElementById('vef_color_picker');
            var vefText = document.getElementById('vef_color_text');
            if (vefPicker && vefText && !vefPicker.dataset.bound) {
                vefPicker.dataset.bound = '1';

                vefPicker.addEventListener('input', function (e) {
                    var t = e && e.target ? e.target : null;
                    if (t && 'value' in t) {
                        vefText.value = t.value;
                    }
                });

                vefText.addEventListener('input', function (e) {
                    var t = e && e.target ? e.target : null;
                    if (t && 'value' in t) {
                        if (/^#[0-9A-F]{6}$/i.test(t.value)) {
                            vefPicker.value = t.value;
                        }
                    }
                });
            }

            // Normalize Google Drive "view" links into a direct download format (client-side helper)
            function bindUrlPreview(opts) {
                var input = document.getElementById(opts.inputId);
                var card = document.getElementById(opts.cardId);
                var img = document.getElementById(opts.imgId);
                var statusEl = document.getElementById(opts.statusId);
                var errorEl = document.getElementById(opts.errorId);
                if (!input || !card || !img || input.dataset.bound) return;
                input.dataset.bound = '1';

                function extractDriveId(u) {
                    if (!u) return '';
                    var m = String(u).match(/\/file\/d\/([^\/]+)/);
                    if (m && m[1]) return m[1];
                    try { var urlObj = new URL(u); return urlObj.searchParams.get('id') || ''; } catch(_) { return ''; }
                }
                function normalizeDriveUrl(u) { var id = extractDriveId(u); return id ? ('https://drive.google.com/uc?export=download&id=' + id) : u; }

                function showLoading() { if (statusEl) statusEl.classList.remove('hidden'); if (errorEl) errorEl.classList.add('hidden'); }
                function hideLoading() { if (statusEl) statusEl.classList.add('hidden'); }
                function showError() { if (errorEl) errorEl.classList.remove('hidden'); }
                function hideError() { if (errorEl) errorEl.classList.add('hidden'); }

                function loadPreview() {
                    var v = input.value ? input.value.trim() : '';
                    if (!v) return;
                    if (v.includes('drive.google.com')) { v = normalizeDriveUrl(v); input.value = v; }
                    hideError();
                    showLoading();
                    var triedAlt = false;
                    img.onload = function(){ hideLoading(); card.classList.remove('hidden'); };
                    img.onerror = function(){
                        if (!triedAlt && v.includes('drive.google.com')) {
                            triedAlt = true;
                            img.src = v.replace('export=download', 'export=view');
                            return;
                        }
                        hideLoading();
                        showError();
                    };
                    img.src = v;
                    card.classList.remove('hidden');
                }

                input.addEventListener('blur', loadPreview);
                input.addEventListener('change', loadPreview);
            }

            bindUrlPreview({
                inputId: 'image_url',
                cardId: 'imageUrlPreviewCard',
                imgId: 'imageUrlPreviewImg',
                statusId: 'imageUrlStatus',
                errorId: 'imageUrlError'
            });

            // Drag & drop ordering
            var grid = document.getElementById('currentImagesGrid');
            var saveBtn = document.getElementById('saveImagesOrderBtn');
            var draggingEl = null;

            function showSaveBtn() {
                if (saveBtn) saveBtn.classList.remove('hidden');
            }

            function bindDraggable(card) {
                card.addEventListener('dragstart', function (e) {
                    draggingEl = card;
                    card.classList.add('ring-2', 'ring-blue-500');
                    e.dataTransfer.effectAllowed = 'move';
                });
                card.addEventListener('dragend', function () {
                    draggingEl = null;
                    card.classList.remove('ring-2', 'ring-blue-500');
                });
                card.addEventListener('dragover', function (e) {
                    e.preventDefault();
                    if (!draggingEl || draggingEl === card) return;
                    var rect = card.getBoundingClientRect();
                    var offset = e.clientY - rect.top;
                    var half = rect.height / 2;
                    if (offset > half) {
                        card.after(draggingEl);
                    } else {
                        card.before(draggingEl);
                    }
                    showSaveBtn();
                });
            }

            if (grid) {
                grid.querySelectorAll('[data-image-card]').forEach(bindDraggable);
            }

            if (saveBtn) {
                saveBtn.addEventListener('click', async function () {
                    if (!grid) return;
                    var ids = Array.from(grid.querySelectorAll('[data-image-card]')).map(function (c) {
                        return c.getAttribute('data-image-id');
                    }).filter(Boolean);
                    if (ids.length === 0) return;
                    saveBtn.disabled = true;
                    saveBtn.textContent = 'Guardando...';
                    try {
                        var res = await fetch('{{ route('dashboard.products.images.reorder', $product->id) }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ order: ids })
                        });
                        if (!res.ok) throw new Error('Error HTTP ' + res.status);
                        saveBtn.textContent = 'Orden guardado';
                        setTimeout(function(){
                            saveBtn.textContent = 'Guardar orden';
                            saveBtn.classList.add('hidden');
                        }, 1200);
                    } catch (err) {
                        console.error(err);
                        alert('No se pudo guardar el orden. Intenta nuevamente.');
                        saveBtn.textContent = 'Guardar orden';
                    } finally {
                        saveBtn.disabled = false;
                    }
                });
            }
        });

        function editVariant(el){
            var id = el.getAttribute('data-variant-id');
            var name = el.getAttribute('data-name');
            if (!name) {
                var tr = el.closest ? el.closest('tr') : null;
                var firstTd = tr ? tr.querySelector('td:nth-child(1)') : null;
                name = firstTd && firstTd.textContent ? firstTd.textContent.trim() : '';
            }
            var color = el.getAttribute('data-color') || '';
            var size = el.getAttribute('data-size') || '';
            var sku = el.getAttribute('data-sku') || '';
            var barcode = el.getAttribute('data-barcode') || '';
            var price = el.getAttribute('data-price') || '';
            var stock = el.getAttribute('data-stock') || '';

            var modal = document.getElementById('variantEditModal');
            var form = document.getElementById('variantEditForm');
            var baseVariantsUrl = '/dashboard/products/{{ $product->id }}/variants';
            form.action = baseVariantsUrl + '/' + id;

            document.getElementById('vef_name').value = name || '';
            document.getElementById('vef_size').value = size || '';
            document.getElementById('vef_sku').value = sku || '';
            document.getElementById('vef_barcode').value = barcode || '';
            document.getElementById('vef_price').value = price || '';
            document.getElementById('vef_stock').value = stock || 0;

            var picker = document.getElementById('vef_color_picker');
            var text = document.getElementById('vef_color_text');
            if (color) {
                text.value = color;
                if (/^#[0-9A-Fa-f]{6}$/.test(color)) picker.value = color;
            } else {
                text.value = '';
            }

            if (modal) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }
        }

        function closeVariantEditModal(){
            var modal = document.getElementById('variantEditModal');
            if (modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
        }
    </script>
</x-layouts.app>
