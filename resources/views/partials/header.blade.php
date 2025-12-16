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
                <a href="#" class="text-gray-600 hover:text-pink-600 transition" aria-label="Facebook">
                    <i class="fab fa-facebook-f"></i>
                </a>
                <a href="#" class="text-gray-600 hover:text-pink-600 transition" aria-label="Instagram">
                    <i class="fab fa-instagram"></i>
                </a>
                <a href="#" class="text-gray-600 hover:text-pink-600 transition" aria-label="TikTok">
                    <i class="fab fa-tiktok"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Main Header -->
<header class="bg-white/90 backdrop-blur sticky top-0 z-50 border-b border-gray-200">
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between py-3">
            <!-- Logo -->
            <a href="{{ route('home') }}" class="flex items-center">
                <span class="text-2xl font-bold tracking-tight"><span class="text-pink-600">Min</span><span class="text-gray-900">coli</span></span>
            </a>

            <!-- Desktop Navigation -->
            <nav class="hidden md:flex items-center space-x-6">
                <a href="{{ route('home') }}" class="text-gray-700 hover:text-pink-600 font-medium transition {{ request()->routeIs('home') ? 'text-pink-600' : '' }}">
                    INICIO
                </a>
                <a href="{{ route('shop') }}" class="text-gray-700 hover:text-pink-600 font-medium transition {{ request()->routeIs('shop*') ? 'text-pink-600' : '' }}">
                    TIENDA
                </a>
            </nav>

            <!-- Actions -->
            <div class="flex items-center space-x-3">
                <!-- Cart -->
                <button onclick="openCartDrawer()" class="relative text-gray-700 hover:text-pink-600 transition">
                    <i class="fas fa-shopping-cart text-lg"></i>
                    <span class="absolute -top-2 -right-2 bg-pink-600 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                        3
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
            <nav class="flex flex-col space-y-2">
                <a href="{{ route('home') }}" class="text-gray-700 hover:text-pink-600 font-medium py-2 {{ request()->routeIs('home') ? 'text-pink-600' : '' }}">
                    INICIO
                </a>
                <a href="{{ route('shop') }}" class="text-gray-700 hover:text-pink-600 font-medium py-2 {{ request()->routeIs('shop*') ? 'text-pink-600' : '' }}">
                    TIENDA
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
