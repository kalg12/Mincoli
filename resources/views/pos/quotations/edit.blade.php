<x-layouts.app :title="__('Editar Cotización')">
    <div class="flex-1" x-data="quotationEditSystem()">
        <div class="border-b border-zinc-200 bg-white px-6 py-4 dark:border-zinc-700 dark:bg-zinc-900">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Editar Cotización</h1>
                    <p class="text-sm text-zinc-600 dark:text-zinc-400">Folio: {{ $quotation->folio }}</p>
                </div>
                <a href="{{ route('dashboard.pos.quotations.index') }}" 
                   class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-zinc-600 hover:bg-zinc-700 text-white text-sm font-semibold transition-all">
                    <i class="fas fa-arrow-left"></i>
                    <span>Cancelar</span>
                </a>
            </div>
        </div>

        <form action="{{ route('dashboard.pos.quotations.update', $quotation->id) }}" method="POST" @submit.prevent="submitForm">
            @csrf
            @method('PUT')
            
            <div class="p-6 grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left Column: Products Search & List -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Search Products -->
                    <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-sm p-4">
                        <h2 class="text-lg font-bold text-zinc-900 dark:text-white mb-4">Buscar Productos</h2>
                        <div class="relative">
                            <input type="text" 
                                   x-model="productSearch" 
                                   @input.debounce.300ms="filterProducts()"
                                   placeholder="Buscar por nombre, SKU o código..."
                                   class="w-full rounded-lg border-zinc-300 bg-white px-4 py-2 pl-10 text-sm shadow-sm focus:border-pink-500 focus:ring-pink-500 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                <i class="fas fa-search text-zinc-400"></i>
                            </div>
                        </div>
                        
                        <!-- Products Grid -->
                        <div class="mt-4 max-h-96 overflow-y-auto custom-scrollbar">
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                                <template x-for="product in filteredProducts" :key="product.id">
                                    <div @click="addProduct(product)"
                                         class="bg-zinc-50 dark:bg-zinc-800 rounded-lg p-3 cursor-pointer hover:ring-2 hover:ring-pink-500 transition-all border border-zinc-200 dark:border-zinc-700">
                                        <div class="aspect-square bg-white dark:bg-zinc-900 rounded mb-2 flex items-center justify-center overflow-hidden">
                                            <img :src="product.image_url || '/images/placeholder.png'" 
                                                 :alt="product.name"
                                                 class="w-full h-full object-contain p-2">
                                        </div>
                                        <h3 class="text-xs font-bold text-zinc-900 dark:text-white line-clamp-2" x-text="product.name"></h3>
                                        <p class="text-sm font-black text-pink-500 mt-1" x-text="'$' + parseFloat(product.price).toLocaleString()"></p>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>

                    <!-- Current Items -->
                    <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-sm p-4">
                        <h2 class="text-lg font-bold text-zinc-900 dark:text-white mb-4">Productos en la Cotización</h2>
                        <div class="space-y-3">
                            <template x-for="(item, index) in cart" :key="index">
                                <div class="flex items-center gap-4 p-3 bg-zinc-50 dark:bg-zinc-800 rounded-lg">
                                    <div class="flex-1">
                                        <h4 class="font-bold text-zinc-900 dark:text-white" x-text="item.name"></h4>
                                        <p x-show="item.variant" class="text-xs text-pink-500" x-text="item.variant ? item.variant.name : ''"></p>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <label class="text-xs text-zinc-500">Cant:</label>
                                        <input type="number" 
                                               x-model.number="item.quantity" 
                                               @change="updateTotals()"
                                               min="1"
                                               class="w-16 rounded border-zinc-300 px-2 py-1 text-sm text-center">
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <label class="text-xs text-zinc-500">Precio:</label>
                                        <input type="number" 
                                               x-model.number="item.price" 
                                               @change="updateTotals()"
                                               step="0.01"
                                               min="0"
                                               class="w-24 rounded border-zinc-300 px-2 py-1 text-sm text-center">
                                    </div>
                                    <div class="text-right w-20">
                                        <p class="font-black text-pink-500" x-text="'$' + (item.quantity * item.price).toLocaleString()"></p>
                                    </div>
                                    <button type="button" 
                                            @click="removeItem(index)"
                                            class="p-2 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </template>
                            <div x-show="cart.length === 0" class="text-center py-8 text-zinc-500">
                                <i class="fas fa-shopping-cart text-4xl mb-2 opacity-20"></i>
                                <p>No hay productos en la cotización</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Customer & Totals -->
                <div class="space-y-6">
                    <!-- Customer Info -->
                    <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-sm p-4">
                        <h2 class="text-lg font-bold text-zinc-900 dark:text-white mb-4">Cliente</h2>
                        <div class="space-y-3">
                            <div>
                                <label class="block text-xs font-semibold text-zinc-500 mb-1">Buscar Cliente</label>
                                <select x-model="selectedCustomerId" 
                                        @change="updateCustomer()"
                                        class="w-full rounded-lg border-zinc-300 bg-white px-3 py-2 text-sm shadow-sm focus:border-pink-500 focus:ring-pink-500 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white">
                                    <option value="">Nuevo Cliente</option>
                                    @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}" {{ $quotation->customer_id == $customer->id ? 'selected' : '' }}>
                                            {{ $customer->name }} - {{ $customer->phone }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div x-show="!selectedCustomerId">
                                <label class="block text-xs font-semibold text-zinc-500 mb-1">Nombre</label>
                                <input type="text" 
                                       name="customer_name" 
                                       x-model="customerName"
                                       class="w-full rounded-lg border-zinc-300 bg-white px-3 py-2 text-sm shadow-sm focus:border-pink-500 focus:ring-pink-500 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white"
                                       value="{{ $quotation->customer_name }}">
                            </div>
                            <div x-show="!selectedCustomerId">
                                <label class="block text-xs font-semibold text-zinc-500 mb-1">Teléfono</label>
                                <input type="text" 
                                       name="customer_phone" 
                                       x-model="customerPhone"
                                       class="w-full rounded-lg border-zinc-300 bg-white px-3 py-2 text-sm shadow-sm focus:border-pink-500 focus:ring-pink-500 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white"
                                       value="{{ $quotation->customer_phone }}">
                            </div>
                            <input type="hidden" name="customer_id" x-model="selectedCustomerId">
                        </div>
                    </div>

                    <!-- Totals -->
                    <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-sm p-4">
                        <h2 class="text-lg font-bold text-zinc-900 dark:text-white mb-4">Totales</h2>
                        <div class="space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-zinc-600 dark:text-zinc-400">Subtotal:</span>
                                <span class="font-bold" x-text="'$' + subtotal.toLocaleString()"></span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-zinc-600 dark:text-zinc-400">IVA (16%):</span>
                                <span class="font-bold" x-text="'$' + ivaTotal.toLocaleString()"></span>
                            </div>
                            <div class="border-t border-zinc-200 dark:border-zinc-700 pt-2 mt-2">
                                <div class="flex justify-between">
                                    <span class="text-lg font-bold text-zinc-900 dark:text-white">Total:</span>
                                    <span class="text-xl font-black text-pink-500" x-text="'$' + total.toLocaleString()"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Notes -->
                    <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-sm p-4">
                        <h2 class="text-lg font-bold text-zinc-900 dark:text-white mb-4">Notas</h2>
                        <textarea name="notes" 
                                  rows="3"
                                  class="w-full rounded-lg border-zinc-300 bg-white px-3 py-2 text-sm shadow-sm focus:border-pink-500 focus:ring-pink-500 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white"
                                  placeholder="Notas adicionales...">{{ $quotation->notes }}</textarea>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" 
                            :disabled="cart.length === 0"
                            class="w-full px-4 py-3 rounded-lg bg-pink-600 hover:bg-pink-700 text-white font-semibold transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                        <i class="fas fa-save mr-2"></i>
                        Guardar Cambios
                    </button>
                </div>
            </div>

            <!-- Hidden inputs for items -->
            <template x-for="(item, index) in cart" :key="index">
                <input type="hidden" 
                       :name="'items[' + index + '][product_id]'" 
                       :value="item.id">
                <input type="hidden" 
                       :name="'items[' + index + '][variant_id]'" 
                       :value="item.variant ? item.variant.id : ''">
                <input type="hidden" 
                       :name="'items[' + index + '][quantity]'" 
                       :value="item.quantity">
                <input type="hidden" 
                       :name="'items[' + index + '][unit_price]'" 
                       :value="item.price">
            </template>
        </form>
    </div>

    <script>
        function quotationEditSystem() {
            return {
                productSearch: '',
                allProducts: @json($products),
                filteredProducts: @json($products),
                cart: @json($cartItems),
                selectedCustomerId: @json($quotation->customer_id),
                customerName: @json($quotation->customer_name),
                customerPhone: @json($quotation->customer_phone ?? ''),
                subtotal: 0,
                ivaTotal: 0,
                total: 0,
                showIva: true,

                init() {
                    this.updateTotals();
                    this.filterProducts();
                },

                filterProducts() {
                    if (!this.productSearch) {
                        this.filteredProducts = this.allProducts;
                        return;
                    }
                    const search = this.productSearch.toLowerCase();
                    this.filteredProducts = this.allProducts.filter(product => 
                        product.name.toLowerCase().includes(search) ||
                        (product.sku && product.sku.toLowerCase().includes(search)) ||
                        (product.barcode && product.barcode.toLowerCase().includes(search))
                    );
                },

                addProduct(product) {
                    // Si tiene variantes, mostrar selector
                    if (product.variants && product.variants.length > 0) {
                        if (product.variants.length === 1) {
                            // Solo una variante, agregarla directamente
                            this.cart.push({
                                id: product.id,
                                name: product.name,
                                price: parseFloat(product.variants[0].price || product.price),
                                quantity: 1,
                                variant: {
                                    id: product.variants[0].id,
                                    name: product.variants[0].name
                                }
                            });
                        } else {
                            // Múltiples variantes - usar la primera por ahora (se puede mejorar con un modal)
                            if (confirm(`El producto tiene ${product.variants.length} variantes. ¿Agregar la primera variante?`)) {
                                this.cart.push({
                                    id: product.id,
                                    name: product.name,
                                    price: parseFloat(product.variants[0].price || product.price),
                                    quantity: 1,
                                    variant: {
                                        id: product.variants[0].id,
                                        name: product.variants[0].name
                                    }
                                });
                            }
                        }
                    } else {
                        // Sin variantes
                        this.cart.push({
                            id: product.id,
                            name: product.name,
                            price: parseFloat(product.price),
                            quantity: 1,
                            variant: null
                        });
                    }
                    this.updateTotals();
                },

                removeItem(index) {
                    this.cart.splice(index, 1);
                    this.updateTotals();
                },

                updateTotals() {
                    this.subtotal = this.cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
                    if (this.showIva) {
                        this.ivaTotal = this.subtotal * 0.16 / 1.16;
                        this.total = this.subtotal;
                    } else {
                        this.ivaTotal = 0;
                        this.total = this.subtotal;
                    }
                },

                updateCustomer() {
                    if (this.selectedCustomerId) {
                        const customers = @json($customers);
                        const customer = customers.find(c => c.id == this.selectedCustomerId);
                        if (customer) {
                            this.customerName = customer.name;
                            this.customerPhone = customer.phone || '';
                        }
                    }
                },

                submitForm() {
                    if (this.cart.length === 0) {
                        alert('Debe agregar al menos un producto a la cotización');
                        return false;
                    }
                    this.$el.submit();
                }
            }
        }
    </script>
</x-layouts.app>
