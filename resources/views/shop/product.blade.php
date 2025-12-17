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
                    <div class="grid grid-cols-2 gap-3">
                        @foreach($product->variants as $variant)
                        <button class="border-2 border-gray-300 hover:border-pink-600 rounded-lg p-4 text-left transition">
                            <div class="font-medium text-gray-900">{{ $variant->name }}</div>
                            @if($variant->size || $variant->color)
                            <div class="text-sm text-gray-600">
                                @if($variant->size) {{ $variant->size }} @endif
                                @if($variant->color) - {{ $variant->color }} @endif
                            </div>
                            @endif
                            <div class="flex justify-between items-center mt-2 flex-wrap gap-2">
                                <div class="flex items-baseline gap-2">
                                    @if($variant->sale_price && $variant->sale_price < $variant->price)
                                    <span class="text-pink-600 font-bold">
                                        ${{ number_format($variant->sale_price, 2) }}
                                    </span>
                                    <span class="text-xs text-gray-400 line-through">
                                        ${{ number_format($variant->price, 2) }}
                                    </span>
                                    @else
                                    <span class="text-pink-600 font-bold">
                                        ${{ number_format($variant->effective_price, 2) }}
                                    </span>
                                    @endif
                                </div>
                                <span class="text-xs {{ $variant->stock > 0 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $variant->stock > 0 ? 'Disponible' : 'Agotado' }}
                                </span>
                            </div>
                        </button>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Quantity -->
                @if($product->total_stock > 0)
                <div class="mb-6">
                    <label class="font-semibold text-gray-900 mb-2 block">Cantidad:</label>
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center border-2 border-gray-300 rounded-lg">
                            <button type="button" onclick="decreaseQuantity()" class="px-4 py-2 text-gray-600 hover:bg-gray-100 transition">
                                <i class="fas fa-minus"></i>
                            </button>
                            <input type="number" id="quantity" value="1" min="1" max="{{ $product->total_stock }}"
                                   class="w-16 text-center border-0 focus:ring-0">
                            <button type="button" onclick="increaseQuantity()" class="px-4 py-2 text-gray-600 hover:bg-gray-100 transition">
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
                    <form action="{{ route('cart.add') }}" method="POST" class="space-y-3">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" id="quantity_field" name="quantity" value="1">
                        <button type="submit" class="w-full bg-pink-600 hover:bg-pink-700 text-white font-bold py-4 rounded-lg transition flex items-center justify-center">
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
            {!! nl2br(e($product->description)) !!}
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
        document.getElementById('main-image').src = url;
    }

    function increaseQuantity() {
        const input = document.getElementById('quantity');
        const max = parseInt(input.max);
        let current = parseInt(input.value);
        if (current < max) {
            input.value = current + 1;
            updateQuantityField();
        }
    }

    function decreaseQuantity() {
        const input = document.getElementById('quantity');
        let current = parseInt(input.value);
        if (current > 1) {
            input.value = current - 1;
            updateQuantityField();
        }
    }

    function updateQuantityField() {
        const quantity = document.getElementById('quantity').value;
        const field = document.getElementById('quantity_field');
        if (field) {
            field.value = quantity;
        }
    }

    // Permitir cambio manual
    document.getElementById('quantity')?.addEventListener('change', function() {
        const max = parseInt(this.max);
        const min = parseInt(this.min);
        let value = parseInt(this.value);

        if (isNaN(value) || value < min) {
            this.value = min;
        } else if (value > max) {
            this.value = max;
        }
        updateQuantityField();
    });

    // Inicializar al cargar
    document.addEventListener('DOMContentLoaded', updateQuantityField);
</script>
@endpush
@endsection
