<x-layouts.page :title="__('Contacto')">
    <div class="min-h-screen bg-gradient-to-b from-white to-gray-50">
        <!-- Header Section -->
        <div class="bg-pink-600 text-white py-12">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <h1 class="text-4xl md:text-5xl font-bold mb-4">Contáctanos</h1>
                <p class="text-lg text-pink-100">Estamos aquí para ayudarte</p>
            </div>
        </div>

        <!-- Main Content -->
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <!-- Contact Info Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
                <!-- WhatsApp -->
                <div class="bg-white p-8 rounded-lg shadow-sm border border-gray-200 hover:shadow-lg transition">
                    <div class="text-5xl mb-4">💬</div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">WhatsApp</h3>
                    <p class="text-gray-600 mb-4">Chatea con nosotros en tiempo real</p>
                    <a href="https://wa.me/5256117011660" target="_blank" class="inline-block bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-6 rounded-lg transition">
                        Iniciar Chat
                    </a>
                </div>

                <!-- Phone -->
                <div class="bg-white p-8 rounded-lg shadow-sm border border-gray-200 hover:shadow-lg transition">
                    <div class="text-5xl mb-4">☎️</div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Teléfono</h3>
                    <p class="text-gray-600 mb-4">Llámanos directamente</p>
                    <a href="tel:+5256117011660" class="inline-block bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-6 rounded-lg transition">
                        +52 56 1170 1166
                    </a>
                </div>

                <!-- Email -->
                <div class="bg-white p-8 rounded-lg shadow-sm border border-gray-200 hover:shadow-lg transition">
                    <div class="text-5xl mb-4">✉️</div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Correo Electrónico</h3>
                    <p class="text-gray-600 mb-4">Envíanos un email</p>
                    <a href="mailto:mincoli.ventas.online@outlook.com" class="inline-block bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-6 rounded-lg transition break-all">
                        mincoli.ventas.online@outlook.com
                    </a>
                </div>

                <!-- Location -->
                <div class="bg-white p-8 rounded-lg shadow-sm border border-gray-200 hover:shadow-lg transition">
                    <div class="text-5xl mb-4">📍</div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Ubicación</h3>
                    <p class="text-gray-600 mb-4">Visitanos en</p>
                    <p class="text-gray-700 font-semibold">Ciudad de México (CDMX)</p>
                </div>
            </div>

            <!-- Hours Section -->
            <div class="bg-white rounded-lg p-8 border border-gray-200 mb-12">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Horarios de Atención</h2>
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-4 bg-pink-50 rounded-lg">
                        <div>
                            <h3 class="font-semibold text-gray-900">Atención al Cliente</h3>
                            <p class="text-gray-600">Lunes a Sábado</p>
                        </div>
                        <div class="text-right">
                            <p class="text-lg font-bold text-pink-600">9:00 AM - 6:00 PM</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Live Shopping Info -->
            <div class="bg-gradient-to-r from-pink-600 to-pink-700 text-white rounded-lg p-8 mb-12">
                <h2 class="text-2xl font-bold mb-6">🎥 Nuestras Transmisiones en Vivo</h2>
                <div class="space-y-4 mb-6">
                    <div class="flex items-center">
                        <span class="text-3xl mr-4">🍬</span>
                        <div>
                            <p class="font-semibold">Domingo</p>
                            <p class="text-pink-100">Dulces - 6:00 p.m.</p>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <span class="text-3xl mr-4">✨</span>
                        <div>
                            <p class="font-semibold">Miércoles y Jueves</p>
                            <p class="text-pink-100">Accesorios y Joyería</p>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <span class="text-3xl mr-4">👗</span>
                        <div>
                            <p class="font-semibold">Viernes y Sábado</p>
                            <p class="text-pink-100">Ropa</p>
                        </div>
                    </div>
                </div>
                <p class="text-pink-100 mb-4">¡Únete a nuestras transmisiones en vivo y descubre nuestros productos en tiempo real!</p>
                <a href="https://wa.me/5256117011660" target="_blank" class="inline-block bg-white text-pink-600 hover:bg-pink-50 font-bold py-2 px-6 rounded-lg transition">
                    Recordarme los Horarios
                </a>
            </div>

            <!-- FAQ Link -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-8 text-center">
                <h3 class="text-2xl font-bold text-blue-900 mb-4">¿Tienes una pregunta específica?</h3>
                <p class="text-gray-700 mb-6">Consulta nuestras preguntas frecuentes para encontrar respuestas rápidas</p>
                <a href="{{ route('faq') }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-lg transition">
                    Ver Preguntas Frecuentes
                </a>
            </div>

            <!-- Social Media Section -->
            <div class="mt-12">
                <h2 class="text-2xl font-bold text-gray-900 mb-6 text-center">Síguenos en Redes Sociales</h2>
                <div class="flex justify-center gap-4 flex-wrap">
                    <a href="https://www.facebook.com/MincoliMx" target="_blank" rel="noopener" class="flex items-center gap-2 bg-white border border-gray-200 hover:border-pink-600 px-6 py-3 rounded-lg transition">
                        <span class="text-2xl">f</span>
                        <span class="font-semibold text-gray-900">Facebook</span>
                    </a>
                    <a href="https://www.instagram.com/mincolimx" target="_blank" rel="noopener" class="flex items-center gap-2 bg-white border border-gray-200 hover:border-pink-600 px-6 py-3 rounded-lg transition">
                        <span class="text-2xl">📷</span>
                        <span class="font-semibold text-gray-900">Instagram</span>
                    </a>
                    <a href="https://www.tiktok.com/@thereal_mincoli_by_jaz" target="_blank" rel="noopener" class="flex items-center gap-2 bg-white border border-gray-200 hover:border-pink-600 px-6 py-3 rounded-lg transition">
                        <span class="text-2xl">▶️</span>
                        <span class="font-semibold text-gray-900">TikTok</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-layouts.page>
