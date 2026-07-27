<div class="listing-card-airbnb group">
    <a href="{{ route('listings.show', $listing) }}" class="block">
        <!-- Photo Container -->
        <div class="relative aspect-[4/3] w-full overflow-hidden bg-gray-100">
            <img src="{{ $listing->image_url }}" alt="{{ $listing->title }}"
                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-out"
                 onerror="this.src='/images/room-placeholder.jpg'">
            
            <!-- Room Type Badge -->
            <div class="absolute top-3 left-3">
                <span class="bg-white/95 backdrop-blur-md text-gray-800 text-[11px] font-extrabold px-3 py-1 rounded-full border border-gray-200 shadow-sm">
                    {{ $listing->room_type_label }}
                </span>
            </div>

            <!-- Heart Wishlist Icon -->
            <button type="button" @click.prevent="" class="absolute top-3 right-3 w-8 h-8 rounded-full bg-white/90 backdrop-blur-md border border-gray-200 flex items-center justify-center text-gray-500 hover:text-[#00796B] hover:bg-white transition-colors shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.684a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
            </button>

            @if(!$listing->is_available)
                <div class="absolute inset-0 bg-gray-900/70 backdrop-blur-sm flex items-center justify-center">
                    <span class="bg-rose-600 text-white text-xs font-black tracking-widest uppercase px-4 py-1.5 rounded-full shadow-md">Not Available</span>
                </div>
            @endif
        </div>

        <!-- Card Body -->
        <div class="p-4 sm:p-5">
            <div class="flex items-start justify-between gap-2 mb-1">
                <h3 class="font-extrabold text-gray-900 text-base leading-snug line-clamp-1 group-hover:text-[#00796B] transition-colors font-heading">{{ $listing->title }}</h3>
                <div class="flex items-center gap-1 text-amber-500 text-xs font-bold shrink-0">
                    <span>★</span>
                    <span class="text-gray-700">4.9</span>
                </div>
            </div>

            <div class="flex items-center gap-1.5 text-gray-500 text-xs mb-3">
                <svg class="w-3.5 h-3.5 shrink-0 text-[#00796B]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <span class="truncate">{{ $listing->area }}, {{ $listing->city }}</span>
            </div>

            <!-- Amenities Chips (Matching Spec Image Screen 1) -->
            <div class="flex flex-wrap items-center gap-1.5 mb-3.5">
                <span class="bg-teal-50 text-[#00796B] border border-teal-100 text-[10px] font-bold px-2 py-0.5 rounded-md">🛏 {{ $listing->bedrooms }} Bed</span>
                <span class="bg-teal-50 text-[#00796B] border border-teal-100 text-[10px] font-bold px-2 py-0.5 rounded-md">🚿 {{ $listing->bathrooms }} Bath</span>
                <span class="bg-teal-50 text-[#00796B] border border-teal-100 text-[10px] font-bold px-2 py-0.5 rounded-md">📶 WiFi</span>
            </div>

            <!-- Price Footer (Matching Spec Image Screen 1: Rs. 14,500 / month) -->
            <div class="flex items-center justify-between gap-2 pt-3 border-t border-gray-100">
                <div>
                    <span class="text-[#00796B] font-black text-lg font-heading">Rs. {{ number_format($listing->price) }}</span>
                    <span class="text-gray-500 text-xs font-normal"> / month</span>
                </div>
                <span class="bg-teal-50 text-[#00796B] border border-teal-200 text-[11px] font-extrabold px-2.5 py-1 rounded-full shrink-0">
                    Unlock: Rs. {{ number_format($listing->unlock_fee) }}
                </span>
            </div>
        </div>
    </a>
</div>
