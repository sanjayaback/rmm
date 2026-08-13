@extends('layouts.app')
@section('title','My Unlocked Rooms')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="mb-8">
        <h1 class="font-display text-3xl font-bold text-white">My Unlocked Rooms</h1>
        <p class="text-white/50 mt-1">All listings you've paid to access</p>
    </div>

    @if($unlocks->count() > 0)
    <div class="space-y-4">
        @foreach($unlocks as $unlock)
        <div class="bg-[#111113] border border-white/[0.08] rounded-2xl p-5 flex flex-col sm:flex-row gap-4">
            <div class="w-full sm:w-28 h-24 rounded-xl overflow-hidden bg-white/5 flex-shrink-0">
                <img src="{{ $unlock->listing->image_url }}" class="w-full h-full object-cover" onerror="this.onerror=null; this.src='/images/room-placeholder.jpg'">
            </div>
            <div class="flex-1 min-w-0">
                <h3 class="font-semibold text-white text-sm mb-0.5">{{ $unlock->listing->title }}</h3>
                <p class="text-white/40 text-xs mb-3">{{ $unlock->listing->area }}, {{ $unlock->listing->city }}</p>
                <div class="grid grid-cols-2 gap-2 text-xs mb-2">
                    <div class="bg-white/5 rounded-lg px-3 py-2">
                        <div class="text-white/40 mb-0.5">📞 Phone</div>
                        <a href="tel:{{ $unlock->listing->phone }}" class="text-orange-400 font-semibold">{{ $unlock->listing->phone }}</a>
                    </div>
                    <div class="bg-white/5 rounded-lg px-3 py-2">
                        <div class="text-white/40 mb-0.5">📍 Address</div>
                        <div class="text-white text-xs leading-tight">{{ $unlock->listing->exact_address }}</div>
                    </div>
                </div>
                <div class="flex flex-wrap gap-3 text-xs text-white/30">
                    <span>Paid: NPR {{ number_format($unlock->amount_paid) }}</span>
                    <span>·</span><span>{{ $unlock->paid_at?->format('M d, Y') }}</span>
                    <span>·</span><span class="capitalize">via {{ $unlock->payment_method }}</span>
                </div>
            </div>
            <div class="flex-shrink-0">
                <a href="{{ route('listings.show', $unlock->listing) }}"
                   class="inline-flex border border-white/10 hover:border-orange-500/40 text-white/60 hover:text-orange-400 text-xs px-3 py-2 rounded-xl transition-all">View</a>
            </div>
        </div>
        @endforeach
    </div>
    <div class="mt-6">{{ $unlocks->links('partials.pagination') }}</div>

    @else
    <div class="bg-[#111113] border border-white/[0.08] rounded-2xl p-16 text-center">
        <div class="text-5xl mb-4">🔓</div>
        <h3 class="font-display text-xl font-semibold text-white mb-2">No unlocks yet</h3>
        <p class="text-white/50 mb-6 text-sm">Browse listings and unlock the ones you're interested in.</p>
        <a href="{{ route('listings.browse') }}" class="inline-flex bg-orange-500 hover:bg-orange-600 text-white font-semibold px-5 py-2.5 rounded-xl transition-all text-sm">Browse Rooms</a>
    </div>
    @endif
</div>
@endsection
