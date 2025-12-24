<x-layouts.app :title="__('Registrar Movimiento')">
    <div class="p-6 grid gap-6">
        <h1 class="text-2xl font-semibold">Registrar movimiento</h1>

        <form method="POST" action="{{ route('dashboard.inventory.movements.store') }}" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @csrf

            <div>
                <label class="block text-sm mb-1">Producto</label>
                <select id="productSelect" name="product_id" class="w-full border rounded px-3 py-2 dark:bg-zinc-900 dark:border-zinc-700" required>
                    <option value="">-- Seleccionar producto --</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm mb-1">Variante (opcional)</label>
                <div id="variantField">
                    <select id="variantSelect" name="variant_id" class="w-full border rounded px-3 py-2 dark:bg-zinc-900 dark:border-zinc-700">
                        <option value="">Seleccione variante</option>
                    </select>
                </div>
                <div id="noVariantInfo" class="hidden mt-2 rounded-lg border border-zinc-200 bg-white p-3 text-sm text-zinc-700 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-300">
                    <div class="flex items-center gap-2">
                        <svg class="h-4 w-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Este producto no tiene variantes. Se usará <span class="font-semibold">stock general</span>.</span>
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-sm mb-1">Tipo</label>
                <select name="type" class="w-full border rounded px-3 py-2 dark:bg-zinc-900 dark:border-zinc-700" required>
                    <option value="in">Entrada</option>
                    <option value="out">Salida</option>
                    <option value="adjust">Ajuste</option>
                </select>
            </div>

            <div>
                <label class="block text-sm mb-1">Cantidad</label>
                <input type="number" name="quantity" min="1" class="w-full border rounded px-3 py-2 dark:bg-zinc-900 dark:border-zinc-700" required />
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm mb-1">Motivo</label>
                <input type="text" name="reason" class="w-full border rounded px-3 py-2 dark:bg-zinc-900 dark:border-zinc-700" required />
            </div>

            <div class="md:col-span-2 flex gap-3">
                <button class="px-4 py-2 rounded bg-primary-600 text-white">Guardar</button>
                <a href="{{ route('dashboard.inventory.movements') }}" class="px-4 py-2 rounded border dark:border-zinc-700">Cancelar</a>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const productSelect = document.getElementById('productSelect');
            const variantSelect = document.getElementById('variantSelect');
            const variantField = document.getElementById('variantField');
            const noVariantInfo = document.getElementById('noVariantInfo');

            // Datos de productos con sus variantes
            const productsData = JSON.parse('{!! json_encode($products) !!}');
            const variantsByProduct = {};

            productsData.forEach(product => {
                variantsByProduct[product.id] = product.variants || [];
            });

            function updateVariants() {
                const productId = productSelect.value;
                const variants = variantsByProduct[productId] || [];

                if (variants.length > 0) {
                    // Mostrar select de variantes
                    if (variantField) variantField.classList.remove('hidden');
                    if (noVariantInfo) noVariantInfo.classList.add('hidden');
                    variantSelect.disabled = false;
                    variantSelect.innerHTML = '<option value="">Seleccione variante</option>';
                    variants.forEach(variant => {
                        const option = document.createElement('option');
                        option.value = variant.id;
                        option.textContent = variant.name;
                        variantSelect.appendChild(option);
                    });
                } else {
                    // No hay variantes: usar stock general y mostrar mensaje bonito
                    if (variantField) variantField.classList.add('hidden');
                    if (noVariantInfo) noVariantInfo.classList.remove('hidden');
                    variantSelect.disabled = true;
                    variantSelect.innerHTML = '';
                }
            }

            productSelect.addEventListener('change', updateVariants);
            // Estado inicial
            updateVariants();
        });
    </script>
</x-layouts.app>
