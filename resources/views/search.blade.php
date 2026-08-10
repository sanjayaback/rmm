@extends('layouts.app')

@section('title', $query ? 'Search Results for "' . $query . '"' : 'Global Search Rooms & Flats')

@section('content')
<div class="bg-gradient-to-b from-[#00796B]/10 via-[#F8F9FA] to-[#F8F9FA] py-10 border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl">
            <h1 class="text-3xl sm:text-4xl font-black text-gray-900 tracking-tight">
                🔍 {{ $query ? 'Results for "' . $query . '"' : 'Global Room Search' }}
            </h1>
            <p class="text-sm text-gray-600 mt-2">
                Showing {{ $listings->total() }} available rooms, apartments, and hostels across Nepal.
            </p>

            <!-- Expanded Search Bar -->
            <form method="GET" action="{{ route('search') }}" class="mt-6 flex flex-col sm:flex-row gap-2">
                <div class="relative flex-1">
                    <input type="text" name="q" value="{{ $query }}"
                           placeholder="Type a city, area (e.g. Thamel, Lazimpat), room type..."
                           class="w-full px-5 py-3.5 pl-12 rounded-2xl bg-white border border-gray-200 focus:ring-2 focus:ring-[#00796B] focus:border-transparent outline-none shadow-sm text-sm font-semibold">
                    <svg class="w-5 h-5 text-gray-400 absolute left-4 top-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <button type="submit" class="px-7 py-3.5 bg-[#00796B] hover:bg-[#004D40] text-white font-extrabold rounded-2xl shadow-md transition-all">
                    Search Now
                </button>
            </form>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">

        <!-- Sidebar Filters -->
        <aside class="lg:col-span-1">
            <form method="GET" action="{{ route('search') }}" class="bg-white rounded-2xl p-6 border border-gray-200/80 shadow-sm space-y-6 sticky top-24">
                <input type="hidden" name="q" value="{{ $query }}">

                <div>
                    <h3 class="text-base font-extrabold text-gray-900 mb-4 border-b pb-2">Refine Search</h3>
                </div>

                <!-- City Filter -->
                <div>
                    <label class="block text-xs font-bold uppercase text-gray-600 mb-1.5">City</label>
                    <select name="city" class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-xs font-semibold focus:ring-2 focus:ring-[#00796B] outline-none">
                        <option value="">All Cities</option>
                        @foreach($cities as $city)
                            <option value="{{ $city }}" {{ request('city') === $city ? 'selected' : '' }}>{{ $city }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Room Type Filter -->
                <div>
                    <label class="block text-xs font-bold uppercase text-gray-600 mb-1.5">Room Type</label>
                    <select name="room_type" class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-xs font-semibold focus:ring-2 focus:ring-[#00796B] outline-none">
                        <option value="">All Room Types</option>
                        @foreach($roomTypes as $key => $label)
                            <option value="{{ $key }}" {{ request('room_type') === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Price Range -->
                <div>
                    <label class="block text-xs font-bold uppercase text-gray-600 mb-1.5">Price Range (NPR)</label>
                    <div class="grid grid-cols-2 gap-2">
                        <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="Min"
                               class="w-full px-3 py-2 text-xs rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#00796B] outline-none">
                        <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Max"
                               class="w-full px-3 py-2 text-xs rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#00796B] outline-none">
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full py-2.5 bg-gray-900 hover:bg-black text-white font-bold text-xs rounded-xl transition shadow-sm">
                        Apply Filters
                    </button>
                    @if(request()->anyFilled(['city', 'room_type', 'min_price', 'max_price']))
                        <a href="{{ route('search', ['q' => $query]) }}" class="block text-center text-xs text-gray-500 hover:text-rose-600 mt-2 font-semibold">
                            Reset Filters
                        </a>
                    @endif
                </div>
            </form>
        </aside>

        <!-- Results Grid -->
        <main class="lg:col-span-3">
            @if($listings->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($listings as $listing)
                        @include('partials.listing-card', ['listing' => $listing])
                    @endforeach
                </div>

                <div class="mt-8">
                    {{ $listings->links() }}
                </div>
            @else
                <div class="bg-white rounded-3xl p-12 text-center border border-gray-200/80 shadow-sm">
                    <div class="w-16 h-16 bg-teal-50 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-3xl">🏠</span>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900">No rooms matched your search</h3>
                    <p class="text-xs text-gray-500 mt-1 max-w-sm mx-auto">
                        Try adjusting your keywords, price range, or selecting a different city.
                    </p>
                    <a href="{{ route('search') }}" class="inline-block mt-4 px-5 py-2.5 bg-[#00796B] text-white text-xs font-bold rounded-xl hover:bg-[#004D40] transition">
                        Clear Search & View All
                    </a>
                </div>
            @endif
        </main>
    </div>
</div>
@endsection
