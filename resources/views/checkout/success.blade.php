@extends('layouts.app')

@section('title', 'Pedido Confirmado')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto text-center py-12 px-4">
        <div class="mb-8">
            <div class="mx-auto h-20 w-20 bg-green-100 rounded-full flex items-center justify-center mb-4">
                <i class="fas fa-check text-4xl text-green-600"></i>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">¡Gracias por tu compra!</h1>
            <p class="text-gray-600 mb-4">Tu pedido #{{ $order->order_number }} ha sido recibido.</p>
            
            <a href="{{ route('checkout.receipt', $order->id) }}" target="_blank" class="inline-flex items-center text-pink-600 hover:text-pink-700 font-semibold border border-pink-200 bg-pink-50 px-4 py-2 rounded-lg transition">
                <i class="fas fa-file-pdf mr-2"></i> Descargar Comprobante
            </a>
        </div>

        @if($paymentMethod && ($paymentMethod->code == 'transfer' || $paymentMethod->code == 'oxxo'))
            <div class="{{ $paymentMethod->code == 'oxxo' ? 'bg-amber-50 border-amber-200' : 'bg-blue-50 border-blue-200' }} border rounded-lg p-6 mb-8 text-left">
                <h3 class="font-bold {{ $paymentMethod->code == 'oxxo' ? 'text-amber-900' : 'text-blue-900' }} mb-4 text-lg">Instrucciones de Pago</h3>
                <p class="{{ $paymentMethod->code == 'oxxo' ? 'text-amber-800' : 'text-blue-800' }} mb-4">{{ $paymentMethod->description }}</p>
                
                <div class="bg-white p-4 rounded border {{ $paymentMethod->code == 'oxxo' ? 'border-amber-100' : 'border-blue-100' }} font-mono text-sm text-gray-700 whitespace-pre-line leading-relaxed">
                    {{ $paymentMethod->instructions }}
                </div>
                
                @if($paymentMethod->code == 'oxxo')
                <div class="mt-4 text-sm text-amber-700">
                    <p><i class="fas fa-clock mr-1"></i> Tu pago pasará a validación. Puede tardar hasta 24 horas.</p>
                </div>
                @else
                <div class="mt-4 text-sm text-blue-700">
                    <p><i class="fas fa-info-circle mr-1"></i> Por favor envía tu comprobante de pago por WhatsApp o Correo indicando tu número de pedido.</p>
                </div>
                @endif
            </div>
        @elseif($paymentMethod && $paymentMethod->code == 'mercadopago')
             <div class="bg-gray-50 border border-gray-200 rounded-lg p-6 mb-8">
                <p class="text-gray-700">El proceso de pago con Mercado Pago se ha iniciado/completado.</p>
             </div>
        @endif

        <div class="flex justify-center gap-4">
            <a href="{{ route('home') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium transition">
                Volver al Inicio
            </a>
            <!-- Link to Order Details if implemented -->
            <!-- <a href="#" class="px-6 py-2 bg-pink-600 rounded-lg text-white hover:bg-pink-700 font-medium transition">
                Ver Pedido
            </a> -->
        </div>
    </div>
</div>
@endsection
