@extends('layouts.app')
@section('title','Admin Dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="mb-8">
        <span class="bg-orange-500/15 text-orange-400 text-xs px-2.5 py-0.5 rounded-full border border-orange-500/30 font-medium">Admin</span>
        <h1 class="font-display text-3xl font-bold text-white mt-2">Control Panel</h1>
        <p class="text-white/50 mt-1">Platform overview</p>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        @foreach([
            ['👥','Total Users',$stats['total_users'],'border-blue-500/20'],
            ['🏠','Total Listings',$stats['total_listings'],'border-orange-500/20'],
            ['⏳','Pending Review',$stats['pending_listings'],'border-yellow-500/20'],
            ['💰','Total Revenue','NPR '.number_format($stats['total_revenue']),'border-green-500/20'],
        ] as [$icon,$label,$value,$border])
        <div class="bg-[#111113] border {{ $border }} rounded-2xl p-5">
            <div class="text-2xl mb-2">{{ $icon }}</div>
            <div class="font-display text-2xl font-bold text-white mb-0.5">{{ $value }}</div>
            <div class="text-white/40 text-sm">{{ $label }}</div>
        </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-8">
        <a href="{{ route('admin.listings.index', ['status'=>'pending']) }}"
           class="bg-[#111113] border border-yellow-500/20 hover:border-yellow-500/50 rounded-2xl p-5 transition-colors">
            <div class="flex items-center justify-between">
                <div><div class="font-display text-xl font-bold text-yellow-400">{{ $stats['pending_listings'] }}</div><div class="text-white/50 text-sm">Pending Approvals</div></div>
                <span class="text-3xl">⏳</span>
            </div>
        </a>
        <a href="{{ route('admin.users.index') }}"
           class="bg-[#111113] border border-white/[0.08] hover:border-orange-500/30 rounded-2xl p-5 transition-colors">
            <div class="flex items-center justify-between">
                <div><div class="font-display text-xl font-bold text-white">{{ $stats['new_users_today'] }}</div><div class="text-white/50 text-sm">New Users Today</div></div>
                <span class="text-3xl">👤</span>
            </div>
        </a>
        <a href="{{ route('admin.payments.index') }}"
           class="bg-[#111113] border border-white/[0.08] hover:border-orange-500/30 rounded-2xl p-5 transition-colors">
            <div class="flex items-center justify-between">
                <div><div class="font-display text-xl font-bold text-green-400">{{ $stats['unlocks_today'] }}</div><div class="text-white/50 text-sm">Unlocks Today</div></div>
                <span class="text-3xl">🔓</span>
            </div>
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-[#111113] border border-white/[0.08] rounded-2xl overflow-hidden">
            <div class="flex items-center justify-between px-5 py-4 border-b border-white/[0.06]">
                <h2 class="font-display font-semibold text-white">Recent Listings</h2>
                <a href="{{ route('admin.listings.index') }}" class="text-orange-400 text-sm hover:text-orange-300 transition-colors">View all →</a>
            </div>
            @foreach($recentListings as $listing)
            <div class="flex items-center gap-3 px-5 py-3.5 hover:bg-white/[0.02] transition-colors border-b border-white/[0.04]">
                <div class="w-10 h-10 rounded-lg overflow-hidden bg-white/5 flex-shrink-0">
                    <img src="{{ $listing->image_url }}" class="w-full h-full object-cover" onerror="this.src='/images/room-placeholder.jpg'">
                </div>
                <div class="flex-1 min-w-0">
                    <div class="text-white text-sm font-medium truncate">{{ $listing->title }}</div>
                    <div class="text-white/30 text-xs">by {{ $listing->owner->name }}</div>
                </div>
                <div class="flex items-center gap-2 flex-shrink-0">
                    {!! $listing->status_badge !!}
                    <a href="{{ route('admin.listings.show', $listing) }}" class="text-white/30 hover:text-orange-400 transition-colors text-xs">→</a>
                </div>
            </div>
            @endforeach
        </div>

        <div class="bg-[#111113] border border-white/[0.08] rounded-2xl overflow-hidden">
            <div class="flex items-center justify-between px-5 py-4 border-b border-white/[0.06]">
                <h2 class="font-display font-semibold text-white">Recent Payments</h2>
                <a href="{{ route('admin.payments.index') }}" class="text-orange-400 text-sm hover:text-orange-300 transition-colors">View all →</a>
            </div>
            @forelse($recentUnlocks as $unlock)
            <div class="flex items-center gap-3 px-5 py-3.5 hover:bg-white/[0.02] transition-colors border-b border-white/[0.04]">
                <div class="w-9 h-9 bg-green-500/15 rounded-full flex items-center justify-center flex-shrink-0">
                    <span class="text-green-400 text-sm font-bold">₨</span>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="text-white text-sm font-medium">{{ $unlock->user->name }}</div>
                    <div class="text-white/30 text-xs truncate">{{ $unlock->listing->title ?? 'Deleted' }}</div>
                </div>
                <div class="text-right flex-shrink-0">
                    <div class="text-green-400 font-semibold text-sm">NPR {{ number_format($unlock->amount_paid) }}</div>
                    <div class="text-white/30 text-xs">{{ $unlock->paid_at?->diffForHumans() }}</div>
                </div>
            </div>
            @empty
            <div class="px-5 py-8 text-center text-white/30 text-sm">No payments yet</div>
            @endforelse
        </div>
    </div>
</div>
@endsection
