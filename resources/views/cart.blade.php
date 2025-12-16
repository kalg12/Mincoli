@extends('layouts.app')

@section('title', 'Carrito de Compras')

@section('content')
<!-- Breadcrumb -->
<div class="bg-gray-100 py-4">
    <div class="container mx-auto px-4">
        <nav class="flex items-center space-x-2 text-sm">
            <a href="{{ route('home') }}" class="text-gray-600 hover:text-pink-600">Inicio</a>
            <i class="fas fa-chevron-right text-gray-400 text-xs"></i>
            <span class="text-gray-900 font-medium">Carrito de Compras</span>
        </nav>
    </div>
</div>

<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Carrito de Compras</h1>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Cart Items -->
        <div class="lg:col-span-2">
            <!-- Empty Cart -->
            <div class="bg-white rounded-lg shadow-md p-8 text-center">
                <i class="fas fa-shopping-cart text-gray-300 text-6xl mb-4"></i>
                <h2 class="text-2xl font-semibold text-gray-700 mb-2">Tu carrito está vacío</h2>
                <p class="text-gray-500 mb-6">Agrega productos para comenzar tu compra</p>
                <a href="{{ route('shop') }}"
                   class="inline-flex items-center bg-pink-600 hover:bg-pink-700 text-white font-semibold px-6 py-3 rounded-lg transition">
                    <i class="fas fa-shopping-bag mr-2"></i>
                    Ir a la Tienda
                </a>
            </div>

            <!-- Cart Items Example (Hidden by default) -->
            <div class="hidden space-y-4">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center space-x-4">
                        <img src="#" alt="Product" class="w-24 h-24 object-contain bg-gray-100 rounded">
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-900 mb-1">Nombre del Producto</h3>
                            <p class="text-sm text-gray-600 mb-2">SKU: XXXXX</p>
                            <div class="flex items-center space-x-4">
                                <div class="flex items-center border-2 border-gray-300 rounded">
                                    <button class="px-3 py-1 text-gray-600 hover:bg-gray-100">
                                        <i class="fas fa-minus text-sm"></i>
                                    </button>
                                    <span class="px-4 py-1 border-x-2 border-gray-300">1</span>
                                    <button class="px-3 py-1 text-gray-600 hover:bg-gray-100">
                                        <i class="fas fa-plus text-sm"></i>
                                    </button>
                                </div>
                                <button class="text-red-600 hover:text-red-700">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-xl font-bold text-pink-600">$99.00</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-md p-6 sticky top-24">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Resumen del Pedido</h2>

                <div class="space-y-3 mb-6">
                    <div class="flex justify-between text-gray-700">
                        <span>Subtotal:</span>
                        <span>$0.00</span>
                    </div>
                    <div class="flex justify-between text-gray-700">
                        <span>IVA (16%):</span>
                        <span>$0.00</span>
                    </div>
                    <div class="flex justify-between text-gray-700">
                        <span>Envío:</span>
                        <span>A calcular</span>
                    </div>
                    <div class="border-t pt-3 flex justify-between text-lg font-bold">
                        <span>Total:</span>
                        <span class="text-pink-600">$0.00</span>
                    </div>
                </div>

                <button class="w-full bg-pink-600 hover:bg-pink-700 text-white font-bold py-3 rounded-lg transition mb-3 opacity-50 cursor-not-allowed" disabled>
                    Proceder al Pago
                </button>

                <a href="{{ route('shop') }}"
                   class="block text-center text-pink-600 hover:text-pink-700 font-medium">
                    Continuar Comprando
                </a>

                <!-- Payment Methods -->
                <div class="mt-6 pt-6 border-t">
                    <p class="text-sm text-gray-600 mb-3">Métodos de pago aceptados:</p>
                    <div class="flex items-center space-x-2">
                        <i class="fab fa-cc-visa text-3xl text-blue-600"></i>
                        <i class="fab fa-cc-mastercard text-3xl text-red-600"></i>
                        <i class="fas fa-credit-card text-3xl text-gray-600"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
