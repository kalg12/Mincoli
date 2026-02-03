@extends('layouts.app')

@section('title', 'Resultados de búsqueda')

@section('content')
<!-- Breadcrumb -->
<div class="bg-gray-100 py-4">
    <div class="container mx-auto px-4">
        <nav class="flex items-center space-x-2 text-sm">
            <a href="{{ route('home') }}" class="text-gray-600 hover:text-pink-600">Inicio</a>
            <i class="fas fa-chevron-right text-gray-400 text-xs"></i>
            <a href="{{ route('shop') }}" class="text-gray-600 hover:text-pink-600">Tienda</a>
            <i class="fas fa-chevron-right text-gray-400 text-xs"></i>
            <span class="text-gray-900 font-medium">Resultados de búsqueda</span>
        </nav>
    </div>
</div>

<div class="container mx-auto px-4 py-8 relative">
    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Sidebar Filters (Desktop) -->
        <aside class="hidden lg:block lg:w-64 flex-shrink-0">
             @include('shop.partials.filters')
        </aside>

        <!-- Mobile Filter Button -->
        <button id="mobile-filter-btn" class="lg:hidden fixed top-24 right-4 z-40 bg-pink-600 text-white px-4 py-2 rounded-lg shadow-lg font-bold flex items-center gap-2 hover:bg-pink-700 transition-all transform hover:scale-105">
            <i class="fas fa-filter"></i> FILTROS
        </button>

        <!-- Mobile Filter Drawer overlay -->
        <div id="mobile-filter-overlay" class="fixed inset-0 bg-black bg-opacity-30 backdrop-blur-sm z-50 hidden lg:hidden transition-opacity duration-300 opacity-0"></div>

        <!-- Mobile Filter Drawer -->
        <div id="mobile-filter-drawer" class="fixed inset-y-0 left-0 w-[85%] max-w-none bg-white shadow-xl z-50 transform -translate-x-full transition-transform duration-300 ease-in-out lg:hidden overflow-y-auto">
            <div class="p-4">
               @include('shop.partials.filters')
            </div>
        </div>

        <!-- Search Results -->
        <main class="flex-1">
            <!-- Search Header -->
            <div class="bg-pink-50 border border-pink-200 rounded-lg p-4 mb-6">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 mb-2">
                            Resultados de búsqueda
                        </h1>
                        <p class="text-gray-600">
                            @if($query)
                                <span class="font-medium">{{ $products->total() }}</span> productos encontrados para 
                                <span class="font-semibold text-pink-600">"{{ $query }}"</span>
                            @else
                                Por favor ingresa un término de búsqueda
                            @endif
                        </p>
                    </div>
                    
                    @if($query)
                    <a href="{{ route('shop') }}" class="inline-flex items-center text-pink-600 hover:text-pink-700 font-medium">
                        <i class="fas fa-times-circle mr-2"></i>
                        Limpiar búsqueda
                    </a>
                    @endif
                </div>
            </div>

            <!-- Search Suggestions (no results) -->
            @if($query && $products->count() == 0)
            <div class="text-center py-16">
                <i class="fas fa-search text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-700 mb-2">No se encontraron productos</h3>
                <p class="text-gray-500 mb-6">
                    Intenta buscar con diferentes palabras clave o navega por categorías
                </p>
                
                <!-- Search Tips -->
                <div class="max-w-md mx-auto text-left bg-gray-50 rounded-lg p-4 mb-6">
                    <h4 class="font-semibold text-gray-700 mb-2">
                        <i class="fas fa-lightbulb text-yellow-500 mr-2"></i>
                        Sugerencias de búsqueda:
                    </h4>
                    <ul class="text-sm text-gray-600 space-y-1">
                        <li>• Usa el nombre del producto (ej: "camisa")</li>
                        <li>• Busca por código SKU (ej: "SKU001")</li>
                        <li>• Escanea o ingresa el código de barras</li>
                        <li>• Intenta con palabras más generales</li>
                    </ul>
                </div>
                
                <div class="flex flex-col sm:flex-row gap-3 justify-center">
                    <a href="{{ route('shop') }}" class="inline-flex items-center bg-pink-600 hover:bg-pink-700 text-white font-semibold px-6 py-3 rounded-lg transition">
                        <i class="fas fa-store mr-2"></i>
                        Ver todos los productos
                    </a>
                    <button onclick="history.back()" class="inline-flex items-center bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold px-6 py-3 rounded-lg transition">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Volver
                    </button>
                </div>
            </div>
            @endif

            <!-- Results Header (if we have results) -->
            @if($products->count() > 0)
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
                <div>
                    <p class="text-gray-600 text-sm">
                        Mostrando {{ $products->firstItem() }}-{{ $products->lastItem() }} de {{ $products->total() }} productos
                    </p>
                </div>

                <!-- Sort -->
                <form method="GET" action="{{ route('shop.search') }}" class="flex items-center space-x-2">
                    <input type="hidden" name="q" value="{{ $query }}">
                    @if(request('category'))
                    <input type="hidden" name="category" value="{{ request('category') }}">
                    @endif
                    <label class="text-sm text-gray-600">Ordenar:</label>
                    <select name="sort" onchange="this.form.submit()"
                            class="border-gray-300 rounded-lg text-sm">
                        <option value="relevance" {{ request('sort') == 'relevance' ? 'selected' : '' }}>Relevancia</option>
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Más recientes</option>
                        <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Precio: Menor a Mayor</option>
                        <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Precio: Mayor a Menor</option>
                        <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Nombre A-Z</option>
                    </select>
                </form>
            </div>

            <!-- Products Grid -->
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
                            <!-- Product Identifiers -->
                            @if($product->sku || $product->barcode)
                            <div class="flex flex-wrap gap-1 mb-2">
                                @if($product->sku)
                                <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded">
                                    SKU: {{ $product->sku }}
                                </span>
                                @endif
                                @if($product->barcode)
                                <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded">
                                    {{ $product->barcode }}
                                </span>
                                @endif
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
            @endif
        </main>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const filterBtn = document.getElementById('mobile-filter-btn');
        const filterDrawer = document.getElementById('mobile-filter-drawer');
        const filterOverlay = document.getElementById('mobile-filter-overlay');
        const closeFilterBtns = document.querySelectorAll('.close-filters-btn');

        function openFilters() {
            filterOverlay.classList.remove('hidden');
            setTimeout(() => {
                filterOverlay.classList.remove('opacity-0');
                filterDrawer.classList.remove('-translate-x-full');
            }, 10);
            document.body.style.overflow = 'hidden';
        }

        function closeFilters() {
            filterOverlay.classList.add('opacity-0');
            filterDrawer.classList.add('-translate-x-full');
            setTimeout(() => {
                filterOverlay.classList.add('hidden');
            }, 300);
            document.body.style.overflow = '';
        }

        if (filterBtn) {
            filterBtn.addEventListener('click', openFilters);
        }

        if (filterOverlay) {
            filterOverlay.addEventListener('click', closeFilters);
        }

        closeFilterBtns.forEach(btn => {
            btn.addEventListener('click', closeFilters);
        });
    });
</script>
@endpush
@endsection