<div wire:poll.10s>
    @if ($activeLive)
    <!-- Live Indicator Button -->
    <button
        wire:click="openPreview"
        class="live-indicator-button relative flex items-center gap-2 px-3 sm:px-4 py-2 rounded-full bg-gradient-to-r from-red-500 to-pink-500 text-white font-semibold text-sm hover:shadow-lg transition-all duration-300 active:scale-95"
        aria-label="Ver transmisión en vivo"
    >
        <!-- Pulsing Dot Animation -->
        <span class="relative flex h-3 w-3">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
            <span class="relative inline-flex rounded-full h-3 w-3 bg-red-600"></span>
        </span>

        <span class="hidden sm:inline">EN VIVO</span>
        <span class="sm:hidden">
            <i class="fas fa-broadcast-tower text-xs"></i>
        </span>
    </button>

        <!-- Preview Modal -->
        @if ($showPreview)
            <div
                class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm"
                wire:click="closePreview"
            >
                <div
                    class="live-preview-modal bg-white rounded-2xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-hidden flex flex-col mx-auto"
                    @click.stop
                >
                    <!-- Header -->
                    <div class="sticky top-0 bg-gradient-to-r from-red-500 to-pink-500 text-white p-4 flex items-center justify-between flex-shrink-0">
                        <div class="flex items-center gap-3 min-w-0">
                            <span class="relative flex h-3 w-3 flex-shrink-0">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-3 w-3 bg-red-600"></span>
                            </span>
                            <h2 class="text-xl font-bold truncate">{{ $activeLive->title }}</h2>
                        </div>
                        <button
                            wire:click="closePreview"
                            class="text-white hover:bg-white/20 rounded-full p-2 transition flex-shrink-0"
                        >
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>

                    <!-- Scrollable Content -->
                    <div class="overflow-y-auto flex-grow">
                        <div class="p-6 space-y-6">
                            <!-- Player -->
                            @if ($embedUrl)
                                <div class="relative w-full overflow-hidden rounded-xl bg-black aspect-video shadow-lg">
                                    <iframe
                                        src="{{ $embedUrl }}"
                                        class="absolute inset-0 h-full w-full"
                                        frameborder="0"
                                        allow="autoplay; fullscreen; picture-in-picture"
                                        allowfullscreen
                                    ></iframe>
                                </div>
                            @else
                                <div class="rounded-xl border border-red-200 bg-red-50 p-5 text-red-700">
                                    No se pudo incrustar la transmisión. Usa el botón de abajo para abrirla.
                                </div>
                            @endif

                            <!-- Live Stream Info -->
                            <div class="rounded-xl border border-red-200 bg-gradient-to-r from-red-50 to-pink-50 p-5">
                                <div class="flex items-center gap-3 mb-3">
                                    <div class="relative flex h-4 w-4">
                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                        <span class="relative inline-flex rounded-full h-4 w-4 bg-red-600"></span>
                                    </div>
                                    <p class="text-gray-900 font-bold text-lg">Transmisión en vivo</p>
                                </div>
                                @if ($activeLive->platform)
                                    <p class="text-gray-700 mb-2 flex items-center gap-2">
                                        <i class="fas fa-video text-pink-600"></i>
                                        <strong>Plataforma:</strong>
                                        <span class="text-pink-600 font-semibold capitalize">{{ $activeLive->platform }}</span>
                                    </p>
                                @endif
                                @if ($activeLive->starts_at)
                                    <p class="text-gray-700 flex items-center gap-2">
                                        <i class="fas fa-clock text-pink-600"></i>
                                        Iniciado {{ $activeLive->starts_at->diffForHumans() }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons (Fixed at bottom) -->
                    <div class="border-t border-gray-200 bg-gray-50 p-4 flex flex-col sm:flex-row gap-3 flex-shrink-0">
                        <button
                            wire:click="goToLive"
                            class="flex-1 bg-gradient-to-r from-red-500 to-pink-500 text-white py-3 rounded-lg font-bold hover:shadow-lg transition duration-300 flex items-center justify-center gap-2 active:scale-95"
                        >
                            <i class="fas fa-broadcast-tower"></i>
                            Abrir transmisión en otra ventana
                        </button>
                        <button
                            wire:click="closePreview"
                            class="flex-1 border-2 border-gray-300 text-gray-700 py-3 rounded-lg font-semibold hover:bg-gray-100 transition duration-300 active:scale-95"
                        >
                            Cerrar
                        </button>
                    </div>
                </div>
            </div>
        @endif
    @endif
</div>
