<x-layouts.page :title="__('Sobre Nosotros')">
    <div class="min-h-screen bg-gradient-to-b from-white to-gray-50">
        <!-- Header Section -->
        <div class="bg-pink-600 text-white py-12">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <h1 class="text-4xl md:text-5xl font-bold mb-4">Sobre Nosotros</h1>
                <p class="text-lg text-pink-100">Descubre la historia detrás de Mincoli</p>
            </div>
        </div>

        <!-- Main Content -->
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <!-- Story Section -->
            <div class="mb-16">
                <h2 class="text-3xl font-bold text-gray-900 mb-6">Nuestra Historia</h2>
                <p class="text-lg text-gray-700 leading-relaxed mb-4">
                    Somos una marca mexicana que nació en 2020 con una visión clara: realzar la esencia y la fortaleza de cada mujer a través del estilo.
                    Hoy, al cumplir 5 años en el mercado, continuamos construyendo una comunidad de mujeres seguras, auténticas y empoderadas que encuentran
                    en la moda una poderosa forma de expresión.
                </p>
            </div>

            <!-- Products & Services -->
            <div class="mb-16">
                <h2 class="text-3xl font-bold text-gray-900 mb-6">Nuestros Productos</h2>
                <p class="text-lg text-gray-700 leading-relaxed mb-4">
                    Nos especializamos en la venta de ropa, accesorios, joyería, bolsas, zapatos y dulces, cuidadosamente seleccionados para ofrecer
                    experiencias completas de estilo, desde los pies hasta la cabeza. Cada pieza es elegida con dedicación, asegurando calidad, tendencia
                    y elegancia en cada detalle.
                </p>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mt-8">
                    <div class="bg-pink-50 p-4 rounded-lg text-center">
                        <p class="font-semibold text-gray-900">👗 Ropa</p>
                    </div>
                    <div class="bg-pink-50 p-4 rounded-lg text-center">
                        <p class="font-semibold text-gray-900">✨ Joyería</p>
                    </div>
                    <div class="bg-pink-50 p-4 rounded-lg text-center">
                        <p class="font-semibold text-gray-900">👜 Bolsas</p>
                    </div>
                    <div class="bg-pink-50 p-4 rounded-lg text-center">
                        <p class="font-semibold text-gray-900">👠 Zapatos</p>
                    </div>
                    <div class="bg-pink-50 p-4 rounded-lg text-center">
                        <p class="font-semibold text-gray-900">🎀 Accesorios</p>
                    </div>
                    <div class="bg-pink-50 p-4 rounded-lg text-center">
                        <p class="font-semibold text-gray-900">🍬 Dulces</p>
                    </div>
                </div>
            </div>

            <!-- Team & Support -->
            <div class="mb-16">
                <h2 class="text-3xl font-bold text-gray-900 mb-6">Nuestro Equipo</h2>
                <p class="text-lg text-gray-700 leading-relaxed mb-4">
                    Contamos con asesoras calificadas que te acompañan en el proceso de elegir la mejor imagen para ti, ayudándote a descubrir tu estilo
                    con confianza y autenticidad. Creemos firmemente que la talla es solo un número y que cada mujer merece portar sus prendas con seguridad,
                    orgullo y amor propio.
                </p>
            </div>

            <!-- Shipping & Payment -->
            <div class="mb-16">
                <h2 class="text-3xl font-bold text-gray-900 mb-6">Experiencia de Compra</h2>
                <p class="text-lg text-gray-700 leading-relaxed mb-4">
                    Realizamos envíos a todo México y ofrecemos métodos de pago seguros mediante tarjetas bancarias y transferencias, brindando una
                    experiencia de compra práctica, confiable y placentera.
                </p>
            </div>

            <!-- Mission -->
            <div class="bg-gradient-to-r from-pink-50 to-pink-100 p-8 rounded-lg">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Nuestra Misión</h2>
                <p class="text-lg text-gray-700 leading-relaxed">
                    Más que una marca, somos un espacio donde la moda se convierte en actitud y el estilo en una declaración de poder.
                </p>
            </div>

            <!-- Live Shopping Section -->
            <div class="mt-16 bg-white border-2 border-pink-200 rounded-lg p-8">
                <h2 class="text-3xl font-bold text-gray-900 mb-6">¡Únete a Nuestras Transmisiones en Vivo!</h2>

                <div class="mb-8">
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Horarios de Atención</h3>
                    <p class="text-gray-700 mb-4">Atendemos tus solicitudes con gusto en nuestro horario laboral:</p>
                    <p class="text-lg font-semibold text-pink-600 mb-6">🕘 Lunes a sábado de 9:00 a.m. a 6:00 p.m.</p>
                </div>

                <div class="mb-8">
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Horarios de Transmisión</h3>
                    <div class="space-y-3">
                        <div class="flex items-center p-4 bg-pink-50 rounded-lg">
                            <span class="text-2xl mr-4">🍬</span>
                            <div>
                                <p class="font-semibold text-gray-900">Domingo</p>
                                <p class="text-gray-600">Dulces - 6:00 p.m.</p>
                            </div>
                        </div>
                        <div class="flex items-center p-4 bg-pink-50 rounded-lg">
                            <span class="text-2xl mr-4">✨</span>
                            <div>
                                <p class="font-semibold text-gray-900">Miércoles y Jueves</p>
                                <p class="text-gray-600">Accesorios y Joyería</p>
                            </div>
                        </div>
                        <div class="flex items-center p-4 bg-pink-50 rounded-lg">
                            <span class="text-2xl mr-4">👗</span>
                            <div>
                                <p class="font-semibold text-gray-900">Viernes y Sábado</p>
                                <p class="text-gray-600">Ropa</p>
                            </div>
                        </div>
                    </div>
                </div>

                <p class="text-gray-700 mb-6">Nuestro objetivo es brindarte una atención cercana, amable y oportuna.</p>

                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="https://wa.me/5256117011660" target="_blank" class="flex-1 bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-6 rounded-lg text-center transition">
                        📱 Contáctanos por WhatsApp
                    </a>
                    <a href="{{ route('shop') }}" class="flex-1 bg-pink-600 hover:bg-pink-700 text-white font-bold py-3 px-6 rounded-lg text-center transition">
                        🛍️ Ver Tienda
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-layouts.page>
