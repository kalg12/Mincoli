<x-layouts.app :title="__('Conteos Físicos')">
    <div class="p-6 grid gap-6">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-semibold">Conteos físicos</h1>
            <a href="{{ route('dashboard.inventory.counts.create') }}" class="px-4 py-2 rounded bg-primary-600 text-white">Nuevo conteo</a>
        </div>

        <div class="overflow-x-auto rounded-lg bg-white dark:bg-zinc-800 border dark:border-zinc-700">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="border-t dark:border-zinc-700">
                        <th class="px-4 py-2 text-left">Nombre</th>
                        <th class="px-4 py-2 text-left">Estado</th>
                        <th class="px-4 py-2 text-left">Creado</th>
                        <th class="px-4 py-2 text-left">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($counts as $count)
                        <tr class="border-t dark:border-zinc-700">
                            <td class="px-4 py-2 text-zinc-900 dark:text-white font-medium">{{ $count->name }}</td>
                            <td class="px-4 py-2">
                                @if($count->status === 'draft')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-300">
                                        Borrador
                                    </span>
                                @elseif($count->status === 'in_progress')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300">
                                        En progreso
                                    </span>
                                @elseif($count->status === 'completed')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300">
                                        Completado
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300">
                                        Revisado
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-2 text-zinc-700 dark:text-zinc-300">{{ $count->created_at?->format('d/m/Y H:i') }}</td>
                            <td class="px-4 py-2">
                                <a href="{{ route('dashboard.inventory.counts.show', $count) }}" class="text-primary-600 hover:underline">Ver detalles</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div>{{ $counts->links() }}</div>
    </div>
</x-layouts.app>
