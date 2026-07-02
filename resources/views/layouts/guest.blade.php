<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ request()->is('login') ? 'Login' : (request()->is('register') ? 'Register' : config('app.name', 'SIPRUKA')) }}</title>
        <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-slate-100 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-slate-950 relative overflow-hidden">
            <!-- Decorative background glows -->
            <div class="absolute -top-40 -right-40 w-96 h-96 bg-indigo-500/15 rounded-full blur-[120px] pointer-events-none"></div>
            <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-purple-500/15 rounded-full blur-[120px] pointer-events-none"></div>

            <div class="z-10">
                <a href="/">
                    <x-application-logo class="w-16 h-16 fill-current text-indigo-500 hover:text-indigo-400 transition duration-300" />
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-8 py-8 bg-slate-900/60 backdrop-blur-md border border-slate-800 shadow-2xl rounded-2xl z-10">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
