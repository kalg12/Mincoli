<x-layouts.app :title="__('POS - Editar Transaccion')">
<div class="p-6 space-y-6 dark:bg-zinc-950 min-h-screen">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Panel Principal -->
        <div class="lg:col-span-2 space-y-6">
            <div class="flex items-start justify-between">
                <h1 class="text-3xl font-bold text-zinc-900 dark:text-zinc-100">{{ $transaction->transaction_number }}</h1>
                <div class="text-sm text-zinc-600 dark:text-zinc-300">
                    Pago: <strong>{{ ucfirst($transaction->payment_status) }}</strong> |
                    Estado: <strong>{{ ucfirst($transaction->status) }}</strong>
                </div>
            </div>

            <!-- Info Cliente -->
            <div class="rounded-xl border border-zinc-200 bg-white p-6 shadow dark:border-zinc-700 dark:bg-zinc-900 space-y-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-semibold text-zinc-900 dark:text-zinc-100">Cliente</h2>
                    @if($transaction->customer)
                        <span class="text-xs text-zinc-500 dark:text-zinc-400">ID: {{ $transaction->customer->id }}</span>
                    @endif
                </div>
                @if($transaction->customer)
                    <div class="text-lg text-zinc-900 dark:text-zinc-100">
                        <p><strong>{{ $transaction->customer->name }}</strong></p>
                        <p class="text-zinc-600 dark:text-zinc-300">{{ $transaction->customer->phone }}</p>
                        <p class="text-zinc-600 dark:text-zinc-300">{{ $transaction->customer->email }}</p>
                    </div>
                @else
                    <p class="text-zinc-500 dark:text-zinc-400">Sin cliente asignado</p>
                @endif
            </div>

            <!-- Info de Envio / Notas -->
            <div class="rounded-xl border border-zinc-200 bg-white p-6 shadow dark:border-zinc-700 dark:bg-zinc-900">
                <h2 class="text-xl font-semibold mb-4 text-zinc-900 dark:text-zinc-100">Envio y notas</h2>

                <form action="{{ route('dashboard.pos.transaction.update', $transaction) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PATCH')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold mb-1 text-zinc-800 dark:text-zinc-200">Telefono de envio</label>
                            <input
                                type="tel"
                                name="shipping_contact_phone"
                                value="{{ old('shipping_contact_phone', $transaction->shipping_contact_phone) }}"
                                placeholder="Si es distinto al cliente"
                                class="w-full px-3 py-2 rounded border border-zinc-300 bg-white text-zinc-900 placeholder-zinc-400
                                       focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                                       dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-100 dark:placeholder-zinc-500"
                            >
                        </div>

                        <div>
                            <label class="block text-sm font-semibold mb-1 text-zinc-800 dark:text-zinc-200">Direccion de envio</label>
                            <textarea
                                name="shipping_address"
                                rows="2"
                                placeholder="Calle, numero, ciudad"
                                class="w-full px-3 py-2 rounded border border-zinc-300 bg-white text-zinc-900 placeholder-zinc-400
                                       focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                                       dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-100 dark:placeholder-zinc-500"
                            >{{ old('shipping_address', $transaction->shipping_address) }}</textarea>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold mb-1 text-zinc-800 dark:text-zinc-200">Notas de envio</label>
                        <textarea
                            name="shipping_notes"
                            rows="2"
                            placeholder="Referencias, horario..."
                            class="w-full px-3 py-2 rounded border border-zinc-300 bg-white text-zinc-900 placeholder-zinc-400
                                   focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                                   dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-100 dark:placeholder-zinc-500"
                        >{{ old('shipping_notes', $transaction->shipping_notes) }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold mb-1 text-zinc-800 dark:text-zinc-200">Notas de la transaccion</label>
                        <textarea
                            name="notes"
                            rows="2"
                            placeholder="Notas generales"
                            class="w-full px-3 py-2 rounded border border-zinc-300 bg-white text-zinc-900 placeholder-zinc-400
                                   focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                                   dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-100 dark:placeholder-zinc-500"
                        >{{ old('notes', $transaction->notes) }}</textarea>
                    </div>

                    <button
                        type="submit"
                        class="px-4 py-2 rounded font-semibold transition
                               bg-zinc-900 text-white hover:bg-black
                               dark:bg-zinc-100 dark:text-zinc-900 dark:hover:bg-white"
                    >
                        Guardar envio/notas
                    </button>
                </form>
            </div>

            <!-- Buscador de Productos -->
            <div class="rounded-xl border border-zinc-200 bg-white p-6 shadow dark:border-zinc-700 dark:bg-zinc-900">
                <h2 class="text-xl font-semibold mb-4 text-zinc-900 dark:text-zinc-100">Agregar Productos</h2>

                <div class="flex gap-2">
                    <input
                        type="text"
                        id="searchInput"
                        placeholder="Buscar por SKU, barcode o nombre..."
                        class="flex-1 px-4 py-2 rounded-lg border border-zinc-300 bg-white text-zinc-900 placeholder-zinc-400
                               focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                               dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-100 dark:placeholder-zinc-500"
                        autocomplete="off"
                    >
                </div>

                <!-- Results container: IMPORTANT for dark mode -->
                <div
                    id="searchResults"
                    class="mt-4 hidden rounded-lg p-4 max-h-64 overflow-y-auto
                           border border-zinc-200 bg-white text-zinc-900
                           dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-100"
                ></div>

                <p class="mt-2 text-xs text-zinc-500 dark:text-zinc-400">
                    Tip: escribe al menos 2 caracteres.
                </p>
            </div>

            <!-- Items en Transaccion -->
            <div class="rounded-xl border border-zinc-200 bg-white p-6 shadow dark:border-zinc-700 dark:bg-zinc-900">
                <h2 class="text-xl font-semibold mb-4 text-zinc-900 dark:text-zinc-100">Productos Apartados</h2>

                @if($transaction->items->count())
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b border-zinc-200 dark:border-zinc-700 text-zinc-700 dark:text-zinc-200">
                                    <th class="text-left py-3 px-2">Producto</th>
                                    <th class="text-center py-3 px-2">Cantidad</th>
                                    <th class="text-right py-3 px-2">Precio</th>
                                    <th class="text-right py-3 px-2">Subtotal</th>
                                    <th class="text-center py-3 px-2">Accion</th>
                                </tr>
                            </thead>
                            <tbody id="itemsTable">
                                @foreach($transaction->items as $item)
                                    <tr
                                        class="border-b border-zinc-200 dark:border-zinc-500
                                               dark:hover:bg-zinc-800/60
                                               text-zinc-900 dark:text-zinc-100"
                                        data-item-id="{{ $item->id }}"
                                    >
                                        <td class="py-3 px-2">
                                            <div class="font-semibold">{{ $item->product_name }}</div>
                                            <div class="text-xs text-zinc-500 dark:text-zinc-400">SKU: {{ $item->product_sku }}</div>
                                        </td>

                                        <td class="text-center py-3 px-2">
                                            <input
                                                type="number"
                                                class="quantity-input w-16 px-2 py-1 rounded border border-zinc-300 bg-white text-zinc-900 text-center
                                                       focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                                                       dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-100"
                                                value="{{ $item->quantity }}"
                                                min="1"
                                                data-item-id="{{ $item->id }}"
                                            >
                                        </td>

                                        <td class="text-right py-3 px-2 text-zinc-900 dark:text-zinc-100">{{ currency($item->unit_price) }}</td>
                                        <td class="text-right py-3 px-2 font-semibold text-zinc-900 dark:text-zinc-100">{{ currency($item->subtotal) }}</td>

                                        <td class="text-center py-3 px-2">
                                            <button
                                                type="button"
                                                class="remove-item font-semibold px-2 py-1 rounded
                                                       text-red-600 hover:text-red-800 hover:bg-red-50
                                                       dark:text-red-400 dark:hover:text-red-300 dark:hover:bg-red-900/30"
                                                data-item-id="{{ $item->id }}"
                                            >
                                                X
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-8 text-zinc-500 dark:text-zinc-400">
                        No hay productos agregados. Busca y agrega productos arriba.
                    </div>
                @endif
            </div>
        </div>

        <!-- Panel Lateral: Resumen y Pagos -->
        <div>
            <div class="rounded-xl border border-zinc-200 bg-white p-6 mb-6 sticky top-20 shadow dark:border-zinc-700 dark:bg-zinc-900">
                <h2 class="text-lg font-semibold mb-4 text-zinc-900 dark:text-zinc-100">Resumen</h2>

                <div class="space-y-2 mb-4">
                    <div class="flex justify-between text-zinc-700 dark:text-zinc-200">
                        <span class="text-zinc-600 dark:text-zinc-300">Subtotal:</span>
                        <span id="subtotalAmount" class="font-semibold">{{ currency($transaction->subtotal) }}</span>
                    </div>

                    @if($showIva)
                    <div class="flex justify-between text-zinc-700 dark:text-zinc-200">
                        <span class="text-zinc-600 dark:text-zinc-300">IVA:</span>
                        <span id="ivaAmount" class="font-semibold">{{ currency($transaction->iva_total) }}</span>
                    </div>
                    @endif

                    <div class="border-t border-zinc-200 dark:border-zinc-700 pt-2 flex justify-between text-lg">
                        <span class="font-semibold text-zinc-900 dark:text-zinc-100">Total:</span>
                        <span id="totalAmount" class="font-bold text-blue-600 dark:text-blue-400">{{ currency($transaction->total) }}</span>
                    </div>
                </div>

                <!-- Pagos -->
                <div class="border-t border-zinc-200 dark:border-zinc-700 pt-4">
                    <h3 class="font-semibold mb-3 text-zinc-900 dark:text-zinc-100">Pagos Registrados</h3>

                    @if($transaction->payments->count())
                        <div class="space-y-2 mb-4 p-3 rounded
                                    border border-zinc-200 bg-zinc-50
                                    dark:border-zinc-700 dark:bg-zinc-800">
                            @foreach($transaction->payments as $payment)
                                <div class="flex justify-between text-sm text-zinc-800 dark:text-zinc-200">
                                    <span>{{ $payment->paymentMethod?->name ?? 'Sin metodo' }} ({{ $payment->status }})</span>
                                    <span class="font-semibold">{{ currency($payment->amount) }}</span>
                                </div>

                                @if($payment->reference)
                                    <div class="text-xs text-zinc-500 dark:text-zinc-400">Ref: {{ $payment->reference }}</div>
                                @endif
                            @endforeach

                            <div class="border-t border-zinc-200 dark:border-zinc-700 pt-2 flex justify-between font-semibold text-zinc-900 dark:text-zinc-100">
                                <span>Pagado (confirmado):</span>
                                <span>{{ currency($transaction->total_paid) }}</span>
                            </div>

                            <div class="flex justify-between font-semibold text-orange-600 dark:text-orange-400">
                                <span>Pendiente:</span>
                                <span>{{ currency($transaction->pending_amount) }}</span>
                            </div>
                        </div>
                    @endif

                    <!-- Formulario Agregar Pago -->
                    <form id="paymentForm" class="space-y-3">
                        @csrf

                        <div>
                            <label class="block text-sm font-semibold mb-1 text-zinc-800 dark:text-zinc-200">Metodo de Pago</label>
                            <select
                                name="payment_method_id"
                                class="w-full px-3 py-2 rounded border border-zinc-300 bg-white text-zinc-900
                                       focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                                       dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-100"
                            >
                                <option value="" class="bg-white text-zinc-900 dark:bg-zinc-800 dark:text-zinc-100">-- Sin metodo --</option>
                                @foreach($paymentMethods as $method)
                                    <option value="{{ $method->id }}" class="bg-white text-zinc-900 dark:bg-zinc-800 dark:text-zinc-100" @if(strtolower($method->name) === 'mercado pago') selected @endif>
                                        {{ $method->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-sm font-semibold mb-1 text-zinc-800 dark:text-zinc-200">Monto</label>
                                <input
                                    type="number"
                                    name="amount"
                                    step="0.01"
                                    min="0"
                                    placeholder="0.00"
                                    class="w-full px-3 py-2 rounded border border-zinc-300 bg-white text-zinc-900 placeholder-zinc-400
                                           focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                                           dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-100 dark:placeholder-zinc-500"
                                >
                            </div>

                            <div>
                                <label class="block text-sm font-semibold mb-1 text-zinc-800 dark:text-zinc-200">Estado</label>
                                <select
                                    name="status"
                                    class="w-full px-3 py-2 rounded border border-zinc-300 bg-white text-zinc-900
                                           focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                                           dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-100"
                                >
                                    <option value="completed" class="bg-white text-zinc-900 dark:bg-zinc-800 dark:text-zinc-100">Confirmado (Mercado Pago)</option>
                                    <option value="pending" class="bg-white text-zinc-900 dark:bg-zinc-800 dark:text-zinc-100">Pendiente (Transferencia diferida)</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold mb-1 text-zinc-800 dark:text-zinc-200">Referencia (Opcional)</label>
                            <input
                                type="text"
                                name="reference"
                                placeholder="Ej: Comprobante"
                                class="w-full px-3 py-2 rounded border border-zinc-300 bg-white text-zinc-900 placeholder-zinc-400
                                       focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                                       dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-100 dark:placeholder-zinc-500"
                            >
                        </div>

                        <div>
                            <label class="block text-sm font-semibold mb-1 text-zinc-800 dark:text-zinc-200">Notas (Opcional)</label>
                            <textarea
                                name="notes"
                                rows="2"
                                class="w-full px-3 py-2 rounded border border-zinc-300 bg-white text-zinc-900 placeholder-zinc-400
                                       focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                                       dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-100 dark:placeholder-zinc-500"
                            ></textarea>
                        </div>

                        <button
                            type="submit"
                            class="w-full px-3 py-2 rounded font-semibold transition
                                   bg-green-600 text-white hover:bg-green-700
                                   dark:bg-green-500 dark:hover:bg-green-600"
                        >
                            Registrar Pago
                        </button>
                    </form>
                </div>

                <!-- Acciones Finales -->
                <div class="border-t border-zinc-200 dark:border-zinc-700 pt-4 mt-4 space-y-2">
                    <a
                        href="{{ route('dashboard.pos.ticket.print', $transaction) }}"
                        target="_blank"
                        class="block text-center px-4 py-2 rounded font-semibold transition
                               bg-blue-600 text-white hover:bg-blue-700
                               dark:bg-blue-500 dark:hover:bg-blue-600"
                    >
                        Imprimir Ticket
                    </a>

                    <form action="{{ route('dashboard.pos.transaction.complete', $transaction) }}" method="POST" class="inline-block w-full">
                        @csrf
                        <button
                            type="submit"
                            class="w-full px-4 py-2 rounded font-semibold transition
                                   bg-purple-600 text-white hover:bg-purple-700
                                   dark:bg-purple-500 dark:hover:bg-purple-600"
                        >
                            Completar Apartado
                        </button>
                    </form>

                    <a
                        href="{{ route('dashboard.pos.index') }}"
                        class="block text-center px-4 py-2 rounded font-semibold transition
                               bg-zinc-200 text-zinc-900 hover:bg-zinc-300
                               dark:bg-zinc-800 dark:text-zinc-100 dark:hover:bg-zinc-700"
                    >
                        Volver
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Configuracion para JS -->
    <div id="posConfig"
         data-transaction-id="{{ $transaction->id }}"
         data-search-url="{{ route('dashboard.pos.searchProduct') }}"
         data-add-item-url="{{ route('dashboard.pos.item.add', $transaction) }}"
         data-update-quantity-url="{{ route('dashboard.pos.item.updateQuantity', ['transaction' => $transaction, 'item' => '__ITEM__']) }}"
         data-remove-item-url="{{ route('dashboard.pos.item.remove', ['transaction' => $transaction, 'item' => '__ITEM__']) }}"
         data-payment-url="{{ route('dashboard.pos.payment.store', $transaction) }}"
         data-csrf="{{ csrf_token() }}"
         style="display:none"></div>

</div>

<script>
const cfgEl = document.getElementById('posConfig');
const searchUrl = cfgEl.dataset.searchUrl;
const addItemUrl = cfgEl.dataset.addItemUrl;
const updateQuantityUrlTmpl = cfgEl.dataset.updateQuantityUrl;
const removeItemUrlTmpl = cfgEl.dataset.removeItemUrl;
const paymentUrl = cfgEl.dataset.paymentUrl;
const csrf = cfgEl.dataset.csrf;

const resultsDiv = document.getElementById('searchResults');

// Busqueda de productos
document.getElementById('searchInput').addEventListener('input', async (e) => {
    const query = e.target.value.trim();
    if (query.length < 2) {
        resultsDiv.classList.add('hidden');
        resultsDiv.innerHTML = '';
        return;
    }

    resultsDiv.innerHTML = '<p class="text-zinc-600 dark:text-zinc-300">Buscando...</p>';
    resultsDiv.classList.remove('hidden');

    const response = await fetch(`${searchUrl}?q=${encodeURIComponent(query)}`);
    const products = await response.json();

    if (products.length === 0) {
        resultsDiv.innerHTML = '<p class="text-zinc-500 dark:text-zinc-400">No se encontraron productos</p>';
        resultsDiv.classList.remove('hidden');
        return;
    }

    let html = '';
    products.forEach(product => {
        const variants = product.variants || [];
        if (variants.length === 0) {
            html += renderProductButton(product.id, null, product.name, product.sku, product.sale_price || product.price);
        } else {
            variants.forEach(variant => {
                html += renderProductButton(product.id, variant.id, `${product.name} - ${variant.name}`, variant.sku, variant.price);
            });
        }
    });

    resultsDiv.innerHTML = html;
    resultsDiv.classList.remove('hidden');

    resultsDiv.querySelectorAll('.add-product').forEach(btn => {
        btn.addEventListener('click', async () => {
            const qtyInput = btn.parentElement.querySelector('.qty-input');
            const quantity = parseInt(qtyInput.value || '1', 10);
            if (!quantity || quantity < 1) return;

            const productId = btn.dataset.productId;
            const variantId = btn.dataset.variantId || null;

            const response = await fetch(addItemUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrf
                },
                body: JSON.stringify({
                    product_id: productId,
                    product_variant_id: variantId,
                    quantity: quantity
                })
            });

            if (response.ok) {
                location.reload();
            }
        });
    });
});

function renderProductButton(productId, variantId, label, sku, price) {
    const displayPrice = parseFloat(price || 0).toFixed(2);

    // IMPORTANT: dark mode colors here
    return `
        <div class="flex items-center justify-between border rounded p-3 mb-2
                    border-zinc-200 bg-white text-zinc-900
                    dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-100">
            <div>
                <div class="font-semibold">${label}</div>
                <div class="text-xs text-zinc-500 dark:text-zinc-400">SKU: ${sku ?? 'N/A'} | $${displayPrice}</div>
            </div>
            <div class="flex items-center gap-2">
                <input type="number"
                       class="qty-input w-16 px-2 py-1 border rounded text-center
                              border-zinc-300 bg-white text-zinc-900
                              focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                              dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-100"
                       value="1" min="1">

                <button type="button"
                        class="add-product px-3 py-2 rounded font-semibold transition
                               bg-blue-600 text-white hover:bg-blue-700
                               dark:bg-blue-500 dark:hover:bg-blue-600"
                        data-product-id="${productId}"
                        data-variant-id="${variantId ?? ''}">
                    Agregar
                </button>
            </div>
        </div>
    `;
}

// Actualizar cantidad
document.querySelectorAll('.quantity-input').forEach(input => {
    input.addEventListener('blur', async (e) => {
        const itemId = e.target.dataset.itemId;
        const quantity = e.target.value;

        const updateUrl = updateQuantityUrlTmpl.replace('__ITEM__', itemId);
        const response = await fetch(updateUrl, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrf
            },
            body: JSON.stringify({ quantity })
        });

        if (response.ok) {
            location.reload();
        }
    });
});

// Remover item
document.querySelectorAll('.remove-item').forEach(btn => {
    btn.addEventListener('click', async (e) => {
        e.preventDefault();
        if (!confirm('Remover producto?')) return;

        const itemId = btn.dataset.itemId;
        const removeUrl = removeItemUrlTmpl.replace('__ITEM__', itemId);
        const response = await fetch(removeUrl, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': csrf }
        });

        if (response.ok) {
            location.reload();
        }
    });
});

// Registrar pago
document.getElementById('paymentForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    const amount = document.querySelector('input[name="amount"]').value;

    if (!amount || amount <= 0) {
        alert('Ingresa un monto valido');
        return;
    }

    const formData = new FormData(e.target);
    const response = await fetch(paymentUrl, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': csrf },
        body: formData
    });

    if (response.ok) {
        location.reload();
    }
});
</script>

</x-layouts.app>
