@extends('layouts.app')

@section('title', 'Tienda')

@section('content')
<!-- Breadcrumb -->
<div class="bg-gray-100 py-4">
    <div class="container mx-auto px-4">
        <nav class="flex items-center space-x-2 text-sm">
            <a href="{{ route('home') }}" class="text-gray-600 hover:text-pink-600">Inicio</a>
            <i class="fas fa-chevron-right text-gray-400 text-xs"></i>
            <span class="text-gray-900 font-medium">Tienda</span>
        </nav>
    </div>
</div>

<div class="container mx-auto px-4 py-8">
    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Sidebar Filters -->
        <aside class="lg:w-64 flex-shrink-0">
            <div class="bg-white rounded-lg shadow-md p-6 sticky top-24">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Filtros</h3>

                <!-- Categories -->
                <div class="mb-6">
                    <h4 class="font-semibold text-gray-700 mb-3">Categorías</h4>
                    <div class="space-y-2">
                        <a href="{{ route('shop') }}" class="block px-3 py-2 rounded {{ !request('category') ? 'bg-pink-50 text-pink-600' : 'text-gray-700 hover:bg-gray-50' }}">
                            Todas las categorías
                        </a>
                        @foreach($categories as $cat)
                        <a href="{{ route('shop.category', $cat->slug) }}"
                           class="block px-3 py-2 rounded {{ request('category') == $cat->slug ? 'bg-pink-50 text-pink-600' : 'text-gray-700 hover:bg-gray-50' }}">
                            {{ $cat->name }}
                            <span class="text-xs text-gray-500">({{ $cat->products_count }})</span>
                        </a>
                        @endforeach
                    </div>
                </div>

                <!-- Price Range -->
                <div class="mb-6">
                    <h4 class="font-semibold text-gray-700 mb-3">Rango de Precio</h4>
                    <form method="GET" action="{{ route('shop') }}" class="space-y-3">
                        @if(request('category'))
                        <input type="hidden" name="category" value="{{ request('category') }}">
                        @endif
                        <div>
                            <label class="text-sm text-gray-600">Mínimo</label>
                            <input type="number" name="min_price" value="{{ request('min_price') }}"
                                   class="w-full border-gray-300 rounded-lg mt-1" placeholder="$0">
                        </div>
                        <div>
                            <label class="text-sm text-gray-600">Máximo</label>
                            <input type="number" name="max_price" value="{{ request('max_price') }}"
                                   class="w-full border-gray-300 rounded-lg mt-1" placeholder="$999">
                        </div>
                        <button type="submit" class="w-full bg-pink-600 hover:bg-pink-700 text-white font-semibold py-2 rounded-lg transition">
                            Aplicar
                        </button>
                    </form>
                </div>

                <!-- Availability -->
                <div>
                    <h4 class="font-semibold text-gray-700 mb-3">Disponibilidad</h4>
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="checkbox" class="rounded text-pink-600 focus:ring-pink-500">
                        <span class="text-sm text-gray-700">Solo productos en stock</span>
                    </label>
                </div>
            </div>
        </aside>

        <!-- Products Grid -->
        <main class="flex-1">
            <!-- Categories Grid (Only on Shop Index) -->
            @if(Route::currentRouteName() == 'shop' && !request('category') && isset($parentCategories) && $parentCategories->count() > 0)
            <div class="mb-8 text-center">
                <h2 class="text-xl font-bold text-gray-900 mb-4 inline-block">Categorías</h2>
                <div class="flex flex-wrap justify-center gap-6">
                    @foreach($parentCategories as $cat)
                    <a href="{{ route('shop.category', $cat->slug) }}" 
                       class="flex flex-col items-center group w-32">
                        <div class="w-24 h-24 rounded-full border-2 border-transparent group-hover:border-pink-500 transition-all overflow-hidden mb-3 shadow-md">
                            @if(isset($cat->random_image))
                                <img src="{{ $cat->random_image }}" alt="{{ $cat->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            @else
                                <div class="w-full h-full bg-gray-100 flex items-center justify-center">
                                    <i class="fas fa-box text-3xl text-gray-300"></i>
                                </div>
                            @endif
                        </div>
                        <span class="text-sm font-semibold text-gray-900 group-hover:text-pink-600 text-center leading-tight">{{ $cat->name }}</span>
                    </a>
                    @endforeach
                </div>
                <hr class="mt-8 border-gray-200">
            </div>
            @endif

            <!-- Header -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">
                        @if(request('category'))
                            {{ $currentCategory->name ?? 'Categoría' }}
                        @else
                            Todos los Productos
                        @endif
                    </h1>
                    <p class="text-gray-600 text-sm mt-1">{{ $products->total() }} productos encontrados</p>
                </div>

                <!-- Sort -->
                <form method="GET" action="{{ route('shop') }}" class="flex items-center space-x-2">
                    @if(request('category'))
                    <input type="hidden" name="category" value="{{ request('category') }}">
                    @endif
                    <label class="text-sm text-gray-600">Ordenar:</label>
                    <select name="sort" onchange="this.form.submit()"
                            class="border-gray-300 rounded-lg text-sm">
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Más recientes</option>
                        <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Precio: Menor a Mayor</option>
                        <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Precio: Mayor a Menor</option>
                        <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Nombre A-Z</option>
                    </select>
                </form>
            </div>

            <!-- Subcategories Grid -->
            @if(isset($subcategories) && $subcategories->count() > 0)
            <div class="mb-8">
                <div class="flex flex-wrap justify-center gap-6">
                    @foreach($subcategories as $sub)
                    <a href="{{ route('shop') }}?category={{ $currentCategory->slug }}&subcategory={{ $sub->id }}" 
                       class="flex flex-col items-center group w-32">
                        <div class="w-24 h-24 rounded-full border-2 border-transparent group-hover:border-pink-500 transition-all overflow-hidden mb-3 shadow-md">
                             @if(isset($sub->random_image))
                                <img src="{{ $sub->random_image }}" alt="{{ $sub->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            @else
                                <div class="w-full h-full bg-gray-100 flex items-center justify-center">
                                    <i class="fas fa-box text-3xl text-gray-300"></i>
                                </div>
                            @endif
                        </div>
                        <span class="text-sm font-semibold text-gray-900 group-hover:text-pink-600 text-center leading-tight">{{ $sub->name }}</span>
                    </a>
                    @endforeach
                </div>
                <hr class="mt-8 border-gray-200">
            </div>
            @endif

            <!-- Products Grid -->
            @if($products->count() > 0)
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($products as $product)
                <div class="bg-white rounded-lg shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden group">
                    <a href="{{ route('shop.product', $product->slug) }}">
                        <div class="relative overflow-hidden bg-gray-100">
                            @if($product->images->first())
                            <img src="{{ $product->images->first()->url }}"
                                 alt="{{ $product->name }}"
                                 class="w-full h-48 object-contain p-4 group-hover:scale-110 transition-transform duration-300">
                            @else
                            <div class="w-full h-48 flex items-center justify-center">
                                <i class="fas fa-image text-gray-300 text-4xl"></i>
                            </div>
                            @endif
                            @if($product->is_featured)
                            <div class="absolute top-2 left-2 bg-pink-600 text-white text-xs font-bold px-2 py-1 rounded">
                                Destacado
                            </div>
                            @endif
                        </div>
                        <div class="p-4">
                            <h3 class="text-sm font-semibold text-gray-800 mb-3 line-clamp-2 min-h-[2.5rem]">
                                {{ $product->name }}
                            </h3>
                            @if($product->sale_price && $product->sale_price < $product->price)
                            <div class="mb-2">
                                <span class="text-xl font-bold text-pink-600">
                                    ${{ number_format($product->sale_price, 2) }}
                                </span>
                                <span class="text-base text-gray-500 line-through decoration-gray-500 decoration-1.5 ml-2">
                                    ${{ number_format($product->price, 2) }}
                                </span>
                            </div>
                            <div class="text-sm font-bold text-red-600 mb-2">
                                -{{ round(((($product->price - $product->sale_price) / $product->price) * 100)) }}%
                            </div>
                            @else
                            <div class="mb-2">
                                <span class="text-xl font-bold text-pink-600">
                                    ${{ number_format($product->price, 2) }}
                                </span>
                            </div>
                            @endif
                            <div class="flex items-center justify-between">
                                @if($product->total_stock > 0)
                                <span class="text-xs text-green-600 font-medium flex-shrink-0">
                                    <i class="fas fa-check-circle"></i> Stock
                                </span>
                                @else
                                <span class="text-xs text-red-600 font-medium flex-shrink-0">
                                    Agotado
                                </span>
                                @endif
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $products->links() }}
            </div>
            @else
            <div class="text-center py-16">
                <i class="fas fa-search text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-700 mb-2">No se encontraron productos</h3>
                <p class="text-gray-500 mb-6">Intenta ajustar los filtros o buscar otra categoría</p>
                <a href="{{ route('shop') }}" class="inline-flex items-center bg-pink-600 hover:bg-pink-700 text-white font-semibold px-6 py-3 rounded-lg transition">
                    Ver todos los productos
                </a>
            </div>
            @endif
        </main>
    </div>
</div>
@endsection
