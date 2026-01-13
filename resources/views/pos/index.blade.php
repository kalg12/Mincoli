<x-layouts.app :title="__('POS - Sistema de Ventas')">
    <div class="h-[calc(100vh-65px)] overflow-hidden bg-zinc-950 text-zinc-100" x-data="posSystem()">
        <div class="flex h-full">
            <!-- Main Content: Products Panel -->
            <div class="flex-1 flex flex-col min-w-0">
                <!-- Top Header: Search & Info -->
                <div class="p-3 bg-zinc-900 border-b border-zinc-800">
                    <div class="relative max-w-2xl mx-auto">
                        <input type="text" x-model="search" @input.debounce.300ms="filterProducts()"
                            placeholder="Buscar productos..."
                            class="w-full rounded-lg bg-zinc-950 border-zinc-800 px-4 py-2 pl-10 text-sm text-zinc-100 placeholder-zinc-600 focus:ring-1 focus:ring-pink-500 focus:border-pink-500 transition-all">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3.5">
                            <i class="fas fa-search text-zinc-500 text-sm"></i>
                        </div>
                    </div>
                </div>

                <!-- Category Navigation -->
                <div class="px-3 py-2 bg-zinc-900 border-b border-zinc-800 overflow-x-auto no-scrollbar">
                    <div class="flex gap-1.5">
                        <button @click="setCategory(null)" 
                            :class="activeCategoryId === null ? 'bg-pink-600 text-white shadow-pink-500/20' : 'bg-zinc-800 text-zinc-400 hover:text-white'"
                            class="px-4 py-1.5 rounded-md text-xs font-bold uppercase tracking-wider transition-all whitespace-nowrap">
                            Todo
                        </button>
                        @foreach($categories as $category)
                            <button @click="setCategory({{ $category->id }})"
                                :class="activeCategoryId === {{ $category->id }} ? 'bg-pink-600 text-white shadow-pink-500/20' : 'bg-zinc-800 text-zinc-400 hover:text-white'"
                                class="px-4 py-1.5 rounded-md text-xs font-bold uppercase tracking-wider transition-all whitespace-nowrap">
                                {{ $category->name }}
                            </button>
                        @endforeach
                    </div>
                </div>

                <!-- Products Grid -->
                <div class="flex-1 overflow-y-auto p-4 custom-scrollbar bg-zinc-950">
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 2xl:grid-cols-6 gap-4">
                        <template x-for="product in filteredProducts" :key="product.id">
                            <div @click="handleProductClick(product)" 
                                class="group bg-zinc-900 rounded-xl overflow-hidden hover:ring-2 hover:ring-pink-500 transition-all flex flex-col border border-zinc-800 shadow-lg cursor-pointer">
                                <!-- Product Image -->
                                <div class="aspect-square bg-zinc-800 relative overflow-hidden flex items-center justify-center">
                                     <img :src="product.image_url" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" :alt="product.name" 
                                          x-on:error="$el.src = 'https://ui-avatars.com/api/?name='+product.name+'&background=27272a&color=a1a1aa&size=256'">
                                     <div x-show="product.variants.length > 0" class="absolute top-2 right-2 bg-zinc-950/90 backdrop-blur-md px-2 py-1 rounded-md text-[10px] font-bold uppercase text-zinc-400 border border-zinc-800">
                                        <span x-text="product.variants.length"></span> VARS
                                     </div>
                                </div>
                                <!-- Product Info -->
                                <div class="p-3 flex-1 flex flex-col justify-between">
                                    <div>
                                        <h2 class="text-sm font-bold text-zinc-100 leading-tight line-clamp-2 group-hover:text-pink-400 transition-colors" x-text="product.name"></h2>
                                        <div class="flex items-center justify-between mt-1">
                                            <div class="text-base font-black text-pink-500" x-text="'$' + parseFloat(product.price).toLocaleString()"></div>
                                            <div x-show="product.variants.length === 0" class="text-[10px] font-black px-1.5 py-0.5 rounded bg-zinc-950 border border-zinc-800"
                                                :class="product.stock > 10 ? 'text-zinc-500' : (product.stock > 0 ? 'text-amber-500' : 'text-red-500')">
                                                <span x-text="product.stock > 0 ? product.stock + ' disp.' : 'Agotado'"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <button @click.stop="handleProductClick(product)" 
                                        :disabled="(product.variants.length === 0 && product.stock <= 0) || (product.variants.length > 0 && product.variants.every(v => v.stock <= 0))"
                                        :class="((product.variants.length === 0 && product.stock <= 0) || (product.variants.length > 0 && product.variants.every(v => v.stock <= 0))) ? 'opacity-50 grayscale cursor-not-allowed bg-zinc-800' : 'bg-pink-600 hover:bg-pink-700 active:scale-95 shadow-md'"
                                        class="w-full mt-3 text-white rounded-lg py-2 text-xs font-black uppercase tracking-widest transition-all">
                                        <span x-text="((product.variants.length === 0 && product.stock <= 0) || (product.variants.length > 0 && product.variants.every(v => v.stock <= 0))) ? 'AGOTADO' : (product.variants.length > 0 ? 'Opciones' : '+ AÑADIR')"></span>
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>
                    
                    <div x-show="isLoading" class="flex flex-col items-center justify-center h-64 text-zinc-800">
                        <i class="fas fa-spinner fa-spin text-5xl mb-4 opacity-10"></i>
                         <p class="text-xs font-black uppercase tracking-[0.2em] opacity-20">Buscando productos...</p>
                    </div>

                    <div x-show="filteredProducts.length === 0 && !isLoading" class="flex flex-col items-center justify-center h-64 text-zinc-800">
                        <i class="fas fa-box-open text-5xl mb-4 opacity-10"></i>
                        <p class="text-xs font-black uppercase tracking-[0.2em] opacity-20">No se encontraron productos</p>
                    </div>
                </div>
            </div>

            <!-- Sidebar: Cart Panel -->
            <div class="w-96 bg-zinc-900 border-l border-zinc-800 flex flex-col shadow-2xl z-10">
                <!-- Cart Header -->
                <div class="p-4 border-b border-zinc-800 space-y-4">
                    <div class="flex justify-between items-center">
                        <h2 class="text-base font-black uppercase tracking-tight flex items-center gap-2 text-white">
                            <i class="fas fa-shopping-basket text-pink-500"></i> 
                            Carrito
                        </h2>
                        <span class="bg-pink-600 text-white px-3 py-1 rounded-full text-xs font-black" x-text="cart.length"></span>
                    </div>

                    <!-- Customer Linking Section -->
                    <div class="bg-zinc-950 p-2 rounded-xl border border-zinc-800">
                        <div x-show="!linkedCustomer && !manualCustomerMode">
                            <div class="flex items-center justify-between px-2 py-1 mb-1">
                                <label class="text-[10px] font-black uppercase text-zinc-500 tracking-widest">Cliente</label>
                                <button @click="manualCustomerMode = true" class="text-[10px] font-black uppercase text-pink-400 hover:text-pink-300 transition-colors underline decoration-pink-900/50 underline-offset-4">Manual</button>
                            </div>
                            <div class="px-1 pb-1">
                                <div class="relative">
                                    <input type="text" x-model="customerSearch" @input.debounce.300ms="searchCustomers()"
                                        placeholder="Buscar por nombre o cel..."
                                        class="w-full bg-zinc-900 border-zinc-800 rounded-lg px-3 py-2 text-sm text-white placeholder-zinc-700 focus:ring-1 focus:ring-pink-600 focus:border-pink-600 transition-all">
                                    
                                    <!-- Customer Results -->
                                    <div x-show="customerResults.length > 0" class="absolute z-50 left-0 right-0 mt-2 bg-zinc-900 border border-zinc-700 rounded-xl overflow-hidden shadow-2xl max-h-60 overflow-y-auto">
                                        <ul class="divide-y divide-zinc-800">
                                            <template x-for="c in customerResults" :key="c.id">
                                                <li>
                                                    <button @click="linkCustomer(c)" class="w-full text-left p-3 hover:bg-pink-600/10 transition-colors">
                                                        <div class="text-sm font-bold text-white" x-text="c.name"></div>
                                                        <div class="text-xs text-zinc-500" x-text="c.phone"></div>
                                                    </button>
                                                </li>
                                            </template>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Manual Mode -->
                        <div x-show="manualCustomerMode && !linkedCustomer" class="p-2 space-y-2">
                            <div class="flex items-center justify-between mb-1">
                                <label class="text-[10px] font-black uppercase text-zinc-500 tracking-widest">Entrada Manual</label>
                                <button @click="manualCustomerMode = false" class="text-red-400 hover:text-red-500 text-xs font-black uppercase tracking-widest flex items-center gap-1">
                                   <i class="fas fa-times"></i> Salir
                                </button>
                            </div>
                            <input type="text" x-model="manualCustomer.name" placeholder="Nombre completo" class="w-full bg-zinc-900 border-zinc-800 rounded-lg px-3 py-2 text-sm text-white placeholder-zinc-700 focus:ring-1 focus:ring-pink-600">
                            <input type="text" x-model="manualCustomer.phone" placeholder="Celular (10 dígitos)" class="w-full bg-zinc-900 border-zinc-800 rounded-lg px-3 py-2 text-sm text-white placeholder-zinc-700 focus:ring-1 focus:ring-pink-600">
                        </div>

                        <div x-show="linkedCustomer" class="p-2">
                             <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-pink-600 text-white flex items-center justify-center text-sm font-black shadow-lg shadow-pink-900/20">
                                        <span x-text="linkedCustomer ? linkedCustomer.name[0] : ''"></span>
                                    </div>
                                    <div>
                                        <div class="text-sm font-black text-white leading-tight" x-text="linkedCustomer ? linkedCustomer.name : ''"></div>
                                        <div class="text-xs text-zinc-500 mt-0.5 font-bold" x-text="linkedCustomer ? linkedCustomer.phone : ''"></div>
                                    </div>
                                </div>
                                <button @click="linkedCustomer = null; manualCustomerMode = false" class="w-8 h-8 rounded-lg bg-zinc-800 text-zinc-500 hover:text-red-400 transition-all flex items-center justify-center border border-zinc-700">
                                    <i class="fas fa-times text-xs"></i>
                                </button>
                             </div>
                        </div>
                    </div>
                </div>

                <!-- Cart Items List -->
                <div class="flex-1 overflow-y-auto p-3 custom-scrollbar space-y-2">
                    <template x-for="(item, index) in cart" :key="index">
                        <div class="bg-zinc-950/40 rounded-xl p-3 border border-zinc-800/60 hover:border-pink-900/30 transition-all group/item">
                            <div class="flex justify-between items-start gap-3">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 mb-1">
                                        <h3 class="font-bold text-zinc-100 text-sm truncate uppercase tracking-tight" x-text="item.name"></h3>
                                        <span x-show="item.variant" class="text-[10px] bg-zinc-800 text-zinc-400 px-2 py-0.5 rounded-full font-bold uppercase border border-zinc-700" x-text="item.variant ? item.variant.name : ''"></span>
                                    </div>
                                    <p class="text-xs text-zinc-500 font-bold uppercase tracking-tight">Precio: $<span x-text="parseFloat(item.price).toLocaleString()"></span></p>
                                </div>
                                <div class="text-right">
                                    <p class="font-black text-pink-500 text-sm" x-text="'$' + (item.price * item.quantity).toLocaleString()"></p>
                                </div>
                            </div>
                            
                            <!-- Internal Note -->
                            <div class="mt-2 bg-zinc-900/50 rounded-lg px-2.5 py-1.5 border border-zinc-800/50">
                                <input type="text" x-model="item.note" placeholder="Agregar nota al producto..." 
                                    class="w-full bg-transparent border-none p-0 text-xs text-zinc-400 placeholder-zinc-700 focus:ring-0">
                            </div>

                            <div class="mt-3 flex items-center justify-between">
                                <!-- Quantity Controls -->
                                <div class="flex items-center bg-zinc-900 rounded-lg p-1 border border-zinc-800 shadow-inner">
                                    <button @click="updateQty(index, -1)" class="w-7 h-7 flex items-center justify-center text-zinc-500 hover:bg-zinc-800 hover:text-white rounded-md transition-colors">
                                        <i class="fas fa-minus text-xs"></i>
                                    </button>
                                    <span class="w-8 text-center text-sm font-black text-white" x-text="item.quantity"></span>
                                    <button @click="updateQty(index, 1)" class="w-7 h-7 flex items-center justify-center text-zinc-500 hover:bg-zinc-800 hover:text-white rounded-md transition-colors">
                                        <i class="fas fa-plus text-xs"></i>
                                    </button>
                                </div>

                                <!-- Actions -->
                                <button @click="removeFromCart(index)" class="w-8 h-8 flex items-center justify-center bg-red-950/10 text-red-900/50 group-hover/item:text-red-500 rounded-lg hover:bg-red-950 hover:text-white transition-all border border-transparent group-hover/item:border-red-900/30">
                                    <i class="fas fa-trash-alt text-xs"></i>
                                </button>
                            </div>
                        </div>
                    </template>
                    
                    <div x-show="cart.length === 0" class="h-48 flex flex-col items-center justify-center text-zinc-800">
                        <i class="fas fa-shopping-basket text-4xl mb-3 opacity-10"></i>
                         <p class="text-xs font-black uppercase tracking-[0.2em] opacity-20">Carrito vacío</p>
                    </div>
                </div>

                <!-- Summary Footer -->
                <div class="bg-zinc-900 p-5 border-t border-zinc-800 space-y-4">
                    <div class="space-y-2">
                        <div class="flex justify-between text-xs" x-show="showIva">
                            <span class="text-zinc-500 font-bold uppercase tracking-widest">Subtotal</span>
                            <span class="font-black text-zinc-300" x-text="'$' + subtotal.toLocaleString()"></span>
                        </div>
                        <div class="flex justify-between text-xs" x-show="showIva">
                            <span class="text-zinc-500 font-bold uppercase tracking-widest">IVA (16%)</span>
                            <span class="font-black text-zinc-300" x-text="'$' + (total - subtotal).toLocaleString()"></span>
                        </div>
                        <div class="pt-2 border-t border-zinc-800 flex justify-between items-center">
                            <span class="text-lg font-black uppercase tracking-tight text-white">Total a Pagar</span>
                            <span class="text-2xl font-black text-pink-500" x-text="'$' + total.toLocaleString()"></span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-3 pt-2 border-t border-zinc-800/50">
                        <button @click="proceedToOrder()" :disabled="cart.length === 0"
                            class="w-full bg-emerald-600 hover:bg-emerald-500 text-white py-3.5 rounded-xl font-black uppercase tracking-widest transition-all disabled:opacity-20 disabled:cursor-not-allowed text-sm shadow-xl shadow-emerald-900/20 active:scale-95">
                            Procesar Orden
                        </button>
                        <div class="grid grid-cols-2 gap-3">
                           <button @click="shareWhatsApp()" :disabled="cart.length === 0"
                               class="w-full border border-zinc-700 text-zinc-400 py-2.5 rounded-xl font-black uppercase tracking-widest hover:bg-zinc-800 transition-all disabled:opacity-20 flex items-center justify-center gap-2 text-[10px]">
                               <i class="fab fa-whatsapp text-emerald-500 text-sm"></i> COTIZACIÓN
                           </button>
                           <button @click="clearCart()" x-show="cart.length > 0"
                               class="w-full border border-zinc-700 text-zinc-500 py-2.5 rounded-xl font-black uppercase tracking-widest hover:bg-red-900/20 hover:text-red-400 transition-all text-[10px]">
                               Borrar Carrito
                           </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Variants Modal -->
        <div x-show="variantModal" 
            x-transition:enter="transition ease-out duration-300" 
            x-transition:enter-start="opacity-0 scale-95" 
            x-transition:enter-end="opacity-100 scale-100"
            class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-zinc-950/90 backdrop-blur-sm" x-cloak>
            
            <div class="bg-zinc-900 w-full max-w-sm rounded-[2rem] border border-zinc-800 shadow-2xl overflow-hidden" @click.away="variantModal = false">
                <div class="p-6 border-b border-zinc-800 text-center">
                    <h2 class="text-lg font-black text-white" x-text="selectingProduct?.name"></h2>
                    <p class="text-xs text-zinc-500 uppercase tracking-widest mt-1">Selecciona una variante</p>
                </div>
                
                <div class="p-6 space-y-3">
                    <div class="grid grid-cols-1 gap-2">
                        <template x-for="v in selectingProduct?.variants" :key="v.id">
                            <button @click="addVariantToCart(v)" 
                                :disabled="v.stock <= 0"
                                :class="v.stock <= 0 ? 'opacity-50 grayscale cursor-not-allowed' : 'hover:border-pink-500 hover:bg-zinc-800 active:scale-95'"
                                class="flex justify-between items-center p-4 rounded-2xl bg-zinc-950 border border-zinc-800 transition-all group shadow-sm">
                                <div class="flex flex-col text-left">
                                    <span class="text-sm font-bold text-zinc-400 group-hover:text-white transition-colors" x-text="v.name"></span>
                                    <span class="text-[10px] font-black uppercase tracking-tighter" :class="v.stock > 0 ? 'text-emerald-500' : 'text-red-500'" 
                                          x-text="v.stock > 0 ? v.stock + ' disponibles' : 'Sin stock'"></span>
                                </div>
                                <span class="text-base font-black text-pink-500" x-text="'$' + parseFloat(v.price).toLocaleString()"></span>
                            </button>
                        </template>
                    </div>
                </div>
                
                <div class="p-6 pt-0">
                    <button @click="variantModal = false" class="w-full py-3 text-xs font-black uppercase tracking-widest text-zinc-500 hover:text-white transition-colors">
                        Cancelar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap');
        
        body { font-family: 'Inter', sans-serif; background-color: #020617; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #27272a; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #3f3f46; }
        [x-cloak] { display: none !important; }

        input { outline: none !important; }
        input::placeholder { color: #334155 !important; }
    </style>

    <script>
        function posSystem() {
            return {
                search: '',
                activeCategoryId: null,
                isLoading: false,
                products: @json($products->items()),
                cart: [],
                showIva: {{ $showIva ? 'true' : 'false' }},
                
                // Variants logic
                variantModal: false,
                selectingProduct: null,
                
                // Customer logic
                customerSearch: '',
                customerResults: [],
                linkedCustomer: null,
                manualCustomerMode: false,
                manualCustomer: { name: '', phone: '' },

                async filterProducts() {
                    if (this.search.length < 3 && !this.activeCategoryId) {
                        // Si no hay busqueda ni categoria, podemos mantener los iniciales o recargar
                        return;
                    }
                    
                    this.isLoading = true;
                    try {
                        const params = new URLSearchParams({
                            q: this.search,
                            category_id: this.activeCategoryId || ''
                        });
                        const resp = await fetch(`{{ route('dashboard.pos.searchProduct') }}?${params.toString()}`);
                        this.products = await resp.json();
                    } catch (e) {
                        console.error('Error searching products', e);
                    } finally {
                        this.isLoading = false;
                    }
                },

                get filteredProducts() {
                    // Si hay busqueda local o remota, el getter simplemente devuelve this.products
                    // ya que el backend o el filtro manual ya se encargaron.
                    // Pero para evitar duplicar logica, podemos mantener un filtro ligero aqui:
                    return this.products.filter(p => {
                        const s = this.search.toLowerCase();
                        const matchesSearch = p.name.toLowerCase().includes(s) || 
                                             (p.sku && p.sku.toLowerCase().includes(s)) ||
                                             (p.barcode && p.barcode.toLowerCase().includes(s));
                        const matchesCategory = this.activeCategoryId === null || p.category_id === this.activeCategoryId;
                        return matchesSearch && matchesCategory;
                    });
                },

                get subtotal() {
                    const total = this.cart.reduce((sum, item) => sum + (parseFloat(item.price) * item.quantity), 0);
                    return this.showIva ? (total / 1.16) : total;
                },

                get total() {
                    return this.cart.reduce((sum, item) => sum + (parseFloat(item.price) * item.quantity), 0);
                },

                setCategory(id) {
                    this.activeCategoryId = id;
                    this.filterProducts();
                },

                handleProductClick(product) {
                    if (product.variants && product.variants.length > 0) {
                        this.selectingProduct = product;
                        this.variantModal = true;
                    } else {
                        this.addToCart(product);
                    }
                },

                addToCart(product) {
                    const existing = this.cart.find(item => item.id === product.id && !item.variant);
                    const currentQty = existing ? existing.quantity : 0;

                    if (currentQty + 1 > product.stock) {
                        alert(`No hay suficiente stock. Disponibles: ${product.stock}`);
                        return;
                    }

                    if (existing) {
                        existing.quantity++;
                    } else {
                        this.cart.push({
                            id: product.id,
                            name: product.name,
                            price: product.price,
                            quantity: 1,
                            variant: null,
                            note: ''
                        });
                    }
                },

                addVariantToCart(variant) {
                    const existing = this.cart.find(item => item.variant && item.variant.id === variant.id);
                    const currentQty = existing ? existing.quantity : 0;

                    if (currentQty + 1 > variant.stock) {
                        alert(`No hay suficiente stock. Disponibles: ${variant.stock}`);
                        return;
                    }

                    if (existing) {
                        existing.quantity++;
                    } else {
                        this.cart.push({
                            id: this.selectingProduct.id,
                            name: this.selectingProduct.name,
                            price: variant.price,
                            quantity: 1,
                            variant: { id: variant.id, name: variant.name },
                            note: ''
                        });
                    }
                    this.variantModal = false;
                },

                updateQty(index, delta) {
                    const item = this.cart[index];
                    if (delta > 0) {
                        const product = this.products.find(p => p.id === item.id);
                        const maxStock = item.variant 
                            ? product.variants.find(v => v.id === item.variant.id).stock 
                            : product.stock;

                        if (item.quantity + delta > maxStock) {
                            alert(`No puedes agregar más. Stock máximo: ${maxStock}`);
                            return;
                        }
                    }

                    item.quantity += delta;
                    if (item.quantity < 1) {
                        this.removeFromCart(index);
                    }
                },

                removeFromCart(index) {
                    this.cart.splice(index, 1);
                },

                clearCart() {
                    if (confirm('¿Estás seguro de vaciar el carrito?')) {
                        this.cart = [];
                        this.linkedCustomer = null;
                        this.manualCustomer = { name: '', phone: '' };
                        this.manualCustomerMode = false;
                    }
                },

                async searchCustomers() {
                    if (this.customerSearch.length < 3) {
                        this.customerResults = [];
                        return;
                    }
                    try {
                        const resp = await fetch(`{{ route('dashboard.pos.customers.search') }}?q=${this.customerSearch}`);
                        this.customerResults = await resp.json();
                    } catch (e) {
                        console.error('Customer search error', e);
                    }
                },

                linkCustomer(customer) {
                    this.linkedCustomer = customer;
                    this.customerSearch = '';
                    this.customerResults = [];
                    this.manualCustomerMode = false;
                },

                shareWhatsApp() {
                    let clientName = '';
                    if (this.linkedCustomer) clientName = this.linkedCustomer.name;
                    else if (this.manualCustomer.name) clientName = this.manualCustomer.name;
                    
                    let message = `Hola${clientName ? ' ' + clientName : ''}! te comparto tu coti:\n\n`;
                    this.cart.forEach(item => {
                        const variantStr = item.variant ? ` (${item.variant.name})` : '';
                        message += `$${parseFloat(item.price).toFixed(0)}    ${item.name}${variantStr}\n`;
                    });
                    message += `\nUn total de $${this.total.toFixed(0)}`;
                    
                    message += `\n\n--------------------------\n`;
                    message += `🏦 DATOS PARA DEPÓSITO/TRANSFERENCIA:\n\n`;
                    message += `*DEPÓSITOS OXXO:*\nCÓDIGO DE DEPÓSITO EN CAJA\n2242 1701 8074 1927\nNOMBRE: KEVIN VALENTIN JIMENEZ MARTINEZ\n\n`;
                    message += `*TRANSFERENCIA BANCO AZTECA:*\nCLABE: 1271 8001 3158 064 597\nNO. DE TARJETA: 4027 6600 0780 3556\nNOMBRE: JAZMÍN REYES`;
                    
                    const encoded = encodeURIComponent(message);
                    let phone = '';
                    if (this.linkedCustomer) phone = this.linkedCustomer.phone;
                    else if (this.manualCustomer.phone) phone = this.manualCustomer.phone;
                    
                    phone = phone.replace(/\D/g, '');
                    window.open(`https://wa.me/${phone}?text=${encoded}`, '_blank');
                },

                async proceedToOrder() {
                    if (this.cart.length === 0) return;
                    
                    try {
                        const response = await fetch("{{ route('dashboard.pos.store-ajax') }}", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": "{{ csrf_token() }}"
                            },
                            body: JSON.stringify({
                                items: this.cart,
                                customer_id: this.linkedCustomer ? this.linkedCustomer.id : null,
                                customer_name: this.manualCustomerMode ? this.manualCustomer.name : null,
                                customer_phone: this.manualCustomerMode ? this.manualCustomer.phone : null
                            })
                        });

                        const result = await response.json();

                        if (result.success) {
                            alert(`Orden ${result.order_number} generada correctamente.`);
                            this.cart = [];
                            this.linkedCustomer = null;
                            this.manualCustomer = { name: '', phone: '' };
                            this.manualCustomerMode = false;
                            this.customerSearch = '';
                        } else {
                            alert('Error: ' + result.message);
                        }
                    } catch (e) {
                        console.error('Order processing error', e);
                        alert('Error al procesar la orden. Intenta de nuevo.');
                    }
                }
            }
        }
    </script>
</x-layouts.app>
