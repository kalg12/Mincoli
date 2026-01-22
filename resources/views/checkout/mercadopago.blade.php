@extends('layouts.app')

@section('title', 'Pagando con Mercado Pago')

@section('content')
<div class="container mx-auto px-4 py-16 text-center">
    @if(isset($isSandbox) && $isSandbox)
        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-6 max-w-lg mx-auto" role="alert">
            <p class="font-bold">Modo de Pruebas (Sandbox)</p>
            <p>Estás utilizando el entorno de pruebas. Usa tarjetas de prueba.</p>
        </div>
    @endif

    <h1 class="text-2xl font-bold mb-4">Redirigiendo a Mercado Pago...</h1>
    <p class="text-gray-600 mb-8">Por favor espera un momento mientras te transferimos a la plataforma de pago segura.</p>
    
    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-pink-600 mx-auto mb-8"></div>

    <div id="wallet_container"></div>
    
    <script src="https://sdk.mercadopago.com/js/v2"></script>
    <script>
        const isSandbox = {{ $isSandbox ? 'true' : 'false' }};
        const initPoint = "{{ $initPoint }}";

        if (isSandbox) {
            // In Sandbox with Production Keys, JS SDK opens Production Modal which fails.
            // We must REDIRECT to the Sandbox URL explicitly.
            console.log('Sandbox Mode: Redirecting to ' + initPoint);
            window.location.href = initPoint;
        } else {
            // In Production, use the JS SDK Modal
            const mp = new MercadoPago("{{ $publicKey }}", {
                locale: 'es-MX'
            });

            mp.checkout({
                preference: {
                    id: '{{ $preference->id }}'
                },
                autoOpen: true, 
            });
        }
    </script>
    
    <div class="mt-8 flex flex-col gap-4 justify-center items-center">
        <a href="{{ $initPoint }}" class="text-pink-600 underline">Si no abre automáticamente, haz clic aquí para abrir la pasarela</a>
        
        <a href="{{ route('checkout.success', $order) }}" class="px-6 py-2 bg-gray-800 text-white rounded hover:bg-gray-700 transition">
            Ya completé/cerré el proceso de pago &rarr;
        </a>
    </div>
</div>
@endsection
