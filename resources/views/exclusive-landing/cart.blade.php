<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>Carrito | Exclusivo Mincoli</title>
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
                <a href="{{ route('exclusive-landing.cart') }}" class="text-pink-600 font-medium flex items-center gap-1">
                    <i class="fas fa-shopping-cart"></i>
                    <span class="hidden sm:inline">Carrito</span>
                </a>
                <a href="{{ route('exclusive-landing.logout') }}" class="text-sm text-gray-500 hover:text-pink-600">Salir</a>
            </div>
        </div>
    </header>

    <div class="container mx-auto px-4 py-6">
        @if(session('success'))
            <div class="mb-4 rounded-lg bg-green-50 border border-green-200 px-4 py-3 text-green-800 text-sm">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="mb-4 rounded-lg bg-red-50 border border-red-200 px-4 py-3 text-red-800 text-sm">{{ session('error') }}</div>
        @endif

        <nav class="flex items-center gap-2 text-sm text-gray-600 mb-6">
            <a href="{{ route('exclusive-landing.index') }}" class="hover:text-pink-600">Tienda</a>
            <i class="fas fa-chevron-right text-gray-400 text-xs"></i>
            <span class="text-gray-900 font-medium">Carrito</span>
        </nav>

        <h1 class="text-3xl font-bold text-gray-900 mb-8">Carrito de Compras</h1>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2">
                @if($items->count() > 0)
                <div class="space-y-4">
                    @foreach($items as $item)
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <div class="flex items-start gap-4">
                            <div class="w-24 h-24 bg-gray-100 rounded-lg flex-shrink-0 overflow-hidden">
                                @php $imageUrl = $item->image_url ?? ($item->variant?->images()->first()?->url ?? $item->product->images->first()?->url); @endphp
                                @if($imageUrl)
                                    <img src="{{ $imageUrl }}" alt="{{ $item->product->name }}" class="w-full h-full object-contain p-2">
                                @else
                                    <div class="w-full h-full flex items-center justify-center"><i class="fas fa-image text-gray-300 text-3xl"></i></div>
                                @endif
                            </div>
                            <div class="flex-1">
                                <a href="{{ route('exclusive-landing.product', $item->product->slug) }}" class="font-semibold text-gray-900 hover:text-pink-600 text-lg mb-1 block">{{ $item->product->name }}</a>
                                @if($item->variant)
                                    <p class="text-sm text-gray-700 mb-1">Variante: <span class="font-medium">{{ $item->variant->name }}</span></p>
                                    <p class="text-sm text-gray-600 mb-3">SKU: {{ $item->variant->sku ?? $item->product->sku }}</p>
                                @else
                                    <p class="text-sm text-gray-600 mb-3">SKU: {{ $item->product->sku }}</p>
                                @endif
                                <div class="mb-3">
                                    <span class="text-xl font-bold text-pink-600">${{ number_format($item->unit_price, 2) }}</span>
                                    @if($item->product->sale_price && $item->product->sale_price < $item->product->price)
                                        <span class="text-base text-gray-500 line-through ml-2">${{ number_format($item->product->price, 2) }}</span>
                                    @endif
                                </div>
                                <div class="flex items-center gap-4">
                                    <form action="{{ route('exclusive-landing.cart.update', $item->id) }}" method="POST" class="flex items-center border-2 border-gray-300 rounded-lg">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" name="quantity" value="{{ max(1, $item->quantity - 1) }}" class="px-3 py-2 text-gray-600 hover:bg-gray-100 transition"><i class="fas fa-minus text-sm"></i></button>
                                        <span class="px-4 py-2 border-x-2 border-gray-300 font-semibold min-w-[3rem] text-center">{{ $item->quantity }}</span>
                                        <button type="submit" name="quantity" value="{{ min($item->max_stock ?? $item->product->total_stock ?? 0, $item->quantity + 1) }}"
                                                class="px-3 py-2 text-gray-600 hover:bg-gray-100 transition {{ ($item->quantity >= ($item->max_stock ?? $item->product->total_stock ?? 0)) ? 'opacity-50 cursor-not-allowed' : '' }}"
                                                {{ ($item->quantity >= ($item->max_stock ?? $item->product->total_stock ?? 0)) ? 'disabled' : '' }}><i class="fas fa-plus text-sm"></i></button>
                                    </form>
                                    <span class="text-sm text-gray-600">{{ $item->max_stock ?? $item->product->total_stock ?? 0 }} disponibles</span>
                                    <form action="{{ route('exclusive-landing.cart.remove', $item->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-700 transition"><i class="fas fa-trash"></i> Eliminar</button>
                                    </form>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-2xl font-bold text-pink-600">${{ number_format($item->subtotal, 2) }}</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="bg-white rounded-lg shadow-md p-8 text-center">
                    <i class="fas fa-shopping-cart text-gray-300 text-6xl mb-4"></i>
                    <h2 class="text-2xl font-semibold text-gray-700 mb-2">Tu carrito está vacío</h2>
                    <p class="text-gray-500 mb-6">Agrega productos exclusivos para comenzar tu compra</p>
                    <a href="{{ route('exclusive-landing.index') }}" class="inline-flex items-center bg-pink-600 hover:bg-pink-700 text-white font-semibold px-6 py-3 rounded-lg transition">
                        <i class="fas fa-shopping-bag mr-2"></i> Ir a la Tienda Exclusiva
                    </a>
                </div>
                @endif
            </div>

            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-md p-6 sticky top-24">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Resumen del Pedido</h2>
                    <div class="space-y-3 mb-6">
                        <div class="flex justify-between text-gray-700"><span>Subtotal:</span><span>${{ number_format($cart->subtotal, 2) }}</span></div>
                        @if($cart->show_iva)
                        <div class="flex justify-between text-gray-700"><span>IVA (16%):</span><span>${{ number_format($cart->total_iva, 2) }}</span></div>
                        @endif
                        <div class="flex justify-between text-gray-700"><span>Envío:</span><span>A calcular</span></div>
                        <div class="border-t pt-3 flex justify-between text-lg font-bold">
                            <span>Total:</span><span class="text-pink-600">${{ number_format($cart->total, 2) }}</span>
                        </div>
                    </div>

                    @if($items->count() > 0)
                    <a href="{{ route('exclusive-landing.checkout') }}" class="block w-full bg-pink-600 hover:bg-pink-700 text-white font-bold py-3 rounded-lg transition mb-3 text-center">Proceder al Pago</a>
                    @else
                    <button class="w-full bg-pink-600 text-white font-bold py-3 rounded-lg opacity-50 cursor-not-allowed mb-3" disabled>Proceder al Pago</button>
                    @endif

                    <a href="{{ route('exclusive-landing.index') }}" class="block text-center text-pink-600 hover:text-pink-700 font-medium">Continuar Comprando</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
