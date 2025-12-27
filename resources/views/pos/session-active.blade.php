<x-layouts.app :title="__('POS - Sesión Activa')">
<div class="p-6 space-y-6">
    <div class="max-w-md mx-auto bg-white rounded-lg shadow p-8">
        <div class="text-center mb-6">
            <div class="text-5xl mb-4">✓</div>
            <h1 class="text-2xl font-bold">Sesión Activa</h1>
        </div>

        <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
            <p class="text-sm text-green-800">
                <strong class="block mb-2">{{ $session->session_number }}</strong>
                Abierta desde: {{ $session->opened_at->format('d/m/Y H:i:s') }}
            </p>
        </div>

        <div class="space-y-3">
            <a href="{{ route('dashboard.pos.transaction.create', $session) }}" class="block text-center px-4 py-3 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition">
                Crear Nueva Transacción
            </a>
            <a href="{{ route('dashboard.pos.index') }}" class="block text-center px-4 py-3 bg-gray-200 text-gray-800 rounded-lg font-semibold hover:bg-gray-300 transition">
                Ir al Dashboard POS
            </a>
        </div>
    </div>
</div>
</x-layouts.app>
