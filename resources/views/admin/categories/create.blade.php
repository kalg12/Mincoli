<x-layouts.app :title="__('Nueva Categoría')">
    <div class="p-6 space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Nueva categoría</h1>
                <p class="text-sm text-zinc-600 dark:text-zinc-400">Organiza joyería, ropa y dulces</p>
            </div>
            <a href="{{ route('dashboard.categories.index') }}" class="rounded-lg border border-zinc-200 px-4 py-2 text-sm font-semibold text-zinc-900 hover:bg-zinc-50 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:border-zinc-700 dark:text-white dark:hover:bg-zinc-800 dark:focus:ring-offset-zinc-900">
                Volver a categorías
            </a>
        </div>

        <form action="{{ route('dashboard.categories.store') }}" method="POST" class="space-y-6">
            @csrf
            <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
                <div class="space-y-4">
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-zinc-900 dark:text-white">Nombre de la categoría</label>
                        <input type="text" name="name" placeholder="Ej: Joyería" class="w-full rounded-lg border border-zinc-200 bg-white px-4 py-2 text-sm text-zinc-900 placeholder-zinc-500 focus:border-pink-500 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:placeholder-zinc-400 dark:focus:ring-offset-zinc-900" required>
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-zinc-900 dark:text-white">Slug</label>
                        <input type="text" name="slug" placeholder="joyeria" class="w-full rounded-lg border border-zinc-200 bg-white px-4 py-2 text-sm text-zinc-900 placeholder-zinc-500 focus:border-pink-500 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:placeholder-zinc-400 dark:focus:ring-offset-zinc-900">
                        <p class="mt-1 text-xs text-zinc-500 dark:text-zinc-500">Se genera automáticamente del nombre</p>
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-zinc-900 dark:text-white">Descripción</label>
                        <textarea name="description" rows="3" placeholder="Describe la categoría..." class="w-full rounded-lg border border-zinc-200 bg-white px-4 py-2 text-sm text-zinc-900 placeholder-zinc-500 focus:border-pink-500 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:placeholder-zinc-400 dark:focus:ring-offset-zinc-900"></textarea>
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-zinc-900 dark:text-white">Estado</label>
                        <select name="status" class="w-full rounded-lg border border-zinc-200 bg-white px-4 py-2 text-sm text-zinc-900 focus:border-pink-500 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:focus:ring-offset-zinc-900">
                            <option value="active">Activa</option>
                            <option value="inactive">Inactiva</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-3">
                <a href="{{ route('dashboard.categories.index') }}" class="rounded-lg border border-zinc-200 px-4 py-2 text-sm font-semibold text-zinc-900 hover:bg-zinc-50 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:border-zinc-700 dark:text-white dark:hover:bg-zinc-800 dark:focus:ring-offset-zinc-900">
                    Cancelar
                </a>
                <button type="submit" class="rounded-lg bg-pink-600 px-4 py-2 text-sm font-semibold text-white hover:bg-pink-700 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-offset-2 dark:bg-pink-500 dark:hover:bg-pink-600 dark:focus:ring-offset-zinc-900">
                    Crear categoría
                </button>
            </div>
        </form>
    </div>
</x-layouts.app>
