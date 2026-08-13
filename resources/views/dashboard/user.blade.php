@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">

    <!-- Header (Matching Spec Image Screen 6 & Screen 11) -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8 pb-6 border-b border-gray-200">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 bg-[#00796B] rounded-full flex items-center justify-center text-white text-2xl font-black shadow-md font-heading">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <div>
                <h1 class="text-2xl sm:text-3xl font-black text-gray-900 font-heading">Welcome back, {{ auth()->user()->name }} 👋</h1>
                <p class="text-xs text-gray-500 font-medium">{{ auth()->user()->email }} • {{ auth()->user()->phone ?? 'No phone set' }}</p>
            </div>
        </div>

        @if(auth()->user()->isOwner() || auth()->user()->isAdmin())
            <a href="{{ route('listings.create') }}" class="btn-teal text-xs font-extrabold shadow-md">
                + Add New Listing
            </a>
        @endif
    </div>

    @if(auth()->user()->isOwner() || auth()->user()->isAdmin())
        <!-- Owner Stat Cards (Matching Spec Image Screen 6) -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-10">
            <div class="card-airbnb p-5 text-center bg-white border border-gray-200">
                <div class="text-3xl font-black text-[#00796B] mb-1 font-heading">{{ $listings->total() }}</div>
                <div class="text-xs font-bold text-gray-600">My Listings</div>
            </div>
            <div class="card-airbnb p-5 text-center bg-white border border-gray-200">
                <div class="text-3xl font-black text-amber-500 mb-1 font-heading">{{ $listings->where('status','pending')->count() }}</div>
                <div class="text-xs font-bold text-gray-600">Pending Review</div>
            </div>
            <div class="card-airbnb p-5 text-center bg-white border border-gray-200">
                <div class="text-3xl font-black text-emerald-600 mb-1 font-heading">{{ $listings->where('status','approved')->count() }}</div>
                <div class="text-xs font-bold text-gray-600">Approved</div>
            </div>
            <div class="card-airbnb p-5 text-center bg-white border border-gray-200">
                <div class="text-3xl font-black text-rose-600 mb-1 font-heading">{{ $listings->where('status','rejected')->count() }}</div>
                <div class="text-xs font-bold text-gray-600">Rejected</div>
            </div>
        </div>

        <!-- My Listings List (Matching Spec Image Screen 6) -->
        <div class="card-airbnb p-6 sm:p-7 mb-10">
            <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-100">
                <h3 class="font-extrabold text-gray-900 text-lg font-heading">My Listings</h3>
                <a href="{{ route('listings.create') }}" class="text-xs text-[#00796B] font-extrabold hover:underline">+ New Room</a>
            </div>

            @if($listings->count() > 0)
                <div class="space-y-4">
                    @foreach($listings as $listing)
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-4 rounded-2xl bg-gray-50 border border-gray-200 hover:bg-white hover:shadow-md transition-all">
                            <div class="flex items-center gap-4">
                                <img src="{{ $listing->image_url }}" alt="{{ $listing->title }}"
                                     class="w-16 h-16 rounded-xl object-cover border border-gray-200 shrink-0"
                                     onerror="this.onerror=null; this.src='/images/room-placeholder.jpg'">
                                <div>
                                    <h4 class="font-bold text-gray-900 text-sm font-heading">{{ $listing->title }}</h4>
                                    <div class="text-xs text-gray-500">📍 {{ $listing->area }}, {{ $listing->city }}</div>
                                    <div class="text-xs text-[#00796B] font-extrabold font-heading mt-0.5">Rs. {{ number_format($listing->price) }} / month</div>
                                </div>
                            </div>
                            <div class="flex items-center gap-3 shrink-0">
                                @if($listing->status === 'approved')
                                    <span class="badge-emerald">Approved</span>
                                @elseif($listing->status === 'pending')
                                    <span class="badge-amber">Pending</span>
                                @else
                                    <span class="badge-red">Rejected</span>
                                @endif
                                <a href="{{ route('listings.edit', $listing) }}" class="btn-secondary !py-1.5 !px-3 !text-xs">Edit</a>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-6">{{ $listings->links('partials.pagination') }}</div>
            @else
                <div class="text-center py-10 text-gray-500 text-xs">
                    You haven't created any room listings yet.
                </div>
            @endif
        </div>
    @endif

    <!-- Profile Options Menu (Matching Spec Image Screen 11) -->
    <div class="card-airbnb p-6 sm:p-7 max-w-2xl">
        <h3 class="font-extrabold text-gray-900 text-lg mb-4 pb-3 border-b border-gray-100 font-heading">Account & Profile Settings</h3>
        <div class="divide-y divide-gray-100 text-xs font-semibold">
            <a href="{{ route('profile.edit') }}" class="flex items-center justify-between py-3.5 text-gray-700 hover:text-[#00796B] transition-colors">
                <span class="flex items-center gap-2.5">👤 My Profile</span>
                <span>›</span>
            </a>
            <a href="{{ route('unlocks.history') }}" class="flex flex-col sm:flex-row sm:items-center justify-between py-3.5 text-gray-700 hover:text-[#00796B] transition-colors">
                <span class="flex items-center gap-2.5">🔓 My Unlocks</span>
                <span>›</span>
            </a>
            <a href="{{ route('profile.edit') }}" class="flex items-center justify-between py-3.5 text-gray-700 hover:text-[#00796B] transition-colors">
                <span class="flex items-center gap-2.5">🔒 Change Password</span>
                <span>›</span>
            </a>
            <form method="POST" action="{{ route('logout') }}" class="pt-3">
                @csrf
                <button type="submit" class="text-rose-600 font-bold hover:underline">
                    🚪 Logout Account
                </button>
            </form>
        </div>
    </div>

</div>
@endsection
