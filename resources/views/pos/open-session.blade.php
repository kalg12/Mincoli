<x-layouts.app :title="__('POS - Abrir Sesión')">
<div class="p-6 space-y-6">
    <div class="max-w-md mx-auto">
        <div class="rounded-xl border border-zinc-200 bg-white p-8 shadow dark:border-zinc-700 dark:bg-zinc-900">
            <h1 class="text-2xl font-bold mb-6">Abrir Sesión de POS</h1>

            <form action="{{ route('dashboard.pos.session.store') }}" method="POST">
                @csrf

                <div class="mb-6">
                    <p class="text-gray-600 mb-4">
                        Una sesión de POS es una jornada de trabajo donde podrás crear transacciones, apartados y registrar pagos.
                    </p>
                </div>

                <button type="submit" class="w-full px-4 py-3 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition">
                    Abrir Nueva Sesión
                </button>

                <a href="{{ route('dashboard.pos.index') }}" class="block text-center mt-4 text-gray-600 hover:text-gray-800">
                    Volver
                </a>
            </form>
        </div>
    </div>
</div>
</x-layouts.app>
