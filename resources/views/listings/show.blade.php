@extends('layouts.app')
@section('title', $listing->title)

@push('head')
<style>#detail-map { height: 280px; border-radius: 16px; overflow: hidden; }</style>
@endpush

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-10 pb-28 md:pb-10">

    <!-- Breadcrumb -->
    <div class="flex items-center justify-between gap-4 mb-4 sm:mb-6">
        <a href="{{ route('listings.browse') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-gray-600 hover:text-[#00796B] transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
            Back to Browse
        </a>
        <button class="inline-flex items-center gap-1.5 text-xs font-bold text-gray-700 bg-white border border-gray-200 px-3 py-1.5 rounded-full transition-all shadow-sm">
            <svg class="w-3.5 h-3.5 text-[#00796B]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/></svg>
            Share
        </button>
    </div>

    <!-- Listing Header -->
    <div class="mb-5">
        <div class="flex items-center gap-2 mb-1.5">
            <span class="badge-teal">{{ $listing->room_type_label }}</span>
            <span class="text-xs text-gray-500">• {{ $listing->city }}</span>
        </div>
        <h1 class="text-2xl sm:text-4xl font-black text-gray-900 tracking-tight mb-2 font-heading leading-tight">{{ $listing->title }}</h1>
        <div class="flex flex-wrap items-center gap-2 sm:gap-4 text-xs text-gray-600">
            <div class="flex items-center gap-1 font-bold text-gray-800">
                <svg class="w-4 h-4 text-[#00796B] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <span>{{ $listing->area }}, {{ $listing->city }}</span>
            </div>
            <span>•</span>
            <span class="text-[#00796B] font-extrabold text-lg sm:text-xl font-heading">Rs. {{ number_format($listing->price) }} <span class="text-gray-500 text-xs font-normal">/ month</span></span>
        </div>
    </div>

    <!-- Hero Image Showcase -->
    <div class="relative rounded-2xl sm:rounded-3xl overflow-hidden h-60 sm:h-80 md:h-[420px] bg-gray-100 mb-6 sm:mb-8 shadow-md border border-gray-200 group">
        <img src="{{ $listing->image_url }}" alt="{{ $listing->title }}"
             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700"
             onerror="this.onerror=null; this.src='/images/room-placeholder.jpg'">
        <div class="absolute bottom-4 left-4 right-4 flex items-end justify-between gap-2">
            <div class="bg-white/95 backdrop-blur-xl px-3.5 py-1.5 sm:px-4 sm:py-2 rounded-xl sm:rounded-2xl border border-gray-200 shadow-md">
                <span class="text-[#00796B] font-black text-lg sm:text-2xl font-heading">Rs. {{ number_format($listing->price) }}</span>
                <span class="text-gray-500 text-xs font-normal"> / month</span>
            </div>
            @if($listing->is_available)
                <span class="badge-emerald !px-3 !py-1 text-xs shadow-sm">✓ Available Now</span>
            @else
                <span class="badge-red !px-3 !py-1 text-xs shadow-sm">Occupied</span>
            @endif
        </div>
    </div>

    <!-- Feature Chips -->
    <div class="flex flex-wrap items-center gap-1.5 sm:gap-2 mb-6 sm:mb-8">
        <span class="bg-teal-50 text-[#00796B] border border-teal-100 text-xs font-bold px-3 py-1.5 rounded-full flex items-center gap-1.5">🛏 Furnished</span>
        <span class="bg-teal-50 text-[#00796B] border border-teal-100 text-xs font-bold px-3 py-1.5 rounded-full flex items-center gap-1.5">📶 High-speed Wi-Fi</span>
        <span class="bg-teal-50 text-[#00796B] border border-teal-100 text-xs font-bold px-3 py-1.5 rounded-full flex items-center gap-1.5">🛡 24/7 Security</span>
        <span class="bg-teal-50 text-[#00796B] border border-teal-100 text-xs font-bold px-3 py-1.5 rounded-full flex items-center gap-1.5">🅿 Parking Available</span>
    </div>

    <!-- Details Grid Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 sm:gap-8">

        <!-- Left Column -->
        <div class="lg:col-span-2 space-y-6 sm:space-y-8">

            <!-- About This Room -->
            <div class="card-airbnb p-5 sm:p-7">
                <h3 class="font-extrabold text-gray-900 text-base sm:text-lg mb-3 pb-3 border-b border-gray-100 font-heading">About this room</h3>
                <p class="text-gray-700 leading-relaxed text-xs sm:text-sm whitespace-pre-line">{{ $listing->description }}</p>
            </div>

            <!-- Room Specifications Table -->
            <div class="card-airbnb p-5 sm:p-7">
                <h3 class="font-extrabold text-gray-900 text-base sm:text-lg mb-4 pb-3 border-b border-gray-100 font-heading">Room Specifications</h3>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-center">
                    <div class="bg-gray-50 border border-gray-200 p-3 rounded-2xl">
                        <span class="text-gray-500 text-[11px] block font-semibold">Bedrooms</span>
                        <strong class="text-gray-900 font-black text-base sm:text-lg font-heading">{{ $listing->bedrooms }}</strong>
                    </div>
                    <div class="bg-gray-50 border border-gray-200 p-3 rounded-2xl">
                        <span class="text-gray-500 text-[11px] block font-semibold">Bathrooms</span>
                        <strong class="text-gray-900 font-black text-base sm:text-lg font-heading">{{ $listing->bathrooms }}</strong>
                    </div>
                    <div class="bg-gray-50 border border-gray-200 p-3 rounded-2xl">
                        <span class="text-gray-500 text-[11px] block font-semibold">Room Type</span>
                        <strong class="text-[#00796B] font-black text-xs sm:text-sm capitalize font-heading">{{ $listing->room_type }}</strong>
                    </div>
                    <div class="bg-gray-50 border border-gray-200 p-3 rounded-2xl">
                        <span class="text-gray-500 text-[11px] block font-semibold">Approx Size</span>
                        <strong class="text-gray-900 font-black text-xs sm:text-sm font-heading">250 sq. ft.</strong>
                    </div>
                </div>
            </div>

            <!-- Location Preview -->
            <div class="card-airbnb p-5 sm:p-7">
                <div class="mb-4">
                    <h3 class="font-extrabold text-gray-900 text-base sm:text-lg font-heading">
                        @if($isUnlocked || $isOwner) Exact Location @else Approximate Location @endif
                    </h3>
                    <p class="text-gray-500 text-xs mt-0.5">
                        @if($isUnlocked || $isOwner)
                            {{ $listing->exact_address }}
                        @else
                            {{ $listing->area }}, {{ $listing->city }} (Exact location shown after unlock)
                        @endif
                    </p>
                </div>
                <div id="detail-map"></div>
            </div>
        </div>

        <!-- Right Column (Desktop Unlock Widget) -->
        <div class="lg:col-span-1">
            <div class="sticky top-24 space-y-6">

                @if($isOwner)
                    <div class="card-airbnb p-6 border-teal-200">
                        <div class="flex items-center gap-2 text-[#00796B] font-extrabold text-sm mb-4 font-heading">
                            <span>📋 Owner Dashboard</span>
                        </div>
                        <div class="space-y-3 text-xs mb-5">
                            <div>
                                <span class="text-gray-500 block mb-0.5 font-semibold">Your Contact Phone</span>
                                <strong class="text-gray-900 font-mono text-sm">{{ $listing->phone }}</strong>
                            </div>
                            <div>
                                <span class="text-gray-500 block mb-0.5 font-semibold">Exact Address</span>
                                <strong class="text-gray-900 text-xs">{{ $listing->exact_address }}</strong>
                            </div>
                        </div>
                        <a href="{{ route('listings.edit', $listing) }}" class="btn-secondary w-full text-xs font-bold text-center">Edit Listing</a>
                    </div>

                @elseif($isUnlocked)
                    <div class="card-airbnb p-6 border-emerald-300 bg-emerald-50/50">
                        <div class="badge-emerald mb-4">✓ You have unlocked this listing</div>
                        <h4 class="font-extrabold text-gray-900 text-base mb-4 font-heading">Contact Owner</h4>
                        
                        <div class="space-y-3 mb-5">
                            <div class="bg-white border border-emerald-200 p-4 rounded-2xl shadow-sm">
                                <span class="text-gray-500 text-[10px] block font-bold uppercase tracking-wider mb-1">PHONE NUMBER</span>
                                <div class="flex items-center justify-between gap-2">
                                    <a href="tel:{{ $unlockedData['phone'] }}" class="text-[#00796B] font-black text-xl hover:underline font-mono">
                                        📞 {{ $unlockedData['phone'] }}
                                    </a>
                                    <a href="https://wa.me/977{{ ltrim($unlockedData['phone'], '0') }}" target="_blank"
                                       class="w-9 h-9 bg-emerald-500 hover:bg-emerald-600 text-white rounded-full flex items-center justify-center shadow-md transition-colors">
                                        💬
                                    </a>
                                </div>
                            </div>

                            <div class="bg-white border border-emerald-200 p-4 rounded-2xl shadow-sm">
                                <span class="text-gray-500 text-[10px] block font-bold uppercase tracking-wider mb-1">EXACT ADDRESS</span>
                                <p class="text-gray-900 text-xs font-bold leading-relaxed">{{ $unlockedData['exact_address'] }}</p>
                            </div>
                        </div>

                        <p class="text-[11px] text-gray-500 text-center">Permanent contact access saved to your profile.</p>
                    </div>

                @else
                    <div class="card-airbnb p-6 shadow-xl relative border-teal-200">
                        <div class="text-center mb-5">
                            <div class="w-12 h-12 bg-teal-50 text-[#00796B] rounded-full flex items-center justify-center text-2xl mx-auto mb-3">🔒</div>
                            <h3 class="text-xl font-black text-gray-900 font-heading">Unlock exact location & contact</h3>
                            <p class="text-gray-500 text-xs mt-1">Pay a small fee to unlock exact location and contact details of the owner.</p>
                        </div>

                        <div class="bg-teal-50/70 border border-teal-200 rounded-2xl p-4 mb-5 text-center">
                            <div class="text-gray-500 text-[11px] font-bold uppercase tracking-wider mb-1">Unlock Fee</div>
                            <div class="text-3xl font-black text-[#00796B] font-heading">Rs. {{ number_format($listing->unlock_fee) }}</div>
                            <div class="text-gray-500 text-[11px] mt-0.5">Instant & lifetime access</div>
                        </div>

                        @auth
                            @if($listing->is_available)
                                <form action="{{ route('unlocks.initiate', $listing) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn-teal w-full !py-3.5 font-extrabold text-sm shadow-md">
                                        Pay Rs. {{ number_format($listing->unlock_fee) }} to Unlock
                                    </button>
                                </form>
                            @else
                                <div class="text-center text-gray-500 text-xs py-3 bg-gray-100 rounded-xl">Room Currently Occupied</div>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="btn-teal w-full !py-3.5 font-bold text-sm text-center">Log In to Unlock</a>
                            <p class="text-center text-xs text-gray-500 mt-3">Don't have an account? <a href="{{ route('register') }}" class="text-[#00796B] font-bold hover:underline">Register Free</a></p>
                        @endauth
                    </div>
                @endif

            </div>
        </div>

    </div>
</div>

<!-- Mobile Sticky Floating Payment / Unlock CTA Bar -->
@if(!$isOwner && !$isUnlocked)
    <div class="md:hidden fixed bottom-[52px] left-0 right-0 z-40 bg-white/95 backdrop-blur-xl border-t border-gray-200 p-3 px-4 flex items-center justify-between shadow-2xl">
        <div>
            <span class="text-[#00796B] font-black text-base font-heading block leading-none">Rs. {{ number_format($listing->price) }}</span>
            <span class="text-gray-500 text-[10px]">/ month</span>
        </div>
        @auth
            @if($listing->is_available)
                <form action="{{ route('unlocks.initiate', $listing) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-teal !py-2.5 !px-4 !text-xs font-extrabold shadow-md">
                        Pay Rs. {{ number_format($listing->unlock_fee) }} to Unlock
                    </button>
                </form>
            @endif
        @else
            <a href="{{ route('login') }}" class="btn-teal !py-2.5 !px-4 !text-xs font-bold">Log In to Unlock</a>
        @endauth
    </div>
@endif
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const lat = {{ ($isUnlocked || $isOwner) ? $listing->lat : $listing->approx_lat }};
    const lng = {{ ($isUnlocked || $isOwner) ? $listing->lng : $listing->approx_lng }};
    const map = L.map('detail-map').setView([lat, lng], 15);
    L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', { maxZoom: 19 }).addTo(map);
    @if($isUnlocked || $isOwner)
        L.marker([lat, lng]).addTo(map).bindPopup('<strong style="font-family:\'Outfit\',sans-serif">{{ addslashes($listing->exact_address) }}</strong>').openPopup();
    @else
        L.circle([lat, lng], { radius: 300, color: '#00796B', fillColor: '#00796B', fillOpacity: 0.18, weight: 2.5 })
            .addTo(map).bindPopup('📍 Approximate area preview — unlock for exact location').openPopup();
    @endif
});
</script>
@endpush
