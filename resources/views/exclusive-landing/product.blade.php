<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>{{ $product->name }} | Exclusivo Mincoli</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
</head>
<body class="min-h-screen bg-gray-50">
    <header class="bg-white border-b border-gray-200 sticky top-0 z-30">
        <div class="container mx-auto px-4 py-3 flex items-center justify-between">
            <a href="{{ route('exclusive-landing.index') }}" class="flex items-center gap-2">
                <img src="{{ asset('mincoli_logo.png') }}" alt="Mincoli" class="h-10">
                <span class="font-bold text-gray-900 hidden sm:inline">Exclusivo</span>
            </a>
            <div class="flex items-center gap-4">
                <a href="{{ route('exclusive-landing.cart') }}" class="text-gray-700 hover:text-pink-600 flex items-center gap-1">
                    <i class="fas fa-shopping-cart"></i>
                    <span class="hidden sm:inline">Carrito</span>
                </a>
                <a href="{{ route('exclusive-landing.logout') }}" class="text-sm text-gray-500 hover:text-pink-600">Salir</a>
            </div>
        </div>
    </header>

    <div class="container mx-auto px-4 py-6">
        <nav class="flex items-center gap-2 text-sm text-gray-600 mb-6">
            <a href="{{ route('exclusive-landing.index') }}" class="hover:text-pink-600">Tienda</a>
            <i class="fas fa-chevron-right text-gray-400 text-xs"></i>
            <span class="text-gray-900 font-medium">{{ Str::limit($product->name, 40) }}</span>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
            <div>
                <div class="bg-white rounded-lg shadow-lg p-8 mb-4">
                    @if($product->images->first())
                        <img id="main-image" src="{{ $product->images->first()->url }}" alt="{{ $product->name }}" class="w-full h-96 object-contain">
                    @else
                        <div class="w-full h-96 bg-gray-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-image text-gray-300 text-6xl"></i>
                        </div>
                    @endif
                </div>
                @if($product->images->count() > 1)
                <div class="grid grid-cols-4 gap-2">
                    @foreach($product->images as $img)
                    <button type="button" onclick="document.getElementById('main-image').src='{{ $img->url }}'" class="bg-white rounded-lg shadow p-2 hover:shadow-md border-2 border-transparent hover:border-pink-600">
                        <img src="{{ $img->url }}" alt="" class="w-full h-20 object-contain">
                    </button>
                    @endforeach
                </div>
                @endif
            </div>

            <div class="bg-white rounded-lg shadow-lg p-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $product->name }}</h1>

                <div class="mb-6">
                    @if($product->sale_price && $product->sale_price < $product->price)
                        <span class="text-4xl font-bold text-pink-600">${{ number_format($product->sale_price, 2) }}</span>
                        <span class="text-xl text-gray-500 line-through ml-2">${{ number_format($product->price, 2) }}</span>
                    @else
                        <span class="text-4xl font-bold text-pink-600">${{ number_format($product->price, 2) }}</span>
                    @endif
                </div>

                @if($product->total_stock > 0)
                <span class="inline-block bg-green-100 text-green-700 text-sm font-semibold px-3 py-1 rounded-full mb-6"><i class="fas fa-check-circle"></i> En Stock</span>
                @else
                <span class="inline-block bg-red-100 text-red-700 text-sm font-semibold px-3 py-1 rounded-full mb-6"><i class="fas fa-times-circle"></i> Agotado</span>
                @endif

                @if($product->variants->count() > 0)
                <div class="mb-6">
                    <h3 class="font-semibold text-gray-900 mb-3">Selecciona una variante:</h3>
                    <div class="grid grid-cols-2 gap-3" id="variant-grid">
                        @foreach($product->variants as $variant)
                        <button type="button" class="variant-option border-2 border-gray-300 hover:border-pink-600 rounded-lg p-4 text-left transition"
                                data-variant-id="{{ $variant->id }}"
                                data-stock="{{ $variant->stock }}">
                            <div class="font-medium text-gray-900">{{ $variant->name }}</div>
                            <div class="text-sm text-gray-600 mt-2">
                                {{ $variant->stock > 0 ? $variant->stock . ' disponibles' : 'Agotado' }} —
                                ${{ number_format($variant->sale_price ?? $variant->price ?? $product->price, 2) }}
                            </div>
                        </button>
                        @endforeach
                    </div>
                </div>
                @endif

                @if($product->total_stock > 0)
                <form action="{{ route('cart.add') }}" method="POST" class="space-y-3" id="add-to-cart-form">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="return_to" value="exclusivo">
                    <input type="hidden" id="quantity_field" name="quantity" value="1">
                    <input type="hidden" id="variant_id" name="variant_id" value="">
                    <div class="flex items-center gap-4 mb-4">
                        <label class="font-semibold">Cantidad:</label>
                        <div class="flex border-2 border-gray-300 rounded-lg">
                            <button type="button" onclick="qtyChange(-1)" class="px-4 py-2 text-gray-600 hover:bg-gray-100"><i class="fas fa-minus"></i></button>
                            <input type="number" id="quantity" value="1" min="1" max="{{ $product->total_stock }}" class="w-16 text-center border-0" @if($product->variants->count() > 0) disabled @endif onchange="document.getElementById('quantity_field').value=this.value">
                            <button type="button" onclick="qtyChange(1)" class="px-4 py-2 text-gray-600 hover:bg-gray-100"><i class="fas fa-plus"></i></button>
                        </div>
                    </div>
                    <button type="submit" id="addToCartBtn" class="w-full bg-pink-600 hover:bg-pink-700 text-white font-bold py-4 rounded-lg transition flex items-center justify-center disabled:opacity-50"
                            @if($product->variants->count() > 0) disabled @endif>
                        <i class="fas fa-shopping-cart mr-2"></i> Agregar al Carrito
                    </button>
                </form>
                @endif

                <div class="mt-6 p-4 bg-blue-50 rounded-lg text-sm">
                    <p class="font-semibold text-gray-900">Envíos a todo México</p>
                    <p class="text-gray-600">Entrega de 3 a 7 días hábiles</p>
                </div>
            </div>
        </div>

        @if($product->description)
        <div class="bg-white rounded-lg shadow-lg p-8 mb-12">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Descripción</h2>
            <div class="prose max-w-none text-gray-700">{!! $product->description !!}</div>
        </div>
        @endif

        @if($relatedProducts->count() > 0)
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Más productos exclusivos</h2>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6">
            @foreach($relatedProducts as $rel)
            @php $pr = $rel->sale_price && $rel->sale_price < $rel->price ? $rel->sale_price : $rel->price; @endphp
            <a href="{{ route('exclusive-landing.product', $rel->slug) }}" class="bg-white rounded-lg shadow-md hover:shadow-xl overflow-hidden block">
                @if($rel->images->first())
                    <img src="{{ $rel->images->first()->url }}" alt="{{ $rel->name }}" class="w-full h-48 object-contain p-4">
                @else
                    <div class="w-full h-48 flex items-center justify-center bg-gray-100"><i class="fas fa-image text-gray-300 text-4xl"></i></div>
                @endif
                <div class="p-4">
                    <h3 class="font-semibold text-gray-800 text-sm line-clamp-2">{{ $rel->name }}</h3>
                    <p class="text-pink-600 font-bold mt-1">${{ number_format($pr, 2) }}</p>
                </div>
            </a>
            @endforeach
        </div>
        @endif
    </div>

    <script>
    function qtyChange(d) {
        var inp = document.getElementById('quantity');
        var v = parseInt(inp.value||1)+d;
        var max = parseInt(inp.max||999);
        inp.value = Math.max(1, Math.min(max, v));
        document.getElementById('quantity_field').value = inp.value;
    }
    document.querySelectorAll('.variant-option').forEach(function(btn) {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.variant-option').forEach(function(b){ b.classList.remove('border-pink-600'); });
            btn.classList.add('border-pink-600');
            document.getElementById('variant_id').value = btn.getAttribute('data-variant-id');
            document.getElementById('addToCartBtn').disabled = false;
            var stock = parseInt(btn.getAttribute('data-stock')||0);
            document.getElementById('quantity').max = stock;
            document.getElementById('quantity').disabled = false;
        });
    });
    document.getElementById('add-to-cart-form').addEventListener('submit', function(e) {
        if ({{ $product->variants->count() > 0 ? 'true' : 'false' }}) {
            if (!document.getElementById('variant_id').value) {
                e.preventDefault();
                alert('Selecciona una variante');
                return;
            }
        }
    });
    </script>
</body>
</html>
