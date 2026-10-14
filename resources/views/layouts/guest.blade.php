<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ request()->is('login') ? 'Login' : (request()->is('register') ? 'Register' : config('app.name', 'SIPRUKA')) }}</title>
        <link rel="icon" type="image/png" href="{{ asset('assets/Logo untuk Pov icon.png') }}">
        <link rel="shortcut icon" href="{{ asset('assets/Logo untuk Pov icon.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Prevent Alpine.js FOUC / Modal Flash -->
        <style>
            [x-cloak] { display: none !important; }
        </style>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-slate-100 antialiased bg-[#030712] selection:bg-cyan-500 selection:text-black overflow-x-hidden">
        <div class="min-h-screen flex items-center justify-center p-4 sm:p-6 lg:p-8 relative overflow-hidden bg-[#030712]">
            
            <!-- Animated Background Image Container -->
            <div class="absolute inset-0 z-0 overflow-hidden pointer-events-none">
                <div class="absolute inset-0 bg-cover bg-center bg-no-repeat animate-bg-pan opacity-50 filter brightness-90 contrast-110"
                     style="background-image: url('{{ asset('assets/login_bg.png') }}');">
                </div>
                
                <!-- Dark Gradient Overlay for High Contrast -->
                <div class="absolute inset-0 bg-gradient-to-t from-[#030712] via-[#030712]/70 to-[#030712]/85"></div>

                <!-- Animated Laser Scanline Line Effect -->
                <div class="absolute inset-x-0 h-44 bg-gradient-to-b from-transparent via-[#00D1FF]/10 to-transparent animate-scanline"></div>
            </div>

            <!-- Main Content Container -->
            <div class="z-10 w-full max-w-4xl mx-auto relative">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
