@extends('layouts.app')
@section('title','Browse Available Rooms')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-10" x-data="{ mobileFilters: false }">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8 pb-6 border-b border-gray-200">
        <div>
            <div class="flex items-center gap-2 mb-1.5">
                <span class="badge-teal">Exploration</span>
                <span class="text-xs text-gray-500">• Global Room Marketplace</span>
            </div>
            <h1 class="text-3xl sm:text-4xl font-black tracking-tight text-gray-900 font-heading">Browse Rooms & Apartments</h1>
            <p class="text-gray-600 text-sm mt-1">
                @if(request('search'))
                    Showing search results for <strong class="text-[#00796B]">"{{ request('search') }}"</strong> ({{ $listings->total() }} rooms found)
                @else
                    Discover {{ $listings->total() }} verified room & flat listings worldwide
                @endif
            </p>
        </div>
        <div class="flex items-center gap-3">
            <!-- Mobile Filter Toggle Button -->
            <button @click="mobileFilters = !mobileFilters" class="lg:hidden btn-secondary !py-2.5 !px-4 text-xs font-bold">
                <svg class="w-4 h-4 text-[#00796B]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                <span>Filter Places</span>
            </button>

            <a href="{{ route('home') }}" class="btn-teal !py-2.5 !px-5 shadow-md text-xs">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                <span>World Map Search</span>
            </a>
        </div>
    </div>

    <!-- Main Content Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">

        <!-- Sidebar Desktop / Mobile Drawer Filters -->
        <div class="lg:col-span-1"
             :class="{ 'fixed inset-0 z-50 bg-white/98 p-6 overflow-y-auto block': mobileFilters, 'hidden lg:block': !mobileFilters }"
             x-cloak>
            
            <div class="flex items-center justify-between lg:hidden mb-6 pb-4 border-b border-gray-200">
                <h3 class="font-extrabold text-gray-900 text-lg font-heading">Filter Rooms</h3>
                <button @click="mobileFilters = false" class="text-gray-500 hover:text-gray-900 p-2 text-xl font-bold">✕</button>
            </div>

            <form method="GET" action="{{ route('listings.browse') }}" class="card-airbnb p-6 space-y-5 sticky top-24 bg-white border border-gray-200">
                <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                    <h3 class="font-bold text-gray-900 text-base flex items-center gap-2 font-heading">
                        <svg class="w-4 h-4 text-[#00796B]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                        Filter Criteria
                    </h3>
                    <a href="{{ route('listings.browse') }}" class="text-xs text-gray-500 hover:text-[#00796B] font-semibold transition-colors">Reset All</a>
                </div>

                <div>
                    <label class="text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5 block">Search Keyword</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Area, title, landmark..."
                           class="input-airbnb text-sm py-2.5">
                </div>

                <div>
                    <label class="text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5 block">City / Location</label>
                    <select name="city" class="input-airbnb text-sm py-2.5">
                        <option value="">All Worldwide Cities</option>
                        @foreach($cities as $city)
                            <option value="{{ $city }}" {{ request('city') == $city ? 'selected' : '' }}>{{ $city }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5 block">Room Type</label>
                    <select name="room_type" class="input-airbnb text-sm py-2.5">
                        <option value="">All Types</option>
                        @foreach(['single'=>'Single Room','double'=>'Double Room','apartment'=>'Apartment','hostel'=>'Hostel'] as $v => $l)
                            <option value="{{ $v }}" {{ request('room_type') == $v ? 'selected' : '' }}>{{ $l }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5 block">Price Range (Rs./mo)</label>
                    <div class="flex gap-2">
                        <input type="number" name="min_price" placeholder="Min" value="{{ request('min_price') }}"
                               class="input-airbnb text-sm py-2 px-3">
                        <input type="number" name="max_price" placeholder="Max" value="{{ request('max_price') }}"
                               class="input-airbnb text-sm py-2 px-3">
                    </div>
                </div>

                <div>
                    <label class="text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5 block">Bedrooms</label>
                    <select name="bedrooms" class="input-airbnb text-sm py-2.5">
                        <option value="">Any Bedrooms</option>
                        @foreach([1,2,3,4] as $n)
                            <option value="{{ $n }}" {{ request('bedrooms') == $n ? 'selected' : '' }}>{{ $n }}+ bed</option>
                        @endforeach
                    </select>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full btn-teal !py-3 font-extrabold text-sm shadow-md">Apply Filters</button>
                </div>
            </form>
        </div>

        <!-- Room Cards Grid -->
        <div class="lg:col-span-3">
            @if($listings->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
                    @foreach($listings as $listing)
                        @include('partials.listing-card', ['listing' => $listing])
                    @endforeach
                </div>
                <div class="mt-10">{{ $listings->links('partials.pagination') }}</div>
            @else
                <div class="card-airbnb p-12 text-center bg-white border border-gray-200">
                    <div class="w-16 h-16 bg-teal-50 text-[#00796B] rounded-full flex items-center justify-center text-3xl mx-auto mb-4">🔍</div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2 font-heading">No Matching Rooms Found</h3>
                    <p class="text-gray-500 mb-6 text-sm max-w-sm mx-auto">We couldn't find any listings matching your search keyword "{{ request('search') }}".</p>
                    <a href="{{ route('listings.browse') }}" class="btn-secondary text-sm">Clear All Search Filters</a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
