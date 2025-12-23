<x-layouts.app :title="__('Nuevo Conteo')">
    <div class="p-6 grid gap-6">
        <h1 class="text-2xl font-semibold">Crear conteo físico</h1>

        <form method="POST" action="{{ route('dashboard.inventory.counts.store') }}" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @csrf

            <div class="md:col-span-2">
                <label class="block text-sm mb-1">Nombre</label>
                <input type="text" name="name" class="w-full border rounded px-3 py-2 dark:bg-zinc-900 dark:border-zinc-700" required />
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm mb-1">Notas</label>
                <textarea name="notes" rows="4" class="w-full border rounded px-3 py-2 dark:bg-zinc-900 dark:border-zinc-700"></textarea>
            </div>

            <div class="md:col-span-2 flex gap-3">
                <button class="px-4 py-2 rounded bg-primary-600 text-white">Crear</button>
                <a href="{{ route('dashboard.inventory.counts.index') }}" class="px-4 py-2 rounded border dark:border-zinc-700">Cancelar</a>
            </div>
        </form>
    </div>
</x-layouts.app>
