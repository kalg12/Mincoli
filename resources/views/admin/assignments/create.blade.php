<x-layouts.app title="Nueva Asignación">
    <div class="flex-1">
        <div class="border-b border-zinc-200 bg-white px-6 py-4 dark:border-zinc-700 dark:bg-zinc-900 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Nueva Asignación de Producto</h1>
            <a href="{{ route('dashboard.assignments.index') }}" class="text-sm font-medium text-zinc-600 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-white">Volver</a>
        </div>

        <div class="p-6 max-w-2xl">
            <div class="rounded-lg border border-zinc-200 bg-white shadow-sm dark:border-zinc-700 dark:bg-zinc-900 p-6">
                <form action="{{ route('dashboard.assignments.store') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Empleado / Vendedor</label>
                        <select name="user_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring-pink-500 sm:text-sm" required>
                            <option value="">Seleccionar Usuario</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} ({{ ucfirst($user->role) }})</option>
                            @endforeach
                        </select>
                        @error('user_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Producto</label>
                        <select name="product_id" id="product_select" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring-pink-500 sm:text-sm" required onchange="updatePrice()">
                            <option value="">Seleccionar Producto</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}" data-price="{{ $product->price }}">{{ $product->name }} (Stock: {{ $product->stock }})</option>
                            @endforeach
                        </select>
                        @error('product_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Cantidad</label>
                            <input type="number" name="quantity" value="1" min="1" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring-pink-500 sm:text-sm" required>
                            @error('quantity') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Precio Unitario</label>
                            <input type="number" step="0.01" name="unit_price" id="unit_price" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring-pink-500 sm:text-sm" required>
                            @error('unit_price') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="w-full rounded-md bg-pink-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-pink-700 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-offset-2">
                            Guardar Asignación
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function updatePrice() {
            var select = document.getElementById('product_select');
            var price = select.options[select.selectedIndex].getAttribute('data-price');
            document.getElementById('unit_price').value = price;
        }
    </script>
</x-layouts.app>
