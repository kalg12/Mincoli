<!-- Cart Drawer Overlay -->
<div id="cart-drawer-overlay" class="fixed inset-0 bg-black/30 backdrop-blur z-50 hidden opacity-0 transition-opacity duration-300"></div>

<!-- Cart Drawer -->
<div id="cart-drawer" class="fixed top-0 right-0 bottom-0 w-full max-w-full sm:max-w-[420px] md:max-w-[460px] lg:max-w-[500px] bg-white z-60 shadow-2xl transform translate-x-full transition-transform duration-300 ease-out flex flex-col">
    <!-- Header -->
    <div class="flex items-center justify-between px-5 py-4 border-b border-gray-200 bg-white sticky top-0 z-10">
        <h2 class="text-lg font-bold text-gray-900 flex items-center">
            <i class="fas fa-shopping-cart text-pink-600 mr-2"></i>
            Tu Carrito <span class="ml-2 text-sm font-normal text-gray-500">(3)</span>
        </h2>
        <button type="button" onclick="closeCartDrawer()" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100 text-gray-500 hover:text-gray-700 transition">
            <i class="fas fa-times text-xl"></i>
        </button>
    </div>

    <!-- Cart Content -->
    <div class="flex-1 overflow-y-auto overscroll-contain">
        <!-- Promotional Banners -->
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white p-3 text-center text-xs font-medium">
            <i class="fas fa-undo mr-1.5"></i>
            30 días para cambios y devoluciones
        </div>
        <div class="bg-purple-50 text-purple-800 p-2.5 text-center text-xs font-medium border-b border-purple-100">
            Termina tu compra, tus piezas ya no están reservadas
        </div>

        <!-- Promotional Offer Bar -->
        <div class="bg-gradient-to-r from-pink-50 to-purple-50 border-b border-pink-100">
            <div class="flex items-center gap-3 p-3 overflow-x-auto scrollbar-hide">
                <div class="flex-shrink-0 text-center">
                    <div class="w-16 h-16 bg-gradient-to-br from-pink-100 to-pink-200 rounded-xl mb-1.5 flex items-center justify-center">
                        <i class="fas fa-percentage text-pink-600 text-lg"></i>
                    </div>
                    <p class="text-[10px] font-bold text-gray-700">-30% OFF</p>
                </div>
                <div class="flex-shrink-0 text-center">
                    <div class="w-16 h-16 bg-gradient-to-br from-purple-100 to-purple-200 rounded-xl mb-1.5 flex items-center justify-center">
                        <i class="fas fa-gem text-purple-600 text-lg"></i>
                    </div>
                    <p class="text-[10px] font-bold text-gray-700 leading-tight">JOYERÍA<br>GRATIS</p>
                </div>
            </div>
        </div>

        <!-- Cart Items -->
        <div class="divide-y divide-gray-100">
            <!-- Free Gift Item -->
            <div class="p-4 bg-gradient-to-r from-green-50 to-emerald-50">
                <div class="flex items-start gap-3">
                    <div class="w-20 h-20 bg-gradient-to-br from-green-400 to-emerald-500 rounded-xl flex items-center justify-center flex-shrink-0 shadow-sm">
                        <i class="fas fa-gift text-white text-xl"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between mb-1">
                            <h3 class="font-semibold text-gray-900 text-sm pr-2">Accesorio Sorpresa</h3>
                        </div>
                        <p class="text-xs text-gray-600 mb-2">Aretes - Anillo - Collar - Pulsera</p>
                        <div class="flex items-center justify-between">
                            <span class="text-xs line-through text-gray-400">400 MXN</span>
                            <span class="text-green-600 font-bold text-sm">GRATIS</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cart Item 1 -->
            <div class="p-4 hover:bg-gray-50 transition">
                <div class="flex items-start gap-3 relative">
                    <img src="https://images.unsplash.com/photo-1611955167811-4711904bb9f8?w=200&h=200&fit=crop" alt="Anillo Sweets" class="w-20 h-20 object-cover rounded-xl flex-shrink-0 shadow-sm">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between mb-1">
                            <h3 class="font-semibold text-gray-900 text-sm pr-2">Anillo Sweets</h3>
                            <button type="button" class="text-gray-400 hover:text-red-500 transition -mt-1">
                                <i class="fas fa-times text-sm"></i>
                            </button>
                        </div>
                        <p class="text-xs text-gray-500 mb-3">Talla #5 • Azul</p>
                        <div class="flex items-center justify-between gap-3">
                            <div class="flex items-center border border-gray-200 rounded-lg overflow-hidden">
                                <button type="button" class="px-2.5 py-1.5 text-gray-600 hover:bg-gray-100 active:bg-gray-200 transition">
                                    <i class="fas fa-minus text-[10px]"></i>
                                </button>
                                <span class="px-3 py-1.5 text-sm font-semibold min-w-[2rem] text-center">1</span>
                                <button type="button" class="px-2.5 py-1.5 text-gray-600 hover:bg-gray-100 active:bg-gray-200 transition">
                                    <i class="fas fa-plus text-[10px]"></i>
                                </button>
                            </div>
                            <div class="text-right">
                                <span class="text-[10px] line-through text-gray-400 block">400 MXN</span>
                                <span class="text-pink-600 font-bold text-sm">149.25 MXN</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cart Item 2 -->
            <div class="p-4 hover:bg-gray-50 transition">
                <div class="flex items-start gap-3 relative">
                    <img src="https://images.unsplash.com/photo-1524592094714-0f0654e20314?w=200&h=200&fit=crop" alt="Reloj Archive" class="w-20 h-20 object-cover rounded-xl flex-shrink-0 shadow-sm">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between mb-1">
                            <h3 class="font-semibold text-gray-900 text-sm pr-2">Reloj Archive 02 - Edición limitada</h3>
                            <button type="button" class="text-gray-400 hover:text-red-500 transition -mt-1">
                                <i class="fas fa-times text-sm"></i>
                            </button>
                        </div>
                        <p class="text-xs text-gray-500 mb-3">1 año de garantía en mecanismo</p>
                        <div class="flex items-center justify-between gap-3">
                            <div class="flex items-center border border-gray-200 rounded-lg overflow-hidden">
                                <button type="button" class="px-2.5 py-1.5 text-gray-600 hover:bg-gray-100 active:bg-gray-200 transition">
                                    <i class="fas fa-minus text-[10px]"></i>
                                </button>
                                <span class="px-3 py-1.5 text-sm font-semibold min-w-[2rem] text-center">1</span>
                                <button type="button" class="px-2.5 py-1.5 text-gray-600 hover:bg-gray-100 active:bg-gray-200 transition">
                                    <i class="fas fa-plus text-[10px]"></i>
                                </button>
                            </div>
                            <div class="text-right">
                                <span class="text-[10px] line-through text-gray-400 block">2,500 MXN</span>
                                <span class="text-pink-600 font-bold text-sm">1,349.25 MXN</span>
                            </div>
                        </div>
                    </div>
            </div>

            <!-- Gift Promo Banner -->
            <div class="p-4 bg-gradient-to-r from-purple-50 via-pink-50 to-purple-50 border-t border-purple-100">
                <div class="flex items-center gap-3">
                    <div class="w-16 h-16 bg-gradient-to-br from-purple-400 to-pink-500 rounded-xl flex items-center justify-center flex-shrink-0 shadow-sm">
                        <i class="fas fa-gem text-white text-lg"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="font-bold text-gray-900 text-sm mb-0.5">JOYERÍA GRATIS</h3>
                        <p class="text-xs text-gray-600 leading-relaxed">Agrega 3 MXN producto y escoge tu regalo gratis.</p>
                    </div>
                    <button type="button" class="text-pink-600 hover:text-pink-700 transition">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer Summary -->
    <div class="border-t-2 border-gray-200 bg-white p-5 space-y-4 shadow-[0_-4px_12px_rgba(0,0,0,0.05)]">
        <!-- Discount Badge -->
        <div class="flex items-center gap-2">
            <div class="bg-black text-white px-2.5 py-1 rounded-md flex items-center text-xs font-bold">
                <i class="fas fa-tag mr-1.5"></i>
                -25%
            </div>
        </div>

        <!-- Summary Lines -->
        <div class="space-y-2 text-sm">
            <div class="flex justify-between items-center">
                <span class="text-gray-600">Subtotal</span>
                <span class="font-semibold text-gray-900">2,197 MXN</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-gray-600">Envío</span>
                <span class="text-green-600 font-bold">GRATIS</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-gray-600">Descuentos</span>
                <span class="text-pink-600 font-bold">-698.50 MXN</span>
            </div>
        </div>

            </div>
        </div>

        <div class="pt-3 border-t-2 border-gray-200">
            <div class="flex justify-between items-center mb-4">
                <span class="text-base font-bold text-gray-900">Total</span>
                <span class="text-2xl font-bold text-pink-600">1,498.50 MXN</span>
            </div>

            <button type="button" class="w-full bg-gradient-to-r from-purple-500 to-pink-500 hover:from-purple-600 hover:to-pink-600 text-white font-bold py-3.5 rounded-xl transition shadow-lg hover:shadow-xl active:scale-[0.98] mb-3">
                Finalizar Pedido
            </button>

            <button type="button" onclick="closeCartDrawer()" class="w-full bg-white hover:bg-gray-50 text-gray-700 font-semibold py-3 rounded-xl border-2 border-gray-300 transition active:scale-[0.98]">
                Seguir comprando
            </button>
        </div>

        <!-- Trust Badges -->
        <div class="grid grid-cols-2 gap-3 pt-4 border-t border-gray-200 mt-4">
            <div class="flex items-start gap-2">
                <div class="w-9 h-9 bg-yellow-100 rounded-full flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-sun text-yellow-600 text-sm"></i>
                </div>
                <div class="text-[10px] leading-tight">
                    <p class="font-bold text-gray-900">365 días de</p>
                    <p class="text-gray-600">garantía en color</p>
                </div>
            </div>
            <div class="flex items-start gap-2">
                <div class="w-9 h-9 bg-orange-100 rounded-full flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-redo text-orange-600 text-sm"></i>
                </div>
                <div class="text-[10px] leading-tight">
                    <p class="font-bold text-gray-900">Menos de 1%</p>
                    <p class="text-gray-600">regresan productos</p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function openCartDrawer() {
        const drawer = document.getElementById('cart-drawer');
        const overlay = document.getElementById('cart-drawer-overlay');

        if (!drawer || !overlay) return;

        overlay.classList.remove('hidden');
        document.body.style.overflow = 'hidden';

        requestAnimationFrame(() => {
            overlay.classList.remove('opacity-0');
            drawer.classList.remove('translate-x-full');
        });
    }

    function closeCartDrawer() {
        const drawer = document.getElementById('cart-drawer');
        const overlay = document.getElementById('cart-drawer-overlay');

        if (!drawer || !overlay) return;

        overlay.classList.add('opacity-0');
        drawer.classList.add('translate-x-full');
        document.body.style.overflow = '';

        setTimeout(() => {
            overlay.classList.add('hidden');
        }, 300);
    }

    // Close on ESC key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closeCartDrawer();
        }
    });

    // Close on overlay click
    document.getElementById('cart-drawer-overlay')?.addEventListener('click', closeCartDrawer);
</script>
@endpush
