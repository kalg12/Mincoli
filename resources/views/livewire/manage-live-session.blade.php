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
                            <option value="instagram">Instagram Live</option>
                            <option value="facebook">Facebook Live</option>
                            <option value="tiktok">TikTok Live</option>
                            <option value="other">YouTube Live</option>
                            <option value="other">Twitch</option>
                            <option value="other">Otro</option>
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

                    <!-- Duration -->
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">
                            Duración Estimada (minutos)
                        </label>
                        <select
                            wire:model="duration_minutes"
                            class="w-full px-4 py-2 border border-zinc-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent dark:border-zinc-600 dark:bg-zinc-800 dark:text-white"
                        >
                            <option value="15">15 minutos</option>
                            <option value="30">30 minutos</option>
                            <option value="45">45 minutos</option>
                            <option value="60">1 hora</option>
                            <option value="90">1.5 horas</option>
                            <option value="120">2 horas</option>
                            <option value="180">3 horas</option>
                        </select>
                        @error('duration_minutes') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
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

    <!-- Lives Table -->
    <div class="overflow-hidden rounded-lg border border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-900">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-zinc-900 dark:text-zinc-100">
                <thead class="border-b border-zinc-200 bg-zinc-50 text-xs font-semibold uppercase text-zinc-700 dark:border-zinc-700 dark:bg-zinc-800/50 dark:text-zinc-400">
                    <tr>
                        <th class="px-6 py-4 text-left">Transmisión</th>
                        <th class="px-6 py-4 text-left">Plataforma</th>
                        <th class="px-6 py-4 text-left">Estado</th>
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
                                    {{ $live->platform_label ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @if ($live->is_live)
                                    <span class="inline-flex items-center rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-800 dark:bg-red-900/30 dark:text-red-400">
                                        🔴 EN VIVO • {{ $live->duration_minutes }} min
                                    </span>
                                @elseif ($live->ends_at && $live->ends_at <= now())
                                    <div class="space-y-1">
                                        <span class="inline-flex items-center rounded-full bg-zinc-100 px-3 py-1 text-xs font-semibold text-zinc-800 dark:bg-zinc-700 dark:text-zinc-400">
                                            ⚫ FINALIZADA
                                        </span>
                                        @if ($live->live_url)
                                            <p class="text-[11px] text-zinc-500 dark:text-zinc-400">
                                                Ya puedes visualizar la grabación en {{ $live->platform_label }}.
                                            </p>
                                        @endif
                                    </div>
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
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <!-- Iniciar/Detener -->
                                    @if ($live->is_live)
                                        <button
                                            wire:click="stopLive({{ $live->id }})"
                                            class="inline-flex items-center gap-2 rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700 transition"
                                        >
                                            <i class="fas fa-stop-circle"></i>
                                            Detener Live
                                        </button>
                                    @else
                                        <button
                                            wire:click="startLive({{ $live->id }})"
                                            class="inline-flex items-center gap-2 rounded-lg bg-green-600 px-4 py-2 text-sm font-semibold text-white hover:bg-green-700 transition"
                                        >
                                            <i class="fas fa-play-circle"></i>
                                            Iniciar Live
                                        </button>
                                    @endif

                                    @if ($live->ends_at && $live->live_url)
                                        <a
                                            href="{{ $live->live_url }}"
                                            target="_blank"
                                            class="inline-flex items-center gap-2 rounded-lg border border-zinc-200 px-3 py-2 text-xs font-semibold text-zinc-700 hover:bg-zinc-50 dark:border-zinc-700 dark:text-zinc-200 dark:hover:bg-zinc-800 transition"
                                        >
                                            <i class="fas fa-play"></i>
                                            Ver grabación
                                        </a>
                                    @endif

                                    <!-- Editar -->
                                    <button
                                        wire:click="openForm({{ $live->id }})"
                                        class="inline-flex items-center gap-2 rounded-lg border border-blue-200 px-3 py-2 text-sm font-semibold text-blue-700 hover:bg-blue-50 dark:border-blue-900/40 dark:text-blue-300 dark:hover:bg-blue-900/20 transition"
                                    >
                                        <i class="fas fa-edit"></i>
                                        Editar
                                    </button>

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
                            <td colspan="4" class="px-6 py-8 text-center">
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
