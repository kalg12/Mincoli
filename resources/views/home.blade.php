@extends('layouts.app')

@section('title', 'Inicio')

@section('content')
<!-- Hero Banner / Banners dinámicos -->
<section class="relative bg-gradient-to-br from-amber-50 to-pink-50 overflow-hidden py-10">
    <div class="container mx-auto px-4">
        @if($banners->count())
            <div class="relative max-w-6xl mx-auto rounded-3xl overflow-hidden shadow-2xl">
                @foreach($banners as $index => $banner)
                <div class="banner-slide absolute inset-0 opacity-0 scale-95 transition duration-700 ease-out {{ $loop->first ? 'opacity-100 scale-100' : '' }}" data-banner-slide="{{ $index }}">
                    <img src="{{ $banner->image_url }}" alt="{{ $banner->title }}" class="w-full h-[480px] object-cover">
                    <div class="absolute inset-0 bg-gradient-to-r from-black/60 via-black/40 to-transparent"></div>
                    <div class="absolute inset-0 flex flex-col items-start justify-center px-8 md:px-16 lg:px-20 text-white max-w-2xl">
                        <p class="text-sm uppercase tracking-[0.2em] text-pink-200 mb-3">Mincoli</p>
                        <h1 class="text-4xl md:text-5xl font-bold leading-tight drop-shadow-lg mb-4">{{ $banner->title }}</h1>
                        <p class="text-lg md:text-xl text-white/90 mb-8">{{ $banner->text }}</p>
                        <div class="flex flex-wrap items-center gap-3">
                            @if($banner->link_url)
                            <a href="{{ $banner->link_url }}" target="_blank" class="bg-pink-600 hover:bg-pink-700 text-white font-semibold px-6 py-3 rounded-lg transition inline-flex items-center shadow-lg">
                                <i class="fas fa-shopping-bag mr-2"></i>
                                Comprar Ahora
                            </a>
                            @endif
                            <a href="{{ route('shop') }}" class="bg-white/90 hover:bg-white text-gray-900 font-semibold px-6 py-3 rounded-lg transition inline-flex items-center">
                                <i class="fas fa-store mr-2 text-pink-600"></i>
                                Ver Tienda
                            </a>
                            <a href="#categories" class="text-white/80 hover:text-white font-semibold inline-flex items-center">
                                Ver Categorías
                                <i class="fas fa-arrow-right ml-2"></i>
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach

                @if($banners->count() > 1)
                <div class="absolute inset-0 flex items-center justify-between px-4 md:px-6">
                    <button type="button" class="banner-prev h-10 w-10 rounded-full bg-white/80 hover:bg-white shadow flex items-center justify-center text-gray-700">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button type="button" class="banner-next h-10 w-10 rounded-full bg-white/80 hover:bg-white shadow flex items-center justify-center text-gray-700">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
                <div class="absolute bottom-4 left-0 right-0 flex items-center justify-center gap-2">
                    @foreach($banners as $index => $banner)
                    <button type="button" class="banner-dot h-2.5 w-2.5 rounded-full bg-white/50 hover:bg-white transition {{ $loop->first ? 'bg-white' : '' }}" data-banner-dot="{{ $index }}"></button>
                    @endforeach
                </div>
                @endif
            </div>
        @else
            <div class="text-center max-w-3xl mx-auto py-12">
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
        @endif

        <!-- Featured Products Preview -->
        <div class="mt-12 max-w-6xl mx-auto grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
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

        <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
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
                                <i class="fas fa-check-circle"></i> Disponible
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
            Contáctanos por WhatsApp y te atenderemos de inmediato
        </p>
        <div class="flex flex-wrap justify-center gap-4">
            <a href="https://wa.me/5256117011660" target="_blank" rel="noopener" class="bg-green-500 hover:bg-green-600 text-white font-semibold px-8 py-4 rounded-lg transition inline-flex items-center text-lg">
                <i class="fab fa-whatsapp mr-2 text-2xl"></i>
                Enviar WhatsApp
            </a>
        </div>
    </div>
</section>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const slides = Array.from(document.querySelectorAll('[data-banner-slide]'));
        const dots = Array.from(document.querySelectorAll('[data-banner-dot]'));
        const nextBtn = document.querySelector('.banner-next');
        const prevBtn = document.querySelector('.banner-prev');
        if (slides.length <= 1) return;

        let current = 0;
        let timer = null;

        const setActive = (index) => {
            slides.forEach((slide, i) => {
                if (i === index) {
                    slide.classList.add('opacity-100', 'scale-100');
                    slide.classList.remove('opacity-0', 'scale-95', 'pointer-events-none');
                } else {
                    slide.classList.remove('opacity-100', 'scale-100');
                    slide.classList.add('opacity-0', 'scale-95', 'pointer-events-none');
                }
            });
            dots.forEach((dot, i) => {
                dot.classList.toggle('bg-white', i === index);
                dot.classList.toggle('bg-white/50', i !== index);
            });
            current = index;
        };

        const next = () => setActive((current + 1) % slides.length);
        const prev = () => setActive((current - 1 + slides.length) % slides.length);

        const restart = () => {
            clearInterval(timer);
            timer = setInterval(next, 6000);
        };

        nextBtn?.addEventListener('click', () => {
            next();
            restart();
        });

        prevBtn?.addEventListener('click', () => {
            prev();
            restart();
        });

        dots.forEach((dot, i) => {
            dot.addEventListener('click', () => {
                setActive(i);
                restart();
            });
        });

        timer = setInterval(next, 6000);
    });
</script>
@endpush
@endsection
