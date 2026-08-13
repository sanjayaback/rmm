@extends('layouts.app')
@section('title','Host Dashboard')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8 pb-6 border-b border-white/10">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="badge-amber">Host Dashboard</span>
                <span class="text-xs text-white/50">• Landlord Portal</span>
            </div>
            <h1 class="text-3xl font-extrabold text-white tracking-tight">Welcome, {{ auth()->user()->name }}</h1>
            <p class="text-white/60 text-sm mt-1">Track room performance, student unlocks, and property listings</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('listings.create') }}" class="btn-airbnb !py-2.5 !px-5 text-sm">
                + Post New Room
            </a>
        </div>
    </div>

    <!-- Analytics Stat Cards Grid -->
    <div class="grid grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
        @foreach([
            ['🏠', 'Total Listings',  $stats['total_listings'], 'Published property posts'],
            ['✅', 'Approved',        $stats['approved'],       'Live on public map'],
            ['⏳', 'Pending Review',  $stats['pending'],        'Awaiting admin check'],
            ['🔓', 'Total Unlocks',   $stats['total_unlocks'],  'Contacts revealed to tenants'],
            ['💰', 'Total Earned',    'NPR '.number_format($stats['total_earned']), 'Revenue generated'],
            ['👁', 'Total Views',     number_format($stats['total_views']),        'Marketplace views'],
        ] as [$icon, $label, $value, $desc])
        <div class="card-airbnb p-6 relative overflow-hidden group">
            <div class="flex items-center justify-between mb-3">
                <span class="text-white/60 text-xs font-semibold uppercase tracking-wider">{{ $label }}</span>
                <span class="text-xl">{{ $icon }}</span>
            </div>
            <div class="text-3xl font-extrabold text-white mb-1">{{ $value }}</div>
            <div class="text-white/40 text-xs">{{ $desc }}</div>
        </div>
        @endforeach
    </div>

    <!-- Recent Listings Management Card -->
    <div class="card-airbnb overflow-hidden mb-10">
        <div class="flex items-center justify-between px-6 py-4 border-b border-white/10">
            <h2 class="font-bold text-white text-base">Your Property Listings</h2>
            <a href="{{ route('listings.index') }}" class="text-xs text-[#FF385C] font-semibold hover:underline">Manage All Listings →</a>
        </div>

        @if($listings->count() > 0)
            <div class="divide-y divide-white/[0.06]">
                @foreach($listings as $listing)
                    <div class="flex items-center gap-4 p-5 hover:bg-white/[0.02] transition-colors">
                        <div class="w-14 h-14 rounded-2xl overflow-hidden bg-[#1C1C22] shrink-0 border border-white/10">
                            <img src="{{ $listing->image_url }}" class="w-full h-full object-cover" onerror="this.onerror=null; this.src='/images/room-placeholder.jpg'">
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="font-bold text-white text-sm truncate mb-0.5">{{ $listing->title }}</h4>
                            <p class="text-white/50 text-xs">{{ $listing->area }}, {{ $listing->city }} · NPR {{ number_format($listing->price) }}/mo</p>
                        </div>
                        <div class="text-center hidden sm:block shrink-0 px-3">
                            <div class="font-bold text-white text-sm">{{ $listing->unlocks_count }}</div>
                            <div class="text-white/40 text-[10px] uppercase tracking-wider">Unlocks</div>
                        </div>
                        <div class="shrink-0">{!! $listing->status_badge !!}</div>
                        <div class="flex items-center gap-2 shrink-0">
                            <a href="{{ route('listings.show', $listing) }}" class="btn-secondary !py-1.5 !px-3 text-xs">View</a>
                            <a href="{{ route('listings.edit', $listing) }}" class="btn-ghost !py-1.5 !px-3 text-xs">Edit</a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="p-12 text-center">
                <div class="text-4xl mb-3">🏡</div>
                <p class="text-white/60 text-sm mb-4">You haven't posted any room listings yet.</p>
                <a href="{{ route('listings.create') }}" class="btn-airbnb !py-2.5 !px-5 text-xs">+ Post Your First Room</a>
            </div>
        @endif
    </div>

    <!-- Host Referral Program -->
    <div class="card-airbnb p-6 border-amber-500/20 relative overflow-hidden">
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
            <div>
                <span class="badge-amber mb-2">Host Referral</span>
                <h3 class="text-xl font-extrabold text-white">Invite Landlords & Owners</h3>
                <p class="text-white/60 text-sm mt-1">Earn unlock bonus credits when other landlords join RoomRent with your link.</p>
            </div>
            <div class="flex items-center gap-2 w-full md:w-auto">
                <input type="text" value="{{ auth()->user()->referral_url }}" readonly id="ref-url"
                       class="input-airbnb !py-2 text-xs font-mono flex-1 md:w-72">
                <button onclick="copyRef(this)" class="btn-airbnb !py-2 !px-4 text-xs shrink-0">Copy Link</button>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
function copyRef(btn) {
    navigator.clipboard.writeText(document.getElementById('ref-url').value);
    btn.textContent = '✓ Copied!';
    setTimeout(() => btn.textContent = 'Copy Link', 2000);
}
</script>
@endpush
