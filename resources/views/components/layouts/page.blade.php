<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
        <!-- Font Awesome for icons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
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
