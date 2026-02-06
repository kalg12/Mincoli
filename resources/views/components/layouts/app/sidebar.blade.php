<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <flux:sidebar sticky stashable class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

            <a href="{{ route('dashboard') }}" class="me-5 flex items-center space-x-2 rtl:space-x-reverse" wire:navigate>
                <x-app-logo />
            </a>

            <flux:navlist variant="outline">
                <flux:navlist.group :heading="__('Contenido')" class="grid">
                    <flux:navlist.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>Dashboard</flux:navlist.item>
                    <flux:navlist.item icon="shopping-bag" :href="route('dashboard.products.index')" :current="request()->routeIs('dashboard.products.*')" wire:navigate>Productos</flux:navlist.item>
                    <flux:navlist.item icon="folder" :href="route('dashboard.categories.index')" :current="request()->routeIs('dashboard.categories.*')" wire:navigate>Categorías</flux:navlist.item>
                    <flux:navlist.item icon="photo" :href="route('dashboard.banners.index')" :current="request()->routeIs('dashboard.banners.*')" wire:navigate>Banners</flux:navlist.item>
                    <flux:navlist.item icon="signal" :href="route('dashboard.live-sessions.index')" :current="request()->routeIs('dashboard.live-sessions.*')" wire:navigate>Transmisiones en Vivo</flux:navlist.item>
                </flux:navlist.group>

                    <flux:navlist.group :heading="__('Blog')" class="grid">
                    <flux:navlist.item icon="newspaper" :href="route('dashboard.blog.posts.index')" :current="request()->routeIs('dashboard.blog.posts.*')" wire:navigate>Artículos</flux:navlist.item>
                    <flux:navlist.item icon="tag" :href="route('dashboard.blog.categories.index')" :current="request()->routeIs('dashboard.blog.categories.*')" wire:navigate>Categorías</flux:navlist.item>
                </flux:navlist.group>


                <flux:navlist.group :heading="__('Inventario')" class="grid">
                    <flux:navlist.item icon="cube" :href="route('dashboard.inventory.index')" :current="request()->routeIs('dashboard.inventory.index')" wire:navigate>Dashboard</flux:navlist.item>
                    <flux:navlist.item icon="arrow-path" :href="route('dashboard.inventory.movements')" :current="request()->routeIs('dashboard.inventory.movements*')" wire:navigate>Movimientos</flux:navlist.item>
                    <flux:navlist.item icon="clipboard-document-list" :href="route('dashboard.inventory.counts.index')" :current="request()->routeIs('dashboard.inventory.counts.*')" wire:navigate>Conteos Físicos</flux:navlist.item>
                </flux:navlist.group>

                <flux:navlist.group :heading="__('Ventas')" class="grid">
                    <flux:navlist.item icon="shopping-cart" :href="route('dashboard.orders.index')" :current="request()->routeIs('dashboard.orders.*')" wire:navigate>Pedidos</flux:navlist.item>
                    <flux:navlist.item icon="user-group" :href="route('dashboard.customers.index')" :current="request()->routeIs('dashboard.customers.*')" wire:navigate>Clientes</flux:navlist.item>
                    <flux:navlist.item icon="credit-card" :href="route('dashboard.payment-methods.index')" :current="request()->routeIs('dashboard.payment-methods.*')" wire:navigate>Métodos de pago</flux:navlist.item>
                    <flux:navlist.item icon="truck" :href="route('dashboard.shipping.index')" :current="request()->routeIs('dashboard.shipping.*')" wire:navigate>Envíos</flux:navlist.item>
                </flux:navlist.group>

                <flux:navlist.group :heading="__('POS')" class="grid">
                    <flux:navlist.item icon="building-storefront" :href="route('dashboard.pos.index')" :current="request()->routeIs('dashboard.pos.index*')" wire:navigate>Dashboard POS</flux:navlist.item>
                    <flux:navlist.item icon="clipboard-document" :href="route('dashboard.pos.quotations.index')" :current="request()->routeIs('dashboard.pos.quotations.*')" wire:navigate>Cotizaciones</flux:navlist.item>
                </flux:navlist.group>

                <flux:navlist.group :heading="__('Administración')" class="grid">
                    <flux:navlist.item icon="users" :href="route('dashboard.users.index')" :current="request()->routeIs('dashboard.users.*')" wire:navigate>Usuarios</flux:navlist.item>
                    <flux:navlist.item icon="clipboard-document-check" :href="route('dashboard.assignments.index')" :current="request()->routeIs('dashboard.assignments.*')" wire:navigate>Asignación de Productos</flux:navlist.item>
                </flux:navlist.group>

                <flux:navlist.group :heading="__('Ayuda')" class="grid">
                    <flux:navlist.item icon="academic-cap" :href="route('dashboard.tutorials.index')" :current="request()->routeIs('dashboard.tutorials.*')" wire:navigate>Tutoriales</flux:navlist.item>
                </flux:navlist.group>

                <flux:navlist.group :heading="__('Configuración')" class="grid">
                    <flux:navlist.item icon="cog-6-tooth" :href="route('dashboard.settings.index')" :current="request()->routeIs('dashboard.settings.*')" wire:navigate>Configuración Tienda</flux:navlist.item>
                    <flux:navlist.item icon="cog-6-tooth" :href="route('profile.edit')" :current="request()->routeIs('profile.edit')" wire:navigate>Ajustes</flux:navlist.item>
                </flux:navlist.group>
            </flux:navlist>

            <flux:spacer />

            <!-- Desktop User Menu -->
            <flux:dropdown class="hidden lg:block" position="bottom" align="start">
                <flux:profile
                    :name="auth()->user()->name"
                    :initials="auth()->user()->initials()"
                    icon:trailing="chevrons-up-down"
                    data-test="sidebar-menu-button"
                />

                <flux:menu class="w-[220px]">
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                    >
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>{{ __('Settings') }}</flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full" data-test="logout-button">
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:sidebar>

        <!-- Mobile User Menu -->
        <flux:header class="lg:hidden">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

            <flux:spacer />

            <flux:dropdown position="top" align="end">
                <flux:profile
                    :initials="auth()->user()->initials()"
                    icon-trailing="chevron-down"
                />

                <flux:menu>
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                    >
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>{{ __('Settings') }}</flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full" data-test="logout-button">
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:header>

        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 rounded-lg bg-green-100 p-4 text-green-800 dark:bg-green-900/30 dark:text-green-200">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 rounded-lg bg-red-100 p-4 text-red-800 dark:bg-red-900/30 dark:text-red-200">
                    {{ session('error') }}
                </div>
            @endif
        </div>

        {{ $slot }}

        @fluxScripts
    </body>
</html>
