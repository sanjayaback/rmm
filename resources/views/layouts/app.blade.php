<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'RoomRent') }} — @yield('title', 'Find Your Perfect Room')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@500;600;700;800;900&family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin=""/>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('css/rentivo.css') }}">

    <style>
        .leaflet-popup-content-wrapper {
            background: #FFFFFF !important;
            border: 1px solid #E5E7EB !important;
            border-radius: 20px !important;
            color: #1F2937 !important;
            padding: 4px !important;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15) !important;
        }
        .leaflet-popup-tip { background: #FFFFFF !important; }
        .leaflet-popup-close-button { color: #9CA3AF !important; padding: 8px 10px 0 0 !important; }
    </style>
    @stack('head')
</head>
<body class="min-h-screen flex flex-col bg-[#F8F9FA] text-[#1F2937] pb-16 md:pb-0 selection:bg-[#00796B]/20 selection:text-[#00796B]">

    @include('partials.nav')
    @include('partials.flash')

    <main class="flex-1 w-full">
        @yield('content')
    </main>

    @unless(request()->routeIs('home'))
        @include('partials.footer')
    @endunless

    <!-- Mobile Bottom Navigation Bar (RoomRent Spec Screen 1) -->
    <nav class="md:hidden fixed bottom-0 left-0 right-0 z-50 bg-white/95 backdrop-blur-xl border-t border-gray-200 py-2 px-4 flex items-center justify-around text-xs shadow-lg">
        <a href="{{ route('home') }}" class="flex flex-col items-center gap-1 transition-all {{ request()->routeIs('home') ? 'text-[#00796B] font-extrabold scale-105' : 'text-gray-500 hover:text-gray-900 font-medium' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
            <span class="text-[11px]">Home</span>
        </a>
        <a href="{{ route('listings.browse') }}" class="flex flex-col items-center gap-1 transition-all {{ request()->routeIs('listings.browse') ? 'text-[#00796B] font-extrabold scale-105' : 'text-gray-500 hover:text-gray-900 font-medium' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <span class="text-[11px]">Browse</span>
        </a>
        @auth
            <a href="{{ route('unlocks.history') }}" class="flex flex-col items-center gap-1 transition-all {{ request()->routeIs('unlocks.*') ? 'text-[#00796B] font-extrabold scale-105' : 'text-gray-500 hover:text-gray-900 font-medium' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                <span class="text-[11px]">Unlocks</span>
            </a>
            <a href="{{ route('dashboard') }}" class="flex flex-col items-center gap-1 transition-all {{ request()->routeIs('dashboard') ? 'text-[#00796B] font-extrabold scale-105' : 'text-gray-500 hover:text-gray-900 font-medium' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                <span class="text-[11px]">Profile</span>
            </a>
        @else
            <a href="{{ route('login') }}" class="flex flex-col items-center gap-1 transition-all {{ request()->routeIs('login') ? 'text-[#00796B] font-extrabold scale-105' : 'text-gray-500 hover:text-gray-900 font-medium' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                <span class="text-[11px]">Log In</span>
            </a>
        @endauth
    </nav>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @stack('scripts')
</body>
</html>
