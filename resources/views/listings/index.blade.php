@extends('layouts.app')
@section('title','My Listings')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="font-display text-3xl font-bold text-white">My Listings</h1>
            <p class="text-white/50 mt-1">{{ $listings->total() }} total</p>
        </div>
        <a href="{{ route('listings.create') }}"
           class="inline-flex items-center gap-2 bg-orange-500 hover:bg-orange-600 text-white font-semibold px-5 py-2.5 rounded-xl transition-all text-sm">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Post New Room
        </a>
    </div>

    @if($listings->count() > 0)
    <div class="space-y-4">
        @foreach($listings as $listing)
        <div class="bg-[#111113] border border-white/[0.08] rounded-2xl p-5 flex flex-col sm:flex-row gap-4">
            <div class="w-full sm:w-32 h-24 rounded-xl overflow-hidden bg-white/5 flex-shrink-0">
                <img src="{{ $listing->image_url }}" class="w-full h-full object-cover" onerror="this.src='/images/room-placeholder.jpg'">
            </div>
            <div class="flex-1 min-w-0">
                <div class="flex flex-wrap items-center gap-2 mb-1">
                    <h3 class="font-semibold text-white truncate">{{ $listing->title }}</h3>
                    {!! $listing->status_badge !!}
                    @if(!$listing->is_available)<span class="bg-red-500/15 text-red-400 text-xs px-2.5 py-0.5 rounded-full border border-red-500/30">Unavailable</span>@endif
                </div>
                <p class="text-white/50 text-sm mb-2">{{ $listing->area }}, {{ $listing->city }} · NPR {{ number_format($listing->price) }}/mo</p>
                <div class="flex flex-wrap gap-4 text-xs text-white/40">
                    <span>👁 {{ $listing->views }} views</span>
                    <span>🔓 {{ $listing->unlocks_count }} unlocks</span>
                    <span>📅 {{ $listing->created_at->format('M d, Y') }}</span>
                </div>
                @if($listing->status === 'rejected' && $listing->rejection_reason)
                <div class="mt-2 text-xs text-red-400 bg-red-500/10 rounded-lg px-3 py-1.5 border border-red-500/20">
                    Rejected: {{ $listing->rejection_reason }}
                </div>
                @endif
            </div>
            <div class="flex sm:flex-col gap-2 flex-shrink-0">
                <a href="{{ route('listings.show', $listing) }}"
                   class="border border-white/10 hover:border-orange-500/40 text-white/60 hover:text-orange-400 text-xs px-3 py-2 rounded-xl transition-all">View</a>
                <a href="{{ route('listings.edit', $listing) }}"
                   class="border border-white/10 hover:border-orange-500/40 text-white/60 hover:text-orange-400 text-xs px-3 py-2 rounded-xl transition-all">Edit</a>
                <form action="{{ route('listings.destroy', $listing) }}" method="POST"
                      onsubmit="return confirm('Delete this listing permanently?')">
                    @csrf @method('DELETE')
                    <button type="submit"
                            class="w-full bg-red-500/10 hover:bg-red-500/20 border border-red-500/30 text-red-400 text-xs px-3 py-2 rounded-xl transition-all">Delete</button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
    <div class="mt-6">{{ $listings->links('partials.pagination') }}</div>

    @else
    <div class="bg-[#111113] border border-white/[0.08] rounded-2xl p-16 text-center">
        <div class="text-5xl mb-4">🏠</div>
        <h3 class="font-display text-xl font-semibold text-white mb-2">No listings yet</h3>
        <p class="text-white/50 mb-6 text-sm">Post your first room and start getting inquiries.</p>
        <a href="{{ route('listings.create') }}"
           class="inline-flex bg-orange-500 hover:bg-orange-600 text-white font-semibold px-6 py-3 rounded-xl transition-all">Post Your First Room</a>
    </div>
    @endif
</div>
@endsection
