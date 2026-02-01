@extends('layouts.app')

@section('title', $product->name)

@section('content')
<!-- Breadcrumb -->
<div class="bg-gray-100 py-4">
    <div class="container mx-auto px-4">
        <nav class="flex items-center space-x-2 text-sm">
            <a href="{{ route('home') }}" class="text-gray-600 hover:text-pink-600">Inicio</a>
            <i class="fas fa-chevron-right text-gray-400 text-xs"></i>
            <a href="{{ route('shop') }}" class="text-gray-600 hover:text-pink-600">Tienda</a>
            <i class="fas fa-chevron-right text-gray-400 text-xs"></i>
            <a href="{{ route('shop.category', $product->category->slug) }}" class="text-gray-600 hover:text-pink-600">
                {{ $product->category->name }}
            </a>
            <i class="fas fa-chevron-right text-gray-400 text-xs"></i>
            <span class="text-gray-900 font-medium">{{ $product->name }}</span>
        </nav>
    </div>
</div>

<div class="container mx-auto px-4 py-8">
    <div id="product-page" class="hidden" data-has-variants="{{ $product->variants->count() > 0 ? '1' : '0' }}"></div>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
        <!-- Product Images -->
        <div>
            <div class="bg-white rounded-lg shadow-lg p-8 mb-4">
                @if($product->images->first())
                <img id="main-image" src="{{ $product->images->first()->url }}"
                     alt="{{ $product->name }}"
                     class="w-full h-96 object-contain">
                @else
                <div class="w-full h-96 bg-gray-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-image text-gray-300 text-6xl"></i>
                </div>
                @endif
            </div>

            @if($product->images->count() > 1)
            <!-- Thumbnail Gallery -->
            <div class="grid grid-cols-4 gap-2">
                @foreach($product->images as $image)
                <button onclick="changeImage('{{ $image->url }}')"
                        class="bg-white rounded-lg shadow p-2 hover:shadow-md transition border-2 border-transparent hover:border-pink-600">
                    <img src="{{ $image->url }}" alt="{{ $product->name }}" class="w-full h-20 object-contain">
                </button>
                @endforeach
            </div>
            @endif
        </div>

        <!-- Product Info -->
        <div>
            <div class="bg-white rounded-lg shadow-lg p-8">
                @if($product->is_featured)
                <span class="inline-block bg-pink-100 text-pink-600 text-xs font-bold px-3 py-1 rounded-full mb-4">
                    Producto Destacado
                </span>
                @endif

                <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $product->name }}</h1>

                <div class="mb-6">
                    @if($product->sale_price && $product->sale_price < $product->price)
                    <span class="text-4xl font-bold text-pink-600">
                        ${{ number_format($product->sale_price, 2) }}
                    </span>
                    <div class="flex items-center gap-3 mt-2">
                        <span class="text-xl text-gray-500 line-through decoration-gray-500 decoration-2">
                            ${{ number_format($product->price, 2) }}
                        </span>
                        <span class="text-lg font-bold text-red-600">
                            -{{ round(((($product->price - $product->sale_price) / $product->price) * 100)) }}%
                        </span>
                    </div>
                    @else
                    <span class="text-4xl font-bold text-pink-600">
                        ${{ number_format($product->price, 2) }}
                    </span>
                    @endif
                </div>

                <div class="flex items-center space-x-4 mb-6 flex-wrap gap-4">
                    @if($product->total_stock > 0)
                    <span class="bg-green-100 text-green-700 text-sm font-semibold px-3 py-1 rounded-full">
                        <i class="fas fa-check-circle"></i> En Stock
                    </span>
                    @else
                    <span class="bg-red-100 text-red-700 text-sm font-semibold px-3 py-1 rounded-full">
                        <i class="fas fa-times-circle"></i> Agotado
                    </span>
                    @endif
                </div>

                <!-- Product Details -->
                <div class="border-t border-b border-gray-200 py-6 mb-6">
                    <dl class="space-y-3">
                        <div class="flex justify-between">
                            <dt class="text-gray-600">SKU:</dt>
                            <dd class="font-medium text-gray-900">{{ $product->sku }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-gray-600">Categoría:</dt>
                            <dd class="font-medium">
                                <a href="{{ route('shop.category', $product->category->slug) }}"
                                   class="text-pink-600 hover:text-pink-700">
                                    {{ $product->category->name }}
                                </a>
                            </dd>
                        </div>
                        @if($product->barcode)
                        <div class="flex justify-between">
                            <dt class="text-gray-600">Código de barras:</dt>
                            <dd class="font-medium text-gray-900">{{ $product->barcode }}</dd>
                        </div>
                        @endif
                    </dl>
                </div>

                <!-- Variants -->
                @if($product->variants->count() > 0)
                <div class="mb-6">
                    <h3 class="font-semibold text-gray-900 mb-3">Selecciona una variante:</h3>
                    <div class="grid grid-cols-2 gap-3" id="variant-grid">
                        @foreach($product->variants as $variant)
                        <button type="button"
                                class="variant-option border-2 border-gray-300 hover:border-pink-600 rounded-lg p-4 text-left transition"
                                data-variant-id="{{ $variant->id }}"
                                data-stock="{{ $variant->stock }}"
                                data-price="{{ $variant->price ?? $product->price }}"
                                data-sale-price="{{ $variant->sale_price ?? '' }}"
                                data-image="{{ $variant->images()->first()?->url ?? '' }}">
                            <div class="font-medium text-gray-900">{{ $variant->name }}</div>
                            @if($variant->size || ($variant->color && !str_starts_with($variant->color, '#')))
                            <div class="text-sm text-gray-600">
                                @if($variant->size) {{ $variant->size }} @endif
                                @if($variant->color && !str_starts_with($variant->color, '#')) - {{ $variant->color }} @endif
                            </div>
                            @endif
                            <div class="flex justify-between items-center mt-2 flex-wrap gap-2">
                                <div class="flex items-baseline gap-2">
                                    @if($variant->sale_price && $variant->sale_price < ($variant->price ?? $product->price))
                                        <span class="text-pink-600 font-bold">
                                            ${{ number_format($variant->sale_price, 2) }}
                                        </span>
                                        <span class="text-xs text-gray-400 line-through">
                                            ${{ number_format($variant->price ?? $product->price, 2) }}
                                        </span>
                                    @else
                                        <span class="text-pink-600 font-bold">
                                            ${{ number_format($variant->effective_price, 2) }}
                                        </span>
                                    @endif
                                </div>
                                <span class="text-xs {{ $variant->stock > 0 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $variant->stock > 0 ? ($variant->stock . ' disponibles') : 'Agotado' }}
                                </span>
                            </div>
                        </button>
                        @endforeach
                    </div>
                    <div id="variant-helper" class="mt-3 rounded-lg border border-yellow-200 bg-yellow-50 text-yellow-800 p-3 text-sm">
                        Selecciona una variante para continuar con la compra.
                    </div>
                </div>
                @endif

                <!-- Quantity -->
                @if($product->total_stock > 0)
                <div class="mb-6">
                    <label class="font-semibold text-gray-900 mb-2 block">Cantidad:</label>
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center border-2 border-gray-300 rounded-lg">
                            <button type="button" id="qty_minus" onclick="decreaseQuantity()" class="px-4 py-2 text-gray-600 hover:bg-gray-100 transition" @if($product->variants->count() > 0) disabled @endif>
                                <i class="fas fa-minus"></i>
                            </button>
                            <input type="number" id="quantity" value="1" min="1" max="{{ $product->total_stock }}"
                                   class="w-16 text-center border-0 focus:ring-0" @if($product->variants->count() > 0) disabled @endif>
                            <button type="button" id="qty_plus" onclick="increaseQuantity()" class="px-4 py-2 text-gray-600 hover:bg-gray-100 transition" @if($product->variants->count() > 0) disabled @endif>
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                        <span class="text-sm text-gray-600">
                            {{ $product->total_stock }} disponibles
                        </span>
                    </div>
                </div>
                @endif

                <!-- Action Buttons -->
                <div class="space-y-3">
                    @if($product->total_stock > 0)
                    <form action="{{ route('cart.add') }}" method="POST" class="space-y-3" id="add-to-cart-form">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" id="quantity_field" name="quantity" value="1">
                        <input type="hidden" id="variant_id" name="variant_id" value="">
                        <button type="submit" id="addToCartBtn" class="w-full bg-pink-600 hover:bg-pink-700 text-white font-bold py-4 rounded-lg transition flex items-center justify-center disabled:opacity-50 disabled:cursor-not-allowed"
                                @if($product->variants->count() > 0) disabled @endif>
                            <i class="fas fa-shopping-cart mr-2"></i>
                            Agregar al Carrito
                        </button>
                    </form>
                    @endif
                    <a href="https://wa.me/525601110166?text=Hola, me interesa el producto: {{ $product->name }}"
                       target="_blank"
                       class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-4 rounded-lg transition flex items-center justify-center">
                        <i class="fab fa-whatsapp mr-2 text-xl"></i>
                        Consultar por WhatsApp
                    </a>
                </div>

                <!-- Shipping Info -->
                <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                    <div class="flex items-start space-x-3">
                        <i class="fas fa-shipping-fast text-blue-600 text-xl mt-1"></i>
                        <div class="text-sm">
                            <p class="font-semibold text-gray-900 mb-1">Envíos a todo México</p>
                            <p class="text-gray-600">Entrega de 3 a 7 días hábiles</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Description -->
    @if($product->description)
    <div class="bg-white rounded-lg shadow-lg p-8 mb-12">
        <h2 class="text-2xl font-bold text-gray-900 mb-4">Descripción del Producto</h2>
        <div class="prose max-w-none text-gray-700">
            {!! $product->description !!}
        </div>
    </div>
    @endif

    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
    <section>
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Productos Relacionados</h2>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6">
            @foreach($relatedProducts as $related)
            <div class="bg-white rounded-lg shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden group">
                <a href="{{ route('shop.product', $related->slug) }}">
                    <div class="relative overflow-hidden bg-gray-100">
                        @if($related->images->first())
                        <img src="{{ $related->images->first()->url }}"
                             alt="{{ $related->name }}"
                             class="w-full h-48 object-contain p-4 group-hover:scale-110 transition-transform duration-300">
                        @else
                        <div class="w-full h-48 flex items-center justify-center">
                            <i class="fas fa-image text-gray-300 text-4xl"></i>
                        </div>
                        @endif
                    </div>
                    <div class="p-4">
                        <h3 class="text-sm font-semibold text-gray-800 mb-3 line-clamp-2 min-h-[2.5rem]">
                            {{ $related->name }}
                        </h3>
                        @if($related->sale_price && $related->sale_price < $related->price)
                        <div class="mb-2">
                            <span class="text-lg font-bold text-pink-600">
                                ${{ number_format($related->sale_price, 2) }}
                            </span>
                            <span class="text-base text-gray-500 line-through decoration-gray-500 decoration-1.5 ml-2">
                                ${{ number_format($related->price, 2) }}
                            </span>
                        </div>
                        <div class="text-xs font-bold text-red-600">
                            -{{ round(((($related->price - $related->sale_price) / $related->price) * 100)) }}%
                        </div>
                        @else
                        <div class="mb-2">
                            <span class="text-lg font-bold text-pink-600">
                                ${{ number_format($related->price, 2) }}
                            </span>
                        </div>
                        @endif
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </section>
    @endif
</div>

@push('scripts')
<script>
    function changeImage(url) {
        var img = document.getElementById('main-image');
        if (img) img.src = url;
    }

    function increaseQuantity() {
        var input = document.getElementById('quantity');
        if (!input) return;
        var max = parseInt(input.max, 10);
        var current = parseInt(input.value, 10);
        if (current < max) {
            input.value = current + 1;
            updateQuantityField();
        }
    }

    function decreaseQuantity() {
        var input = document.getElementById('quantity');
        if (!input) return;
        var current = parseInt(input.value, 10);
        if (current > 1) {
            input.value = current - 1;
            updateQuantityField();
        }
    }

    function updateQuantityField() {
        var qtyEl = document.getElementById('quantity');
        var field = document.getElementById('quantity_field');
        if (qtyEl && field) {
            field.value = qtyEl.value;
        }
    }

    // Permitir cambio manual
    (function () {
        var qtyEl = document.getElementById('quantity');
        if (!qtyEl) return;
        qtyEl.addEventListener('change', function () {
            var max = parseInt(this.max, 10);
            var min = parseInt(this.min, 10);
            var value = parseInt(this.value, 10);

            if (isNaN(value) || value < min) {
                this.value = min;
            } else if (!isNaN(max) && value > max) {
                this.value = max;
            }
            updateQuantityField();
        });
    })();

    // Inicializar al cargar
    document.addEventListener('DOMContentLoaded', function () {
        updateQuantityField();
    });

    // Variants selection logic
    document.addEventListener('DOMContentLoaded', function () {
        var isSubmitting = false;

        var page = document.getElementById('product-page');
        var hasVariants = page && page.getAttribute('data-has-variants') === '1';

        var variantButtons = document.querySelectorAll('.variant-option');
        var variantInput = document.getElementById('variant_id');
        var qtyInput = document.getElementById('quantity');
        var priceContainer = document.querySelector('.mb-6');
        var mainImage = document.getElementById('main-image');

        var variantStockText = document.createElement('div');
        variantStockText.className = 'text-sm text-gray-600 mt-2';

        var qtySection = document.querySelector('label.font-semibold + .flex');
        var qtyAvailableSpan = qtySection && qtySection.parentElement ? qtySection.parentElement.querySelector('span.text-sm.text-gray-600') : null;
        if (qtySection && qtySection.parentElement) {
            qtySection.parentElement.appendChild(variantStockText);
        }

        var helper = document.getElementById('variant-helper');
        var addBtn = document.getElementById('addToCartBtn');
        var minusBtn = document.getElementById('qty_minus');
        var plusBtn = document.getElementById('qty_plus');

        function clearSelected() {
            for (var i = 0; i < variantButtons.length; i++) {
                variantButtons[i].classList.remove('border-pink-600');
            }
        }

        for (var i = 0; i < variantButtons.length; i++) {
            (function (btn) {
                btn.addEventListener('click', function () {
                    var id = btn.getAttribute('data-variant-id');
                    var stock = parseInt(btn.getAttribute('data-stock') || '0', 10);
                    var salePrice = btn.getAttribute('data-sale-price');
                    var price = parseFloat(btn.getAttribute('data-price') || '0');

                    clearSelected();
                    btn.classList.add('border-pink-600');
                    if (variantInput) variantInput.value = id || '';

                    // Ajustar límite de cantidad según stock de la variante
                    if (qtyInput) {
                        qtyInput.max = String(stock);
                        if (parseInt(qtyInput.value, 10) > stock) {
                            qtyInput.value = String(Math.max(1, stock));
                            updateQuantityField();
                        }
                        qtyInput.disabled = false;
                        if (minusBtn) minusBtn.removeAttribute('disabled');
                        if (plusBtn) plusBtn.removeAttribute('disabled');
                    }

                    // Actualizar texto de disponibilidad en la fila de cantidad
                    if (qtyAvailableSpan) {
                        qtyAvailableSpan.textContent = stock > 0 ? (stock + ' disponibles') : 'Agotado';
                    }

                    // Actualizar precio principal mostrado
                    if (priceContainer) {
                        var priceHtml = '';
                        if (salePrice && parseFloat(salePrice) < price) {
                            var discount = Math.round(((price - parseFloat(salePrice)) / price) * 100);
                            priceHtml =
                                '<span class="text-4xl font-bold text-pink-600">$' + Number(salePrice).toFixed(2) + '</span>' +
                                '<div class="flex items-center gap-3 mt-2">' +
                                    '<span class="text-xl text-gray-500 line-through decoration-gray-500 decoration-2">$' + price.toFixed(2) + '</span>' +
                                    '<span class="text-lg font-bold text-red-600">-' + discount + '%</span>' +
                                '</div>';
                        } else {
                            priceHtml = '<span class="text-4xl font-bold text-pink-600">$' + price.toFixed(2) + '</span>';
                        }
                        priceContainer.innerHTML = priceHtml;
                    }

                    // Actualizar imagen principal si la variante tiene imagen
                    var imageUrl = btn.getAttribute('data-image');
                    if (mainImage && imageUrl) {
                        mainImage.src = imageUrl;
                    }

                    // Mostrar texto de stock por variante
                    if (variantStockText) {
                        variantStockText.textContent = stock > 0 ? (stock + ' disponibles de esta variante') : 'Agotado';
                    }

                    // Ocultar ayuda y habilitar botón agregar
                    if (helper) helper.classList.add('hidden');
                    if (addBtn) addBtn.removeAttribute('disabled');
                });
            })(variantButtons[i]);
        }

        // Evitar submit si no hay variante seleccionada
        var addToCartForm = document.getElementById('add-to-cart-form');
        if (addToCartForm) {
            addToCartForm.addEventListener('submit', function (e) {
                var selectedVariant = variantInput ? variantInput.value : '';

                if (hasVariants && !selectedVariant) {
                    e.preventDefault();
                    if (helper) {
                        helper.classList.remove('hidden');
                        helper.textContent = 'Selecciona una variante para continuar.';
                    }
                    var grid = document.getElementById('variant-grid');
                    if (grid) {
                        grid.classList.add('ring-2', 'ring-red-500');
                        grid.scrollIntoView();
                        setTimeout(function () {
                            grid.classList.remove('ring-2', 'ring-red-500');
                        }, 1500);
                    }
                    return;
                }

                if (isSubmitting) {
                    e.preventDefault();
                    return;
                }

                isSubmitting = true;
                e.preventDefault();

                // Feedback visual al agregar (más visible)
                var btn = document.getElementById('addToCartBtn');
                if (btn) {
                    btn.classList.add('scale-95');
                    var originalHtml = btn.innerHTML;
                    btn.innerHTML = '<span class="flex items-center gap-2">' +
                        '<svg class="animate-spin h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor">' +
                            '<circle class="opacity-25" cx="12" cy="12" r="10" stroke-width="4"></circle>' +
                            '<path class="opacity-75" d="M4 12a8 8 0 018-8" stroke-width="4"></path>' +
                        '</svg> Agregando...</span>';

                    // Burbuja flotante con check (más grande y visible)
                    var bubble = document.createElement('div');
                    bubble.className = 'fixed z-[10000] px-4 py-3 rounded-full bg-emerald-600 text-white text-sm font-semibold shadow-2xl flex items-center gap-2';
                    bubble.innerHTML = '<svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"/></svg><span>Agregado al carrito</span>';

                    var rect = btn.getBoundingClientRect();
                    bubble.style.left = (rect.left + rect.width / 2) + 'px';
                    bubble.style.top = (rect.top - 16) + 'px';
                    bubble.style.transform = 'translate(-50%, -10px)';
                    document.body.appendChild(bubble);

                    // Transiciones CSS
                    bubble.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                    bubble.style.opacity = '0';
                    bubble.style.transform = 'translate(-50%, 10px) scale(0.9)';

                    requestAnimationFrame(function () {
                        bubble.style.opacity = '1';
                        bubble.style.transform = 'translate(-50%, -2px) scale(1)';
                        setTimeout(function () {
                            bubble.style.opacity = '1';
                            bubble.style.transform = 'translate(-50%, -10px) scale(1)';
                            setTimeout(function () {
                                bubble.style.opacity = '0';
                                bubble.style.transform = 'translate(-50%, -32px) scale(0.96)';
                            }, 900);
                        }, 300);
                    });

                    // Halo/ripple desde el botón
                    var ripple = document.createElement('span');
                    ripple.className = 'pointer-events-none absolute inset-0 rounded-lg bg-white/30';
                    ripple.style.position = 'absolute';
                    ripple.style.left = '0';
                    ripple.style.top = '0';
                    ripple.style.width = '100%';
                    ripple.style.height = '100%';
                    ripple.style.borderRadius = '9999px';
                    ripple.style.opacity = '0';
                    ripple.style.transition = 'transform 0.4s ease, opacity 0.6s ease';
                    btn.style.position = 'relative';
                    btn.appendChild(ripple);

                    requestAnimationFrame(function () {
                        ripple.style.opacity = '1';
                        ripple.style.transform = 'scale(1.15)';
                    });

                    setTimeout(function () {
                        ripple.style.opacity = '0';
                        ripple.remove();
                    }, 600);

                    setTimeout(function () {
                        bubble.remove();
                        btn.innerHTML = originalHtml;
                        btn.classList.remove('scale-95');
                    }, 1800);
                }

                // Dar tiempo a que se vea el efecto antes de enviar
                var form = this;
                setTimeout(function () {
                    form.submit();
                }, 500);
            });
        }
    });
</script>
@endpush
@endsection
