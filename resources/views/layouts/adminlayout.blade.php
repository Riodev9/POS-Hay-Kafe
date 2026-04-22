<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? 'Dashboard' }}</title>
    @extends('partials.head')
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="min-h-screen bg-gradient-to-br from-blue-600 via-blue-400 to-blue-300">

<div class="flex">

    {{-- SIDEBAR --}}
    @include('partials.sidebar')

    {{-- CONTENT --}}
    <main class="flex-1 ml-64 p-6 relative">

        {{-- NAVBAR (LAYER ATAS SENDIRI) --}}
        <div class="relative z-50">
            @include('partials.navbar')
        </div>

        {{-- CARD UTAMA --}}
        <div class="mt-6 px-4 lg:px-6 relative z-10">
            <div
                class="
                    min-h-[calc(100vh-8rem)]
                    bg-gray-300/40
                    backdrop-blur-xl
                    rounded-3xl
                    border border-white/30
                    shadow-xl shadow-black/10
                    p-5 md:p-6 lg:p-8
                "
            >
                @yield('content')
            </div>
        </div>

    </main>

</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        lucide.createIcons();
    });
</script>

</body>
</html>
