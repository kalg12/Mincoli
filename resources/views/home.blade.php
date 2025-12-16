@extends('layouts.app')

@section('title', 'Inicio')

@section('content')
<!-- Hero Banner -->
<section class="relative bg-gradient-to-br from-amber-50 to-pink-50 overflow-hidden">
    <div class="container mx-auto px-4 py-16">
        <div class="text-center max-w-4xl mx-auto">
            <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6">
                ¡Tus Favoritos de Facebook, Directo a tu Casa esta Navidad!
            </h1>
            <p class="text-xl text-gray-700 mb-8">
                Haz tu pedido por Mensaje
                <a href="#" class="inline-flex items-center text-blue-600 hover:text-blue-700">
                    <i class="fab fa-facebook-messenger text-2xl mx-1"></i>
                </a>
            </p>
            <div class="flex flex-wrap justify-center gap-4">
                <a href="{{ route('shop') }}" class="bg-pink-600 hover:bg-pink-700 text-white font-semibold px-8 py-3 rounded-lg transition inline-flex items-center">
                    <i class="fas fa-shopping-bag mr-2"></i>
                    Comprar Ahora
                </a>
                <a href="#categories" class="bg-white hover:bg-gray-50 text-gray-900 font-semibold px-8 py-3 rounded-lg transition border-2 border-gray-300">
                    Ver Categorías
                </a>
            </div>
        </div>

        <!-- Featured Products Preview -->
        <div class="mt-12 grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
            @forelse($featuredProducts as $product)
            <div class="bg-white rounded-lg shadow-md p-4 hover:shadow-lg transition">
                @if($product->images->first())
                <img src="{{ $product->images->first()->url }}" alt="{{ $product->name }}" class="w-full h-32 object-contain mb-2">
                @else
                <div class="w-full h-32 bg-gray-200 rounded-lg mb-2 flex items-center justify-center">
                    <i class="fas fa-image text-gray-400 text-3xl"></i>
                </div>
                @endif
                <h3 class="text-sm font-semibold text-gray-800 truncate">{{ $product->name }}</h3>
                <p class="text-pink-600 font-bold">${{ number_format($product->price, 2) }}</p>
            </div>
            @empty
            @for($i = 0; $i < 6; $i++)
            <div class="bg-white rounded-lg shadow-md p-4">
                <div class="w-full h-32 bg-gray-200 rounded-lg mb-2"></div>
                <div class="h-4 bg-gray-200 rounded mb-2"></div>
                <div class="h-4 bg-gray-200 rounded w-2/3"></div>
            </div>
            @endfor
            @endforelse
        </div>
    </div>

    <!-- Decorative Elements -->
    <div class="absolute top-0 right-0 w-64 h-64 bg-pink-200 rounded-full blur-3xl opacity-20 -z-10"></div>
    <div class="absolute bottom-0 left-0 w-64 h-64 bg-amber-200 rounded-full blur-3xl opacity-20 -z-10"></div>
</section>

<!-- Categories Section -->
<section id="categories" class="py-16 bg-white">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                Descubre nuestras categorías
            </h2>
            <p class="text-gray-600 max-w-2xl mx-auto">
                Encuentra los mejores productos organizados por categorías
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($categories as $category)
            <a href="{{ route('shop.category', $category->slug) }}" class="group relative overflow-hidden rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                <div class="aspect-w-16 aspect-h-12 bg-gradient-to-br from-pink-100 to-purple-100">
                    <!-- Category Image Placeholder -->
                    <div class="w-full h-64 bg-gradient-to-br from-pink-100 to-purple-100 flex items-center justify-center">
                        <i class="fas fa-box-open text-6xl text-pink-300 group-hover:text-pink-400 transition"></i>
                    </div>
                </div>
                <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/70 to-transparent p-6">
                    <h3 class="text-2xl font-bold text-white mb-2">{{ $category->name }}</h3>
                    <p class="text-white/80 text-sm">
                        {{ $category->products_count }} producto{{ $category->products_count != 1 ? 's' : '' }}
                    </p>
                </div>
                <div class="absolute top-4 right-4 bg-white/90 backdrop-blur-sm rounded-full px-4 py-2">
                    <span class="text-pink-600 font-semibold">Ver más →</span>
                </div>
            </a>
            @empty
            <div class="col-span-full text-center py-12">
                <i class="fas fa-box-open text-6xl text-gray-300 mb-4"></i>
                <p class="text-gray-500 text-lg">Próximamente nuevas categorías</p>
            </div>
            @endforelse
        </div>
    </div>
</section>

<!-- Featured Products Section -->
@if($featuredProducts->count() > 0)
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                Productos Destacados
            </h2>
            <p class="text-gray-600">Los más vendidos y recomendados para ti</p>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
            @foreach($featuredProducts as $product)
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
                        <h3 class="text-sm font-semibold text-gray-800 mb-2 line-clamp-2 min-h-[2.5rem]">
                            {{ $product->name }}
                        </h3>
                        <div class="flex items-center justify-between">
                            <span class="text-lg font-bold text-pink-600">
                                ${{ number_format($product->price, 2) }}
                            </span>
                            @if($product->total_stock > 0)
                            <span class="text-xs text-green-600 font-medium">
                                <i class="fas fa-check-circle"></i> Disponible
                            </span>
                            @else
                            <span class="text-xs text-red-600 font-medium">
                                Agotado
                            </span>
                            @endif
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>

        <div class="text-center mt-12">
            <a href="{{ route('shop') }}" class="inline-flex items-center bg-pink-600 hover:bg-pink-700 text-white font-semibold px-8 py-3 rounded-lg transition">
                Ver todos los productos
                <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>
    </div>
</section>
@endif

<!-- Call to Action -->
<section class="py-16 bg-gradient-to-r from-pink-600 to-purple-600 text-white">
    <div class="container mx-auto px-4 text-center">
        <h2 class="text-3xl md:text-4xl font-bold mb-6">
            ¿Listo para hacer tu pedido?
        </h2>
        <p class="text-xl mb-8 opacity-90">
            Contáctanos por WhatsApp o Facebook Messenger y te atenderemos de inmediato
        </p>
        <div class="flex flex-wrap justify-center gap-4">
            <a href="https://wa.me/525601110166" target="_blank" class="bg-green-500 hover:bg-green-600 text-white font-semibold px-8 py-4 rounded-lg transition inline-flex items-center text-lg">
                <i class="fab fa-whatsapp mr-2 text-2xl"></i>
                Enviar WhatsApp
            </a>
            <a href="#" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-8 py-4 rounded-lg transition inline-flex items-center text-lg">
                <i class="fab fa-facebook-messenger mr-2 text-2xl"></i>
                Messenger
            </a>
        </div>
    </div>
</section>
@endsection
