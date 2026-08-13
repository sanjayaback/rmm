@extends('layouts.app')
@section('title','Review Listing')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="mb-6">
        <a href="{{ route('admin.listings.index') }}" class="inline-flex items-center gap-2 text-white/50 hover:text-orange-400 text-sm mb-4 transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>All Listings
        </a>
        <div class="flex items-center gap-3">
            <h1 class="font-display text-2xl font-bold text-white">Review Listing</h1>
            {!! $listing->status_badge !!}
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-5">
            <div class="rounded-2xl overflow-hidden h-60 bg-white/5">
                <img src="{{ $listing->image_url }}" class="w-full h-full object-cover" onerror="this.onerror=null; this.src='/images/room-placeholder.jpg'">
            </div>

            <div class="bg-[#111113] border border-white/[0.08] rounded-2xl p-5 space-y-4">
                <h2 class="font-display text-lg font-semibold text-white">{{ $listing->title }}</h2>
                <p class="text-white/60 text-sm leading-relaxed">{{ $listing->description }}</p>

                <div class="grid grid-cols-2 gap-3 text-sm">
                    @foreach([
                        ['Price','NPR '.number_format($listing->price).'/mo'],
                        ['Unlock Fee','NPR '.number_format($listing->unlock_fee)],
                        ['Room Type',$listing->room_type_label],
                        ['Bedrooms',$listing->bedrooms],
                        ['Bathrooms',$listing->bathrooms],
                        ['City',$listing->city],
                        ['Area',$listing->area],
                        ['Views',$listing->views],
                    ] as [$label,$value])
                    <div class="bg-white/5 rounded-xl p-3">
                        <div class="text-white/40 text-xs mb-0.5">{{ $label }}</div>
                        <div class="text-white font-medium">{{ $value }}</div>
                    </div>
                    @endforeach
                </div>

                <div class="bg-red-500/10 border border-red-500/20 rounded-xl p-3">
                    <div class="text-white/40 text-xs mb-0.5">📞 Phone (hidden from public)</div>
                    <div class="text-white font-medium">{{ $listing->phone }}</div>
                </div>
                <div class="bg-red-500/10 border border-red-500/20 rounded-xl p-3">
                    <div class="text-white/40 text-xs mb-0.5">📍 Exact Address (hidden from public)</div>
                    <div class="text-white font-medium">{{ $listing->exact_address }}</div>
                </div>

                @if($listing->amenities)
                <div class="flex flex-wrap gap-2">
                    @foreach($listing->amenities as $a)
                        <span class="bg-white/5 border border-white/10 text-white/60 text-xs px-2.5 py-1 rounded-full">{{ $a }}</span>
                    @endforeach
                </div>
                @endif
            </div>
        </div>

        <div class="space-y-4" x-data="{ showReject: false }">
            {{-- Owner --}}
            <div class="bg-[#111113] border border-white/[0.08] rounded-2xl p-5">
                <h3 class="font-semibold text-white text-sm mb-3">Owner</h3>
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-orange-500 rounded-full flex items-center justify-center text-white text-sm font-bold flex-shrink-0">{{ substr($listing->owner->name,0,1) }}</div>
                    <div>
                        <div class="text-white text-sm font-medium">{{ $listing->owner->name }}</div>
                        <div class="text-white/40 text-xs">{{ $listing->owner->email }}</div>
                    </div>
                </div>
                <div class="mt-3 text-xs text-white/30 space-y-0.5">
                    <div>Phone: {{ $listing->owner->phone ?? 'N/A' }}</div>
                    <div>Since: {{ $listing->owner->created_at->format('M Y') }}</div>
                </div>
            </div>

            @if($listing->status !== 'approved')
            <form action="{{ route('admin.listings.approve', $listing) }}" method="POST">
                @csrf
                <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white font-semibold py-3 rounded-xl transition-all">✓ Approve Listing</button>
            </form>
            @endif

            @if($listing->status !== 'rejected')
            <div>
                <button @click="showReject = !showReject"
                        class="w-full bg-red-500/10 hover:bg-red-500/20 border border-red-500/30 text-red-400 font-medium py-3 rounded-xl transition-all">✕ Reject Listing</button>
                <div x-show="showReject" x-cloak class="mt-3">
                    <form action="{{ route('admin.listings.reject', $listing) }}" method="POST" class="space-y-2">
                        @csrf
                        <textarea name="reason" rows="3" required minlength="10"
                                  class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white text-sm placeholder-white/25 focus:outline-none focus:border-red-500/60 transition-all"
                                  placeholder="Reason for rejection (required)..."></textarea>
                        <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white font-semibold py-2.5 rounded-xl transition-all text-sm">Confirm Rejection</button>
                    </form>
                </div>
            </div>
            @endif

            <form action="{{ route('admin.listings.delete', $listing) }}" method="POST"
                  onsubmit="return confirm('Permanently delete?')">
                @csrf @method('DELETE')
                <button type="submit" class="w-full text-center text-white/25 hover:text-red-400 text-xs py-2 transition-colors">Permanently Delete</button>
            </form>

            <div class="bg-[#111113] border border-white/[0.08] rounded-xl p-4 text-xs text-white/30 space-y-1">
                <div>Coords: {{ $listing->lat }}, {{ $listing->lng }}</div>
                <div>Posted: {{ $listing->created_at->format('M d, Y H:i') }}</div>
            </div>
        </div>
    </div>
</div>
@endsection
