<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name','RoomRent') }} — {{ $title ?? 'Auth' }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@500;600;700;800;900&family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css','resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('css/rentivo.css') }}">
</head>
<body class="min-h-screen flex items-center justify-center p-4 sm:p-6 bg-[#F8F9FA] text-[#1F2937] relative overflow-x-hidden selection:bg-[#00796B]/20 selection:text-[#00796B]">

    <div class="w-full max-w-md relative z-10 my-6">
        <!-- Brand Header Logo (Matching Spec Image Screen 4 & 5) -->
        <div class="text-center mb-6">
            <a href="{{ route('home') }}" class="inline-flex flex-col items-center gap-2 group">
                <div class="w-14 h-14 bg-[#00796B] rounded-2xl flex items-center justify-center shadow-lg shadow-[#00796B]/25 group-hover:scale-105 transition-transform">
                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/></svg>
                </div>
                <span class="font-heading font-black text-3xl tracking-tight text-gray-900 mt-1">Room<span class="text-[#00796B]">Rent</span></span>
            </a>
        </div>

        <!-- Auth Card Container -->
        <div class="card-airbnb p-6 sm:p-8 bg-white border border-gray-200 shadow-xl rounded-3xl">
            {{ $slot }}
        </div>

        @if(session('status'))
            <div class="mt-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl px-4 py-3 text-xs font-bold text-center shadow-sm">
                {{ session('status') }}
            </div>
        @endif

        <!-- Footer Link -->
        <div class="text-center mt-6">
            <a href="{{ route('home') }}" class="text-xs text-gray-500 hover:text-[#00796B] font-bold transition-colors">← Back to Map Search</a>
        </div>
    </div>

    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>
