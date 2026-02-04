<x-layouts.app :title="__('POS - Sistema de Ventas')">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <div class="h-[calc(100vh-65px)] overflow-hidden bg-zinc-950 text-zinc-100" x-data="posSystem()">
        <div class="flex h-full">
            <!-- Main Content: Products Panel -->
            <div class="flex-1 flex flex-col min-w-0">
                <!-- Top Header: Search & Info -->
                <div class="p-3 bg-zinc-900 border-b border-zinc-800">
                    <div class="flex items-center gap-4 max-w-4xl mx-auto">
                        <div class="relative flex-1">
                            <input type="text" x-model="search" @input.debounce.300ms="filterProducts()"
                                placeholder="Buscar productos..."
                                class="w-full rounded-lg bg-zinc-950 border-zinc-800 px-4 py-2 pl-10 text-sm text-zinc-100 placeholder-zinc-500 focus:ring-1 focus:ring-pink-500 focus:border-pink-500 transition-all">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3.5">
                                <i class="fas fa-search text-zinc-400 text-sm"></i>
                            </div>
                        </div>
                        
                        <!-- Products per page selector -->
                        <div class="flex items-center gap-2">
                            <label class="text-xs font-bold uppercase text-zinc-500 tracking-widest">Mostrar</label>
                            <select x-model="perPage" @change="updatePerPage()" 
                                    class="rounded-lg bg-zinc-950 border-zinc-800 px-3 py-2 text-sm text-zinc-100 focus:ring-1 focus:ring-pink-500 focus:border-pink-500 transition-all">
                                <option value="10">10</option>
                                <option value="20">20</option>
                                <option value="30">30</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                            <label class="text-xs font-bold uppercase text-zinc-500 tracking-widest">productos</label>
                        </div>
                    </div>
                </div>

                <!-- Category Navigation -->
                <div class="bg-zinc-900 border-b border-zinc-800">
                    <!-- Parent Categories (Tabs) -->
                    <div class="px-3 overflow-x-auto no-scrollbar border-b border-zinc-800/50">
                        <div class="flex gap-4">
                            <button @click="setCategory(null)" 
                                :class="activeCategoryId === null ? 'text-pink-500 border-pink-500' : 'text-zinc-400 border-transparent hover:text-zinc-200'"
                                class="py-3 text-xs font-black uppercase tracking-widest transition-all whitespace-nowrap border-b-2">
                                Todo
                            </button>
                            @foreach($categories as $category)
                                <button @click="setCategory({{ $category->id }})"
                                    :class="activeCategoryId === {{ $category->id }} ? 'text-pink-500 border-pink-500' : 'text-zinc-400 border-transparent hover:text-zinc-200'"
                                    class="py-3 text-xs font-black uppercase tracking-widest transition-all whitespace-nowrap border-b-2">
                                    {{ $category->name }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                    
                    <!-- Subcategories (Pills) -->
                    <div class="px-3 py-2 overflow-x-auto no-scrollbar bg-zinc-950/30" x-show="activeCategoryId !== null && getSubcategories().length > 0">
                        <div class="flex gap-2">
                            <button @click="setSubcategory(null)"
                                :class="activeSubcategoryId === null ? 'bg-zinc-800 text-white border border-zinc-700' : 'bg-transparent text-zinc-500 border border-transparent hover:text-zinc-300'"
                                class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider transition-all whitespace-nowrap">
                                Ver Todo
                            </button>
                            <template x-for="sub in getSubcategories()" :key="sub.id">
                                <button @click="setSubcategory(sub.id)"
                                    :class="activeSubcategoryId === sub.id ? 'bg-zinc-800 text-pink-500 border border-pink-500/30' : 'bg-zinc-900 text-zinc-400 border border-zinc-800 hover:border-zinc-700'"
                                    class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider transition-all whitespace-nowrap"
                                    x-text="sub.name">
                                </button>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- Products Grid -->
                <div class="flex-1 overflow-y-auto p-4 custom-scrollbar bg-zinc-950">
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 2xl:grid-cols-6 gap-4">
                        <template x-for="product in filteredProducts" :key="product.id">
                            <div @click="handleProductClick(product)" 
                                class="group bg-zinc-900 rounded-xl overflow-hidden hover:ring-2 hover:ring-pink-500 transition-all flex flex-col border border-zinc-800 shadow-lg cursor-pointer h-full">
                                 <!-- Product Image -->
                                 <div class="aspect-[4/3] bg-white relative overflow-hidden flex items-center justify-center">
                                      <img :src="product.image_url" class="w-full h-full object-contain p-2 group-hover:scale-105 transition-transform duration-500" :alt="product.name" 
                                           loading="lazy">
                                      <div x-show="product.variants && product.variants.length > 0" class="absolute top-2 right-2 bg-zinc-950/90 backdrop-blur-md px-2 py-1 rounded-md text-[10px] font-bold uppercase text-zinc-400 border border-zinc-800 shadow-sm">
                                         <span x-text="product.variants.length"></span> Opcs
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
                    
                    <div x-show="isLoading" class="flex flex-col items-center justify-center h-64 text-zinc-700">
                        <i class="fas fa-spinner fa-spin text-5xl mb-4 opacity-20"></i>
                         <p class="text-xs font-black uppercase tracking-[0.2em] opacity-40">Buscando productos...</p>
                    </div>

                     <div x-show="filteredProducts.length === 0 && !isLoading" class="flex flex-col items-center justify-center h-64 text-zinc-700">
                        <i class="fas fa-box-open text-5xl mb-4 opacity-20"></i>
                        <p class="text-xs font-black uppercase tracking-[0.2em] opacity-40">No se encontraron productos</p>
                    </div>

                    <!-- Pagination -->
                    <div x-show="lastPage > 1" class="mt-6 border-t border-zinc-800 pt-4">
                        <div class="flex items-center justify-between">
                            <div class="text-xs text-zinc-500">
                                <span x-text="totalProducts"></span> productos totales
                            </div>
                            <div class="flex items-center gap-2">
                                <button @click="prevPage()" :disabled="currentPage <= 1"
                                        class="px-3 py-1 rounded-lg bg-zinc-800 text-zinc-400 hover:text-white disabled:opacity-50 disabled:cursor-not-allowed transition-all text-xs font-bold uppercase">
                                    <i class="fas fa-chevron-left"></i>
                                </button>
                                
                                <div class="flex items-center gap-1">
                                    <template x-for="page in Math.min(lastPage, 5)" :key="page">
                                        <button @click="goToPage(page)"
                                                :class="currentPage === page ? 'bg-pink-600 text-white' : 'bg-zinc-800 text-zinc-400 hover:text-white'"
                                                class="w-8 h-8 rounded-lg text-xs font-bold transition-all">
                                            <span x-text="page"></span>
                                        </button>
                                    </template>
                                    
                                    <span x-show="lastPage > 5" class="text-zinc-500 text-xs px-2">...</span>
                                    
                                    <template x-for="page in Math.max(0, lastPage - 2)" :key="'last-' + page">
                                        <button @click="goToPage(page)"
                                                :class="currentPage === page ? 'bg-pink-600 text-white' : 'bg-zinc-800 text-zinc-400 hover:text-white'"
                                                class="w-8 h-8 rounded-lg text-xs font-bold transition-all">
                                            <span x-text="page"></span>
                                        </button>
                                    </template>
                                </div>
                                
                                <button @click="nextPage()" :disabled="currentPage >= lastPage"
                                        class="px-3 py-1 rounded-lg bg-zinc-800 text-zinc-400 hover:text-white disabled:opacity-50 disabled:cursor-not-allowed transition-all text-xs font-bold uppercase">
                                    <i class="fas fa-chevron-right"></i>
                                </button>
                            </div>
                        </div>
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
                                        class="w-full bg-zinc-900 border-zinc-800 rounded-lg px-3 py-2 text-sm text-white placeholder-zinc-500 focus:ring-1 focus:ring-pink-600 focus:border-pink-600 transition-all">
                                    
                                    <!-- Customer Results -->
                                    <div x-show="customerSearch.length > 0" class="absolute z-50 left-0 right-0 mt-2 bg-zinc-900 border border-zinc-700 rounded-xl overflow-hidden shadow-2xl max-h-60 overflow-y-auto" x-cloak>
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
                             <input type="text" x-model="manualCustomer.name" placeholder="Nombre completo" class="w-full bg-zinc-900 border-zinc-800 rounded-lg px-3 py-2 text-sm text-white placeholder-zinc-500 focus:ring-1 focus:ring-pink-600">
                            <input type="text" x-model="manualCustomer.phone" placeholder="Celular (10 dígitos)" class="w-full bg-zinc-900 border-zinc-800 rounded-lg px-3 py-2 text-sm text-white placeholder-zinc-500 focus:ring-1 focus:ring-pink-600">
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
                                     <button @click="updateQty(index, -1)" class="w-7 h-7 flex items-center justify-center text-zinc-400 hover:text-white hover:bg-pink-600 rounded transition-all text-sm font-bold">
                                         −
                                     </button>
                                     <span class="w-8 text-center text-sm font-black text-white" x-text="item.quantity"></span>
                                     <button @click="updateQty(index, 1)" class="w-7 h-7 flex items-center justify-center text-zinc-400 hover:text-white hover:bg-pink-600 rounded transition-all text-sm font-bold">
                                         +
                                     </button>
                                 </div>

                                 <!-- Actions -->
                                 <button @click="removeFromCart(index)" class="w-8 h-8 flex items-center justify-center text-zinc-400 hover:text-white hover:bg-red-600 rounded-lg transition-all border border-zinc-700 hover:border-red-600 text-sm font-bold">
                                     ×
                                 </button>
                            </div>
                        </div>
                    </template>
                    <div x-show="cart.length === 0" class="h-48 flex flex-col items-center justify-center text-zinc-700">
                        <i class="fas fa-shopping-basket text-4xl mb-3 opacity-20"></i>
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
                        <div class="flex gap-2">
                             <button @click="paymentStatus = 'paid'" 
                                     :class="{'bg-pink-600 text-white shadow-pink-900/40': paymentStatus === 'paid', 'bg-zinc-800 text-zinc-500 hover:text-white': paymentStatus !== 'paid'}"
                                     class="flex-1 py-2 rounded-lg text-[10px] font-black uppercase tracking-wider transition-all flex items-center justify-center gap-2 shadow-lg">
                                 <i class="fas fa-check-circle"></i> Pagado
                             </button>
                             <button @click="paymentStatus = 'pending'"
                                     :class="{'bg-yellow-600 text-white shadow-yellow-900/40': paymentStatus === 'pending', 'bg-zinc-800 text-zinc-500 hover:text-white': paymentStatus !== 'pending'}"
                                     class="flex-1 py-2 rounded-lg text-[10px] font-black uppercase tracking-wider transition-all flex items-center justify-center gap-2 shadow-lg">
                                 <i class="fas fa-clock"></i> Pendiente
                             </button>
                        </div>
                        <button @click="proceedToOrder()" :disabled="cart.length === 0"
                            class="w-full bg-emerald-600 hover:bg-emerald-500 text-white py-3.5 rounded-xl font-black uppercase tracking-widest transition-all disabled:opacity-20 disabled:cursor-not-allowed text-sm shadow-xl shadow-emerald-900/20 active:scale-95">
                            Procesar Orden
                        </button>
                        <div class="grid grid-cols-2 gap-3">
                            <div class="relative w-full" x-data="{ open: false }" @click.away="open = false">
                                <button @click="open = !open" :disabled="cart.length === 0"
                                    class="w-full border border-zinc-700 text-zinc-400 py-2.5 rounded-xl font-black uppercase tracking-widest hover:bg-zinc-800 transition-all disabled:opacity-20 flex items-center justify-center gap-2 text-[10px]">
                                    <i class="fas fa-share-alt text-pink-500"></i> COTIZACIÓN
                                </button>
                                
                                <div x-show="open" x-cloak
                                    class="absolute bottom-full left-0 w-full mb-2 bg-zinc-900 border border-zinc-800 rounded-xl shadow-2xl overflow-hidden z-[100]">
                                    <button @click="shareWhatsApp(); open = false" class="w-full text-left px-4 py-3 text-[10px] font-bold text-zinc-300 hover:bg-zinc-800 flex items-center gap-3 transition-colors">
                                        <i class="fab fa-whatsapp text-emerald-500 text-sm"></i> ENVIAR TEXTO WHATSAPP
                                    </button>
                                    <button @click="exportQuotation('copy'); open = false" class="w-full text-left px-4 py-3 text-[10px] font-bold text-zinc-300 hover:bg-zinc-800 border-t border-zinc-800/50 flex items-center gap-3 transition-colors">
                                        <i class="fas fa-copy text-pink-400 text-sm"></i> COPIAR IMAGEN (PARA PEGAR EN WA)
                                    </button>
                                    <button @click="exportQuotation('image'); open = false" class="w-full text-left px-4 py-3 text-[10px] font-bold text-zinc-300 hover:bg-zinc-800 border-t border-zinc-800/50 flex items-center gap-3 transition-colors">
                                        <i class="fas fa-image text-blue-400 text-sm"></i> DESCARGAR IMAGEN (JPG)
                                    </button>
                                    <button @click="exportQuotation('pdf'); open = false" class="w-full text-left px-4 py-3 text-[10px] font-bold text-zinc-300 hover:bg-zinc-800 border-t border-zinc-800/50 flex items-center gap-3 transition-colors">
                                        <i class="fas fa-file-pdf text-red-500 text-sm"></i> DESCARGAR PDF
                                    </button>
                                </div>
                            </div>
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
    <!-- Hidden Quotation Template for Export -->
    <div id="quotation-template" 
         class="fixed bg-white p-10 w-[600px] leading-tight pointer-events-none" 
         :style="'opacity: ' + (isExporting ? '1' : '0') + '; z-index: ' + (isExporting ? '-1' : '-999') + '; left: ' + (isExporting ? '0' : '-9999px') + '; top: ' + (isExporting ? '0' : '-9999px') + ';'"
         style="font-family: 'Arial', sans-serif; color: #18181b; left: -9999px; top: -9999px; letter-spacing: 0.5px;">
         <div class="flex justify-between items-center pb-6" style="border-bottom: 2px solid #ec4899;">
             <div>
                 <img src="{{ asset('mincoli_logo.png') }}" alt="Mincoli" class="h-16 w-auto mb-2 object-contain">
                 <p class="text-xs font-bold uppercase tracking-widest" style="color: #71717a;">Tienda Online</p>
             </div>
             <div class="text-right">
                 <h2 class="text-xl font-black uppercase" style="color: #db2777;">Cotización</h2>
                 <p class="text-[10px] font-bold" style="color: #71717a;" x-text="new Date().toLocaleString('es-MX', { dateStyle: 'long', timeStyle: 'short' })"></p>
             </div>
         </div>

        <div class="mt-8 grid grid-cols-2 gap-8">
            <div>
                <h3 class="text-[10px] font-black uppercase mb-2 tracking-widest" style="color: #000000;">Cliente</h3>
                <p class="text-sm font-black uppercase" style="color: #000000; word-spacing: 1px;" x-text="(linkedCustomer ? linkedCustomer.name : (manualCustomer.name ? manualCustomer.name : 'Público General'))"></p>
                <p class="text-xs font-bold" style="color: #000000;" x-text="(linkedCustomer ? linkedCustomer.phone : (manualCustomer.phone ? manualCustomer.phone : '-'))"></p>
            </div>
            <div class="text-right">
                <h3 class="text-[10px] font-black uppercase mb-2 tracking-widest" style="color: #000000;">Métodos de Pago</h3>
                <p class="text-[9px] font-bold leading-relaxed" style="color: #000000;">Depósitos OXXO: 2242 1701 8074 1927</p>
                <p class="text-[9px] font-bold leading-relaxed" style="color: #000000;">CLABE AZTECA: 1271 8001 3158 064 597</p>
            </div>
        </div>

        <div class="mt-10">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr style="border-bottom: 1px solid #000000;">
                        <th class="py-3 text-[10px] font-black uppercase tracking-widest" style="color: #000000;">Producto</th>
                        <th class="py-3 text-[10px] font-black uppercase tracking-widest text-center" style="color: #000000;">Cant.</th>
                        <th class="py-3 text-[10px] font-black uppercase tracking-widest text-right" style="color: #000000;">Precio</th>
                        <th class="py-3 text-[10px] font-black uppercase tracking-widest text-right" style="color: #000000;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="item in cart" :key="item.id + (item.variant ? '-' + item.variant.id : '')">
                        <tr style="border-bottom: 1px solid #e4e4e7;">
                            <td class="py-4">
                                <div class="text-xs font-black uppercase" style="color: #000000; word-spacing: 1px;" x-text="item.name"></div>
                                <div x-show="item.variant" class="text-[9px] font-bold mt-0.5" style="color: #000000;" x-text="item.variant ? item.variant.name : ''"></div>
                            </td>
                            <td class="py-4 text-center text-xs font-bold" style="color: #000000;" x-text="item.quantity"></td>
                            <td class="py-4 text-right text-xs font-bold" style="color: #000000;" x-text="'$' + parseFloat(item.price).toLocaleString()"></td>
                            <td class="py-4 text-right text-xs font-black" style="color: #db2777;" x-text="'$' + (item.price * item.quantity).toLocaleString()"></td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>

        <div class="mt-8 flex justify-end">
            <div class="w-64 space-y-2">
                <div class="flex justify-between text-[10px] font-bold uppercase" style="color: #000000;" x-show="showIva">
                    <span>Subtotal</span>
                    <span x-text="'$' + subtotal.toLocaleString()"></span>
                </div>
                <div class="flex justify-between text-[10px] font-bold uppercase" style="color: #000000;" x-show="showIva">
                    <span>IVA (16%)</span>
                    <span x-text="'$' + (total - subtotal).toLocaleString()"></span>
                </div>
                <div class="flex justify-between items-center py-3 mt-2" style="border-top: 2px solid #ec4899;">
                    <span class="text-xs font-black uppercase tracking-tight" style="color: #000000;">Total</span>
                    <span class="text-2xl font-black" style="color: #db2777; display: block;" x-text="'$' + total.toLocaleString()"></span>
                </div>
            </div>
        </div>

        <div class="mt-12 text-center pt-8" style="border-top: 1px solid #f4f4f5;">
            <p class="text-[9px] font-bold uppercase tracking-[0.2em]" style="color: #a1a1aa;">¡Gracias por tu preferencia! — mincoli.com</p>
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
        input::placeholder { color: #71717a !important; }
    </style>

    <script>
        function posSystem() {
            return {
                search: '',
                activeCategoryId: null,
                activeSubcategoryId: null,
                isLoading: false,
                isExporting: false,
                 products: @json($products->items()),
                 categories: @json($categories),
                 cart: [],
                 showIva: {{ $showIva ? 'true' : 'false' }},
                 perPage: {{ $perPage }},
                 currentPage: {{ $products->currentPage() }},
                 lastPage: {{ $products->lastPage() }},
                 totalProducts: {{ $products->total() }},

                 // Customer logic
                 customerSearch: '',
                 customerResults: [],
                 linkedCustomer: null,
                 manualCustomerMode: false,
                 manualCustomer: { name: '', phone: '' },
                 paymentStatus: 'paid',

                 // Initialize customer data from localStorage
                 init() {
                     try {
                         // Load persisted customer data
                         const savedCustomer = localStorage.getItem('posLinkedCustomer');
                         const savedManualCustomer = localStorage.getItem('posManualCustomer');
                         const savedManualMode = localStorage.getItem('posManualCustomerMode');
                         
                         if (savedCustomer) {
                             this.linkedCustomer = JSON.parse(savedCustomer);
                         }
                         
                         if (savedManualCustomer) {
                             this.manualCustomer = JSON.parse(savedManualCustomer);
                         }
                         
                         if (savedManualMode === 'true') {
                             this.manualCustomerMode = true;
                         }
                         
                         // Watch for changes and save to localStorage
                         this.$watch('linkedCustomer', (value) => {
                             if (value) {
                                 localStorage.setItem('posLinkedCustomer', JSON.stringify(value));
                             } else {
                                 localStorage.removeItem('posLinkedCustomer');
                             }
                         });
                         
                         this.$watch('manualCustomer', (value) => {
                             localStorage.setItem('posManualCustomer', JSON.stringify(value));
                         });
                         
                         this.$watch('manualCustomerMode', (value) => {
                             localStorage.setItem('posManualCustomerMode', value);
                         });
                     } catch (e) {
                         console.error('Error initializing customer data:', e);
                     }
                 },

                 async updatePerPage() {
                    this.currentPage = 1;
                    await this.filterProducts();
                },

                async goToPage(page) {
                    this.currentPage = page;
                    await this.filterProducts();
                },

                async nextPage() {
                    if (this.currentPage < this.lastPage) {
                        this.currentPage++;
                        await this.filterProducts();
                    }
                },

                 async prevPage() {
                    if (this.currentPage > 1) {
                        this.currentPage--;
                        await this.filterProducts();
                    }
                },

                setCategory(id) {
                    this.activeCategoryId = id;
                    this.activeSubcategoryId = null; // Reset subcategory when changing category
                    this.currentPage = 1;
                    this.filterProducts();
                },

                setSubcategory(id) {
                    this.activeSubcategoryId = id;
                    this.currentPage = 1;
                    this.filterProducts();
                },

                getSubcategories() {
                    if (!this.activeCategoryId) return [];
                    const category = this.categories.find(cat => cat.id === this.activeCategoryId);
                    return category ? category.children || [] : [];
                },

                 async filterProducts() {
                    this.isLoading = true;
                    try {
                        const params = new URLSearchParams({
                            q: this.search,
                            category_id: this.activeCategoryId || '',
                            subcategory_id: this.activeSubcategoryId || '',
                            per_page: this.perPage || 30,
                            page: this.currentPage || 1
                        });
                        const resp = await fetch(`{{ route('dashboard.pos.searchProduct') }}?${params.toString()}`);
                        const result = await resp.json();
                        
                        if (result.data) {
                            this.products = result.data;
                            this.currentPage = result.current_page;
                            this.lastPage = result.last_page;
                            this.totalProducts = result.total;
                        } else {
                            this.products = result;
                        }
                    } catch (e) {
                        console.error('Error searching products', e);
                    } finally {
                        this.isLoading = false;
                    }
                },

                get filteredProducts() {
                    return this.products;
                },

                getSubcategories() {
                    if (!this.activeCategoryId) return [];
                    const category = this.categories.find(c => c.id === this.activeCategoryId);
                    return category ? category.children : [];
                },

                setCategory(id) {
                    this.activeCategoryId = id;
                    this.activeSubcategoryId = null; // Reset subcategory
                    this.currentPage = 1;
                    this.filterProducts();
                },

                setSubcategory(id) {
                    this.activeSubcategoryId = id;
                    this.currentPage = 1;
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
                         // Clear localStorage
                         localStorage.removeItem('posLinkedCustomer');
                         localStorage.removeItem('posManualCustomer');
                         localStorage.removeItem('posManualCustomerMode');
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
                    
                    let message = `Hola${clientName ? ' ' + clientName : ''}! te comparto tu cotización de Mincoli:\n\n`;
                    this.cart.forEach(item => {
                        const variantStr = item.variant ? ` (${item.variant.name})` : '';
                        const totalItem = parseFloat(item.price) * item.quantity;
                        message += `▪️ *${item.quantity}x* ${item.name}${variantStr}\n   Precio unitario: $${parseFloat(item.price).toLocaleString('es-MX', {minimumFractionDigits: 2})} | Total: $${totalItem.toLocaleString('es-MX', {minimumFractionDigits: 2})}\n`;
                    });
                    
                    message += `\n*TOTAL A PAGAR: $${this.total.toLocaleString('es-MX', {minimumFractionDigits: 2})}*\n`;
                    
                    message += `\n--------------------------\n`;
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

                async exportQuotation(type) {
                    if (this.cart.length === 0) {
                        alert('El carrito está vacío');
                        return;
                    }
                    
                    this.isLoading = true;
                    this.isExporting = true;
                    
                    try {
                        // Wait for Alpine to show and render the template
                        await this.$nextTick();
                        
                        // Ensure logo is loaded
                        const logoImg = document.querySelector('#quotation-template img');
                        if (logoImg && !logoImg.complete) {
                             await new Promise((resolve) => {
                                logoImg.onload = resolve;
                                logoImg.onerror = resolve; // Continue even if error
                             });
                        }
                        
                        await new Promise(r => setTimeout(r, 1000)); // Extra buffer for fonts
                        
                        const element = document.getElementById('quotation-template');
                        
                        // Verify element is visible and has content
                        if (!element || element.offsetHeight === 0) {
                            throw new Error('Template not rendered');
                        }
                        
                        // Safety check for Image constructor
                        if (typeof window.Image !== 'function') {
                            console.warn('Image constructor corrupted, attempting restore...');
                            // Create an iframe to recover native Image constructor if absolutely necessary
                            const iframe = document.createElement('iframe');
                            iframe.style.display = 'none';
                            document.body.appendChild(iframe);
                            window.Image = iframe.contentWindow.Image;
                            document.body.removeChild(iframe);
                        }
                        
                        // Capture with html2canvas
                        const canvas = await html2canvas(element, {
                            scale: 2,
                            backgroundColor: '#ffffff',
                            logging: false, 
                            useCORS: true,
                            allowTaint: false, // Must be false for toDataURL
                            windowWidth: 600,
                            windowHeight: element.scrollHeight
                        });

                        // Validate canvas
                        if (!canvas || canvas.width === 0 || canvas.height === 0) {
                            throw new Error('Canvas generation failed');
                        }

                        if (type === 'image') {
                            const dataUrl = canvas.toDataURL('image/jpeg', 0.95);
                            const link = document.createElement('a');
                            link.download = `Cotizacion_Mincoli_${new Date().getTime()}.jpg`;
                            link.href = dataUrl;
                            link.click();
                        } else if (type === 'pdf') {
                            const { jsPDF } = window.jspdf;
                            const pdf = new jsPDF('p', 'mm', 'a4');
                            
                            const imgData = canvas.toDataURL('image/jpeg', 0.98);
                            
                            // Use canvas dimensions directly
                            const imgWidth = 190; // A4 width in mm minus margins
                            const imgHeight = (canvas.height * imgWidth) / canvas.width;
                            
                            pdf.addImage(imgData, 'JPEG', 10, 10, imgWidth, imgHeight);
                            pdf.save(`Cotizacion_Mincoli_${new Date().getTime()}.pdf`);
                        } else if (type === 'copy') {
                            canvas.toBlob(async (blob) => {
                                try {
                                    if (!blob) throw new Error('Blob creation failed');
                                    
                                    if (navigator.clipboard && navigator.clipboard.write) {
                                        const data = [new ClipboardItem({ [blob.type]: blob })];
                                        await navigator.clipboard.write(data);
                                        alert('¡Imagen copiada al portapapeles! Ya puedes pegarla en WhatsApp.');
                                    } else {
                                        throw new Error('Clipboard API unavailable');
                                    }
                                } catch (err) {
                                    console.warn('Clipboard write failed, falling back to preview', err);
                                    // Fallback mainly for non-secure contexts (http) or unsupported browsers
                                    alert('No pudimos copiar al portapapeles directamente. Descarga la imagen en su lugar.');
                                    const dataUrl = canvas.toDataURL('image/jpeg', 0.95);
                                    const link = document.createElement('a');
                                    link.download = `Cotizacion_Mincoli_${new Date().getTime()}.jpg`;
                                    link.href = dataUrl;
                                    link.click();
                                }
                            }, 'image/png');
                        }
                    } catch (e) {
                        console.error('Export error', e);
                        alert('Error al generar la cotización: ' + e.message + '\n\nIntenta de nuevo o usa la opción de WhatsApp (texto).');
                    } finally {
                        this.isExporting = false;
                        this.isLoading = false;
                    }
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
                                customer_phone: this.manualCustomerMode ? this.manualCustomer.phone : null,
                                payment_status: this.paymentStatus
                            })
                        });

                        const result = await response.json();

                        if (result.success) {
                            // Redirigir a la vista de éxito premium
                            if (result.redirect_url) {
                                window.location.href = result.redirect_url;
                            } else {
                                // Fallback
                                alert(`Orden ${result.order_number} generada correctamente.`);
                                this.cart = [];
                                this.linkedCustomer = null;
                                this.manualCustomer = { name: '', phone: '' };
                                this.manualCustomerMode = false;
                                this.customerSearch = '';
                                // Clear localStorage
                                localStorage.removeItem('posLinkedCustomer');
                                localStorage.removeItem('posManualCustomer');
                                localStorage.removeItem('posManualCustomerMode');
                            }
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
