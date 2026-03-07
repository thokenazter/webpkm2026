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
        
        <!-- 3D Login Assets (only for login page) -->
        @if(request()->routeIs('login'))
            <link href="{{ asset('css/3d-login.css') }}" rel="stylesheet">
        @endif

        <!-- Styles -->
        @livewireStyles
    </head>
    <body>
        <div class="font-sans text-gray-900 antialiased">
            {{ $slot }}
        </div>

        @livewireScripts
        
        <!-- 3D Login JavaScript (only for login page) -->
        @if(request()->routeIs('login'))
            <script src="{{ asset('js/3d-login.js') }}"></script>
        @endif
    </body>
</html>
