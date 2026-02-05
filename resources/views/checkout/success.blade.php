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

        @php
            $payment = $order->payments->first();
        @endphp

        @if($paymentMethod)
            <div class="border border-zinc-200 bg-white rounded-2xl p-6 mb-8 text-left shadow-sm">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h3 class="text-xl font-bold text-zinc-900">Instrucciones de Pago</h3>
                        <p class="text-sm text-zinc-600">Método seleccionado: <span class="font-semibold text-zinc-900">{{ $paymentMethod->name }}</span></p>
                    </div>
                    <span class="inline-flex items-center gap-2 rounded-full bg-zinc-100 px-3 py-1 text-xs font-semibold text-zinc-700">
                        <i class="fas fa-receipt"></i>
                        Pedido {{ $order->order_number }}
                    </span>
                </div>

                @if($paymentMethod->instructions)
                    <div class="mt-4 rounded-xl border border-zinc-200 bg-zinc-50 p-4 text-sm text-zinc-700 whitespace-pre-line leading-relaxed">
                        {{ $paymentMethod->instructions }}
                    </div>
                @endif

                @if($payment && $payment->card_number)
                    <div class="mt-6 rounded-2xl border-2 border-emerald-200 bg-gradient-to-br from-emerald-50 via-white to-emerald-100 p-5">
                        <div class="flex items-center justify-between">
                            <h4 class="font-bold text-emerald-800 flex items-center gap-2">
                                <i class="fas fa-credit-card"></i>
                                Datos para Deposito/Transferencia
                            </h4>
                            <span class="inline-flex items-center rounded-full bg-emerald-600 px-3 py-1 text-xs font-semibold text-white">Visible para pagar</span>
                        </div>

                        <div class="mt-4 grid gap-4 md:grid-cols-2">
                            <div class="md:col-span-2">
                                <label class="block text-xs font-semibold text-emerald-700 mb-1">Numero de tarjeta</label>
                                <div class="w-full rounded-xl border-2 border-emerald-300 bg-white px-4 py-3 text-xl tracking-wider font-mono text-center font-bold text-zinc-900">
                                    {{ chunk_split($payment->card_number, 4, ' ') }}
                                </div>
                            </div>

                            @if($payment->card_type)
                                <div>
                                    <label class="block text-xs font-semibold text-emerald-700 mb-1">Tipo</label>
                                    <div class="w-full rounded-xl border border-emerald-200 bg-white px-4 py-3 text-sm font-semibold text-center">
                                        {{ $payment->card_type === 'credit' ? 'Credito' : 'Debito' }}
                                    </div>
                                </div>
                            @endif

                            @if($paymentMethod->bank_name)
                                <div>
                                    <label class="block text-xs font-semibold text-emerald-700 mb-1">Banco</label>
                                    <div class="w-full rounded-xl border border-emerald-200 bg-white px-4 py-3 text-sm font-semibold text-center">
                                        {{ $paymentMethod->bank_name }}
                                    </div>
                                </div>
                            @endif

                            @if($payment->card_holder_name)
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-semibold text-emerald-700 mb-1">Titular</label>
                                    <div class="w-full rounded-xl border border-emerald-200 bg-white px-4 py-3 text-sm font-semibold text-center">
                                        {{ $payment->card_holder_name }}
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="mt-4 rounded-lg bg-amber-50 border border-amber-200 p-3 text-xs text-amber-900 flex items-start gap-2">
                            <i class="fas fa-info-circle text-amber-700 mt-0.5"></i>
                            <span>Estos datos son los que debes usar para tu deposito o transferencia. Guarda esta pantalla o descarga el comprobante.</span>
                        </div>
                    </div>
                @endif

                @if($paymentMethod->code == 'mercadopago')
                    <div class="mt-4 text-sm text-zinc-600">
                        <i class="fas fa-info-circle mr-1"></i>
                        El proceso de pago con Mercado Pago se ha iniciado/completado.
                    </div>
                @endif
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
