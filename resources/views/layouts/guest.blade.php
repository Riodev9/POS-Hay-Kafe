<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'POS Hay') }}</title>

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    {{-- Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen flex items-center justify-center
                bg-gradient-to-br from-blue-700 via-blue-500 to-blue-400">

        {{-- CARD --}}
        <div class="w-full max-w-md
                    bg-white/20 backdrop-blur-xl
                    rounded-3xl
                    border border-white/30
                    shadow-2xl
                    p-8">

            {{-- LOGO --}}
            <div class="text-center mb-8">
                <img src="{{ asset('logo1.png') }}"
                     class="w-20 mx-auto mb-3 rounded-xl shadow-md">
                <h1 class="text-2xl font-bold text-white">
                    Login Kasir
                </h1>
                <p class="text-sm text-white/80">
                    Cafe & Space
                </p>
            </div>

            {{-- SLOT FORM --}}
            {{ $slot }}

        </div>
    </div>
</body>
</html>
