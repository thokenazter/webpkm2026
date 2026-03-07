<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="description" content="Sistem Laporan Pertanggungjawaban BOK - Aplikasi manajemen keuangan digital untuk Puskesmas">

        <title>{{ config('app.name', 'LPJ BOK System') }}</title>
        
        <!-- Favicon -->
        <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Dashboard Modern Styles -->
        <link href="{{ asset('css/dashboard-modern.css') }}" rel="stylesheet">

        <!-- Icons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

        <!-- Styles -->
        @livewireStyles
    </head>
    <body class="font-sans antialiased">
        <x-banner />

        <div class="min-h-screen bg-gray-100">
            @livewire('navigation-menu')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        <!-- Footer -->
        <footer class="bg-gray-800 text-white">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <div class="flex items-center space-x-2 mb-4 md:mb-0">
                        <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/>
                            <line x1="12" y1="6" x2="12" y2="18" stroke="currentColor" stroke-width="2"/>
                            <path d="M8 9a3 3 0 0 1 3-3m-3 9a3 3 0 0 0 3 3" stroke="currentColor" stroke-width="2" fill="none"/>
                        </svg>
                        <span class="text-lg font-semibold">LPJ BOK System</span>
                    </div>
                    
                    <div class="text-center md:text-right">
                        <p class="text-sm text-gray-300 mb-1">
                            &copy; {{ date('Y') }} LPJ System. All rights reserved.
                        </p>
                        <p class="text-xs text-gray-400">
                            Aplikasi dikembangkan oleh 
                            <span class="text-blue-400 font-medium">Beta|Development</span>
                        </p>
                    </div>
                </div>
                
                <!-- Divider -->
                <div class="border-t border-gray-700 mt-4 pt-4">
                    <div class="flex flex-col md:flex-row justify-between items-center text-xs text-gray-400">
                        <div class="mb-2 md:mb-0">
                            <span>Sistem Laporan Pertanggungjawaban (LPJ)</span>
                        </div>
                        <div class="flex items-center space-x-4">
                            <span>Version 3.0</span>
                            <span>&bull;</span>
                            <span>Laravel {{ app()->version() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </footer>

        @stack('modals')

        @stack('scripts')
        @livewireScripts
    </body>
</html>
