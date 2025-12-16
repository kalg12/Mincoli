<x-layouts.page :title="__('Preguntas Frecuentes')">
    <div class="min-h-screen bg-gradient-to-b from-white to-gray-50">
        <!-- Header Section -->
        <div class="bg-pink-600 text-white py-12">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <h1 class="text-4xl md:text-5xl font-bold mb-4">Preguntas Frecuentes</h1>
                <p class="text-lg text-pink-100">Encuentra respuestas a tus dudas</p>
            </div>
        </div>

        <!-- Main Content -->
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <!-- FAQ Items -->
            <div class="space-y-4">
                <details class="group bg-white rounded-lg border border-gray-200 p-6 cursor-pointer hover:border-pink-400 transition">
                    <summary class="flex items-center justify-between font-semibold text-gray-900 text-lg">
                        ¿Cuáles son los tiempos de envío?
                        <span class="transform group-open:rotate-180 transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                            </svg>
                        </span>
                    </summary>
                    <p class="text-gray-600 mt-4 leading-relaxed">
                        Los tiempos de envío varían según tu ubicación:
                        <br><br>
                        <strong>Ciudad de México:</strong> 1 a 4 días hábiles ($85 MXN)<br>
                        <strong>Estado de México:</strong> 1 a 4 días hábiles ($150 MXN)<br>
                        <strong>República Mexicana:</strong> 1 a 6 días hábiles ($185 MXN+)
                    </p>
                </details>

                <details class="group bg-white rounded-lg border border-gray-200 p-6 cursor-pointer hover:border-pink-400 transition">
                    <summary class="flex items-center justify-between font-semibold text-gray-900 text-lg">
                        ¿Qué métodos de pago aceptan?
                        <span class="transform group-open:rotate-180 transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                            </svg>
                        </span>
                    </summary>
                    <p class="text-gray-600 mt-4 leading-relaxed">
                        Aceptamos los siguientes métodos de pago seguros:
                        <br><br>
                        • Tarjetas bancarias (débito y crédito)<br>
                        • Transferencias bancarias
                    </p>
                </details>

                <details class="group bg-white rounded-lg border border-gray-200 p-6 cursor-pointer hover:border-pink-400 transition">
                    <summary class="flex items-center justify-between font-semibold text-gray-900 text-lg">
                        ¿Puedo cambiar un producto que ya usé?
                        <span class="transform group-open:rotate-180 transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                            </svg>
                        </span>
                    </summary>
                    <p class="text-gray-600 mt-4 leading-relaxed">
                        No, no se aceptan cambios en artículos que hayan sido usados. Los productos deben estar en su estado original para ser elegibles para un cambio por talla o color (solo aplica a artículos en precio regular, no en promoción).
                    </p>
                </details>

                <details class="group bg-white rounded-lg border border-gray-200 p-6 cursor-pointer hover:border-pink-400 transition">
                    <summary class="flex items-center justify-between font-semibold text-gray-900 text-lg">
                        ¿Qué hago si recibo un producto dañado?
                        <span class="transform group-open:rotate-180 transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                            </svg>
                        </span>
                    </summary>
                    <p class="text-gray-600 mt-4 leading-relaxed">
                        Es muy importante que grabes un video continuo al abrir el paquete, mostrando claramente los sellos de seguridad. Envía este video por WhatsApp dentro de las 24 horas siguientes a la recepción del pedido. Sin este video, no podremos procesar tu reclamación.
                    </p>
                </details>

                <details class="group bg-white rounded-lg border border-gray-200 p-6 cursor-pointer hover:border-pink-400 transition">
                    <summary class="flex items-center justify-between font-semibold text-gray-900 text-lg">
                        ¿Realizan devoluciones de dinero?
                        <span class="transform group-open:rotate-180 transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                            </svg>
                        </span>
                    </summary>
                    <p class="text-gray-600 mt-4 leading-relaxed">
                        No realizamos devoluciones de dinero. Sin embargo, ofrecemos cambios por talla o color para artículos en precio regular que no hayan sido usados. Para artículos en promoción no hay cambios disponibles.
                    </p>
                </details>

                <details class="group bg-white rounded-lg border border-gray-200 p-6 cursor-pointer hover:border-pink-400 transition">
                    <summary class="flex items-center justify-between font-semibold text-gray-900 text-lg">
                        ¿Cuál es el costo del cambio de talla?
                        <span class="transform group-open:rotate-180 transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                            </svg>
                        </span>
                    </summary>
                    <p class="text-gray-600 mt-4 leading-relaxed">
                        El costo del cambio es que el cliente debe cubrir los gastos de envío para que el nuevo producto llegue a su domicilio. No hay costo adicional más allá del envío.
                    </p>
                </details>

                <details class="group bg-white rounded-lg border border-gray-200 p-6 cursor-pointer hover:border-pink-400 transition">
                    <summary class="flex items-center justify-between font-semibold text-gray-900 text-lg">
                        ¿Puedo rastrear mi pedido?
                        <span class="transform group-open:rotate-180 transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                            </svg>
                        </span>
                    </summary>
                    <p class="text-gray-600 mt-4 leading-relaxed">
                        Sí, una vez que tu pedido sea enviado, recibirás un correo electrónico con el número de seguimiento de tu paquete. Puedes usar este número para rastrear tu envío con la paquetería correspondiente.
                    </p>
                </details>

                <details class="group bg-white rounded-lg border border-gray-200 p-6 cursor-pointer hover:border-pink-400 transition">
                    <summary class="flex items-center justify-between font-semibold text-gray-900 text-lg">
                        ¿Tienen transmisiones en vivo?
                        <span class="transform group-open:rotate-180 transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                            </svg>
                        </span>
                    </summary>
                    <p class="text-gray-600 mt-4 leading-relaxed">
                        ¡Sí! Realizamos transmisiones en vivo regularmente:
                        <br><br>
                        <strong>Domingo:</strong> Dulces - 6:00 p.m.<br>
                        <strong>Miércoles y Jueves:</strong> Accesorios y Joyería<br>
                        <strong>Viernes y Sábado:</strong> Ropa<br><br>
                        <a href="{{ route('about') }}" class="text-pink-600 hover:text-pink-700 font-semibold">Ver más detalles →</a>
                    </p>
                </details>

                <details class="group bg-white rounded-lg border border-gray-200 p-6 cursor-pointer hover:border-pink-400 transition">
                    <summary class="flex items-center justify-between font-semibold text-gray-900 text-lg">
                        ¿Cuáles son sus horarios de atención?
                        <span class="transform group-open:rotate-180 transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                            </svg>
                        </span>
                    </summary>
                    <p class="text-gray-600 mt-4 leading-relaxed">
                        Atendemos tus solicitudes de lunes a sábado de 9:00 a.m. a 6:00 p.m. Estamos aquí para ayudarte con cualquier pregunta sobre tu compra.
                    </p>
                </details>
            </div>

            <!-- Contact CTA -->
            <div class="mt-12 bg-gradient-to-r from-pink-50 to-pink-100 p-8 rounded-lg text-center">
                <h3 class="text-2xl font-bold text-gray-900 mb-4">¿No encontraste lo que buscas?</h3>
                <p class="text-gray-700 mb-6">Contáctanos directamente por WhatsApp</p>
                <a href="https://wa.me/5256117011660" target="_blank" class="inline-block bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-8 rounded-lg transition">
                    💬 Enviar Mensaje
                </a>
            </div>
        </div>
    </div>
</x-layouts.page>
