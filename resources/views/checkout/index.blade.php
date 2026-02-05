@extends('layouts.app')

@section('title', 'Finalizar Compra')

@section('content')
<div class="bg-gray-100 py-4">
    <div class="container mx-auto px-4">
        <nav class="flex items-center space-x-2 text-sm">
            <a href="{{ route('home') }}" class="text-gray-600 hover:text-pink-600">Inicio</a>
            <i class="fas fa-chevron-right text-gray-400 text-xs"></i>
            <a href="{{ route('cart') }}" class="text-gray-600 hover:text-pink-600">Carrito</a>
            <i class="fas fa-chevron-right text-gray-400 text-xs"></i>
            <span class="text-gray-900 font-medium">Finalizar Compra</span>
        </nav>
    </div>
</div>

<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Finalizar Compra</h1>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Checkout Form -->
        <div class="lg:col-span-2">
            <form action="{{ route('checkout.process') }}" method="POST" id="checkout-form">
                @csrf

                <!-- Customer Information -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Información de Contacto</h2>
                    <div class="grid gap-4 md:grid-cols-2">
                        <div class="col-span-2">
                            <label class="mb-1 block text-sm font-medium text-gray-700">Nombre Completo</label>
                            <input
                                type="text"
                                name="customer_name"
                                value="{{ old('customer_name', auth()->user()->name ?? '') }}"
                                @class([
                                    'w-full rounded-lg focus:border-pink-500 focus:ring-pink-500',
                                    'border-gray-300' => ! $errors->has('customer_name'),
                                    'border-red-500' => $errors->has('customer_name'),
                                ])
                                required
                            >
                            @error('customer_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-span-2">
                            <label class="mb-1 block text-sm font-medium text-gray-700">Correo Electrónico</label>
                            <input
                                type="email"
                                name="customer_email"
                                value="{{ old('customer_email', auth()->user()->email ?? '') }}"
                                @class([
                                    'w-full rounded-lg focus:border-pink-500 focus:ring-pink-500',
                                    'border-gray-300' => ! $errors->has('customer_email'),
                                    'border-red-500' => $errors->has('customer_email'),
                                ])
                                required
                            >
                            @error('customer_email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700">Teléfono</label>
                            <input
                                type="tel"
                                id="customer_phone"
                                name="customer_phone"
                                value="{{ old('customer_phone') }}"
                                placeholder="Ingresa tu teléfono"
                                inputmode="numeric"
                                autocomplete="off"
                                @class([
                                    'w-full rounded-lg focus:border-pink-500 focus:ring-pink-500',
                                    'border-gray-300' => ! $errors->has('customer_phone'),
                                    'border-red-500' => $errors->has('customer_phone'),
                                ])
                                required
                            >
                            @error('customer_phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700">Confirmar Teléfono</label>
                            <input
                                type="tel"
                                name="customer_phone_confirmation"
                                placeholder="Ingresa tu número nuevamente"
                                inputmode="numeric"
                                autocomplete="off"
                                onpaste="return false;"
                                @class([
                                    'w-full rounded-lg focus:border-pink-500 focus:ring-pink-500',
                                    'border-gray-300' => ! $errors->has('customer_phone_confirmation'),
                                    'border-red-500' => $errors->has('customer_phone_confirmation'),
                                ])
                                required
                            >
                            @error('customer_phone_confirmation')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @else
                                <p class="mt-1 text-xs text-gray-500">Confirma tu número para evitar errores.</p>
                            @enderror
                        </div>
                    </div>
                </div>


                <!-- Payment Methods -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Método de Pago</h2>

                    @if(session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <strong class="font-bold">Error!</strong>
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif

                    <div class="space-y-4">
                        @foreach($paymentMethods as $method)
                        <div class="border rounded-lg p-4 cursor-pointer hover:border-pink-500 transition relative payment-method-card" data-method-id="{{ $method->id }}" data-method-code="{{ $method->code }}">
                            <label class="flex items-start cursor-pointer w-full h-full">
                                <input type="radio" name="payment_method_id" value="{{ $method->id }}" class="mt-1 mr-4 text-pink-600 focus:ring-pink-500" required {{ old('payment_method_id') == $method->id ? 'checked' : '' }}>
                                <div class="flex-1">
                                    <span class="block font-semibold text-gray-900">{{ $method->name }}</span>
                                    <span class="block text-sm text-gray-600 mt-1">{{ $method->description }}</span>
                                    @if($method->code == 'mercadopago')
                                        <div class="flex items-center gap-2 mt-2">
                                            <i class="fab fa-cc-visa text-2xl text-blue-600"></i>
                                            <i class="fab fa-cc-mastercard text-2xl text-red-600"></i>
                                            <span class="text-xs bg-yellow-400 text-yellow-900 px-2 py-0.5 rounded font-bold">OXXO</span>
                                        </div>
                                    @endif
                                </div>
                            </label>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="text-right">
                    <button type="submit" class="bg-pink-600 hover:bg-pink-700 text-white font-bold py-3 px-8 rounded-lg transition text-lg shadow-lg">
                        Pagar ${{ number_format($total, 2) }}
                    </button>
                </div>
            </form>
        </div>

        <!-- Order Summary -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-md p-6 sticky top-24">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Resumen del Pedido</h2>

                <div class="space-y-4 mb-6 max-h-96 overflow-y-auto pr-2 custom-scrollbar">
                    @foreach($items as $item)
                    <div class="flex gap-3">
                        <img src="{{ $item->image_url }}" alt="{{ $item->name }}" class="w-16 h-16 object-cover rounded bg-gray-100">
                        <div class="flex-1">
                            <h4 class="text-sm font-medium text-gray-900 line-clamp-2">{{ $item->name }}</h4>
                            <p class="text-xs text-gray-500">Cant: {{ $item->quantity }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-semibold text-gray-900">${{ number_format($item->subtotal, 2) }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="border-t pt-4 space-y-3">
                    <div class="flex justify-between text-gray-700">
                        <span>Subtotal:</span>
                        <span>${{ number_format($subtotal, 2) }}</span>
                    </div>
                    @if($iva > 0)
                    <div class="flex justify-between text-gray-700">
                        <span>IVA (16%):</span>
                        <span>${{ number_format($iva, 2) }}</span>
                    </div>
                    @endif
                    <div class="border-t pt-3 flex justify-between text-lg font-bold">
                        <span>Total:</span>
                        <span class="text-pink-600">${{ number_format($total, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
