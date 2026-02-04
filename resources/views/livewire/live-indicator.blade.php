<div>
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
                    class="live-preview-modal bg-white rounded-xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-hidden flex flex-col"
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
                        <div class="p-6">
                            <!-- Live Stream Info -->
                            <div class="mb-6 p-4 bg-gradient-to-r from-red-50 to-pink-50 rounded-lg border border-red-200">
                                <div class="flex items-center gap-2 mb-3">
                                    <i class="fas fa-info-circle text-red-600"></i>
                                    <p class="text-gray-700 font-semibold">
                                        <span class="text-red-600">En transmisión</span>
                                    </p>
                                </div>
                                @if ($activeLive->platform)
                                    <p class="text-gray-600 text-sm mb-2">
                                        <strong>Plataforma:</strong>
                                        <span class="text-pink-600 capitalize">{{ $activeLive->platform }}</span>
                                    </p>
                                @endif
                                @if ($activeLive->starts_at)
                                    <p class="text-sm text-gray-600">
                                        <i class="fas fa-clock mr-2 text-pink-600"></i>
                                        Iniciado hace {{ $activeLive->starts_at->diffForHumans() }}
                                    </p>
                                @endif
                            </div>

                            <!-- Highlighted Products -->
                            @if ($activeLive->productHighlights->count() > 0)
                                <div class="mb-6">
                                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                                        <i class="fas fa-star text-yellow-500"></i>
                                        Productos Destacados en Vivo
                                        <span class="text-xs bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full ml-auto">{{ $activeLive->productHighlights->count() }}</span>
                                    </h3>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        @foreach ($activeLive->productHighlights as $highlight)
                                            <div class="border border-gray-200 rounded-lg overflow-hidden hover:shadow-md hover:border-pink-300 transition duration-300 group">
                                                @if ($highlight->product)
                                                    <div class="relative overflow-hidden bg-gray-100 h-40">
                                                        <img
                                                            src="{{ $highlight->product->image_url }}"
                                                            alt="{{ $highlight->product->name }}"
                                                            class="w-full h-full object-cover group-hover:scale-105 transition duration-300"
                                                        >
                                                    </div>
                                                @else
                                                    <div class="w-full h-40 bg-gray-200 flex items-center justify-center">
                                                        <i class="fas fa-image text-gray-400 text-2xl"></i>
                                                    </div>
                                                @endif
                                                <div class="p-3">
                                                    @if ($highlight->product)
                                                        <h4 class="font-semibold text-gray-800 text-sm mb-2 line-clamp-2 group-hover:text-pink-600 transition">
                                                            {{ $highlight->product->name }}
                                                        </h4>
                                                        <div class="flex items-center justify-between">
                                                            <p class="text-pink-600 font-bold text-sm">
                                                                {{ $highlight->product->sale_price ? '$' . number_format($highlight->product->sale_price, 2, '.', ',') : '$' . number_format($highlight->product->price, 2, '.', ',') }}
                                                            </p>
                                                            @if ($highlight->product->sale_price && $highlight->product->sale_price < $highlight->product->price)
                                                                <span class="text-xs bg-red-100 text-red-700 px-2 py-1 rounded">
                                                                    -{{ round((($highlight->product->price - $highlight->product->sale_price) / $highlight->product->price) * 100) }}%
                                                                </span>
                                                            @endif
                                                        </div>
                                                    @endif
                                                    @if ($highlight->description)
                                                        <p class="text-gray-600 text-xs mt-2 italic">{{ Str::limit($highlight->description, 80) }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @else
                                <div class="mb-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
                                    <p class="text-blue-700 text-center">
                                        <i class="fas fa-info-circle mr-2"></i>
                                        Sin productos destacados en esta transmisión
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Action Buttons (Fixed at bottom) -->
                    <div class="border-t border-gray-200 bg-gray-50 p-4 flex gap-3 flex-shrink-0">
                        <button
                            wire:click="goToLive"
                            class="flex-1 bg-gradient-to-r from-red-500 to-pink-500 text-white py-3 rounded-lg font-bold hover:shadow-lg transition duration-300 flex items-center justify-center gap-2 active:scale-95"
                        >
                            <i class="fas fa-broadcast-tower"></i>
                            Ver Transmisión
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
