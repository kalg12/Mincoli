<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>Campaña finalizada | Mincoli</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
</head>
<body class="min-h-screen bg-gradient-to-br from-pink-50 to-white flex items-center justify-center p-4">
    <div class="w-full max-w-lg">
        <div class="bg-white rounded-2xl shadow-xl p-8 text-center">
            <div class="mb-6">
                <img src="{{ asset('mincoli_logo.png') }}" alt="Mincoli" class="h-16 mx-auto">
            </div>
            <div class="text-6xl text-gray-300 mb-4">
                <i class="fas fa-calendar-times"></i>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 mb-4">Campaña finalizada</h1>
            @if(!empty($expired_message))
                <p class="text-gray-600 mb-6 whitespace-pre-line">{{ $expired_message }}</p>
            @else
                <p class="text-gray-600 mb-6">Esta campaña de contenido exclusivo ha finalizado. Te esperamos en la próxima.</p>
            @endif
            <a href="{{ route('home') }}" class="inline-block bg-pink-600 hover:bg-pink-700 text-white font-semibold px-6 py-3 rounded-lg transition">
                Ir a la tienda
            </a>
        </div>
    </div>
</body>
</html>
