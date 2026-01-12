<x-layouts.app title="Editar Usuario">
    <div class="flex-1">
        <div class="border-b border-zinc-200 bg-white px-6 py-4 dark:border-zinc-700 dark:bg-zinc-900 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Editar Usuario</h1>
            <a href="{{ route('dashboard.users.index') }}" class="text-sm font-medium text-zinc-600 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-white">Volver</a>
        </div>

        <div class="p-6 max-w-2xl">
            <div class="rounded-lg border border-zinc-200 bg-white shadow-sm dark:border-zinc-700 dark:bg-zinc-900 p-6">
                <form action="{{ route('dashboard.users.update', $user->id) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')
                    
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Nombre</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring-pink-500 sm:text-sm" required>
                        @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Email</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring-pink-500 sm:text-sm" required>
                        @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Rol</label>
                        <select name="role" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring-pink-500 sm:text-sm">
                            <option value="employee" {{ old('role', $user->role) == 'employee' ? 'selected' : '' }}>Empleado</option>
                            <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Administrador</option>
                            <option value="customer" {{ old('role', $user->role) == 'customer' ? 'selected' : '' }}>Cliente</option>
                        </select>
                        @error('role') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="border-t border-gray-200 pt-6">
                        <h3 class="text-sm font-medium text-gray-900 dark:text-white">Cambiar Contraseña (Opcional)</h3>
                        <div class="mt-4 space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Nueva Contraseña</label>
                                <input type="password" name="password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring-pink-500 sm:text-sm">
                                @error('password') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Confirmar Nueva Contraseña</label>
                                <input type="password" name="password_confirmation" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring-pink-500 sm:text-sm">
                            </div>
                        </div>
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="w-full rounded-md bg-pink-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-pink-700 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-offset-2">
                            Actualizar Usuario
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.app>
