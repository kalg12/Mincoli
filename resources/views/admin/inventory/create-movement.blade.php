<x-layouts.app :title="__('Registrar Movimiento')">
    <div class="p-6 grid gap-6">
        <h1 class="text-2xl font-semibold">Registrar movimiento</h1>

        <form method="POST" action="{{ route('dashboard.inventory.movements.store') }}" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @csrf

            <div>
                <label class="block text-sm mb-1">Producto</label>
                <select name="product_id" class="w-full border rounded px-3 py-2 dark:bg-zinc-900 dark:border-zinc-700" required>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm mb-1">Variante (opcional)</label>
                <select name="variant_id" class="w-full border rounded px-3 py-2 dark:bg-zinc-900 dark:border-zinc-700">
                    <option value="">N/A</option>
                    @foreach($products as $product)
                        @foreach($product->variants as $variant)
                            <option value="{{ $variant->id }}">{{ $product->name }} - {{ $variant->name }}</option>
                        @endforeach
                    @endforeach
                </select>
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
</x-layouts.app>
