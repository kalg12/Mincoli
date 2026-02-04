<x-layouts.app :title="__('Gestionar Transmisiones en Vivo')">
    <div class="container mx-auto px-4 py-8">
        <livewire:manage-live-session />
    </div>

    @push('scripts')
    <script>
        // Escuchar eventos de notificación de Livewire
        Livewire.on('notify', data => {
            const { type, message } = data;
            showNotification(message, type);
        });

        function showNotification(message, type = 'success') {
            // Crear elemento de notificación
            const notification = document.createElement('div');
            const bgColor = type === 'success' ? 'bg-green-500' : type === 'error' ? 'bg-red-500' : 'bg-blue-500';

            notification.className = `fixed top-4 right-4 ${bgColor} text-white px-6 py-3 rounded-lg shadow-lg z-50 animate-pulse`;
            notification.textContent = message;

            document.body.appendChild(notification);

            setTimeout(() => {
                notification.remove();
            }, 3000);
        }
    </script>
    @endpush
</x-layouts.app>
