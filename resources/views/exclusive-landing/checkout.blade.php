<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>Finalizar Compra | Exclusivo Mincoli</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
</head>
<body class="min-h-screen bg-gray-50">
    <header class="bg-white border-b border-gray-200 sticky top-0 z-30">
        <div class="container mx-auto px-4 py-3 flex items-center justify-between">
            <a href="{{ route('exclusive-landing.index') }}" class="flex items-center gap-2">
                <img src="{{ asset('mincoli_logo.png') }}" alt="Mincoli" class="h-10">
                <span class="font-bold text-gray-900 hidden sm:inline">Exclusivo</span>
            </a>
            <div class="flex items-center gap-4">
                <a href="{{ route('exclusive-landing.cart') }}" class="text-gray-700 hover:text-pink-600 flex items-center gap-1">
                    <i class="fas fa-shopping-cart"></i>
                    <span class="hidden sm:inline">Carrito</span>
                </a>
                <a href="{{ route('exclusive-landing.logout') }}" class="text-sm text-gray-500 hover:text-pink-600">Salir</a>
            </div>
        </div>
    </header>

    <div class="container mx-auto px-4 py-6">
        <nav class="flex items-center gap-2 text-sm text-gray-600 mb-6">
            <a href="{{ route('exclusive-landing.index') }}" class="hover:text-pink-600">Tienda</a>
            <i class="fas fa-chevron-right text-gray-400 text-xs"></i>
            <a href="{{ route('exclusive-landing.cart') }}" class="hover:text-pink-600">Carrito</a>
            <i class="fas fa-chevron-right text-gray-400 text-xs"></i>
            <span class="text-gray-900 font-medium">Finalizar Compra</span>
        </nav>

        <h1 class="text-3xl font-bold text-gray-900 mb-8">Finalizar Compra</h1>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2">
                <form action="{{ route('exclusive-landing.checkout.process') }}" method="POST" id="checkout-form">
                    @csrf
                    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Información de Contacto</h2>
                        <div class="grid gap-4 md:grid-cols-2">
                            <div class="col-span-2">
                                <label class="mb-1 block text-sm font-medium text-gray-700">Nombre Completo</label>
                                <input type="text" name="customer_name" value="{{ old('customer_name', auth()->user()->name ?? '') }}"
                                    class="w-full rounded-lg border-gray-300 focus:border-pink-500 focus:ring-pink-500 @error('customer_name') border-red-500 @enderror" required>
                                @error('customer_name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                            <div class="col-span-2">
                                <label class="mb-1 block text-sm font-medium text-gray-700">Correo Electrónico</label>
                                <input type="email" name="customer_email" value="{{ old('customer_email', auth()->user()->email ?? '') }}"
                                    class="w-full rounded-lg border-gray-300 focus:border-pink-500 focus:ring-pink-500 @error('customer_email') border-red-500 @enderror" required>
                                @error('customer_email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="mb-1 block text-sm font-medium text-gray-700">Teléfono</label>
                                <input type="tel" id="customer_phone" name="customer_phone" value="{{ old('customer_phone') }}"
                                    placeholder="Ingresa tu teléfono" inputmode="numeric" autocomplete="off"
                                    class="w-full rounded-lg border-gray-300 focus:border-pink-500 focus:ring-pink-500 @error('customer_phone') border-red-500 @enderror" required>
                                @error('customer_phone')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="mb-1 block text-sm font-medium text-gray-700">Confirmar Teléfono</label>
                                <input type="tel" name="customer_phone_confirmation" placeholder="Confirma tu número" inputmode="numeric" autocomplete="off"
                                    class="w-full rounded-lg border-gray-300 focus:border-pink-500 focus:ring-pink-500 @error('customer_phone_confirmation') border-red-500 @enderror" required>
                                @error('customer_phone_confirmation')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@else<p class="mt-1 text-xs text-gray-500">Confirma tu número para evitar errores.</p>@enderror
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Método de Pago</h2>
                        @if(session('error'))
                            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">{{ session('error') }}</div>
                        @endif
                        <div class="space-y-4">
                            @foreach($paymentMethods as $method)
                            <div class="border rounded-lg p-4 cursor-pointer hover:border-pink-500 transition">
                                <label class="flex items-start cursor-pointer">
                                    <input type="radio" name="payment_method_id" value="{{ $method->id }}" class="mt-1 mr-4 text-pink-600" required {{ old('payment_method_id') == $method->id ? 'checked' : '' }}>
                                    <div class="flex-1">
                                        <span class="block font-semibold text-gray-900">{{ $method->name }}</span>
                                        <span class="block text-sm text-gray-600 mt-1">{{ $method->description }}</span>
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

            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-md p-6 sticky top-24">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Resumen del Pedido</h2>
                    <div class="space-y-4 mb-6 max-h-96 overflow-y-auto">
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
                        <div class="flex justify-between text-gray-700"><span>Subtotal:</span><span>${{ number_format($subtotal, 2) }}</span></div>
                        @if($iva > 0)
                        <div class="flex justify-between text-gray-700"><span>IVA (16%):</span><span>${{ number_format($iva, 2) }}</span></div>
                        @endif
                        <div class="border-t pt-3 flex justify-between text-lg font-bold">
                            <span>Total:</span><span class="text-pink-600">${{ number_format($total, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
