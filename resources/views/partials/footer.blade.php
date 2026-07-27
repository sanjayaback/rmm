<footer class="border-t border-gray-200 bg-white mt-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-12">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 mb-8 pb-8 border-b border-gray-100">
            <div class="col-span-2 md:col-span-1">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-2 mb-3 group">
                    <div class="w-8 h-8 bg-[#00796B] rounded-xl flex items-center justify-center shadow-md shadow-[#00796B]/20">
                        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/></svg>
                    </div>
                    <span class="font-extrabold text-lg text-gray-900 font-heading">Rent<span class="text-[#00796B]">ivo</span></span>
                </a>
                <p class="text-gray-500 text-xs leading-relaxed">Worldwide premier map-based room & flat marketplace.</p>
            </div>
            <div>
                <h4 class="text-gray-900 text-xs font-extrabold uppercase tracking-wider mb-3.5 font-heading">Marketplace</h4>
                <ul class="space-y-2 text-xs text-gray-600 font-medium">
                    <li><a href="{{ route('home') }}" class="hover:text-[#00796B] transition-colors">Interactive World Map</a></li>
                    <li><a href="{{ route('listings.browse') }}" class="hover:text-[#00796B] transition-colors">Browse All Places</a></li>
                    @auth<li><a href="{{ route('dashboard') }}" class="hover:text-[#00796B] transition-colors">My Dashboard</a></li>@endauth
                </ul>
            </div>
            <div>
                <h4 class="text-gray-900 text-xs font-extrabold uppercase tracking-wider mb-3.5 font-heading">Landlords & Hosts</h4>
                <ul class="space-y-2 text-xs text-gray-600 font-medium">
                    <li><a href="{{ route('listings.create') }}" class="hover:text-[#00796B] transition-colors">Host a Listing</a></li>
                    <li><a href="{{ route('login') }}" class="hover:text-[#00796B] transition-colors">Host Portal</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-gray-900 text-xs font-extrabold uppercase tracking-wider mb-3.5 font-heading">Support & Reach</h4>
                <ul class="space-y-2 text-xs text-gray-600 font-medium">
                    <li class="font-mono">support@rentivo.rent</li>
                    <li>Global Cities & Worldwide Locations</li>
                </ul>
            </div>
        </div>
        <div class="flex flex-col sm:flex-row justify-between items-center gap-3 text-xs text-gray-500 font-medium">
            <p>© {{ date('Y') }} Rentivo Global. All rights reserved.</p>
            <p class="flex items-center gap-1">🌐 Worldwide Room & Apartment Rentals</p>
        </div>
    </div>
</footer>
