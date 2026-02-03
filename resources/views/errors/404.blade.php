@extends('layouts.app')

@section('title', 'Página no encontrada')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-pink-50 via-amber-50 to-white flex items-center justify-center px-4 overflow-hidden relative">
    <!-- Background animated elements -->
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute -top-20 -left-20 w-96 h-96 bg-pink-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-pulse"></div>
        <div class="absolute -bottom-20 -right-20 w-96 h-96 bg-amber-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-pulse" style="animation-delay: 2s;"></div>
        <div class="absolute top-1/2 left-1/3 w-64 h-64 bg-pink-300 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-pulse" style="animation-delay: 4s;"></div>
    </div>

    <!-- Floating elements -->
    <div class="absolute inset-0 pointer-events-none">
        <div class="floating-element" style="position: absolute; top: 10%; left: 10%; animation: float 6s ease-in-out infinite;">
            <i class="fas fa-shopping-bag text-pink-300 text-3xl opacity-40"></i>
        </div>
        <div class="floating-element" style="position: absolute; top: 20%; right: 15%; animation: float 8s ease-in-out infinite; animation-delay: 1s;">
            <i class="fas fa-heart text-pink-400 text-2xl opacity-40"></i>
        </div>
        <div class="floating-element" style="position: absolute; bottom: 20%; left: 20%; animation: float 7s ease-in-out infinite; animation-delay: 2s;">
            <i class="fas fa-star text-amber-300 text-2xl opacity-40"></i>
        </div>
        <div class="floating-element" style="position: absolute; bottom: 30%; right: 10%; animation: float 9s ease-in-out infinite; animation-delay: 3s;">
            <i class="fas fa-gift text-pink-300 text-3xl opacity-40"></i>
        </div>
    </div>

    <!-- Main content -->
    <div class="relative z-10 text-center max-w-2xl mx-auto">
        <!-- Logo with animation -->
         <div class="mb-4 transform hover:scale-105 transition-transform duration-500">
            <img src="{{ asset('mincoli_logo.png') }}" alt="Mincoli Logo" class="w-64 md:w-80 h-auto mx-auto drop-shadow-2xl">
        </div>

        <!-- 404 Text with gradient -->
        <div class="mb-0 relative group">
            <h1 class="text-[12rem] md:text-[24rem] font-black bg-gradient-to-r from-pink-500 via-purple-500 to-pink-500 bg-clip-text text-transparent leading-none drop-shadow-[0_20px_20px_rgba(219,39,119,0.3)] animate-float-text select-none tracking-tighter">
                404
            </h1>
            <div class="absolute inset-x-0 bottom-20 bg-gradient-to-t from-white/30 to-transparent blur-2xl -z-10 group-hover:opacity-100 transition-opacity duration-1000 h-40"></div>
        </div>

        <!-- Creative error message -->
        <div class="mb-8">
            <h2 class="text-3xl md:text-4xl font-extrabold text-zinc-900 mb-4 tracking-tight">
                ¡Ups! Este producto se ha escapado del catálogo 🛍️
            </h2>
            <p class="text-xl text-zinc-600 max-w-lg mx-auto leading-relaxed">
                Parece que lo que buscas no está en este rincón. ¡Pero no te preocupes! Tenemos miles de tesoros esperándote.
            </p>
        </div>

        <!-- Interactive search box -->
        <div class="mb-10 max-w-md mx-auto">
            <div class="relative group">
                <input type="text" 
                       placeholder="¿Qué estás buscando? (ej: anillo, collar...)" 
                       class="w-full px-8 py-5 pr-14 rounded-2xl border-2 border-pink-100 focus:border-pink-500 focus:outline-none bg-white shadow-xl focus:shadow-pink-100/50 transition-all duration-300 text-lg"
                       id="searchInput">
                <button onclick="performSearch()" class="absolute right-3 top-1/2 transform -translate-y-1/2 bg-pink-600 hover:bg-pink-700 text-white w-12 h-12 rounded-xl flex items-center justify-center transition-all duration-300 shadow-lg hover:scale-105 active:scale-95">
                    <i class="fas fa-search text-xl"></i>
                </button>
            </div>
            <div id="searchSuggestions" class="mt-4 text-base text-zinc-500 hidden bg-white/50 backdrop-blur-sm py-2 px-4 rounded-full border border-pink-100/50 inline-block">
                Buscando productos...
            </div>
        </div>

         <!-- Action buttons with pink theme -->
         <div class="flex flex-wrap justify-center gap-4 mb-12">
            <a href="{{ route('shop') }}" 
               class="bg-pink-600 hover:bg-pink-700 text-white font-semibold px-6 py-3 rounded-lg transition inline-flex items-center shadow-lg">
                <i class="fas fa-shopping-bag mr-2"></i>
                Explorar Tienda
            </a>
            <a href="{{ route('home') }}" 
               class="bg-white hover:bg-gray-50 text-gray-900 font-semibold px-6 py-3 rounded-lg transition border-2 border-gray-300 inline-flex items-center">
                <i class="fas fa-home mr-2 text-pink-600"></i>
                Volver al Inicio
            </a>
        </div>

        <!-- Fun message with emoji -->
        <div class="text-center">
            <p class="text-zinc-400 text-sm mb-4 uppercase tracking-widest font-semibold">
                Ideas para iluminar tu día:
            </p>
            <div class="inline-flex items-center gap-3 bg-white/80 backdrop-blur shadow-sm px-8 py-4 rounded-full border border-pink-50">
                <span class="text-3xl filter drop-shadow-sm" id="randomEmoji">🎁</span>
                <span class="text-zinc-700 font-bold text-lg" id="randomMessage">¡Tu próxima joya te espera!</span>
            </div>
        </div>
    </div>

    <!-- Bottom decoration -->
    <div class="absolute bottom-0 left-0 right-0 h-48 bg-gradient-to-t from-white to-transparent pointer-events-none"></div>
</div>

<style>
@keyframes float {
    0%, 100% { transform: translateY(0px) rotate(0deg); }
    33% { transform: translateY(-20px) rotate(5deg); }
    66% { transform: translateY(-10px) rotate(-5deg); }
}

@keyframes float-text {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-15px); }
}

.animate-float-text {
    animation: float-text 6s ease-in-out infinite;
}

.floating-element {
    animation-timing-function: ease-in-out;
}

/* 404 styling */
h1 {
    font-family: 'Inter', system-ui, -apple-system, sans-serif;
    letter-spacing: -0.05em;
}
</style>

@push('scripts')
<script>
    function performSearch() {
        const query = document.getElementById('searchInput').value.trim();
        if (query) {
            window.location.href = `{{ route('shop.search') }}?q=${encodeURIComponent(query)}`;
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Search functionality
        const searchInput = document.getElementById('searchInput');
        const searchSuggestions = document.getElementById('searchSuggestions');
        
        searchInput.addEventListener('input', function() {
            if (this.value.length > 2) {
                searchSuggestions.classList.remove('hidden');
                searchSuggestions.innerHTML = `¿Quieres buscar <strong>"${this.value}"</strong>? <a href="{{ route('shop.search') }}?q=${encodeURIComponent(this.value)}" class="text-pink-600 font-bold hover:underline ml-2">Ver resultados <i class="fas fa-arrow-right text-xs"></i></a>`;
            } else {
                searchSuggestions.classList.add('hidden');
            }
        });

        // Random messages and emojis
        const messages = [
            '¡El mejor regalo está por llegar!',
            '¡Algo especial te espera!',
            '¡La perfecta sorpresa está aquí!',
            '¡Tu producto ideal te busca!',
            '¡Descubre algo increíble!',
            '¡La magia de Mincoli te espera!'
        ];
        
        const emojis = ['🎁', '💝', '🌟', '🎀', '💕', '✨', '🛍️', '🎉'];
        
        function updateRandomMessage() {
            const messageEl = document.getElementById('randomMessage');
            const emojiEl = document.getElementById('randomEmoji');
            
            const randomMessage = messages[Math.floor(Math.random() * messages.length)];
            const randomEmoji = emojis[Math.floor(Math.random() * emojis.length)];
            
            messageEl.style.opacity = '0';
            emojiEl.style.opacity = '0';
            
            setTimeout(() => {
                messageEl.textContent = randomMessage;
                emojiEl.textContent = randomEmoji;
                messageEl.style.opacity = '1';
                emojiEl.style.opacity = '1';
            }, 300);
        }
        
        // Change message every 3 seconds
        setInterval(updateRandomMessage, 3000);
        
        // Add enter key support for search
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                performSearch();
            }
        });
    });
</script>
@endpush
@endsection