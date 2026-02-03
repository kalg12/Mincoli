<footer class="bg-gray-900 text-white mt-12">
    <div class="container mx-auto px-4 py-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 text-center md:text-left">
            <!-- About -->
            <div>
                <h3 class="text-xl font-bold text-pink-500 mb-4">Mincoli</h3>
                <p class="text-gray-400 text-sm max-w-xs mx-auto md:mx-0">
                    Tu tienda en línea favorita para productos mexicanos. Envíos a todo México.
                </p>
            </div>

            <!-- Quick Links -->
            <div>
                <h4 class="font-semibold mb-4">Enlaces Rápidos</h4>
                <ul class="space-y-2 text-sm text-gray-400">
                    <li><a href="{{ route('home') }}" class="hover:text-pink-500 transition">Inicio</a></li>
                    <li><a href="{{ route('shop') }}" class="hover:text-pink-500 transition">Tienda</a></li>
                    <li><a href="{{ route('about') }}" class="hover:text-pink-500 transition">Sobre Nosotros</a></li>
                    <li><a href="{{ route('contact') }}" class="hover:text-pink-500 transition">Contacto</a></li>
                </ul>
            </div>

            <!-- Customer Service -->
            <div>
                <h4 class="font-semibold mb-4">Atención al Cliente</h4>
                <ul class="space-y-2 text-sm text-gray-400">
                    <li><a href="{{ route('shipping') }}" class="hover:text-pink-500 transition">Políticas de Envío</a></li>
                    <li><a href="{{ route('returns') }}" class="hover:text-pink-500 transition">Devoluciones</a></li>
                    <li><a href="{{ route('faq') }}" class="hover:text-pink-500 transition">Preguntas Frecuentes</a></li>
                    <li><a href="{{ route('terms') }}" class="hover:text-pink-500 transition">Términos y Condiciones</a></li>
                </ul>
            </div>

            <!-- Contact -->
            <div>
                <h4 class="font-semibold mb-4">Legal</h4>
                <ul class="space-y-2 text-sm text-gray-400">
                    <li><a href="{{ route('privacy') }}" class="hover:text-pink-500 transition">Política de Privacidad</a></li>
                    <li><a href="{{ route('legal') }}" class="hover:text-pink-500 transition">Aviso Legal</a></li>
                </ul>

                <h4 class="font-semibold mb-4 mt-6">Contacto Directo</h4>
                <ul class="space-y-2 text-sm text-gray-400">
                    <li class="flex items-center justify-center md:justify-start">
                        <i class="fas fa-phone-alt mr-2 text-pink-500"></i>
                        <a href="tel:+5256117011660" class="hover:text-pink-500 transition">+52 56 1170 1166</a>
                    </li>
                    <li class="flex items-center justify-center md:justify-start">
                        <i class="fas fa-envelope mr-2 text-pink-500"></i>
                        <a href="mailto:mincoli.ventas.online@outlook.com" class="hover:text-pink-500 transition break-all">mincoli.ventas.online@outlook.com</a>
                    </li>
                    <li class="flex items-center justify-center md:justify-start">
                        <i class="fas fa-map-marker-alt mr-2 text-pink-500"></i>
                        <span>Ciudad de México</span>
                    </li>
                </ul>

                <!-- Social Links -->
                <div class="flex items-center justify-center md:justify-start space-x-4 mt-4">
                    <a href="https://www.facebook.com/MincoliMx" target="_blank" rel="noopener" title="Facebook" class="text-gray-400 hover:text-pink-500 transition">
                        <i class="fab fa-facebook-f text-lg"></i>
                    </a>
                    <a href="https://www.instagram.com/mincolimx" target="_blank" rel="noopener" title="Instagram" class="text-gray-400 hover:text-pink-500 transition">
                        <i class="fab fa-instagram text-lg"></i>
                    </a>
                    <a href="https://www.tiktok.com/@thereal_mincoli_by_jaz" target="_blank" rel="noopener" title="TikTok" class="text-gray-400 hover:text-pink-500 transition">
                        <i class="fab fa-tiktok text-lg"></i>
                    </a>
                    <a href="https://wa.me/5256117011660" target="_blank" rel="noopener" title="WhatsApp" class="text-gray-400 hover:text-pink-500 transition">
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
