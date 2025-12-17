<x-layouts.app :title="__('Nuevo Producto')">
    <div class="p-6 space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Nuevo producto</h1>
                <p class="text-sm text-zinc-600 dark:text-zinc-400">Agrega variantes, precios e imágenes</p>
            </div>
            <a href="{{ route('dashboard.products.index') }}" class="rounded-lg border border-zinc-200 px-4 py-2 text-sm font-semibold text-zinc-900 hover:bg-zinc-50 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:border-zinc-700 dark:text-white dark:hover:bg-zinc-800 dark:focus:ring-offset-zinc-900">
                Volver al catálogo
            </a>
        </div>

        <form action="{{ route('dashboard.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            <!-- Información básica -->
            <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
                <h2 class="mb-4 text-lg font-semibold text-zinc-900 dark:text-white">Información básica</h2>
                <div class="space-y-4">
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-zinc-900 dark:text-white">Nombre del producto</label>
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="Ej: Collar Aura" required class="w-full rounded-lg border @error('name') border-red-500 @else border-zinc-200 @enderror bg-white px-4 py-2 text-sm text-zinc-900 placeholder-zinc-500 focus:border-pink-500 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:placeholder-zinc-400 dark:focus:ring-offset-zinc-900">
                        @error('name')<p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-zinc-900 dark:text-white">Descripción</label>
                        <textarea name="description" rows="4" placeholder="Describe las características del producto..." class="w-full rounded-lg border @error('description') border-red-500 @else border-zinc-200 @enderror bg-white px-4 py-2 text-sm text-zinc-900 placeholder-zinc-500 focus:border-pink-500 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:placeholder-zinc-400 dark:focus:ring-offset-zinc-900">{{ old('description') }}</textarea>
                        @error('description')<p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-zinc-900 dark:text-white">SKU</label>
                            <input type="text" name="sku" value="{{ old('sku') }}" placeholder="COL-001" required class="w-full rounded-lg border @error('sku') border-red-500 @else border-zinc-200 @enderror bg-white px-4 py-2 text-sm text-zinc-900 placeholder-zinc-500 focus:border-pink-500 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:placeholder-zinc-400 dark:focus:ring-offset-zinc-900">
                            @error('sku')<p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-zinc-900 dark:text-white">Categoría</label>
                            <select name="category_id" required class="w-full rounded-lg border @error('category_id') border-red-500 @else border-zinc-200 @enderror bg-white px-4 py-2 text-sm text-zinc-900 focus:border-pink-500 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:focus:ring-offset-zinc-900">
                                <option value="">Seleccionar categoría</option>
                                @foreach($categories ?? [] as $cat)
                                    <option value="{{ $cat->id }}" @selected(old('category_id') == $cat->id)>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id')<p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Precios e inventario -->
            <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
                <h2 class="mb-4 text-lg font-semibold text-zinc-900 dark:text-white">Precios e inventario</h2>
                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-zinc-900 dark:text-white">Precio</label>
                        <input type="number" name="price" step="0.01" min="0" value="{{ old('price') }}" placeholder="0.00" required class="w-full rounded-lg border @error('price') border-red-500 @else border-zinc-200 @enderror bg-white px-4 py-2 text-sm text-zinc-900 placeholder-zinc-500 focus:border-pink-500 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:placeholder-zinc-400 dark:focus:ring-offset-zinc-900">
                        @error('price')<p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-zinc-900 dark:text-white">Precio oferta</label>
                        <input type="number" name="sale_price" step="0.01" min="0" value="{{ old('sale_price') }}" placeholder="0.00" class="w-full rounded-lg border @error('sale_price') border-red-500 @else border-zinc-200 @enderror bg-white px-4 py-2 text-sm text-zinc-900 placeholder-zinc-500 focus:border-pink-500 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:placeholder-zinc-400 dark:focus:ring-offset-zinc-900">
                        @error('sale_price')<p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-zinc-900 dark:text-white">Stock</label>
                        <input type="number" name="stock" min="0" value="{{ old('stock') }}" placeholder="0" required class="w-full rounded-lg border @error('stock') border-red-500 @else border-zinc-200 @enderror bg-white px-4 py-2 text-sm text-zinc-900 placeholder-zinc-500 focus:border-pink-500 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:placeholder-zinc-400 dark:focus:ring-offset-zinc-900">
                        @error('stock')<p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                    </div>
                </div>
                <div class="mt-4">
                    <label class="mb-1.5 block text-sm font-medium text-zinc-900 dark:text-white">Estado</label>
                    <select name="status" required class="w-full rounded-lg border @error('status') border-red-500 @else border-zinc-200 @enderror bg-white px-4 py-2 text-sm text-zinc-900 focus:border-pink-500 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:focus:ring-offset-zinc-900">
                        <option value="published" @selected(old('status') == 'published')>Publicado</option>
                        <option value="draft" @selected(old('status') == 'draft' || !old('status'))>Borrador</option>
                        <option value="out_of_stock" @selected(old('status') == 'out_of_stock')>Agotado</option>
                    </select>
                    @error('status')<p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                </div>
            </div>

            <!-- Imágenes -->
            <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
                <h2 class="mb-4 text-lg font-semibold text-zinc-900 dark:text-white">Imágenes</h2>
                <div class="flex items-center justify-center rounded-lg border-2 border-dashed border-zinc-300 bg-zinc-50 px-6 py-12 dark:border-zinc-700 dark:bg-zinc-800">
                    <div class="text-center">
                        <svg class="mx-auto h-12 w-12 text-zinc-400 dark:text-zinc-500" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <div class="mt-4">
                            <button type="button" class="rounded-lg bg-pink-600 px-4 py-2 text-sm font-semibold text-white hover:bg-pink-700 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-offset-2 dark:bg-pink-500 dark:hover:bg-pink-600 dark:focus:ring-offset-zinc-900">
                                Subir imágenes
                            </button>
                        </div>
                        <p class="mt-2 text-xs text-zinc-500 dark:text-zinc-500">PNG, JPG, WEBP hasta 10MB</p>
                    </div>
                </div>
            </div>

            <!-- Botones de acción -->
            <div class="flex justify-end gap-3">
                <a href="{{ route('dashboard.products.index') }}" class="rounded-lg border border-zinc-200 px-4 py-2 text-sm font-semibold text-zinc-900 hover:bg-zinc-50 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:border-zinc-700 dark:text-white dark:hover:bg-zinc-800 dark:focus:ring-offset-zinc-900">
                    Cancelar
                </a>
                <button type="submit" class="rounded-lg bg-pink-600 px-4 py-2 text-sm font-semibold text-white hover:bg-pink-700 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-offset-2 dark:bg-pink-500 dark:hover:bg-pink-600 dark:focus:ring-offset-zinc-900">
                    Crear producto
                </button>
            </div>
        </form>
    </div>
</x-layouts.app>
