<!-- Cart Drawer Overlay -->
<div id="cart-drawer-overlay" class="fixed inset-0 bg-black/50 z-[9998] hidden opacity-0 transition-opacity duration-300"></div>

<!-- Cart Drawer -->
<div id="cart-drawer" class="fixed top-0 right-0 bottom-0 w-full max-w-full sm:max-w-[420px] md:max-w-[480px] bg-white z-[9999] shadow-2xl transform translate-x-full transition-transform duration-300 ease-out flex flex-col">
    <!-- Header -->
    <div class="flex items-center justify-between px-5 py-4 border-b border-gray-200 bg-white sticky top-0 z-10">
        <h2 class="text-lg font-bold text-gray-900 flex items-center">
            <i class="fas fa-shopping-cart text-pink-600 mr-2"></i>
            Tu Carrito <span id="cart-count" class="ml-2 text-sm font-normal text-gray-500">(0)</span>
        </h2>
        <button type="button" onclick="window.closeCartDrawer()" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100 text-gray-500 hover:text-gray-700 transition">
            <i class="fas fa-times text-xl"></i>
        </button>
    </div>

    <!-- Cart Content -->
    <div class="flex-1 overflow-y-auto overscroll-contain">
        <!-- Cart Items Section -->
        <div id="cart-items-container" class="divide-y divide-gray-100">
            <div class="p-8 text-center text-gray-500">
                <i class="fas fa-shopping-cart text-4xl mb-3 block opacity-30"></i>
                <p class="text-sm">Tu carrito está vacío</p>
            </div>
        </div>

        <!-- Recomendaciones Section -->
        <div id="recommendations-section" class="hidden bg-gradient-to-b from-pink-50 via-white to-white border-t-2 border-pink-100">
            <div class="p-4">
                <h3 class="text-sm font-bold text-gray-900 mb-3 flex items-center">
                    <span class="inline-block w-1 h-5 bg-pink-600 rounded-full mr-2"></span>
                    <i class="fas fa-sparkles text-pink-600 mr-2"></i>
                    Quizás te interese
                </h3>
                <div id="recommendations-list" class="space-y-2">
                    <!-- Recomendaciones cargadas dinámicamente -->
                </div>
            </div>
        </div>
    </div>

    <!-- Footer Summary -->
    <div class="border-t-2 border-gray-200 bg-white p-5 space-y-4 shadow-[0_-4px_12px_rgba(0,0,0,0.05)]">
        <!-- Summary Lines -->
        <div class="space-y-2 text-sm">
            <div class="flex justify-between">
                <span class="text-gray-600">Subtotal:</span>
                <span id="cart-subtotal" class="font-medium">$0.00</span>
            </div>
            <div id="cart-iva-row" class="flex justify-between">
                <span class="text-gray-600">IVA (16%):</span>
                <span id="cart-iva" class="font-medium">$0.00</span>
            </div>
            <div class="border-t pt-2 flex justify-between text-lg font-bold">
                <span>Total:</span>
                <span id="cart-total" class="text-pink-600">$0.00</span>
            </div>
        </div>

        <!-- Buttons -->
        <a href="{{ route('cart') }}" class="w-full bg-gradient-to-r from-purple-500 to-pink-500 hover:from-purple-600 hover:to-pink-600 text-white font-bold py-3.5 rounded-xl transition shadow-lg hover:shadow-xl active:scale-[0.98] block text-center">
            Finalizar Pedido
        </a>

        <button type="button" onclick="window.closeCartDrawer()" class="w-full bg-white hover:bg-gray-50 text-gray-700 font-semibold py-3 rounded-xl border-2 border-gray-300 transition active:scale-[0.98]">
            Seguir comprando
        </button>
    </div>
</div>

@push('scripts')
<script>
// Variables globales del carrito
window.cartData = {
    items: [],
    recommendations: [],
    subtotal: 0,
    iva: 0,
    total: 0,
    show_iva: true
};

// Control de concurrencia
let pendingRequest = null;
let requestQueue = [];

// Actualizar carrito desde servidor
async function updateCart() {
    try {
        const response = await fetch('{{ route("cart.data") }}');
        const data = await response.json();

        window.cartData = {
            items: data.items.map(item => ({
                ...item,
                quantity: parseInt(item.quantity),
                unit_price: parseFloat(item.unit_price),
                subtotal: parseFloat(item.subtotal)
            })),
            recommendations: data.recommendations || [],
            subtotal: parseFloat(data.subtotal),
            iva: parseFloat(data.iva),
            total: parseFloat(data.total),
            show_iva: data.show_iva !== undefined ? data.show_iva : true
        };

        renderCart();
        updateTotals();
    } catch (error) {
        console.error('Error updating cart:', error);
    }
}

// Renderizar items del carrito
function renderCart() {
    const container = document.getElementById('cart-items-container');

    if (window.cartData.items.length === 0) {
        container.innerHTML = `
            <div class="p-8 text-center text-gray-500">
                <i class="fas fa-shopping-cart text-4xl mb-3 block opacity-30"></i>
                <p class="text-sm">Tu carrito está vacío</p>
            </div>
        `;
        return;
    }

    container.innerHTML = window.cartData.items.map(item => `
        <div class="p-4 hover:bg-gray-50 transition border-b border-gray-100 last:border-b-0">
            <div class="flex items-start gap-3">
                <div class="relative overflow-hidden bg-gray-100 rounded-lg w-20 h-20 flex-shrink-0">
                    ${item.product.image && item.product.image !== '/images/placeholder.jpg'
                        ? `<img src="${item.product.image}" alt="${item.product.name}" class="w-full h-full object-contain p-1" onerror="this.parentElement.innerHTML='<div class=\\'w-full h-full flex items-center justify-center\\'><i class=\\'fas fa-image text-gray-300 text-2xl\\'></i></div>'">`
                        : `<div class="w-full h-full flex items-center justify-center"><i class="fas fa-image text-gray-300 text-2xl"></i></div>`
                    }
                </div>
                <div class="flex-1 min-w-0 flex flex-col justify-between">
                    <div>
                        <div class="flex items-start justify-between mb-1">
                            <h3 class="font-semibold text-gray-900 text-sm pr-2 line-clamp-2">${item.product.name}</h3>
                            <button type="button" onclick="window.removeFromCart('${item.id}')" class="text-gray-400 hover:text-red-500 transition flex-shrink-0">
                                <i class="fas fa-times text-sm"></i>
                            </button>
                        </div>
                        ${item.variant ? `<p class="text-xs text-gray-700 mb-1">Variante: <span class="font-medium">${item.variant.name}</span>${(item.variant.size || (item.variant.color && !item.variant.color.startsWith('#'))) ? ` — ${item.variant.size || ''} ${item.variant.color && !item.variant.color.startsWith('#') ? '· ' + item.variant.color : ''}` : ''}</p>` : ''}
                        <p class="text-xs text-gray-500 mb-2">${item.variant && item.variant.sku ? item.variant.sku : item.product.sku}</p>

                        <!-- Price Display with Discount -->
                        <div class="mb-2">
                            <span class="text-lg font-bold text-pink-600">$${(parseFloat(item.unit_price) || 0).toLocaleString('es-MX', {minimumFractionDigits: 2})}</span>
                            ${item.product.has_discount ? `<span class="text-base text-gray-500 line-through decoration-gray-500 decoration-1.5 ml-2">$${(parseFloat(item.product.original_price) || 0).toLocaleString('es-MX', {minimumFractionDigits: 2})}</span>` : ''}
                        </div>
                    </div>

                    <div class="flex items-center justify-between gap-2">
                        <div class="flex items-center border border-gray-200 rounded-lg overflow-hidden bg-white">
                            <button type="button" onclick="window.updateQuantity('${item.id}', Math.max(1, ${item.quantity - 1}))" class="px-2.5 py-1.5 text-gray-600 hover:bg-gray-100 active:bg-gray-200 transition">
                                <i class="fas fa-minus text-[10px]"></i>
                            </button>
                            <span class="px-3 py-1.5 text-sm font-semibold min-w-[2rem] text-center">${item.quantity}</span>
                            <button type="button" ${item.quantity >= item.max_stock ? 'disabled' : ''} onclick="window.updateQuantity('${item.id}', Math.min(${item.max_stock}, ${item.quantity + 1}))" class="px-2.5 py-1.5 text-gray-600 hover:bg-gray-100 active:bg-gray-200 transition ${item.quantity >= item.max_stock ? 'opacity-50 cursor-not-allowed' : ''}">
                                <i class="fas fa-plus text-[10px]"></i>
                            </button>
                        </div>
                        <div class="text-xs text-gray-500">${item.max_stock > 0 ? (item.max_stock + ' disponibles') : 'Sin stock'}</div>
                        <div class="text-right">
                            <span class="text-sm font-bold text-pink-600">$${(parseFloat(item.subtotal) || 0).toLocaleString('es-MX', {minimumFractionDigits: 2})}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `).join('');

    // Mostrar recomendaciones si hay items
    if (window.cartData.recommendations && window.cartData.recommendations.length > 0) {
        document.getElementById('recommendations-section').classList.remove('hidden');
        renderRecommendations();
    } else {
        document.getElementById('recommendations-section').classList.add('hidden');
    }
}

// Renderizar recomendaciones
function renderRecommendations() {
    const container = document.getElementById('recommendations-list');

    container.innerHTML = window.cartData.recommendations.map(rec => {
        // Precio
        const priceHtml = rec.has_discount
            ? `<span class="text-xs font-bold text-pink-600">$${parseFloat(rec.price).toLocaleString('es-MX', {minimumFractionDigits: 2})}</span>
               <span class="text-xs line-through text-gray-500 decoration-gray-500 decoration-1">$${parseFloat(rec.original_price).toLocaleString('es-MX', {minimumFractionDigits: 2})}</span>`
            : `<span class="text-xs font-bold text-pink-600">$${parseFloat(rec.price).toLocaleString('es-MX', {minimumFractionDigits: 2})}</span>`;

        // Imagen (igual al carrito)
        const imageHtml = rec.image && rec.image !== '/images/placeholder.jpg'
            ? `<img src="${rec.image}" alt="${rec.name}" class="w-full h-full object-contain p-1" onerror="this.parentElement.innerHTML='<div class=\\'w-full h-full flex items-center justify-center\\'><i class=\\'fas fa-image text-gray-300 text-2xl\\'></i></div>'">`
            : `<div class="w-full h-full flex items-center justify-center"><i class="fas fa-image text-gray-300 text-2xl"></i></div>`;

        // Selector de variantes
        let actionHtml = '';
        if (rec.has_variants) {
            const variantOptions = (rec.variants || []).map(v => {
                const variantLabel = v.name + (v.size ? ` — ${v.size}` : '') + (v.color && !v.color.startsWith('#') ? ` · ${v.color}` : '');
                const stockStatus = v.stock > 0 ? '' : ' (Agotado)';
                const disabled = v.stock <= 0 ? 'disabled' : '';
                return `<option value="${v.id}" ${disabled}>${variantLabel}${stockStatus}</option>`;
            }).join('');

            actionHtml = `
                <div class="mt-2 flex items-center gap-2">
                    <select id="rec-select-${rec.id}" class="flex-1 border border-gray-300 rounded-lg text-xs px-2 py-1 focus:ring-pink-500 focus:border-pink-500">
                        <option value="">Selecciona variante</option>
                        ${variantOptions}
                    </select>
                    <button type="button" onclick="window.quickAddRecommended(${rec.id})" class="bg-pink-600 hover:bg-pink-700 text-white rounded-full px-3 py-2 transition active:scale-90 flex-shrink-0">
                        <i class="fas fa-cart-plus text-xs"></i>
                    </button>
                </div>`;
        } else {
            actionHtml = `<button type="button" onclick="quickAddToCart(${rec.id})" class="bg-pink-600 hover:bg-pink-700 text-white rounded-full p-2 transition active:scale-90 flex-shrink-0">
                <i class="fas fa-plus text-xs"></i>
            </button>`;
        }

        return `
        <div class="bg-white rounded-lg p-3 hover:shadow-md transition border border-gray-200 hover:border-pink-300">
            <div class="flex items-start gap-3">
                <div class="relative overflow-hidden bg-gray-100 rounded-lg w-20 h-20 flex-shrink-0">
                    ${imageHtml}
                </div>
                <div class="flex-1 min-w-0 flex flex-col justify-between">
                    <div>
                        <a href="/tienda/producto/${rec.slug}" class="text-xs font-semibold text-gray-900 hover:text-pink-600 line-clamp-2 block transition mb-1">
                            ${rec.name}
                        </a>
                        <div class="flex items-baseline gap-1">${priceHtml}</div>
                    </div>
                    <div class="mt-2">
                        ${rec.has_variants ? actionHtml : actionHtml}
                    </div>
                </div>
            </div>
        </div>`;
    }).join('');
}

// Agregar rápidamente un producto recomendado
window.quickAddToCart = async function(productId, variantId) {
    try {
        const response = await fetch('{{ route("cart.add") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({
                product_id: productId,
                quantity: 1,
                variant_id: variantId || null
            })
        });

        if (response.ok) {
            const data = await response.json();
            await updateCart();
            showToast('¡Producto agregado!', 'success');
        } else {
            const errorData = await response.json();
            showToast(errorData.message || 'Error al agregar producto', 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showToast('Error en la solicitud', 'error');
    }
};

// Agregar recomendado con selector de variante
window.quickAddRecommended = async function(productId) {
    var select = document.getElementById('rec-select-' + productId);
    var variantId = select ? select.value : '';
    if (!variantId) {
        showToast('Selecciona una variante', 'error');
        return;
    }
    await window.quickAddToCart(productId, variantId);
};

// Mostrar toast de notificación
function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `fixed bottom-4 right-4 px-6 py-3 rounded-lg text-white z-[10000] shadow-lg ${
        type === 'success' ? 'bg-green-500' : type === 'error' ? 'bg-red-500' : 'bg-blue-500'
    }`;
    toast.textContent = message;
    document.body.appendChild(toast);

    setTimeout(() => {
        toast.classList.add('opacity-0', 'transition-opacity');
        setTimeout(() => toast.remove(), 300);
    }, 2000);
}

// Actualizar totales en el drawer
function updateTotals() {
    document.getElementById('cart-subtotal').textContent = `$${window.cartData.subtotal.toLocaleString('es-MX', {minimumFractionDigits: 2})}`;
    document.getElementById('cart-iva').textContent = `$${window.cartData.iva.toLocaleString('es-MX', {minimumFractionDigits: 2})}`;
    document.getElementById('cart-total').textContent = `$${window.cartData.total.toLocaleString('es-MX', {minimumFractionDigits: 2})}`;
    document.getElementById('cart-count').textContent = `(${window.cartData.items.length})`;

    // Controlar visibilidad del IVA SIN flash: solo cambiar si el estado es diferente
    const ivaRow = document.getElementById('cart-iva-row');
    if (ivaRow) {
        const shouldShow = window.cartData.show_iva && window.cartData.iva > 0;
        const currentDisplay = window.getComputedStyle(ivaRow).display;
        const isCurrentlyVisible = currentDisplay !== 'none';
        
        // Solo cambiar si el estado deseado es diferente del actual
        if (shouldShow && !isCurrentlyVisible) {
            ivaRow.style.display = 'flex';
        } else if (!shouldShow && isCurrentlyVisible) {
            ivaRow.style.display = 'none';
        }
    }

    // Actualizar contador del header
    updateHeaderCartCount(window.cartData.items.length);
}

// Actualizar contador del header
function updateHeaderCartCount(count) {
    const headerCount = document.getElementById('header-cart-count');
    if (headerCount) {
        headerCount.textContent = count;
        if (count > 0) {
            headerCount.classList.remove('hidden');
        } else {
            headerCount.classList.add('hidden');
        }
    }
}

// Recalcular totales localmente
function recalculateTotals() {
    const subtotal = window.cartData.items.reduce((sum, item) => sum + parseFloat(item.subtotal || 0), 0);
    // Solo calcular IVA si está activado en la configuración
    const iva = window.cartData.show_iva ? (subtotal * 0.16) : 0;
    const total = subtotal + iva;

    window.cartData.subtotal = subtotal;
    window.cartData.iva = iva;
    window.cartData.total = total;

    updateTotals();
}

// Actualizar cantidad (con optimistic UI y cola de solicitudes)
window.updateQuantity = async function(itemId, newQuantity) {
    if (newQuantity <= 0) {
        window.removeFromCart(itemId);
        return;
    }

    const itemIndex = window.cartData.items.findIndex(item => item.id === itemId);
    if (itemIndex === -1) return;

    // Clamp to max stock
    const maxStock = parseInt(window.cartData.items[itemIndex].max_stock || 0);
    if (maxStock > 0 && newQuantity > maxStock) {
        newQuantity = maxStock;
        showToast('Alcanzaste el stock disponible', 'info');
    }

    // Actualizar localmente (optimistic update)
    const newSubtotal = parseFloat(window.cartData.items[itemIndex].unit_price) * newQuantity;
    window.cartData.items[itemIndex].quantity = newQuantity;
    window.cartData.items[itemIndex].subtotal = newSubtotal;

    recalculateTotals();
    renderCart();
    updateTotals();

    // Agregar a la cola
    const request = {
        type: 'update',
        itemId: itemId,
        newQuantity: newQuantity,
        timestamp: Date.now()
    };

    requestQueue.push(request);

    if (!pendingRequest) {
        processRequestQueue();
    }
};

// Eliminar del carrito
window.removeFromCart = async function(itemId) {
    const itemIndex = window.cartData.items.findIndex(item => item.id === itemId);
    if (itemIndex === -1) return;

    // Eliminar localmente
    window.cartData.items.splice(itemIndex, 1);
    recalculateTotals();
    renderCart();
    updateTotals();

    // Agregar a la cola
    const request = {
        type: 'delete',
        itemId: itemId,
        timestamp: Date.now()
    };

    requestQueue.push(request);

    if (!pendingRequest) {
        processRequestQueue();
    }
};

// Procesar cola de solicitudes
async function processRequestQueue() {
    if (requestQueue.length === 0) {
        pendingRequest = null;
        return;
    }

    const request = requestQueue.shift();
    pendingRequest = request;

    try {
        if (request.type === 'update') {
            const response = await fetch(`/carrito/${request.itemId}`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({ quantity: request.newQuantity })
            });

            if (!response.ok) {
                await updateCart();
            }
        } else if (request.type === 'delete') {
            const response = await fetch(`/carrito/${request.itemId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').content,
                }
            });

            if (!response.ok) {
                await updateCart();
            }
        }
    } catch (error) {
        console.error('Error procesando solicitud:', error);
        await updateCart();
    }

    await processRequestQueue();
}

// Abrir drawer
window.openCartDrawer = function() {
    const drawer = document.getElementById('cart-drawer');
    const overlay = document.getElementById('cart-drawer-overlay');

    if (!drawer || !overlay) return;

    overlay.classList.remove('hidden');
    document.body.style.overflow = 'hidden';

    requestAnimationFrame(() => {
        overlay.classList.remove('opacity-0');
        drawer.classList.remove('translate-x-full');
    });

    updateCart();
};

// Cerrar drawer
window.closeCartDrawer = function() {
    const drawer = document.getElementById('cart-drawer');
    const overlay = document.getElementById('cart-drawer-overlay');

    if (!drawer || !overlay) return;

    overlay.classList.add('opacity-0');
    drawer.classList.add('translate-x-full');
    document.body.style.overflow = '';

    setTimeout(() => {
        overlay.classList.add('hidden');
    }, 300);
};

// Cerrar con ESC
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        window.closeCartDrawer();
    }
});

// Cerrar al hacer clic en el overlay
document.getElementById('cart-drawer-overlay')?.addEventListener('click', window.closeCartDrawer);

// Inicializar
document.addEventListener('DOMContentLoaded', () => {
    // Ocultar IVA inicialmente hasta que se carguen los datos del servidor
    const ivaRow = document.getElementById('cart-iva-row');
    if (ivaRow) {
        ivaRow.style.display = 'none';
    }
    updateCart();
});
</script>
@endpush
