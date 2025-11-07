<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Styles -->
        @livewireStyles
        @stack('styles')
    </head>
    <body class="font-sans antialiased">
        <x-banner />

        <div class="min-h-screen bg-gray-100 flex">
            <!-- Sidebar Navigation -->
            <div class="hidden lg:flex lg:flex-shrink-0 lg:fixed lg:inset-y-0">
                <div class="flex flex-col w-64">
                    <div class="flex flex-col flex-grow bg-white border-r border-gray-200 pt-5 pb-4 overflow-y-auto">
                        <!-- Logo -->
                        <div class="flex items-center flex-shrink-0 px-6 mb-8">
                            <a href="{{ route('dashboard') }}" class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10 rounded-xl bg-gradient-to-br from-blue-500 to-cyan-600 flex items-center justify-center">
                                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h1 class="text-lg font-bold text-gray-900">Reservas</h1>
                                    <p class="text-xs text-gray-500">Venta de Hielo</p>
                                </div>
                            </a>
                        </div>

                        <!-- Navigation Links -->
                        <nav class="flex-1 px-3 space-y-1">
                            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'bg-gradient-to-r from-blue-500 to-cyan-600 text-white' : 'text-gray-700 hover:bg-gray-100' }} group flex items-center px-3 py-3 text-sm font-semibold rounded-xl transition-all duration-200">
                                <svg class="mr-3 h-5 w-5 {{ request()->routeIs('dashboard') ? 'text-white' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                </svg>
                                Dashboard
                            </a>

                            <a href="{{ route('clientes.index') }}" class="{{ request()->routeIs('clientes.*') ? 'bg-gradient-to-r from-blue-500 to-cyan-600 text-white' : 'text-gray-700 hover:bg-gray-100' }} group flex items-center px-3 py-3 text-sm font-semibold rounded-xl transition-all duration-200">
                                <svg class="mr-3 h-5 w-5 {{ request()->routeIs('clientes.*') ? 'text-white' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                Clientes
                            </a>

                            <a href="{{ route('vehiculos.index') }}" class="{{ request()->routeIs('vehiculos.*') ? 'bg-gradient-to-r from-green-500 to-emerald-600 text-white' : 'text-gray-700 hover:bg-gray-100' }} group flex items-center px-3 py-3 text-sm font-semibold rounded-xl transition-all duration-200">
                                <svg class="mr-3 h-5 w-5 {{ request()->routeIs('vehiculos.*') ? 'text-white' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                </svg>
                                Vehículos
                            </a>

                            <a href="{{ route('reservas.index') }}" class="{{ request()->routeIs('reservas.*') ? 'bg-gradient-to-r from-purple-500 to-pink-600 text-white' : 'text-gray-700 hover:bg-gray-100' }} group flex items-center px-3 py-3 text-sm font-semibold rounded-xl transition-all duration-200">
                                <svg class="mr-3 h-5 w-5 {{ request()->routeIs('reservas.*') ? 'text-white' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                Reservas
                            </a>

                            <a href="{{ route('reportes.index') }}" class="{{ request()->routeIs('reportes.*') ? 'bg-gradient-to-r from-red-500 to-orange-600 text-white' : 'text-gray-700 hover:bg-gray-100' }} group flex items-center px-3 py-3 text-sm font-semibold rounded-xl transition-all duration-200">
                                <svg class="mr-3 h-5 w-5 {{ request()->routeIs('reportes.*') ? 'text-white' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                                Reportes
                            </a>
                        </nav>

                        <!-- User Section -->
                        <div class="flex-shrink-0 flex border-t border-gray-200 p-4">
                            <div class="flex items-center w-full">
                                <div class="flex-shrink-0">
                                    <div class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-400 to-cyan-500 flex items-center justify-center">
                                        <span class="text-white font-bold text-sm">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                                    </div>
                                </div>
                                <div class="ml-3 flex-1">
                                    <p class="text-sm font-semibold text-gray-900">{{ Auth::user()->name }}</p>
                                    <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
                                </div>
                                <div class="ml-3 relative">
                                    <div class="relative" x-data="{ open: false }">
                                        <button @click="open = !open" class="flex text-sm text-gray-400 hover:text-gray-500 focus:outline-none">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                            </svg>
                                        </button>
                                        
                                        <div x-show="open" 
                                             @click.away="open = false"
                                             x-transition:enter="transition ease-out duration-200"
                                             x-transition:enter-start="opacity-0 scale-95"
                                             x-transition:enter-end="opacity-100 scale-100"
                                             x-transition:leave="transition ease-in duration-75"
                                             x-transition:leave-start="opacity-100 scale-100"
                                             x-transition:leave-end="opacity-0 scale-95"
                                             class="absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50"
                                             style="display: none;">
                                            <div class="py-1">
                                                <a href="{{ route('profile.show') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                    {{ __('Profile') }}
                                                </a>
                                                <div class="border-t border-gray-200"></div>
                                                <form method="POST" action="{{ route('logout') }}">
                                                    @csrf
                                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                        {{ __('Log Out') }}
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="flex-1 flex flex-col overflow-hidden lg:pl-64">
                <!-- Top Navigation Bar (Mobile) -->
                <div class="lg:hidden">
            @livewire('navigation-menu')
                </div>

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
                <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
                {{ $slot }}
            </main>
            </div>
        </div>

        @stack('modals')
        @stack('scripts')

        @livewireScripts
    </body>
</html>
