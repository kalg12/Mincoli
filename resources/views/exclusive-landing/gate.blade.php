<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>Acceso Exclusivo | Mincoli</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
</head>
<body class="min-h-screen bg-gradient-to-br from-pink-50 to-white flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <div class="bg-white rounded-2xl shadow-xl p-8 text-center">
            <div class="mb-6">
                <img src="{{ asset('mincoli_logo.png') }}" alt="Mincoli" class="h-16 mx-auto">
            </div>
            <h1 class="text-2xl font-bold text-gray-900 mb-2">Contenido Exclusivo</h1>
            <p class="text-gray-600 mb-6">Ingresa tu número telefónico para acceder</p>

            @if($errors->any())
                <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg text-red-700 text-sm">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('exclusive-landing.validate') }}" method="POST" class="space-y-4">
                @csrf
                <div class="text-left">
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Número con lada (ej. 55 1234 5678)</label>
                    <input type="tel" name="phone" id="phone" value="{{ old('phone') }}"
                           placeholder="55 1234 5678"
                           class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:ring-2 focus:ring-pink-500 focus:border-pink-500"
                           required autofocus>
                </div>
                <button type="submit" class="w-full bg-pink-600 hover:bg-pink-700 text-white font-semibold py-3 rounded-lg transition">
                    Ver contenido exclusivo
                </button>
            </form>
        </div>
    </div>
</body>
</html>
