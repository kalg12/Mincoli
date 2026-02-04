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
            <!-- Logo and Live Indicator -->
            <div class="flex items-center gap-3">
                <a href="{{ route('home') }}" class="flex items-center">
                    <img src="{{ asset('mincoli_logo.png') }}" alt="Mincoli" class="w-16 h-auto">
                </a>
                <!-- Live Indicator Component -->
                <livewire:live-indicator />
            </div>

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
                <!-- Search (Desktop) -->
                <div class="hidden lg:block">
                    <form action="{{ route('shop.search') }}" method="GET" class="relative">
                        <input
                            type="text"
                            name="q"
                            placeholder="Buscar..."
                            class="w-48 pl-4 pr-10 py-2 border border-gray-300 rounded-full text-sm focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-transparent transition-all"
                            value="{{ request('q') }}"
                        >
                        <button type="submit" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-pink-600 transition">
                            <i class="fas fa-search text-sm"></i>
                        </button>
                    </form>
                </div>

                <!-- Search (Mobile) -->
                <button onclick="toggleMobileSearch()" class="lg:hidden text-gray-700 hover:text-pink-600 transition">
                    <i class="fas fa-search text-lg"></i>
                </button>

                <!-- Cart -->
                <button onclick="window.openCartDrawer()" class="relative text-gray-700 hover:text-pink-600 transition">
                    <i class="fas fa-shopping-cart text-lg"></i>
                    <span id="header-cart-count" class="absolute -top-2 -right-2 bg-pink-600 text-white text-xs rounded-full h-5 w-5 items-center justify-center hidden" style="display: none;">
                        0
                    </span>
                </button>

                <!-- Mobile Menu Toggle -->
                <button id="mobile-menu-toggle" class="md:hidden text-gray-700 hover:text-pink-600">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>
        </div>

        <!-- Mobile Search Bar -->
        <div id="mobile-search-bar" class="hidden lg:hidden pb-3">
            <form action="{{ route('shop.search') }}" method="GET" class="relative">
                <input
                    type="text"
                    name="q"
                    placeholder="Buscar productos por nombre, SKU o código de barras..."
                    class="w-full pl-4 pr-10 py-3 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-transparent transition-all"
                    value="{{ request('q') }}"
                    autofocus
                >
                <button type="submit" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-pink-600 transition">
                    <i class="fas fa-search"></i>
                </button>
            </form>
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

    // Mobile search toggle
    function toggleMobileSearch() {
        const searchBar = document.getElementById('mobile-search-bar');
        const menu = document.getElementById('mobile-menu');

        // Close menu when opening search
        if (!searchBar.classList.contains('hidden')) {
            searchBar.classList.add('hidden');
        } else {
            searchBar.classList.remove('hidden');
            menu.classList.add('hidden');
            // Focus on input
            searchBar.querySelector('input').focus();
        }
    }

    // Close mobile search when clicking outside
    document.addEventListener('click', function(e) {
        const searchBar = document.getElementById('mobile-search-bar');
        const searchButton = e.target.closest('[onclick="toggleMobileSearch()"]');
        const searchInput = e.target.closest('#mobile-search-bar input');

        if (!searchBar.contains(e.target) && !searchButton && !searchInput) {
            searchBar.classList.add('hidden');
        }
    });
</script>
@endpush
