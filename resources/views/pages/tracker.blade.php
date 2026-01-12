@extends('layouts.app')

@section('title', 'Rastreo de Pedido')

@section('content')
<div class="bg-gray-100 py-4">
    <div class="container mx-auto px-4">
        <nav class="flex items-center space-x-2 text-sm">
            <a href="{{ route('home') }}" class="text-gray-600 hover:text-pink-600">Inicio</a>
            <i class="fas fa-chevron-right text-gray-400 text-xs"></i>
            <span class="text-gray-900 font-medium">Rastreo de Pedido</span>
        </nav>
    </div>
</div>

<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-900 mb-8 text-center">Rrastreo de Pedido</h1>

        <!-- Track Form -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <form action="{{ route('tracker.track') }}" method="POST" class="flex gap-4">
                @csrf
                <div class="flex-1">
                    <label for="order_number" class="sr-only">Número de Pedido</label>
                    <input type="text" name="order_number" id="order_number" 
                           class="w-full rounded-lg border-gray-300 focus:border-pink-500 focus:ring-pink-500" 
                           placeholder="Ingresa tu número de pedido (ej. ORD-123...)"
                           value="{{ old('order_number', $order->order_number ?? '') }}"
                           required>
                </div>
                <button type="submit" class="bg-pink-600 hover:bg-pink-700 text-white font-bold py-2 px-6 rounded-lg transition">
                    Rastrear
                </button>
            </form>
            @error('order_number')
                <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
            @enderror
        </div>

        @if(isset($order))
        <!-- Order Result -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <div>
                    <h2 class="text-lg font-bold text-gray-900">Pedido #{{ $order->order_number }}</h2>
                    <p class="text-sm text-gray-600">{{ $order->placed_at->format('d/m/Y H:i') }}</p>
                </div>
                <div class="px-3 py-1 rounded-full text-sm font-semibold 
                    {{ $order->status === 'delivered' ? 'bg-green-100 text-green-800' : 
                       ($order->status === 'cancelled' ? 'bg-red-100 text-red-800' : 
                       'bg-yellow-100 text-yellow-800') }}">
                    {{ ucfirst(__($order->status)) }}
                </div>
            </div>

            <div class="p-6">
                <!-- Status Steps -->
                <div class="relative mb-8">
                    <div class="absolute left-0 top-1/2 w-full h-1 bg-gray-200 -translate-y-1/2 rounded"></div>
                    
                    @php
                        $statuses = ['pending', 'paid', 'shipped', 'delivered'];
                        $currentStep = array_search($order->status, $statuses);
                        if ($currentStep === false && $order->status === 'cancelled') $currentStep = -1;
                    @endphp

                    <div class="relative flex justify-between">
                        @foreach(['Pendiente', 'Pagado', 'Enviado', 'Entregado'] as $index => $label)
                        <div class="text-center bg-white px-2">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center mx-auto mb-2 border-2 
                                {{ $index <= $currentStep ? 'bg-pink-600 border-pink-600 text-white' : 'bg-white border-gray-300 text-gray-300' }}">
                                <i class="fas {{ ['fa-file-alt', 'fa-credit-card', 'fa-truck', 'fa-box-open'][$index] }} text-xs"></i>
                            </div>
                            <span class="text-xs font-medium {{ $index <= $currentStep ? 'text-pink-600' : 'text-gray-400' }}">{{ $label }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Payment Info -->
                <div class="border-t border-gray-100 pt-6">
                    <h3 class="font-bold text-gray-900 mb-3">Información de Pago</h3>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <p class="text-gray-500">Método:</p>
                            <p class="font-medium">
                                {{ $order->payments->first()?->method->name ?? 'No especificado' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-gray-500">Estado de Pago:</p>
                            <p class="font-medium">
                                {{ $order->payments->first()?->status ?? 'Pendiente' }}
                            </p>
                        </div>
                    </div>
                    @if($order->status === 'pending' && $order->payments->first()?->method->code === 'oxxo')
                    <div class="mt-4 bg-yellow-50 p-4 rounded-lg text-sm text-yellow-800">
                        <p class="font-bold"><i class="fas fa-exclamation-triangle mr-1"></i> Validación Pendiente</p>
                        <p class="mt-1">Tu pago en OXXO está en proceso de validación. Una vez confirmado, actualizaremos el estado de tu pedido.</p>
                    </div>
                    @endif
                     @if($order->status === 'pending' && $order->payments->first()?->method->code === 'transfer')
                    <div class="mt-4 bg-blue-50 p-4 rounded-lg text-sm text-blue-800">
                        <p class="font-bold"><i class="fas fa-info-circle mr-1"></i> Transferencia</p>
                        <p class="mt-1">Recuerda enviar tu comprobante para validar el pago.</p>
                    </div>
                    @endif
                </div>

                <!-- Items -->
                <div class="border-t border-gray-100 pt-6 mt-6">
                    <h3 class="font-bold text-gray-900 mb-3">Productos</h3>
                    <ul class="space-y-3">
                        @foreach($order->items as $item)
                        <li class="flex justify-between text-sm">
                            <span class="text-gray-700">
                                {{ $item->quantity }}x {{ $item->product->name }}
                                @if($item->variant) ({{ $item->variant->name }}) @endif
                            </span>
                            <span class="font-medium">${{ number_format($item->subtotal, 2) }}</span>
                        </li>
                        @endforeach
                    </ul>
                    <div class="border-t border-gray-100 mt-3 pt-3 flex justify-between font-bold text-lg">
                        <span>Total</span>
                        <span class="text-pink-600">${{ number_format($order->total, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
