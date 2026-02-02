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
            @php
                $borderClass = function (string $field) use ($errors): string {
                    return $errors->has($field) ? 'border-red-500' : 'border-zinc-200';
                };
            @endphp
            <!-- Información básica -->
            <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
                <h2 class="mb-4 text-lg font-semibold text-zinc-900 dark:text-white">Información básica</h2>
                <div class="space-y-4">
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-zinc-900 dark:text-white">Nombre del producto</label>
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="Ej: Collar Aura" required class="w-full rounded-lg border {{ $borderClass('name') }} bg-white px-4 py-2 text-sm text-zinc-900 placeholder-zinc-500 focus:border-pink-500 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:placeholder-zinc-400 dark:focus:ring-offset-zinc-900">
                        @error('name')<p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-zinc-900 dark:text-white">Descripción</label>
                        <textarea name="description" rows="4" placeholder="Describe las características del producto..." class="w-full rounded-lg border {{ $borderClass('description') }} bg-white px-4 py-2 text-sm text-zinc-900 placeholder-zinc-500 focus:border-pink-500 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:placeholder-zinc-400 dark:focus:ring-offset-zinc-900">{{ old('description') }}</textarea>
                        @error('description')<p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                    </div>
                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-zinc-900 dark:text-white">SKU</label>
                            <input type="text" name="sku" value="{{ old('sku') }}" placeholder="COL-001" required class="w-full rounded-lg border {{ $borderClass('sku') }} bg-white px-4 py-2 text-sm text-zinc-900 placeholder-zinc-500 focus:border-pink-500 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:placeholder-zinc-400 dark:focus:ring-offset-zinc-900">
                            @error('sku')<p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-zinc-900 dark:text-white">Código de barras <span class="text-xs text-zinc-500">(opcional)</span></label>
                            <input type="text" name="barcode" value="{{ old('barcode') }}" placeholder="123456789012" class="w-full rounded-lg border {{ $borderClass('barcode') }} bg-white px-4 py-2 text-sm text-zinc-900 placeholder-zinc-500 focus:border-pink-500 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:placeholder-zinc-400 dark:focus:ring-offset-zinc-900">
                            @error('barcode')<p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-zinc-900 dark:text-white">Categoría Principal</label>
                                <select name="category_id" id="category_id" required class="w-full rounded-lg border {{ $borderClass('category_id') }} bg-white px-4 py-2 text-sm text-zinc-900 focus:border-pink-500 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:focus:ring-offset-zinc-900">
                                    <option value="">Seleccionar categoría</option>
                                    @foreach($categories ?? [] as $cat)
                                        <option value="{{ $cat->id }}" @selected(old('category_id') == $cat->id)>{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                                @error('category_id')<p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-zinc-900 dark:text-white">Subcategoría <span class="text-xs text-zinc-500">(opcional)</span></label>
                                <select name="subcategory_id" id="subcategory_id" class="w-full rounded-lg border {{ $borderClass('subcategory_id') }} bg-white px-4 py-2 text-sm text-zinc-900 focus:border-pink-500 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:focus:ring-offset-zinc-900">
                                    <option value="">Seleccionar subcategoría</option>
                                </select>
                                @error('subcategory_id')<p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                            </div>
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
                        <input type="number" name="price" step="0.01" min="0" value="{{ old('price') }}" placeholder="0.00" required class="w-full rounded-lg border {{ $borderClass('price') }} bg-white px-4 py-2 text-sm text-zinc-900 placeholder-zinc-500 focus:border-pink-500 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:placeholder-zinc-400 dark:focus:ring-offset-zinc-900">
                        @error('price')<p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-zinc-900 dark:text-white">Precio oferta</label>
                        <input type="number" name="sale_price" step="0.01" min="0" value="{{ old('sale_price') }}" placeholder="0.00" class="w-full rounded-lg border {{ $borderClass('sale_price') }} bg-white px-4 py-2 text-sm text-zinc-900 placeholder-zinc-500 focus:border-pink-500 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:placeholder-zinc-400 dark:focus:ring-offset-zinc-900">
                        @error('sale_price')<p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-zinc-900 dark:text-white">Stock</label>
                        <input type="number" name="stock" min="0" value="{{ old('stock') }}" placeholder="0" required class="w-full rounded-lg border {{ $borderClass('stock') }} bg-white px-4 py-2 text-sm text-zinc-900 placeholder-zinc-500 focus:border-pink-500 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:placeholder-zinc-400 dark:focus:ring-offset-zinc-900">
                        @error('stock')<p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                    </div>
                </div>
                <div class="mt-4">
                    <label class="mb-1.5 block text-sm font-medium text-zinc-900 dark:text-white">Estado</label>
                    <select name="status" required class="w-full rounded-lg border {{ $borderClass('status') }} bg-white px-4 py-2 text-sm text-zinc-900 focus:border-pink-500 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:focus:ring-offset-zinc-900">
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
                <div class="grid md:grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="mb-1.5 block text-sm font-medium text-zinc-900 dark:text-white">Subir imagen</label>
                        <input id="create_image_file_input" type="file" name="image" accept="image/*" class="w-full rounded-lg border {{ $borderClass('image') }} bg-white px-4 py-2 text-sm text-zinc-900 focus:border-pink-500 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:focus:ring-offset-zinc-900">
                        @error('image')<p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                        <p class="text-xs text-zinc-500 dark:text-zinc-500">PNG, JPG, WEBP hasta 5MB.</p>
                        <div id="createImageLocalPreviewCard" class="mt-2 hidden rounded-lg border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-3">
                            <p class="text-xs text-zinc-600 dark:text-zinc-400 mb-2">Vista previa (no guardada)</p>
                            <img id="createImageLocalPreviewImg" alt="Vista previa" class="w-full h-40 object-contain">
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label class="mb-1.5 block text-sm font-medium text-zinc-900 dark:text-white">Enlace público (Google Drive)</label>
                        <input id="create_image_url" type="url" name="image_url" value="{{ old('image_url') }}" placeholder="https://drive.google.com/file/d/ID/view?usp=sharing" class="w-full rounded-lg border {{ $borderClass('image_url') }} bg-white px-4 py-2 text-sm text-zinc-900 placeholder-zinc-500 focus:border-pink-500 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:placeholder-zinc-400 dark:focus:ring-offset-zinc-900">
                        @error('image_url')<p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                        <p class="text-xs text-zinc-500 dark:text-zinc-500">Puedes pegar el enlace tal cual de Drive (público). Lo convertimos a vista directa automáticamente.</p>
                        <p class="text-xs text-zinc-500 dark:text-zinc-500">Ejemplo: https://drive.google.com/file/d/abcdef123/view?usp=sharing</p>
                        <div id="createImageUrlPreviewCard" class="mt-2 hidden rounded-lg border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-3">
                            <div class="flex items-center gap-2 text-xs text-zinc-600 dark:text-zinc-400 mb-2">
                                <span>Vista previa del enlace</span>
                                <span id="createImageUrlStatus" class="hidden animate-pulse text-blue-600 dark:text-blue-400">Cargando...</span>
                            </div>
                            <p id="createImageUrlError" class="hidden text-xs text-red-600 dark:text-red-400 mb-2">No pudimos cargar la imagen. Verifica el enlace.</p>
                            <img id="createImageUrlPreviewImg" alt="Vista previa URL" class="w-full h-40 object-contain">
                        </div>
                    </div>
                </div>
                <p class="mt-3 text-xs text-zinc-500 dark:text-zinc-500">Puedes usar cualquiera de las dos opciones o ambas; si subes archivo será la primera imagen.</p>
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

    <script>
    document.addEventListener('DOMContentLoaded', function(){
        const categories = @json($categories);
        const categorySelect = document.getElementById('category_id');
        const subcategorySelect = document.getElementById('subcategory_id');
        const oldSubcategoryId = "{{ old('subcategory_id') }}";

        function updateSubcategories() {
            const categoryId = categorySelect.value;
            const category = categories.find(c => c.id == categoryId);
            
            subcategorySelect.innerHTML = '<option value="">Seleccionar subcategoría</option>';
            
            if (category && category.children && category.children.length > 0) {
                category.children.forEach(sub => {
                    const option = document.createElement('option');
                    option.value = sub.id;
                    option.textContent = sub.name;
                    if (sub.id == oldSubcategoryId) {
                        option.selected = true;
                    }
                    subcategorySelect.appendChild(option);
                });
            }
        }

        categorySelect.addEventListener('change', updateSubcategories);
        if (categorySelect.value) {
            updateSubcategories();
        }

        var fileInput = document.getElementById('create_image_file_input');
        var fileCard = document.getElementById('createImageLocalPreviewCard');
        var fileImg = document.getElementById('createImageLocalPreviewImg');
        if (fileInput && fileCard && fileImg && !fileInput.dataset.bound) {
            fileInput.dataset.bound = '1';
            fileInput.addEventListener('change', function(){
                var f = fileInput.files && fileInput.files[0] ? fileInput.files[0] : null;
                if (!f) { fileCard.classList.add('hidden'); return; }
                var url = URL.createObjectURL(f);
                fileImg.src = url;
                fileCard.classList.remove('hidden');
            });
        }

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
            inputId: 'create_image_url',
            cardId: 'createImageUrlPreviewCard',
            imgId: 'createImageUrlPreviewImg',
            statusId: 'createImageUrlStatus',
            errorId: 'createImageUrlError'
        });
    });
    </script>
</x-layouts.app>
