<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        @php
            $pageTitle = config('app.name', 'SIPRUKA');
            if (request()->routeIs('dashboard')) {
                $pageTitle = 'Dashboard';
            } elseif (request()->routeIs('buku.*')) {
                $pageTitle = 'Buku';
            } elseif (request()->routeIs('ruangan.*')) {
                $pageTitle = 'Ruangan';
            } elseif (request()->routeIs('peminjaman-buku.*')) {
                $pageTitle = 'Peminjaman Buku';
            } elseif (request()->routeIs('peminjaman-ruangan.*')) {
                $pageTitle = 'Peminjaman Ruangan';
            }
        @endphp
        <title>{{ $pageTitle }}</title>
        <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Custom Styles for Dark Theme Input Elements -->
        <style>
            input[type="date"]::-webkit-calendar-picker-indicator,
            input[type="time"]::-webkit-calendar-picker-indicator {
                filter: invert(1);
                cursor: pointer;
            }
        </style>
    </head>
    <body class="font-sans antialiased text-slate-100">
        <div class="min-h-screen bg-slate-950">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-slate-900 border-b border-slate-800 shadow-sm">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
