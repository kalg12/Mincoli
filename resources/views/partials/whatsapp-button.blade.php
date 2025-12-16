<!-- WhatsApp Floating Button -->
<a href="https://wa.me/+5215611701166?text=Hola%2C%20me%20gustar%C3%ADa%20obtener%20m%C3%A1s%20informaci%C3%B3n%20sobre%20sus%20productos."
   target="_blank"
   rel="noopener noreferrer"
   class="fixed bottom-6 right-6 z-50 group"
   aria-label="Contactar por WhatsApp">
    <div class="relative">
        <!-- Pulse animation -->
        <div class="absolute inset-0 bg-green-500 rounded-full animate-ping opacity-75"></div>

        <!-- Button -->
        <div class="relative w-16 h-16 bg-green-500 hover:bg-green-600 rounded-full shadow-2xl flex items-center justify-center transition-all duration-300 hover:scale-110 active:scale-95">
            <i class="fab fa-whatsapp text-white text-3xl"></i>
        </div>

        <!-- Tooltip -->
        <div class="absolute right-20 top-1/2 -translate-y-1/2 bg-gray-900 text-white px-4 py-2 rounded-lg text-sm font-medium whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none shadow-lg">
            ¿Necesitas ayuda? Chatea con nosotros
            <div class="absolute right-0 top-1/2 -translate-y-1/2 translate-x-2 w-0 h-0 border-t-8 border-t-transparent border-b-8 border-b-transparent border-l-8 border-l-gray-900"></div>
        </div>
    </div>
</a>

<style>
    @keyframes ping {
        75%, 100% {
            transform: scale(1.5);
            opacity: 0;
        }
    }
    .animate-ping {
        animation: ping 1.5s cubic-bezier(0, 0, 0.2, 1) infinite;
    }
</style>
