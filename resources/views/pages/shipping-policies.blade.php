<x-layouts.page :title="__('Políticas de Envío')">
    <div class="min-h-screen bg-gradient-to-b from-white to-gray-50">
        <!-- Header Section -->
        <div class="bg-pink-600 text-white py-12">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <h1 class="text-4xl md:text-5xl font-bold mb-4">Políticas de Envío</h1>
                <p class="text-lg text-pink-100">Conoce nuestros tiempos y costos de entrega</p>
            </div>
        </div>

        <!-- Main Content -->
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <!-- Introduction -->
            <div class="mb-12">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Sobre Nuestros Envíos</h2>
                <p class="text-lg text-gray-700 leading-relaxed">
                    Somos una marca establecida en la Ciudad de México (CDMX) y realizamos envíos a toda la República Mexicana.
                    Todos los pedidos son cuidadosamente preparados y empacados para asegurar que lleguen en perfectas condiciones.
                    Trabajamos con paqueterías confiables para ofrecer un servicio seguro y puntual.
                </p>
            </div>

            <!-- Shipping Rates -->
            <div class="mb-12">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Costos y Tiempos de Entrega</h2>
                <div class="space-y-4">
                    <div class="bg-white border-l-4 border-pink-600 p-6 rounded-lg shadow-sm">
                        <div class="flex items-start justify-between">
                            <div>
                                <h3 class="text-xl font-semibold text-gray-900 mb-2">Ciudad de México (CDMX)</h3>
                                <p class="text-gray-600">1 a 4 días hábiles</p>
                            </div>
                            <span class="text-3xl font-bold text-pink-600">$85</span>
                        </div>
                        <p class="text-sm text-gray-500 mt-2">MXN</p>
                    </div>

                    <div class="bg-white border-l-4 border-pink-600 p-6 rounded-lg shadow-sm">
                        <div class="flex items-start justify-between">
                            <div>
                                <h3 class="text-xl font-semibold text-gray-900 mb-2">Estado de México</h3>
                                <p class="text-gray-600">1 a 4 días hábiles</p>
                            </div>
                            <span class="text-3xl font-bold text-pink-600">$150</span>
                        </div>
                        <p class="text-sm text-gray-500 mt-2">MXN</p>
                    </div>

                    <div class="bg-white border-l-4 border-pink-600 p-6 rounded-lg shadow-sm">
                        <div class="flex items-start justify-between">
                            <div>
                                <h3 class="text-xl font-semibold text-gray-900 mb-2">República Mexicana</h3>
                                <p class="text-gray-600">1 a 6 días hábiles</p>
                                <p class="text-sm text-gray-500 mt-1">*Aplica cargo extra para zonas extendidas</p>
                            </div>
                            <span class="text-3xl font-bold text-pink-600">$185+</span>
                        </div>
                        <p class="text-sm text-gray-500 mt-2">MXN</p>
                    </div>
                </div>
            </div>

            <!-- Important Notes -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-12">
                <h3 class="text-lg font-semibold text-blue-900 mb-4">📍 Información Importante</h3>
                <ul class="space-y-2 text-gray-700">
                    <li class="flex items-start">
                        <span class="text-blue-600 mr-3 font-bold">✓</span>
                        <span>Los tiempos de entrega son aproximados y pueden variar según la ubicación</span>
                    </li>
                    <li class="flex items-start">
                        <span class="text-blue-600 mr-3 font-bold">✓</span>
                        <span>Trabajamos de lunes a sábado. Los pedidos realizados el domingo se procesarán el lunes</span>
                    </li>
                    <li class="flex items-start">
                        <span class="text-blue-600 mr-3 font-bold">✓</span>
                        <span>El costo de envío se agregará al total de tu compra en el carrito</span>
                    </li>
                    <li class="flex items-start">
                        <span class="text-blue-600 mr-3 font-bold">✓</span>
                        <span>Puedes rastrear tu paquete con el número de seguimiento que te enviaremos por correo</span>
                    </li>
                </ul>
            </div>

            <!-- CTA Section -->
            <div class="bg-gradient-to-r from-pink-50 to-pink-100 p-8 rounded-lg text-center">
                <h3 class="text-2xl font-bold text-gray-900 mb-4">¿Dudas sobre tu envío?</h3>
                <p class="text-gray-700 mb-6">Contáctanos por WhatsApp y con gusto te ayudaremos</p>
                <a href="https://wa.me/5256117011660" target="_blank" class="inline-block bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-8 rounded-lg transition">
                    💬 Chatea con nosotros
                </a>
            </div>
        </div>
    </div>
</x-layouts.page>
