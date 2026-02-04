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
            EN VIVO
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
                <p class="font-bold text-gray-900 text-xs uppercase tracking-wider">EN VIVO</p>
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
            <div class="fixed inset-0 z-50 flex items-center justify-center">
                <!-- Backdrop -->
                <div class="absolute inset-0 bg-black bg-opacity-75" wire:click="closePreview"></div>

                <!-- Modal Container -->
                <div class="relative w-full max-w-4xl h-[90vh] m-4 bg-white rounded-xl shadow-2xl flex flex-col">
                    <!-- Header -->
                    <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-white">
                        <div class="flex items-center gap-3">
                            <div class="relative flex h-4 w-4">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-4 w-4 bg-red-600"></span>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900">{{ $activeLive->title }}</h3>
                        </div>
                        <button
                            wire:click="closePreview"
                            class="text-gray-500 hover:text-gray-700 transition-colors"
                        >
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>

                    <!-- Content Area -->
                    <div class="flex-1 overflow-hidden flex flex-col">
                        <!-- Player -->
                        @if ($embedUrl)
                            <div class="relative w-full overflow-hidden bg-black aspect-video">
                                <iframe
                                    src="{{ $embedUrl }}"
                                    class="absolute inset-0 h-full w-full"
                                    frameborder="0"
                                    allow="autoplay; fullscreen; picture-in-picture"
                                    allowfullscreen
                                ></iframe>
                            </div>
                        @else
                            <div class="flex-1 flex items-center justify-center bg-gray-100 p-8">
                                <div class="text-center">
                                    <i class="fas fa-video-slash text-6xl text-gray-400 mb-4"></i>
                                    <p class="text-gray-600 mb-4">No se pudo incrustar la transmisión</p>
                                    <button
                                        wire:click="goToLive"
                                        class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg font-semibold transition-colors"
                                    >
                                        Abrir en nueva ventana
                                    </button>
                                </div>
                            </div>
                        @endif

                        <!-- Stream Info -->
                        <div class="p-6 bg-gray-50 border-t border-gray-200">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center gap-2">
                                    <span class="relative flex h-3 w-3">
                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-500 opacity-75"></span>
                                        <span class="relative inline-flex rounded-full h-3 w-3 bg-red-600"></span>
                                    </span>
                                    <h4 class="text-lg font-bold text-gray-900">Transmisión en Vivo</h4>
                                </div>
                                @if ($activeLive->platform)
                                    <span class="inline-flex items-center gap-1 bg-red-100 text-red-700 rounded-full px-3 py-1 text-sm font-medium">
                                        <i class="fas fa-video"></i>
                                        {{ ucfirst($activeLive->platform) }}
                                    </span>
                                @endif
                            </div>

                            @if ($activeLive->starts_at)
                                <div class="text-sm text-gray-600">
                                    <i class="fas fa-clock mr-1"></i>
                                    Iniciado {{ $activeLive->starts_at->diffForHumans() }}
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Footer Actions -->
                    <div class="p-4 border-t border-gray-200 bg-white flex gap-3">
                        <button
                            wire:click="goToLive"
                            class="flex-1 bg-red-600 hover:bg-red-700 text-white font-bold py-3 rounded-lg transition-colors duration-200 flex items-center justify-center gap-2"
                        >
                            <i class="fas fa-external-link-alt"></i>
                            Abrir en nueva ventana
                        </button>
                        <button
                            wire:click="closePreview"
                            class="flex-1 border-2 border-gray-300 text-gray-700 hover:bg-gray-50 font-bold py-3 rounded-lg transition-colors duration-200"
                        >
                            Cerrar
                        </button>
                    </div>
                </div>
            </div>
        @endif
    @endif
</div>
