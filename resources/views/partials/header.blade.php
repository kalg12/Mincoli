<!-- Top Bar -->
<div class="bg-gray-100">
    <div class="container mx-auto px-4">
        <div class="flex flex-wrap items-center justify-between py-2 text-sm">
            <!-- Contact Info -->
            <div class="flex items-center space-x-6 text-gray-700">
                <a href="tel:+5256117011660" class="flex items-center hover:text-pink-600 transition">
                    <i class="fas fa-phone-alt mr-2"></i>
                    +52 56 1170 1166
                </a>
                <a href="mailto:mincoli.ventas.online@outlook.com" class="hidden md:flex items-center hover:text-pink-600 transition">
                    <i class="fas fa-envelope mr-2"></i>
                    mincoli.ventas.online@outlook.com
                </a>
                <span class="hidden lg:flex items-center">
                    <i class="fas fa-map-marker-alt mr-2 text-pink-600"></i>
                    México
                </span>
            </div>

            <!-- Social Links -->
            <div class="flex items-center space-x-4">
                <a href="https://www.facebook.com/MincoliMx" target="_blank" rel="noopener" class="text-gray-600 hover:text-pink-600 transition" aria-label="Facebook">
                    <i class="fab fa-facebook-f"></i>
                </a>
                <a href="https://www.instagram.com/mincolimx" target="_blank" rel="noopener" class="text-gray-600 hover:text-pink-600 transition" aria-label="Instagram">
                    <i class="fab fa-instagram"></i>
                </a>
                <a href="https://www.tiktok.com/@thereal_mincoli_by_jaz" target="_blank" rel="noopener" class="text-gray-600 hover:text-pink-600 transition" aria-label="TikTok">
                    <i class="fab fa-tiktok"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Main Header -->
<header class="bg-white/90 backdrop-blur sticky top-0 z-40 border-b border-gray-200">
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between py-3">
            <!-- Logo -->
            <a href="{{ route('home') }}" class="flex items-center">
                <img src="{{ asset('mincoli_logo.png') }}" alt="Mincoli" class="w-16 h-auto">
            </a>

            <!-- Desktop Navigation -->
            <nav class="hidden md:flex items-center space-x-6">
                <a href="{{ route('home') }}" class="text-gray-700 hover:text-pink-600 font-medium transition {{ request()->routeIs('home') ? 'text-pink-600' : '' }}">
                    INICIO
                </a>
                <a href="{{ route('shop') }}" class="text-gray-700 hover:text-pink-600 font-medium transition {{ request()->routeIs('shop*') ? 'text-pink-600' : '' }}">
                    TIENDA
                </a>
                <a href="{{ route('blog.index') }}" class="text-gray-700 hover:text-pink-600 font-medium transition {{ request()->routeIs('blog.*') ? 'text-pink-600' : '' }}">
                    BLOG
                </a>
            </nav>


            <!-- Actions -->
            <div class="flex items-center space-x-3">
                <!-- Cart -->
                <button onclick="window.openCartDrawer()" class="relative text-gray-700 hover:text-pink-600 transition">
                    <i class="fas fa-shopping-cart text-lg"></i>
                    <span id="header-cart-count" class="absolute -top-2 -right-2 bg-pink-600 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center hidden">
                        0
                    </span>
                </button>

                <!-- Mobile Menu Toggle -->
                <button id="mobile-menu-toggle" class="md:hidden text-gray-700 hover:text-pink-600">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>
        </div>

        <!-- Mobile Navigation -->
        <div id="mobile-menu" class="hidden md:hidden pb-3">
            <nav class="flex flex-col items-center space-y-2 py-4">
                <a href="{{ route('home') }}" class="w-full text-center text-gray-700 hover:text-pink-600 font-medium py-2 {{ request()->routeIs('home') ? 'text-pink-600' : '' }}">
                    INICIO
                </a>
                <a href="{{ route('shop') }}" class="w-full text-center text-gray-700 hover:text-pink-600 font-medium py-2 {{ request()->routeIs('shop*') ? 'text-pink-600' : '' }}">
                    TIENDA
                </a>
                <a href="{{ route('blog.index') }}" class="w-full text-center text-gray-700 hover:text-pink-600 font-medium py-2 {{ request()->routeIs('blog.*') ? 'text-pink-600' : '' }}">
                    BLOG
                </a>
            </nav>
        </div>
    </div>
</header>

@push('scripts')
<script>
    // Mobile menu toggle
    document.getElementById('mobile-menu-toggle')?.addEventListener('click', function() {
        const menu = document.getElementById('mobile-menu');
        menu.classList.toggle('hidden');
    });
</script>
@endpush
