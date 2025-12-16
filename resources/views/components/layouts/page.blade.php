<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
        <title>{{ $title ?? 'Mincoli' }}</title>
    </head>
    <body class="min-h-screen bg-white antialiased">
        <!-- Header -->
        @include('partials.header')

        <!-- Main Content -->
        <main class="flex-1">
            {{ $slot }}
        </main>

        <!-- Cart Drawer -->
        @include('partials.cart-drawer')

        <!-- WhatsApp Button -->
        @include('partials.whatsapp-button')

        <!-- Footer -->
        @include('partials.footer')

        @vite('resources/js/app.js')
    </body>
</html>
