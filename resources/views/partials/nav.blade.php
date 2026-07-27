<nav class="sticky top-0 z-50 border-b border-gray-200/80 bg-white/95 backdrop-blur-xl transition-all shadow-sm" x-data="{ mobileOpen: false }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16 md:h-20 gap-2 sm:gap-4">

            <!-- Brand Logo -->
            <a href="{{ route('home') }}" class="flex items-center gap-2 shrink-0 group">
                <div class="w-9 h-9 sm:w-10 sm:h-10 bg-[#00796B] rounded-2xl flex items-center justify-center shadow-md shadow-[#00796B]/25 group-hover:scale-105 transition-transform">
                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/></svg>
                </div>
                <div class="flex flex-col">
                    <span class="text-lg sm:text-2xl font-black tracking-tight text-gray-900 font-heading leading-none">Room<span class="text-[#00796B]">Rent</span></span>
                    <span class="text-[9px] sm:text-[10px] text-gray-500 font-medium hidden sm:inline-block">Find your perfect room</span>
                </div>
            </a>

            <!-- Responsive Active Search Bar (Fixed Wide Search on Mobile & Desktop) -->
            <form method="GET" action="{{ route('listings.browse') }}" class="flex-1 max-w-md flex items-center bg-gray-50 border border-gray-200 hover:border-[#00796B]/50 focus-within:border-[#00796B] rounded-full py-1 px-2.5 sm:px-3 shadow-sm text-xs font-bold transition-all ml-1 sm:ml-0">
                <svg class="w-4 h-4 text-[#00796B] shrink-0 ml-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Search rooms, areas or landmarks..."
                       class="w-full bg-transparent border-none py-1.5 px-2 text-[11px] sm:text-xs text-gray-900 focus:outline-none focus:ring-0 placeholder-gray-400 font-medium">
                <button type="submit" class="w-6 h-6 sm:w-7 sm:h-7 bg-[#00796B] hover:bg-[#00695C] rounded-full flex items-center justify-center text-white shadow-sm shrink-0 transition-colors cursor-pointer">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </button>
            </form>

            <!-- Right Actions (Hidden on Mobile, Log In / Profile is in Sticky Bottom Nav) -->
            <div class="hidden sm:flex items-center gap-2 shrink-0">
                @auth
                    @if(auth()->user()->isOwner() || auth()->user()->isAdmin())
                        <a href="{{ route('listings.create') }}" class="hidden lg:inline-flex btn-teal !py-2 !px-4 !text-xs">
                            + Add New Listing
                        </a>
                    @endif

                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="hidden md:inline-flex text-xs font-bold bg-amber-50 hover:bg-amber-100 text-amber-700 border border-amber-200 px-3 py-1.5 rounded-full transition-all">
                            ⚡ Admin
                        </a>
                    @endif

                    <!-- User Pill Dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center gap-1.5 bg-gray-50 hover:bg-gray-100 border border-gray-200 rounded-full p-1 pl-2.5 transition-all shadow-sm">
                            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h16"/></svg>
                            <div class="w-7 h-7 bg-[#00796B] rounded-full flex items-center justify-center text-white text-xs font-extrabold shadow-sm">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                        </button>
                        <div x-show="open" @click.outside="open = false" x-cloak
                             class="absolute right-0 mt-2 w-56 bg-white border border-gray-200 rounded-2xl shadow-xl overflow-hidden z-50 py-2"
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100">
                            <div class="px-4 py-2 border-b border-gray-100 mb-1">
                                <p class="text-xs font-extrabold text-gray-900 truncate">{{ auth()->user()->name }}</p>
                                <p class="text-[11px] text-gray-500 truncate capitalize">{{ auth()->user()->role }} Account</p>
                            </div>
                            <a href="{{ route('dashboard') }}" class="block px-4 py-2.5 text-xs font-semibold text-gray-700 hover:bg-teal-50 hover:text-[#00796B] transition-colors">Dashboard</a>
                            <a href="{{ route('unlocks.history') }}" class="block px-4 py-2.5 text-xs font-semibold text-gray-700 hover:bg-teal-50 hover:text-[#00796B] transition-colors">My Unlocks</a>
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2.5 text-xs font-semibold text-gray-700 hover:bg-teal-50 hover:text-[#00796B] transition-colors">Profile Settings</a>
                            <div class="border-t border-gray-100 my-1"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2.5 text-xs font-semibold text-rose-600 hover:bg-rose-50 transition-colors">Log Out</button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="text-xs text-gray-700 hover:text-[#00796B] font-bold px-2.5 py-1.5 transition-all">Log In</a>
                    <a href="{{ route('register') }}" class="btn-teal !py-1.5 !px-3.5 !text-xs">Register</a>
                @endauth
            </div>

        </div>
    </div>
</nav>
