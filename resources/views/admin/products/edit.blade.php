<x-layouts.app :title="__('Editar producto')">
    <div class="flex-1">
        <div class="border-b border-zinc-200 bg-white px-6 py-4 dark:border-zinc-700 dark:bg-zinc-900">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Editar producto</h1>
                    <p class="text-sm text-zinc-600 dark:text-zinc-400">Actualiza la información del producto</p>
                </div>
                <a href="{{ route('dashboard.products.index') }}" class="rounded-lg border border-zinc-200 px-4 py-2 text-sm font-semibold text-zinc-900 hover:bg-zinc-100/50 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:border-zinc-700 dark:text-white dark:hover:bg-zinc-800 dark:focus:ring-offset-zinc-900">Volver al listado</a>
            </div>
        </div>

        <form action="{{ route('dashboard.products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="bg-white dark:bg-zinc-900">
            @csrf
            @method('PUT')
            <div class="grid gap-px border-b border-zinc-200 bg-zinc-200 dark:border-zinc-700 dark:bg-zinc-700/40 md:grid-cols-2">
                <!-- Información básica -->
                <div class="space-y-4 bg-white p-6 dark:bg-zinc-900">
                    <h2 class="text-lg font-semibold text-zinc-900 dark:text-white">Información básica</h2>
                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Nombre</label>
                            <input type="text" name="name" value="{{ old('name', $product->name) }}" class="w-full rounded-lg border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-900 placeholder-zinc-500 focus:border-pink-500 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:placeholder-zinc-400 dark:focus:ring-offset-zinc-900"/>
                            @error('name')<p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">SKU</label>
                            <input type="text" name="sku" value="{{ old('sku', $product->sku) }}" class="w-full rounded-lg border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-900 placeholder-zinc-500 focus:border-pink-500 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:placeholder-zinc-400 dark:focus:ring-offset-zinc-900"/>
                            @error('sku')<p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                        </div>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Descripción</label>
                        <textarea name="description" rows="4" class="w-full rounded-lg border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-900 placeholder-zinc-500 focus:border-pink-500 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:placeholder-zinc-400 dark:focus:ring-offset-zinc-900">{{ old('description', $product->description) }}</textarea>
                        @error('description')<p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                    </div>
                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Categoría</label>
                            <select name="category_id" class="w-full rounded-lg border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-pink-500 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:focus:ring-offset-zinc-900">
                                <option value="">Seleccionar categoría</option>
                                @foreach($categories ?? [] as $cat)
                                    <option value="{{ $cat->id }}" @selected(old('category_id', $product->category_id) == $cat->id)>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id')<p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Estado</label>
                            <select name="status" class="w-full rounded-lg border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-pink-500 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:focus:ring-offset-zinc-900">
                                <option value="published" @selected(old('status', $product->status) == 'published')>Publicado</option>
                                <option value="draft" @selected(old('status', $product->status) == 'draft')>Borrador</option>
                                <option value="out_of_stock" @selected(old('status', $product->status) == 'out_of_stock')>Agotado</option>
                            </select>
                            @error('status')<p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <!-- Precios e inventario -->
                <div class="space-y-4 bg-white p-6 dark:bg-zinc-900">
                    <h2 class="text-lg font-semibold text-zinc-900 dark:text-white">Precios e inventario</h2>
                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Precio</label>
                            <input type="number" name="price" step="0.01" min="0" value="{{ old('price', $product->price) }}" class="w-full rounded-lg border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-900 placeholder-zinc-500 focus:border-pink-500 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:placeholder-zinc-400 dark:focus:ring-offset-zinc-900"/>
                            @error('price')<p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Precio oferta</label>
                            <input type="number" name="sale_price" step="0.01" min="0" value="{{ old('sale_price', $product->sale_price) }}" placeholder="Opcional" class="w-full rounded-lg border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-900 placeholder-zinc-500 focus:border-pink-500 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:placeholder-zinc-400 dark:focus:ring-offset-zinc-900"/>
                            @error('sale_price')<p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Stock</label>
                            <input type="number" name="stock" min="0" value="{{ old('stock', $product->stock) }}" class="w-full rounded-lg border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-900 placeholder-zinc-500 focus:border-pink-500 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:placeholder-zinc-400 dark:focus:ring-offset-zinc-900"/>
                            @error('stock')<p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <!-- Imágenes -->
                <div class="space-y-4 bg-white p-6 dark:bg-zinc-900 md:col-span-2">
                    <h2 class="text-lg font-semibold text-zinc-900 dark:text-white">Imágenes</h2>
                    <div class="grid gap-4 md:grid-cols-4">
                        <div class="aspect-square rounded-lg border border-dashed border-zinc-300 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-800"></div>
                        <div class="aspect-square rounded-lg border border-dashed border-zinc-300 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-800"></div>
                        <div class="aspect-square rounded-lg border border-dashed border-zinc-300 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-800"></div>
                        <div class="aspect-square rounded-lg border border-dashed border-zinc-300 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-800"></div>
                    </div>
                    <div class="rounded-lg border border-dashed border-zinc-300 p-6 text-center text-sm text-zinc-600 dark:border-zinc-700 dark:text-zinc-400">
                        Arrastra imágenes aquí o <span class="font-semibold text-pink-600 dark:text-pink-500">haz clic para subir</span>
                    </div>
                </div>
            </div>

            <!-- Action bar -->
            <div class="sticky bottom-0 flex items-center justify-between border-t border-zinc-200 bg-white px-6 py-4 dark:border-zinc-700 dark:bg-zinc-900">
                <p class="text-xs text-zinc-500 dark:text-zinc-500">Última edición: {{ $product->updated_at->diffForHumans() }}</p>
                <div class="flex gap-2">
                    <a href="{{ route('dashboard.products.index') }}" class="rounded-lg border border-zinc-200 px-4 py-2 text-sm font-medium text-zinc-900 hover:bg-zinc-100/50 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:border-zinc-700 dark:text-white dark:hover:bg-zinc-800 dark:focus:ring-offset-zinc-900">Cancelar</a>
                    <button type="submit" class="rounded-lg bg-pink-600 px-4 py-2 text-sm font-semibold text-white hover:bg-pink-700 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-offset-2 dark:bg-pink-500 dark:hover:bg-pink-600 dark:focus:ring-offset-zinc-900">Guardar cambios</button>
                </div>
            </div>
        </form>
    </div>
</x-layouts.app>
