<x-layouts.app title="Crear Usuario">
    <div class="flex-1">
        <div class="border-b border-zinc-200 bg-white px-6 py-4 dark:border-zinc-700 dark:bg-zinc-900 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Nuevo Usuario</h1>
            <a href="{{ route('dashboard.users.index') }}" class="text-sm font-medium text-zinc-600 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-white">Volver</a>
        </div>

        <div class="p-6 max-w-2xl">
            <div class="rounded-lg border border-zinc-200 bg-white shadow-sm dark:border-zinc-700 dark:bg-zinc-900 p-6">
                <form action="{{ route('dashboard.users.store') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Nombre</label>
                        <input type="text" name="name" value="{{ old('name') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring-pink-500 sm:text-sm" required>
                        @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring-pink-500 sm:text-sm" required>
                        @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Rol</label>
                        <select name="role" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring-pink-500 sm:text-sm">
                            <option value="employee" {{ old('role') == 'employee' ? 'selected' : '' }}>Empleado</option>
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrador</option>
                            <option value="customer" {{ old('role') == 'customer' ? 'selected' : '' }}>Cliente</option>
                        </select>
                        @error('role') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Contraseña</label>
                        <input type="password" name="password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring-pink-500 sm:text-sm" required>
                        @error('password') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Confirmar Contraseña</label>
                        <input type="password" name="password_confirmation" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring-pink-500 sm:text-sm" required>
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="w-full rounded-md bg-pink-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-pink-700 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-offset-2">
                            Crear Usuario
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.app>
