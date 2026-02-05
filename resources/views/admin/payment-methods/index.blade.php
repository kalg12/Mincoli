<x-layouts.app :title="__('Métodos de pago')">
    <div class="p-6 space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Métodos de pago</h1>
                <p class="text-sm text-zinc-600 dark:text-zinc-400">Sincroniza tarjetas, transferencia y OXXO</p>
            </div>
            <a href="{{ route('dashboard.payment-methods.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-pink-600 px-4 py-2 text-sm font-semibold text-white hover:bg-pink-700 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-offset-2 dark:bg-pink-500 dark:hover:bg-pink-600 dark:focus:ring-offset-zinc-900 transition-colors">
                <i class="fas fa-plus"></i>
                Nuevo Método
            </a>
        </div>

        <!-- Payment Methods Grid -->
        <div class="grid gap-4 md:grid-cols-2">
            @foreach($methods as $method)
            <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
                <div class="mb-4 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="flex h-12 w-12 items-center justify-center rounded-lg {{ $method->is_active ? 'bg-green-100 dark:bg-green-900/30' : 'bg-gray-100 dark:bg-gray-800' }}">
                            @if($method->code == 'mercadopago')
                                <i class="fas fa-credit-card text-xl {{ $method->is_active ? 'text-green-600' : 'text-gray-500' }}"></i>
                            @elseif($method->code == 'transfer')
                                <i class="fas fa-university text-xl {{ $method->is_active ? 'text-green-600' : 'text-gray-500' }}"></i>
                            @else
                                <i class="fas fa-wallet text-xl {{ $method->is_active ? 'text-green-600' : 'text-gray-500' }}"></i>
                            @endif
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-zinc-900 dark:text-white">{{ $method->name }}</h3>
                            <p class="text-xs text-zinc-500 dark:text-zinc-500">{{ Str::limit($method->description, 30) }}</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $method->is_active ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' }}">
                        {{ $method->is_active ? 'Activo' : 'Inactivo' }}
                    </span>
                </div>
                <p class="mb-4 text-sm text-zinc-600 dark:text-zinc-400 h-10 overflow-hidden">{{ $method->description }}</p>
                <div class="flex gap-2">
                    <a href="{{ route('dashboard.payment-methods.edit', $method->id) }}" class="inline-flex flex-1 justify-center rounded-lg border border-zinc-200 bg-white px-4 py-2 text-sm font-medium text-zinc-900 hover:bg-zinc-100/50 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:hover:bg-zinc-700 dark:focus:ring-offset-zinc-900 transition-colors">
                        Configurar
                    </a>
                    <form action="{{ route('dashboard.payment-methods.destroy', $method->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este método de pago?');" class="inline-block">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="rounded-lg border border-red-200 bg-white px-4 py-2 text-sm font-medium text-red-600 hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-red-500 dark:border-red-700 dark:bg-zinc-800 dark:text-red-400 dark:hover:bg-red-900/20 transition-colors">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</x-layouts.app>
