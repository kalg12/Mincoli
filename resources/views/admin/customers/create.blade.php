<x-layouts.app :title="__('Agregar Cliente')">
    <div class="space-y-6">
        <!-- Header -->
        <div class="border-b border-zinc-200 bg-white px-6 py-4 dark:border-zinc-700 dark:bg-zinc-900">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Agregar Cliente</h1>
                    <p class="text-sm text-zinc-600 dark:text-zinc-400">Completa el formulario para registrar un nuevo cliente en la plataforma</p>
                </div>
                <a href="{{ route('dashboard.customers.index') }}" class="rounded-lg border border-zinc-200 px-4 py-2 text-sm font-semibold text-zinc-900 hover:bg-zinc-100/50 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-zinc-700 dark:text-white dark:hover:bg-zinc-800 dark:focus:ring-offset-zinc-900">Volver</a>
            </div>
        </div>

        <!-- Contenido principal -->
        <div class="px-6 pb-6">
            <div class="grid gap-6 md:grid-cols-3">
                <!-- Formulario principal -->
                <form method="POST" action="{{ route('dashboard.customers.store') }}" class="space-y-6 md:col-span-2">
                    @csrf

                    <!-- Errores -->
                    @if ($errors->any())
                        <div class="rounded-lg bg-red-50 border border-red-200 p-4 dark:bg-red-900/20 dark:border-red-800">
                            <div class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-red-600 dark:text-red-400 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                                <div>
                                    <p class="text-sm font-medium text-red-800 dark:text-red-300">Por favor corrige los siguientes errores:</p>
                                    <ul class="mt-2 list-inside list-disc space-y-1 text-sm text-red-700 dark:text-red-400">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Información personal -->
                    <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
                        <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-4">Información Personal</h3>

                        <div class="grid gap-4 md:grid-cols-2">
                            <div>
                                <label for="name" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">
                                    Nombre Completo <span class="text-red-500">*</span>
                                </label>
                                <input
                                    type="text"
                                    id="name"
                                    name="name"
                                    value="{{ old('name') }}"
                                    placeholder="Juan Pérez"
                                    class="w-full rounded-lg border border-zinc-200 bg-white px-4 py-2.5 text-sm text-zinc-900 placeholder-zinc-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:placeholder-zinc-500 dark:focus:ring-offset-zinc-900"
                                    required
                                >
                                @error('name')
                                    <p class="mt-1 text-xs text-red-500 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">
                                    Email <span class="text-red-500">*</span>
                                </label>
                                <input
                                    type="email"
                                    id="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    placeholder="juan@ejemplo.com"
                                    class="w-full rounded-lg border border-zinc-200 bg-white px-4 py-2.5 text-sm text-zinc-900 placeholder-zinc-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:placeholder-zinc-500 dark:focus:ring-offset-zinc-900"
                                    required
                                >
                                @error('email')
                                    <p class="mt-1 text-xs text-red-500 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="phone" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">
                                    Teléfono <span class="text-red-500">*</span>
                                </label>
                                <input
                                    type="tel"
                                    id="phone"
                                    name="phone"
                                    value="{{ old('phone') }}"
                                    placeholder="+57 300 123 4567"
                                    class="w-full rounded-lg border border-zinc-200 bg-white px-4 py-2.5 text-sm text-zinc-900 placeholder-zinc-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:placeholder-zinc-500 dark:focus:ring-offset-zinc-900"
                                    required
                                >
                                @error('phone')
                                    <p class="mt-1 text-xs text-red-500 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="company" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">
                                    Empresa (Opcional)
                                </label>
                                <input
                                    type="text"
                                    id="company"
                                    name="company"
                                    value="{{ old('company') }}"
                                    placeholder="Mi Empresa S.A.S"
                                    class="w-full rounded-lg border border-zinc-200 bg-white px-4 py-2.5 text-sm text-zinc-900 placeholder-zinc-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:placeholder-zinc-500 dark:focus:ring-offset-zinc-900"
                                >
                            </div>
                        </div>
                    </div>

                    <!-- Información de envío -->
                    <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
                        <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-4">Dirección de Envío</h3>

                        <div class="grid gap-4 md:grid-cols-2">
                            <div class="md:col-span-2">
                                <label for="address" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">
                                    Dirección (Opcional)
                                </label>
                                <input
                                    type="text"
                                    id="address"
                                    name="address"
                                    value="{{ old('address') }}"
                                    placeholder="Calle 10 #25-30"
                                    class="w-full rounded-lg border border-zinc-200 bg-white px-4 py-2.5 text-sm text-zinc-900 placeholder-zinc-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:placeholder-zinc-500 dark:focus:ring-offset-zinc-900"
                                >
                            </div>

                            <div>
                                <label for="city" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">
                                    Ciudad (Opcional)
                                </label>
                                <input
                                    type="text"
                                    id="city"
                                    name="city"
                                    value="{{ old('city') }}"
                                    placeholder="México DF"
                                    class="w-full rounded-lg border border-zinc-200 bg-white px-4 py-2.5 text-sm text-zinc-900 placeholder-zinc-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:placeholder-zinc-500 dark:focus:ring-offset-zinc-900"
                                >
                            </div>

                            <div>
                                <label for="state" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">
                                    Estado/Provincia (Opcional)
                                </label>
                                <input
                                    type="text"
                                    id="state"
                                    name="state"
                                    value="{{ old('state') }}"
                                    placeholder="CDMX"
                                    class="w-full rounded-lg border border-zinc-200 bg-white px-4 py-2.5 text-sm text-zinc-900 placeholder-zinc-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:placeholder-zinc-500 dark:focus:ring-offset-zinc-900"
                                >
                            </div>

                            <div>
                                <label for="postal_code" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">
                                    Código Postal (Opcional)
                                </label>
                                <input
                                    type="text"
                                    id="postal_code"
                                    name="postal_code"
                                    value="{{ old('postal_code') }}"
                                    placeholder="28001"
                                    class="w-full rounded-lg border border-zinc-200 bg-white px-4 py-2.5 text-sm text-zinc-900 placeholder-zinc-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:placeholder-zinc-500 dark:focus:ring-offset-zinc-900"
                                >
                            </div>

                            <div>
                                <label for="country" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">
                                    País (Opcional)
                                </label>
                                <input
                                    type="text"
                                    id="country"
                                    name="country"
                                    value="{{ old('country') }}"
                                    placeholder="México"
                                    class="w-full rounded-lg border border-zinc-200 bg-white px-4 py-2.5 text-sm text-zinc-900 placeholder-zinc-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:placeholder-zinc-500 dark:focus:ring-offset-zinc-900"
                                >
                            </div>

                            <div>
                                <label for="notes" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">
                                    Notas (Opcional)
                                </label>
                                <textarea
                                    id="notes"
                                    name="notes"
                                    placeholder="Instrucciones de entrega especiales..."
                                    rows="3"
                                    class="w-full rounded-lg border border-zinc-200 bg-white px-4 py-2.5 text-sm text-zinc-900 placeholder-zinc-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:placeholder-zinc-500 dark:focus:ring-offset-zinc-900"
                                >{{ old('notes') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Botones de acción -->
                    <div class="flex gap-3 justify-end pt-4 border-t border-zinc-200 dark:border-zinc-700">
                        <a
                            href="{{ route('dashboard.customers.index') }}"
                            class="px-6 py-2.5 bg-zinc-200 hover:bg-zinc-300 text-zinc-900 rounded-lg font-medium transition dark:bg-zinc-700 dark:hover:bg-zinc-600 dark:text-white"
                        >
                            Cancelar
                        </a>
                        <button
                            type="submit"
                            class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition flex items-center gap-2"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Crear Cliente
                        </button>
                    </div>
                </form>

                <!-- Panel de información -->
                <div class="space-y-4">
                    <!-- Tip: Campos requeridos -->
                    <div class="rounded-xl border border-blue-200 bg-blue-50 p-4 dark:border-blue-800 dark:bg-blue-900/20">
                        <div class="flex gap-3">
                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                            </svg>
                            <div>
                                <p class="font-medium text-blue-900 dark:text-blue-300">Campos Requeridos</p>
                                <p class="text-xs text-blue-800 dark:text-blue-400 mt-1">Nombre, Email y Teléfono son obligatorios. El teléfono debe ser único en el sistema.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Tip: Datos de ecommerce -->
                    <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-4 dark:border-emerald-800 dark:bg-emerald-900/20">
                        <div class="flex gap-3">
                            <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5 2a1 1 0 011 1v1h1a1 1 0 000-2H6V3a1 1 0 01-1-1zm0 0a1 1 0 00-1 1v1H3a1 1 0 100 2h1v1a1 1 0 102 0V4h1a1 1 0 100-2H6V3a1 1 0 00-1-1zm6 0a1 1 0 011 1v1h1a1 1 0 100-2h-1V3a1 1 0 01-1-1zm0 0a1 1 0 00-1 1v1h-1a1 1 0 100 2h1v1a1 1 0 102 0V4h1a1 1 0 100-2h-1V3a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                            <div>
                                <p class="font-medium text-emerald-900 dark:text-emerald-300">Información Adicional</p>
                                <p class="text-xs text-emerald-800 dark:text-emerald-400 mt-1">Los campos de dirección, ciudad y país ayudan a mejorar el envío. Las notas internas quedan disponibles para el equipo.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Consejo: Verificación -->
                    <div class="rounded-xl border border-amber-200 bg-amber-50 p-4 dark:border-amber-800 dark:bg-amber-900/20">
                        <div class="flex gap-3">
                            <svg class="w-5 h-5 text-amber-600 dark:text-amber-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                            <div>
                                <p class="font-medium text-amber-900 dark:text-amber-300">Verifica el Email</p>
                                <p class="text-xs text-amber-800 dark:text-amber-400 mt-1">Asegúrate que el email sea correcto ya que el cliente recibirá confirmaciones de pedidos.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
