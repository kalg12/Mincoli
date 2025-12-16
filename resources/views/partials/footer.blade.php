<footer class="bg-gray-900 text-white mt-12">
    <div class="container mx-auto px-4 py-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <!-- About -->
            <div>
                <h3 class="text-xl font-bold text-pink-500 mb-4">Mincoli</h3>
                <p class="text-gray-400 text-sm">
                    Tu tienda en línea favorita para productos mexicanos. Envíos a todo México.
                </p>
            </div>

            <!-- Quick Links -->
            <div>
                <h4 class="font-semibold mb-4">Enlaces Rápidos</h4>
                <ul class="space-y-2 text-sm text-gray-400">
                    <li><a href="{{ route('home') }}" class="hover:text-pink-500 transition">Inicio</a></li>
                    <li><a href="{{ route('shop') }}" class="hover:text-pink-500 transition">Tienda</a></li>
                    <li><a href="#" class="hover:text-pink-500 transition">Sobre Nosotros</a></li>
                    <li><a href="#" class="hover:text-pink-500 transition">Contacto</a></li>
                </ul>
            </div>

            <!-- Customer Service -->
            <div>
                <h4 class="font-semibold mb-4">Atención al Cliente</h4>
                <ul class="space-y-2 text-sm text-gray-400">
                    <li><a href="#" class="hover:text-pink-500 transition">Políticas de Envío</a></li>
                    <li><a href="#" class="hover:text-pink-500 transition">Devoluciones</a></li>
                    <li><a href="#" class="hover:text-pink-500 transition">Preguntas Frecuentes</a></li>
                    <li><a href="#" class="hover:text-pink-500 transition">Términos y Condiciones</a></li>
                </ul>
            </div>

            <!-- Contact -->
            <div>
                <h4 class="font-semibold mb-4">Contacto</h4>
                <ul class="space-y-2 text-sm text-gray-400">
                    <li class="flex items-start">
                        <i class="fas fa-phone-alt mt-1 mr-2 text-pink-500"></i>
                        <span>+52 5601110166</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-envelope mt-1 mr-2 text-pink-500"></i>
                        <span class="break-all">mincoli.ventas.online@outlook.com</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-map-marker-alt mt-1 mr-2 text-pink-500"></i>
                        <span>México</span>
                    </li>
                </ul>

                <!-- Social Links -->
                <div class="flex space-x-4 mt-4">
                    <a href="#" class="text-gray-400 hover:text-pink-500 transition">
                        <i class="fab fa-facebook-f text-lg"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-pink-500 transition">
                        <i class="fab fa-instagram text-lg"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-pink-500 transition">
                        <i class="fab fa-tiktok text-lg"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-pink-500 transition">
                        <i class="fab fa-whatsapp text-lg"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Bottom Bar -->
        <div class="border-t border-gray-800 mt-8 pt-6 text-center text-sm text-gray-400">
            <p>Todos los derechos de autor ©{{ date('Y') }} Mincoli</p>
        </div>
    </div>
</footer>
