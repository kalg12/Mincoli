<x-layouts.app title="Asignación de Productos">
    <div class="flex-1">
        <div class="border-b border-zinc-200 bg-white px-6 py-4 dark:border-zinc-700 dark:bg-zinc-900 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Asignación de Productos</h1>
            <a href="{{ route('dashboard.assignments.create') }}" class="rounded-lg bg-pink-600 px-4 py-2 text-sm font-medium text-white hover:bg-pink-700">
                <i class="fas fa-plus mr-2"></i> Nueva Asignación
            </a>
        </div>

        <div class="p-6">
            <div class="rounded-lg border border-zinc-200 bg-white shadow-sm dark:border-zinc-700 dark:bg-zinc-900 overflow-hidden">
                <table class="w-full text-left text-sm text-zinc-600 dark:text-zinc-400">
                    <thead class="bg-zinc-50 text-xs uppercase text-zinc-500 dark:bg-zinc-800 dark:text-zinc-400">
                        <tr>
                            <th class="px-6 py-3">Nombre</th>
                            <th class="px-6 py-3">Producto</th>
                            <th class="px-6 py-3 text-right">Cantidad</th>
                            <th class="px-6 py-3 text-right">Precio Unit.</th>
                            <th class="px-6 py-3 text-right">Total</th>
                            <th class="px-6 py-3">Estado</th>
                            <th class="px-6 py-3">Fecha</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                        @foreach($assignments as $assignment)
                        <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50">
                            <td class="px-6 py-4 font-medium text-zinc-900 dark:text-white">{{ $assignment->user->name }}</td>
                            <td class="px-6 py-4">
                                {{ $assignment->product->name }}
                                @if($assignment->variant_id)
                                    <span class="text-xs text-zinc-500">({{ $assignment->variant_id }})</span> <!-- Ideally variant name -->
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">{{ $assignment->quantity }}</td>
                            <td class="px-6 py-4 text-right">${{ number_format($assignment->unit_price, 2) }}</td>
                            <td class="px-6 py-4 text-right font-bold text-zinc-900 dark:text-white">${{ number_format($assignment->total_amount, 2) }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex rounded-full px-2 py-1 text-xs font-semibold
                                    {{ $assignment->status === 'completed' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                    {{ ucfirst($assignment->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">{{ $assignment->created_at->format('d/m/Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="p-4">
                    {{ $assignments->links() }}
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
