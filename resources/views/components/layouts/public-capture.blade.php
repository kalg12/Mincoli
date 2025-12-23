<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Captura de Inventario' }} - {{ config('app.name') }}</title>

    @include('partials.head')
</head>
<body class="min-h-screen bg-zinc-50 dark:bg-zinc-900">
    <div class="min-h-screen">
        <!-- Simple header -->
        <header class="bg-white dark:bg-zinc-800 border-b border-zinc-200 dark:border-zinc-700">
            <div class="container mx-auto px-4 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <x-app-logo />
                        <div>
                            <h1 class="text-lg font-semibold text-zinc-900 dark:text-white">Captura de Inventario</h1>
                            <p class="text-sm text-zinc-500">Solo personal autorizado</p>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main content -->
        <main class="container mx-auto">
            {{ $slot }}
        </main>
    </div>

    @stack('scripts')
</body>
</html>
