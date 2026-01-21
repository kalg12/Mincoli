@extends('layouts.app')

@section('title', 'Pagando con Mercado Pago')

@section('content')
<div class="container mx-auto px-4 py-16 text-center">
    <h1 class="text-2xl font-bold mb-4">Redirigiendo a Mercado Pago...</h1>
    <p class="text-gray-600 mb-8">Por favor espera un momento mientras te transferimos a la plataforma de pago segura.</p>
    
    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-pink-600 mx-auto mb-8"></div>

    <div id="wallet_container"></div>
    
    <script src="https://sdk.mercadopago.com/js/v2"></script>
    <script>
        const mp = new MercadoPago("{{ $publicKey }}", {
            locale: 'es-MX'
        });

        mp.checkout({
            preference: {
                id: '{{ $preference->id }}'
            },
            autoOpen: true, // Opens the modal or redirect automatically
        });
    </script>
    
    <div class="mt-8">
        <a href="{{ $preference->init_point }}" class="text-pink-600 underline">Si no abre automáticamente, haz clic aquí</a>
    </div>
</div>
@endsection
