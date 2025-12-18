<x-layouts.app :title="__('Editar banner')">
    <div class="flex-1">
        <div class="border-b border-zinc-200 bg-white px-6 py-4 dark:border-zinc-700 dark:bg-zinc-900">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Editar banner</h1>
                    <p class="text-sm text-zinc-600 dark:text-zinc-400">Actualiza la imagen, enlace y estado</p>
                </div>
                <a href="{{ route('dashboard.banners.index') }}" class="rounded-lg border border-zinc-200 px-4 py-2 text-sm font-semibold text-zinc-900 hover:bg-zinc-100/50 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:border-zinc-700 dark:text-white dark:hover:bg-zinc-800 dark:focus:ring-offset-zinc-900">Volver</a>
            </div>
        </div>

        <form action="{{ route('dashboard.banners.update', $banner->id) }}" method="POST" enctype="multipart/form-data" class="bg-white dark:bg-zinc-900">
            @csrf
            @method('PUT')
            <div class="grid gap-px border-b border-zinc-200 bg-zinc-200 dark:border-zinc-700 dark:bg-zinc-700/40 md:grid-cols-2">
                <div class="space-y-4 bg-white p-6 dark:bg-zinc-900">
                    <h2 class="text-lg font-semibold text-zinc-900 dark:text-white">Información</h2>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Título</label>
                        <input type="text" name="title" value="{{ old('title', $banner->title) }}" class="w-full rounded-lg border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-900 placeholder-zinc-500 focus:border-pink-500 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:placeholder-zinc-400 dark:focus:ring-offset-zinc-900 @error('title') border-red-500 @enderror"/>
                        @error('title')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Texto</label>
                        <textarea name="text" rows="3" class="w-full rounded-lg border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-900 placeholder-zinc-500 focus:border-pink-500 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:placeholder-zinc-400 dark:focus:ring-offset-zinc-900 @error('text') border-red-500 @enderror">{{ old('text', $banner->text) }}</textarea>
                        @error('text')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">URL destino</label>
                        <input type="url" name="link_url" value="{{ old('link_url', $banner->link_url) }}" class="w-full rounded-lg border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-900 placeholder-zinc-500 focus:border-pink-500 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:placeholder-zinc-400 dark:focus:ring-offset-zinc-900 @error('link_url') border-red-500 @enderror"/>
                        @error('link_url')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Posición</label>
                            <input type="number" name="position" min="1" value="{{ old('position', $banner->position) }}" class="w-full rounded-lg border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-pink-500 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:focus:ring-offset-zinc-900 @error('position') border-red-500 @enderror"/>
                            @error('position')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Estado</label>
                            <select name="status" class="w-full rounded-lg border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-pink-500 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:focus:ring-offset-zinc-900 @error('status') border-red-500 @enderror">
                                <option value="active" {{ old('status', $banner->is_active ? 'active' : 'inactive') == 'active' ? 'selected' : '' }}>Activo</option>
                                <option value="scheduled" {{ old('status', $banner->is_active ? 'active' : 'inactive') == 'scheduled' ? 'selected' : '' }}>Programado</option>
                                <option value="inactive" {{ old('status', $banner->is_active ? 'active' : 'inactive') == 'inactive' ? 'selected' : '' }}>Inactivo</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="space-y-4 bg-white p-6 dark:bg-zinc-900">
                    <h2 class="text-lg font-semibold text-zinc-900 dark:text-white">Imagen</h2>
                    @if($banner->image_url)
                        <div class="aspect-[16/5] w-full rounded-lg border border-zinc-300 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-800 overflow-hidden">
                            <img src="{{ $banner->image_url }}" alt="{{ $banner->title }}" class="w-full h-full object-cover">
                        </div>
                    @else
                        <div class="aspect-[16/5] w-full rounded-lg border border-dashed border-zinc-300 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-800 flex items-center justify-center">
                            <p class="text-zinc-500 dark:text-zinc-400">No hay imagen</p>
                        </div>
                    @endif
                    <div>
                        <label class="text-sm font-medium text-zinc-700 dark:text-zinc-300 block mb-2">Cambiar imagen</label>
                        <input type="file" name="image" accept="image/jpeg,image/png,image/webp" id="edit-image-input" class="w-full text-sm text-zinc-500 dark:text-zinc-400 file:rounded-lg file:border-0 file:bg-pink-600 file:text-white file:font-semibold hover:file:bg-pink-700 file:cursor-pointer @error('image') border-red-500 @enderror" />
                        @error('image')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <p class="text-sm text-zinc-600 dark:text-zinc-400">Recomendado: 1920×600px, JPG/PNG/WEBP, < 5MB</p>
                </div>
            </div>

            <div class="sticky bottom-0 flex items-center justify-end gap-2 border-t border-zinc-200 bg-white px-6 py-4 dark:border-zinc-700 dark:bg-zinc-900">
                <a href="{{ route('dashboard.banners.index') }}" class="rounded-lg border border-zinc-200 px-4 py-2 text-sm font-medium text-zinc-900 hover:bg-zinc-100/50 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:border-zinc-700 dark:text-white dark:hover:bg-zinc-800 dark:focus:ring-offset-zinc-900">Cancelar</a>
                <button type="submit" class="rounded-lg bg-pink-600 px-4 py-2 text-sm font-semibold text-white hover:bg-pink-700 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-offset-2 dark:bg-pink-500 dark:hover:bg-pink-600 dark:focus:ring-offset-zinc-900">Guardar</button>
            </div>
        </form>
    </div>
</x-layouts.app>
