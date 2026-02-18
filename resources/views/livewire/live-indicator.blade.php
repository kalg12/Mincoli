<div wire:poll.10s>
    @if ($activeLive)
        <!-- Mobile: Compact Badge -->
        <button
            wire:click="openPreview"
            class="md:hidden bg-red-600 hover:bg-red-700 text-white px-3 py-1.5 rounded-full text-xs font-bold uppercase tracking-wide transition-all duration-200 flex items-center gap-2 shadow-lg"
        >
            <span class="relative flex h-2 w-2">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-white opacity-75"></span>
                <span class="relative inline-flex h-full w-full rounded-full bg-white"></span>
            </span>
            @if ($activeLive->is_live)
                EN VIVO
            @else
                GRABACIÓN
            @endif
        </button>

        <!-- Desktop: Full Card -->
        <div class="hidden md:flex bg-white rounded-lg shadow-xl border-2 border-red-500 p-4 items-center gap-3 min-w-[280px] max-w-[320px]">
            <!-- Live Dot Animation -->
            <div class="flex items-center gap-2">
                <span class="relative flex h-3 w-3">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-500"></span>
                    <span class="relative inline-flex h-full w-full rounded-full bg-red-600"></span>
                </span>
            </div>
            <div class="text-sm flex-1">
                <p class="font-bold text-gray-900 text-xs uppercase tracking-wider">
                    @if ($activeLive->is_live)
                        EN VIVO
                    @else
                        ÚLTIMO LIVE GRABADO
                    @endif
                </p>
                <p class="text-gray-600">{{ $activeLive->title }}</p>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-2">
                <button
                    wire:click="openPreview"
                    class="bg-red-600 hover:bg-red-700 text-white p-2 rounded-lg text-xs font-semibold transition-colors duration-200 flex items-center justify-center gap-1"
                    title="Ver transmisión"
                >
                    <i class="fas fa-eye"></i>
                </button>

                <button
                    wire:click="goToLive"
                    class="bg-pink-600 hover:bg-pink-700 text-white p-2 rounded-lg text-xs font-semibold transition-colors duration-200 flex items-center justify-center gap-1"
                    title="Abrir en nueva ventana"
                >
                    <i class="fas fa-external-link-alt"></i>
                </button>
            </div>
        </div>

        <!-- Full Stream Preview Modal -->
        @if ($showPreview)
            @teleport('body')
                <div 
                    class="fixed inset-0 z-[99999] flex items-center justify-center p-4"
                    style="background-color: rgba(0, 0, 0, 0.7);"
                    wire:click="closePreview"
                >
                    <!-- Modal Container - Compacto -->
                    <div 
                        class="relative w-full max-w-3xl bg-white rounded-xl shadow-2xl overflow-hidden"
                        style="animation: modalSlideUp 0.2s ease-out;"
                        @click.stop
                    >
                        <!-- Header Limpio -->
                        <div class="bg-white border-b border-gray-200 px-5 py-3.5">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2.5 flex-1 min-w-0">
                                    <!-- Live Dot -->
                                    <span class="relative flex h-2.5 w-2.5 flex-shrink-0">
                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-500 opacity-75"></span>
                                        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-red-600"></span>
                                    </span>
                                    
                                    <div class="flex-1 min-w-0">
                                        <h3 class="text-base font-bold text-gray-900 truncate">
                                            {{ $activeLive->title }}
                                        </h3>
                                        <div class="flex items-center gap-2 text-xs text-gray-500 mt-0.5">
                                            <span class="font-semibold text-red-600">EN VIVO</span>
                                            @if ($activeLive->platform)
                                                <span>•</span>
                                                <span>{{ $activeLive->platform_label }}</span>
                                            @endif
                                            @if ($activeLive->starts_at)
                                                <span>•</span>
                                                <span>{{ $activeLive->starts_at->diffForHumans() }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Botón Cerrar -->
                                <button
                                    wire:click="closePreview"
                                    class="flex-shrink-0 ml-3 w-7 h-7 flex items-center justify-center rounded-lg hover:bg-gray-100 text-gray-400 hover:text-gray-600 transition-colors"
                                >
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Video Player -->
                        @if ($embedUrl)
                            <div class="relative w-full bg-black aspect-video">
                                <iframe
                                    src="{{ $embedUrl }}"
                                    class="absolute inset-0 w-full h-full"
                                    frameborder="0"
                                    allow="autoplay; fullscreen; picture-in-picture; encrypted-media"
                                    allowfullscreen
                                ></iframe>
                            </div>
                        @else
                            <div class="aspect-video flex items-center justify-center bg-gray-50">
                                <div class="text-center px-6">
                                    <div class="w-14 h-14 mx-auto mb-3 rounded-full bg-red-50 flex items-center justify-center">
                                        <i class="fas fa-video-slash text-xl text-red-500"></i>
                                    </div>
                                    <p class="text-gray-700 font-semibold text-sm mb-1.5">No se pudo cargar el video</p>
                                    <p class="text-xs text-gray-500 mb-3">Abre la transmisión en una nueva ventana</p>
                                    <button
                                        wire:click="goToLive"
                                        class="inline-flex items-center gap-1.5 bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-semibold text-xs transition-colors"
                                    >
                                        <i class="fas fa-external-link-alt text-xs"></i>
                                        Abrir Enlace
                                    </button>
                                </div>
                            </div>
                        @endif

                        <!-- Footer Minimalista -->
                        <div class="bg-white px-5 py-3 border-t border-gray-200">
                            <div class="flex items-center justify-between gap-4">
                                <div class="flex items-center gap-2 text-sm text-gray-600">
                                    <i class="fas fa-clock"></i>
                                    <span>Iniciado {{ $activeLive->starts_at ? $activeLive->starts_at->diffForHumans() : 'ahora' }}</span>
                                </div>
                                
                                <div class="flex gap-2">
                                    <button
                                        wire:click="goToLive"
                                        class="inline-flex items-center gap-1.5 bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-semibold text-sm transition-colors"
                                    >
                                        <i class="fas fa-external-link-alt text-xs"></i>
                                        Abrir
                                    </button>
                                    <button
                                        wire:click="closePreview"
                                        class="inline-flex items-center gap-1.5 border border-gray-300 text-gray-700 hover:bg-gray-50 px-4 py-2 rounded-lg font-semibold text-sm transition-colors"
                                    >
                                        Cerrar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Animación -->
                <style>
                    @keyframes modalSlideUp {
                        from {
                            opacity: 0;
                            transform: translateY(8px);
                        }
                        to {
                            opacity: 1;
                            transform: translateY(0);
                        }
                    }
                </style>
            @endteleport
        @endif
    @endif
</div>
