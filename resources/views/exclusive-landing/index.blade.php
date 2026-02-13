<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>Contenido Exclusivo | Mincoli</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
</head>
<body class="min-h-screen bg-gray-50">
    {{-- Header --}}
    <header class="bg-white border-b border-gray-200 sticky top-0 z-30">
        <div class="container mx-auto px-4 py-3 flex items-center justify-between">
            <a href="{{ route('exclusive-landing.index') }}" class="flex items-center gap-2">
                <img src="{{ asset('mincoli_logo.png') }}" alt="Mincoli" class="h-10">
                <span class="font-bold text-gray-900 hidden sm:inline">Exclusivo</span>
            </a>
            <div class="flex items-center gap-4">
                <a href="{{ route('cart') }}" class="text-gray-700 hover:text-pink-600 flex items-center gap-1">
                    <i class="fas fa-shopping-cart"></i>
                    <span class="hidden sm:inline">Carrito</span>
                </a>
                <a href="{{ route('exclusive-landing.logout') }}" class="text-sm text-gray-500 hover:text-pink-600">Salir</a>
            </div>
        </div>
    </header>

    <div class="container mx-auto px-4 py-6">
        <h1 class="text-2xl font-bold text-gray-900 mb-4">Contenido exclusivo</h1>

        {{-- Filtros --}}
        <form method="GET" action="{{ route('exclusive-landing.index') }}" class="mb-8 flex flex-col sm:flex-row gap-4 flex-wrap">
            @if($config->show_filter_category)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Categoría</label>
                    <select name="category" class="rounded-lg border border-gray-300 text-sm" onchange="this.form.submit()">
                        <option value="">Todas</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @foreach($cat->children ?? [] as $child)
                                <option value="{{ $child->id }}" {{ request('category') == $child->id ? 'selected' : '' }}>— {{ $child->name }}</option>
                            @endforeach
                        @endforeach
                    </select>
                </div>
            @endif
            @if($config->show_filter_type && $subcategories->count() > 0)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipo</label>
                    <select name="subcategory" class="rounded-lg border border-gray-300 text-sm" onchange="this.form.submit()">
                        <option value="">Todos</option>
                        @foreach($subcategories as $sub)
                            <option value="{{ $sub->id }}" {{ request('subcategory') == $sub->id ? 'selected' : '' }}>{{ $sub->name }}</option>
                        @endforeach
                    </select>
                </div>
            @endif
            @if($config->show_filter_price)
                <div class="flex flex-wrap items-end gap-2">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Mín</label>
                        <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="0" min="0" step="0.01" class="rounded-lg border border-gray-300 text-sm w-24">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Máx</label>
                        <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="9999" min="0" step="0.01" class="rounded-lg border border-gray-300 text-sm w-24">
                    </div>
                    <input type="hidden" name="category" value="{{ request('category') }}">
                    <input type="hidden" name="subcategory" value="{{ request('subcategory') }}">
                    <button type="submit" class="bg-pink-600 hover:bg-pink-700 text-white text-sm font-medium px-4 py-2 rounded-lg">Aplicar</button>
                </div>
            @endif
        </form>

        <p class="text-gray-600 text-sm mb-6">{{ $products->total() }} productos</p>

        @if($products->count() > 0)
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
                @foreach($products as $product)
                    @php
                        $price = $product->sale_price && $product->sale_price < $product->price ? $product->sale_price : $product->price;
                    @endphp
                    <a href="{{ route('shop.product', $product->slug) }}" class="bg-white rounded-xl shadow-md hover:shadow-lg transition overflow-hidden group block">
                        <div class="aspect-square bg-gray-100 overflow-hidden">
                            @if($product->images->first())
                                <img src="{{ $product->images->first()->url }}" alt="{{ $product->name }}" class="w-full h-full object-contain p-4 group-hover:scale-105 transition">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <i class="fas fa-image text-gray-300 text-4xl"></i>
                                </div>
                            @endif
                        </div>
                        <div class="p-4">
                            <h3 class="font-semibold text-gray-900 line-clamp-2 text-sm">{{ $product->name }}</h3>
                            <p class="text-pink-600 font-bold mt-1">${{ number_format($price, 2) }}</p>
                            @if($product->variants->count() > 0)
                                <p class="text-xs text-gray-500 mt-1">Colores/Tallas disponibles</p>
                            @endif
                            @if($product->total_stock > 0)
                                <span class="inline-block mt-2 text-xs text-green-600 font-medium"><i class="fas fa-check-circle"></i> Stock</span>
                            @else
                                <span class="inline-block mt-2 text-xs text-red-600 font-medium">Agotado</span>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $products->withQueryString()->links() }}
            </div>
        @else
            <div class="text-center py-16 bg-white rounded-xl">
                <i class="fas fa-box-open text-5xl text-gray-300 mb-4"></i>
                <p class="text-gray-600">No hay productos con los filtros seleccionados.</p>
            </div>
        @endif
    </div>
</body>
</html>
