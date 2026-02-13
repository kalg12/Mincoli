<x-layouts.app title="Landing de Contenido Exclusivo">
    <div class="flex-1">
        <div class="border-b border-zinc-200 bg-white px-6 py-4 dark:border-zinc-700 dark:bg-zinc-900">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Landing de Contenido Exclusivo</h1>
                    <p class="text-sm text-zinc-600 dark:text-zinc-400">Activa la landing, vigencia y filtros visibles</p>
                </div>
                <a href="{{ route('exclusive-landing.gate') }}" target="_blank" class="rounded-lg bg-pink-600 px-4 py-2 text-sm font-medium text-white hover:bg-pink-700">Ver landing <i class="fas fa-external-link-alt ml-1"></i></a>
            </div>
        </div>

        @if(session('success'))
            <div class="mx-6 mt-6 border-l-4 border-green-500 bg-green-50 px-4 py-3 dark:bg-green-900/20 dark:border-green-600">
                <p class="text-sm font-medium text-green-700 dark:text-green-300">{{ session('success') }}</p>
            </div>
        @endif

        <form action="{{ route('dashboard.exclusive-landing.update') }}" method="POST" class="mx-6 mt-6 space-y-6">
            @csrf
            @method('PUT')

            <div class="rounded-lg border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
                <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-4">Estado y vigencia</h3>
                <div class="space-y-4">
                    <label class="flex items-center gap-3">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" {{ $config->is_active ? 'checked' : '' }} class="rounded border-zinc-300 text-pink-600 focus:ring-pink-500">
                        <span class="text-zinc-700 dark:text-zinc-300">Landing activa</span>
                    </label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Fecha de inicio</label>
                            <input type="datetime-local" name="starts_at" value="{{ $config->starts_at?->format('Y-m-d\TH:i') }}" class="w-full rounded-lg border border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Fecha de fin</label>
                            <input type="datetime-local" name="ends_at" value="{{ $config->ends_at?->format('Y-m-d\TH:i') }}" class="w-full rounded-lg border border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white">
                        </div>
                    </div>
                </div>
            </div>

            <div class="rounded-lg border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
                <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-4">Contacto y mensajes</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Teléfono para solicitar acceso</label>
                        <input type="text" name="contact_phone" value="{{ old('contact_phone', $config->contact_phone) }}" placeholder="55 0000 0000" class="w-full rounded-lg border border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Mensaje cuando el número no está autorizado (opcional)</label>
                        <textarea name="restricted_message" rows="4" class="w-full rounded-lg border border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white">{{ old('restricted_message', $config->restricted_message) }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Mensaje cuando la campaña ha finalizado (opcional)</label>
                        <textarea name="expired_message" rows="2" class="w-full rounded-lg border border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white">{{ old('expired_message', $config->expired_message) }}</textarea>
                    </div>
                </div>
            </div>

            <div class="rounded-lg border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
                <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-4">Filtros visibles en la landing</h3>
                <div class="space-y-3">
                    <label class="flex items-center gap-3">
                        <input type="hidden" name="show_filter_category" value="0">
                        <input type="checkbox" name="show_filter_category" value="1" {{ $config->show_filter_category ? 'checked' : '' }} class="rounded border-zinc-300 text-pink-600 focus:ring-pink-500">
                        <span class="text-zinc-700 dark:text-zinc-300">Mostrar filtro por categoría</span>
                    </label>
                    <label class="flex items-center gap-3">
                        <input type="hidden" name="show_filter_type" value="0">
                        <input type="checkbox" name="show_filter_type" value="1" {{ $config->show_filter_type ? 'checked' : '' }} class="rounded border-zinc-300 text-pink-600 focus:ring-pink-500">
                        <span class="text-zinc-700 dark:text-zinc-300">Mostrar filtro por tipo (subcategoría)</span>
                    </label>
                    <label class="flex items-center gap-3">
                        <input type="hidden" name="show_filter_price" value="0">
                        <input type="checkbox" name="show_filter_price" value="1" {{ $config->show_filter_price ? 'checked' : '' }} class="rounded border-zinc-300 text-pink-600 focus:ring-pink-500">
                        <span class="text-zinc-700 dark:text-zinc-300">Mostrar filtro por rango de precio</span>
                    </label>
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="rounded-lg bg-pink-600 px-6 py-2 font-medium text-white hover:bg-pink-700">Guardar configuración</button>
            </div>
        </form>

        <div class="mx-6 mt-8">
            <a href="{{ route('dashboard.exclusive-landing.phones.index') }}" class="text-pink-600 hover:text-pink-700 font-medium">Gestionar números autorizados →</a>
        </div>
    </div>
</x-layouts.app>
