<div class="p-6 space-y-6">
    <!-- Header -->
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">
                Gestionar Transmisiones en Vivo
            </h1>
            <p class="text-sm text-zinc-600 dark:text-zinc-400 mt-1">Crea, inicia y administra tus transmisiones en vivo</p>
        </div>
        <button
            wire:click="openForm"
            class="inline-flex items-center justify-center rounded-lg bg-pink-500 px-4 py-2 text-sm font-semibold text-white hover:bg-pink-600 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:bg-pink-600 dark:hover:bg-pink-700 dark:focus:ring-offset-zinc-900 transition-colors gap-2"
        >
            <i class="fas fa-plus"></i>
            Nueva Transmisión
        </button>
    </div>

    <!-- Form Modal -->
    @if ($showForm)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50" wire:click="closeForm">
            <div
                class="rounded-lg bg-white shadow-2xl max-w-md w-full p-6 dark:bg-zinc-900"
                @click.stop
            >
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-zinc-900 dark:text-white">
                        {{ $editingLive ? 'Editar Transmisión' : 'Nueva Transmisión' }}
                    </h3>
                    <button wire:click="closeForm" class="text-zinc-500 hover:text-zinc-700 dark:hover:text-zinc-300">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <form wire:submit="saveLive" class="space-y-4">
                    <!-- Title -->
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">
                            Título de la Transmisión
                        </label>
                        <input
                            type="text"
                            wire:model="title"
                            placeholder="ej: Especial de Verano"
                            class="w-full px-4 py-2 border border-zinc-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent dark:border-zinc-600 dark:bg-zinc-800 dark:text-white dark:placeholder-zinc-400"
                        >
                        @error('title') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Platform -->
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">
                            Plataforma
                        </label>
                        <select
                            wire:model="platform"
                            class="w-full px-4 py-2 border border-zinc-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent dark:border-zinc-600 dark:bg-zinc-800 dark:text-white"
                        >
                            <option value="Instagram Live">Instagram Live</option>
                            <option value="Facebook Live">Facebook Live</option>
                            <option value="TikTok Live">TikTok Live</option>
                            <option value="YouTube Live">YouTube Live</option>
                            <option value="Twitch">Twitch</option>
                            <option value="Otro">Otro</option>
                        </select>
                        @error('platform') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- URL -->
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">
                            URL de la Transmisión (Opcional)
                        </label>
                        <input
                            type="url"
                            wire:model="live_url"
                            placeholder="https://www.instagram.com/mincolimx/live/"
                            class="w-full px-4 py-2 border border-zinc-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent dark:border-zinc-600 dark:bg-zinc-800 dark:text-white dark:placeholder-zinc-400"
                        >
                        @error('live_url') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Buttons -->
                    <div class="flex gap-3 pt-4">
                        <button
                            type="button"
                            wire:click="closeForm"
                            class="flex-1 rounded-lg border border-zinc-300 px-4 py-2 text-sm font-semibold text-zinc-900 hover:bg-zinc-50 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:border-zinc-600 dark:text-white dark:hover:bg-zinc-800 dark:focus:ring-offset-zinc-900 transition-colors"
                        >
                            Cancelar
                        </button>
                        <button
                            type="submit"
                            class="flex-1 rounded-lg bg-pink-500 px-4 py-2 text-sm font-semibold text-white hover:bg-pink-600 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:bg-pink-600 dark:hover:bg-pink-700 dark:focus:ring-offset-zinc-900 transition-colors"
                        >
                            {{ $editingLive ? 'Actualizar' : 'Crear' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Products Modal -->
    @if ($showProducts && $selectedLive)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50" wire:click="closeProducts">
            <div
                class="rounded-lg bg-white shadow-2xl max-w-2xl w-full p-6 max-h-[80vh] overflow-y-auto dark:bg-zinc-900"
                @click.stop
            >
                <div class="flex items-center justify-between mb-6 sticky top-0 bg-white dark:bg-zinc-900">
                    <h3 class="text-xl font-bold text-zinc-900 dark:text-white">
                        Productos Destacados en Vivo
                    </h3>
                    <button wire:click="closeProducts" class="text-zinc-500 hover:text-zinc-700 dark:hover:text-zinc-300">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <!-- Productos Actuales -->
                <div class="mb-6">
                    <h4 class="font-semibold text-zinc-900 dark:text-white mb-3">Productos en Esta Transmisión</h4>
                    @if ($selectedLive->productHighlights->count() > 0)
                        <div class="space-y-2">
                            @foreach ($selectedLive->productHighlights as $highlight)
                                <div class="flex items-center justify-between p-3 rounded-lg border border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-800">
                                    <div>
                                        <p class="font-semibold text-zinc-900 dark:text-white">{{ $highlight->product->name }}</p>
                                        @if ($highlight->description)
                                            <p class="text-sm text-zinc-600 dark:text-zinc-400">{{ $highlight->description }}</p>
                                        @endif
                                    </div>
                                    <button
                                        wire:click="removeProductFromLive({{ $highlight->id }})"
                                        class="text-red-600 hover:text-red-700 dark:text-red-500 dark:hover:text-red-400 transition"
                                    >
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-zinc-500 dark:text-zinc-400">Sin productos agregados aún</p>
                    @endif
                </div>

                <!-- Agregar Productos -->
                <div class="border-t border-zinc-200 dark:border-zinc-700 pt-6">
                    <h4 class="font-semibold text-zinc-900 dark:text-white mb-3">Agregar Productos</h4>
                    <div class="space-y-2 max-h-64 overflow-y-auto">
                        @foreach ($availableProducts as $product)
                            @if (!$selectedLive->productHighlights->pluck('product_id')->contains($product->id))
                                <button
                                    wire:click="addProductToLive({{ $product->id }})"
                                    class="w-full text-left p-3 rounded-lg border border-zinc-200 hover:border-pink-500 hover:bg-pink-50 dark:border-zinc-700 dark:hover:border-pink-500 dark:hover:bg-zinc-800 transition"
                                >
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="font-semibold text-zinc-900 dark:text-white">{{ $product->name }}</p>
                                            <p class="text-sm font-bold text-pink-600 dark:text-pink-400">
                                                ${{ number_format($product->sale_price ?? $product->price, 2, '.', ',') }}
                                            </p>
                                        </div>
                                        <i class="fas fa-plus text-pink-500"></i>
                                    </div>
                                </button>
                            @endif
                        @endforeach
                    </div>
                </div>

                <!-- Close Button -->
                <div class="mt-6 pt-4 border-t border-zinc-200 dark:border-zinc-700">
                    <button
                        wire:click="closeProducts"
                        class="w-full rounded-lg border border-zinc-300 px-4 py-2 text-sm font-semibold text-zinc-900 hover:bg-zinc-50 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:border-zinc-600 dark:text-white dark:hover:bg-zinc-800 dark:focus:ring-offset-zinc-900 transition-colors"
                    >
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Lives Table -->
    <div class="overflow-hidden rounded-lg border border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-900">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-zinc-900 dark:text-zinc-100">
                <thead class="border-b border-zinc-200 bg-zinc-50 text-xs font-semibold uppercase text-zinc-700 dark:border-zinc-700 dark:bg-zinc-800/50 dark:text-zinc-400">
                    <tr>
                        <th class="px-6 py-4 text-left">Transmisión</th>
                        <th class="px-6 py-4 text-left">Plataforma</th>
                        <th class="px-6 py-4 text-left">Estado</th>
                        <th class="px-6 py-4 text-left">Productos</th>
                        <th class="px-6 py-4 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                    @forelse ($lives as $live)
                        <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors">
                            <td class="px-6 py-4">
                                <div>
                                    <p class="font-semibold text-zinc-900 dark:text-white">{{ $live->title }}</p>
                                    <p class="text-xs text-zinc-500 dark:text-zinc-400">
                                        {{ $live->created_at->format('d/m/Y H:i') }}
                                    </p>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-800 dark:bg-blue-900/30 dark:text-blue-400">
                                    {{ $live->platform ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @if ($live->is_live)
                                    <span class="inline-flex items-center rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-800 dark:bg-red-900/30 dark:text-red-400">
                                        🔴 EN VIVO
                                    </span>
                                @elseif ($live->ends_at && $live->ends_at <= now())
                                    <span class="inline-flex items-center rounded-full bg-zinc-100 px-3 py-1 text-xs font-semibold text-zinc-800 dark:bg-zinc-700 dark:text-zinc-400">
                                        ⚫ FINALIZADA
                                    </span>
                                @elseif ($live->starts_at && $live->starts_at > now())
                                    <span class="inline-flex items-center rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-800 dark:bg-blue-900/30 dark:text-blue-400">
                                        ⏰ PROGRAMADA
                                    </span>
                                @else
                                    <span class="inline-flex items-center rounded-full bg-zinc-100 px-3 py-1 text-xs font-semibold text-zinc-800 dark:bg-zinc-700 dark:text-zinc-400">
                                        ⚪ BORRADOR
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm font-semibold text-zinc-700 dark:text-zinc-300">
                                    {{ $live->productHighlights()->count() }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <!-- Editar -->
                                    <button
                                        wire:click="openForm({{ $live->id }})"
                                        title="Editar"
                                        class="rounded p-2 text-blue-600 hover:bg-blue-50 dark:text-blue-400 dark:hover:bg-blue-900/20 transition"
                                    >
                                        <i class="fas fa-edit"></i>
                                    </button>

                                    <!-- Productos -->
                                    <button
                                        wire:click="openProducts({{ $live->id }})"
                                        title="Agregar productos"
                                        class="rounded p-2 text-green-600 hover:bg-green-50 dark:text-green-400 dark:hover:bg-green-900/20 transition"
                                    >
                                        <i class="fas fa-box"></i>
                                    </button>

                                    <!-- Iniciar/Detener -->
                                    @if ($live->is_live)
                                        <button
                                            wire:click="stopLive({{ $live->id }})"
                                            title="Detener transmisión"
                                            class="rounded p-2 text-red-600 hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-900/20 transition"
                                        >
                                            <i class="fas fa-stop-circle"></i>
                                        </button>
                                    @else
                                        <button
                                            wire:click="startLive({{ $live->id }})"
                                            title="Iniciar transmisión"
                                            class="rounded p-2 text-green-600 hover:bg-green-50 dark:text-green-400 dark:hover:bg-green-900/20 transition"
                                        >
                                            <i class="fas fa-play-circle"></i>
                                        </button>
                                    @endif

                                    <!-- Eliminar -->
                                    <button
                                        wire:click="deleteLive({{ $live->id }})"
                                        title="Eliminar"
                                        onclick="return confirm('¿Estás seguro de que deseas eliminar esta transmisión?')"
                                        class="rounded p-2 text-red-600 hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-900/20 transition"
                                    >
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center">
                                <i class="fas fa-inbox text-4xl mb-3 opacity-30 text-zinc-400"></i>
                                <p class="text-lg font-semibold text-zinc-900 dark:text-white">No hay transmisiones creadas</p>
                                <p class="text-sm text-zinc-600 dark:text-zinc-400">Crea tu primera transmisión en vivo haciendo clic en el botón "Nueva Transmisión"</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="border-t border-zinc-200 px-6 py-4 dark:border-zinc-700">
            {{ $lives->links() }}
        </div>
    </div>
</div>
