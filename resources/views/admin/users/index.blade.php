<x-layouts.app title="Administración de Usuarios">
    <div class="flex-1">
        <div class="border-b border-zinc-200 bg-white px-6 py-4 dark:border-zinc-700 dark:bg-zinc-900 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Usuarios</h1>
            <a href="{{ route('dashboard.users.create') }}" class="rounded-lg bg-pink-600 px-4 py-2 text-sm font-medium text-white hover:bg-pink-700">
                <i class="fas fa-plus mr-2"></i> Nuevo Usuario
            </a>
        </div>

        <div class="p-6">
            <div class="rounded-lg border border-zinc-200 bg-white shadow-sm dark:border-zinc-700 dark:bg-zinc-900 overflow-hidden">
                <table class="w-full text-left text-sm text-zinc-600 dark:text-zinc-400">
                    <thead class="bg-zinc-50 text-xs uppercase text-zinc-500 dark:bg-zinc-800 dark:text-zinc-400">
                        <tr>
                            <th class="px-6 py-3">Nombre</th>
                            <th class="px-6 py-3">Email</th>
                            <th class="px-6 py-3">Rol</th>
                            <th class="px-6 py-3 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                        @foreach($users as $user)
                        <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50">
                            <td class="px-6 py-4 font-medium text-zinc-900 dark:text-white">{{ $user->name }}</td>
                            <td class="px-6 py-4">{{ $user->email }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex rounded-full px-2 py-1 text-xs font-semibold
                                    {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-700' : 
                                       ($user->role === 'employee' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-700') }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right flex justify-end gap-2">
                                <a href="{{ route('dashboard.users.edit', $user->id) }}" class="text-blue-600 hover:text-blue-500">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if(auth()->id() !== $user->id)
                                <form action="{{ route('dashboard.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('¿Eliminar usuario?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-500">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="p-4">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
