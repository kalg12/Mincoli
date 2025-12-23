<x-layouts.public-capture :title="'Captura - ' . $count->name">
    <div class="p-6 grid gap-6">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-zinc-900 dark:text-white">{{ $count->name }}</h1>
            <span class="text-sm text-zinc-500">Comparte este enlace únicamente con personal autorizado</span>
        </div>

        <div class="flex items-center gap-3">
            <label for="counter-name" class="text-sm font-semibold text-zinc-700 dark:text-zinc-200">Nombre de quien captura</label>
            <input id="counter-name" type="text" placeholder="Ej. Juan Pérez" class="w-64 border rounded px-3 py-2 text-sm dark:bg-zinc-900 dark:border-zinc-700" />
        </div>

        <div class="overflow-x-auto rounded-lg bg-white dark:bg-zinc-800 border dark:border-zinc-700">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="border-t dark:border-zinc-700">
                        <th class="px-4 py-2 text-left">Producto</th>
                        <th class="px-4 py-2 text-left">Sistema</th>
                        <th class="px-4 py-2 text-left">Contado</th>
                        <th class="px-4 py-2 text-left">Diferencia</th>
                        <th class="px-4 py-2 text-left">Notas</th>
                        <th class="px-4 py-2 text-left">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $item)
                        <tr class="border-t dark:border-zinc-700" id="row-{{ $item->id }}">
                            <td class="px-4 py-2">{{ $item->product?->name }}@if($item->variant) - {{ $item->variant->name }} @endif</td>
                            <td class="px-4 py-2">{{ $item->system_quantity }}</td>
                            <td class="px-4 py-2">
                                <input type="number" min="0" value="{{ $item->counted_quantity ?? 0 }}" class="w-24 border rounded px-2 py-1 dark:bg-zinc-900 dark:border-zinc-700" id="counted-{{ $item->id }}" />
                            </td>
                            <td class="px-4 py-2"><span id="diff-{{ $item->id }}">{{ $item->difference ?? 0 }}</span></td>
                            <td class="px-4 py-2">
                                <input type="text" placeholder="Notas..." value="{{ $item->notes }}" class="w-full border rounded px-2 py-1 dark:bg-zinc-900 dark:border-zinc-700 text-sm" id="notes-{{ $item->id }}" />
                            </td>
                            <td class="px-4 py-2">
                                <button class="px-3 py-1 rounded bg-primary-600 text-white" onclick="saveItem('{{ $token }}', {{ $item->id }})">Guardar</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div>{{ $items->links() }}</div>
    </div>

    @push('scripts')
    <script>
        function showToast(message, type = 'success') {
            const toast = document.createElement('div');
            toast.className = `fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg text-white z-50 ${type === 'success' ? 'bg-green-600' : 'bg-red-600'}`;
            toast.textContent = message;
            document.body.appendChild(toast);
            setTimeout(() => {
                toast.style.transition = 'opacity 0.3s';
                toast.style.opacity = '0';
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }

        async function saveItem(token, itemId) {
            const counted = document.getElementById('counted-' + itemId).value;
            const notes = document.getElementById('notes-' + itemId).value;
            const counterName = (document.getElementById('counter-name')?.value || '').trim();

            if (!counterName) {
                showToast('Escribe el nombre de quien captura', 'error');
                return;
            }

            const res = await fetch(`{{ url('/inventory-capture') }}/${token}/items/${itemId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                },
                body: JSON.stringify({
                    counted_quantity: Number(counted),
                    notes: notes,
                    counter_name: counterName
                })
            });

            if (res.ok) {
                const data = await res.json();
                document.getElementById('diff-' + itemId).innerText = data.difference ?? 0;
                showToast('Guardado correctamente', 'success');
            } else {
                showToast('Error al guardar', 'error');
            }
        }
    </script>
    @endpush
</x-layouts.public-capture>
