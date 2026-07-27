@extends('layouts.app')
@section('title','Manage Listings')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="flex items-center justify-between mb-8">
        <div>
            <span class="bg-orange-500/15 text-orange-400 text-xs px-2.5 py-0.5 rounded-full border border-orange-500/30">Admin</span>
            <h1 class="font-display text-3xl font-bold text-white mt-2">Manage Listings</h1>
            <p class="text-white/50 mt-1">{{ $listings->total() }} total listings</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="border border-white/10 text-white/60 hover:text-white px-4 py-2.5 rounded-xl text-sm transition-all">← Dashboard</a>
    </div>

    <form method="GET" action="{{ route('admin.listings.index') }}" class="flex flex-wrap gap-3 mb-6">
        <select name="status" onchange="this.form.submit()" class="bg-white/5 border border-white/10 rounded-xl px-4 py-2.5 text-white text-sm focus:outline-none focus:border-orange-500/60">
            <option value="">All Status</option>
            @foreach(['pending','approved','rejected'] as $s)
                <option value="{{ $s }}" {{ request('status')==$s?'selected':'' }}>{{ ucfirst($s) }}</option>
            @endforeach
        </select>
        <div class="flex gap-2 flex-1">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search listings..."
                   class="bg-white/5 border border-white/10 rounded-xl px-4 py-2.5 text-white text-sm flex-1 focus:outline-none focus:border-orange-500/60 placeholder-white/25">
            <button type="submit" class="bg-orange-500 hover:bg-orange-600 text-white text-sm px-4 py-2.5 rounded-xl transition-all">Search</button>
            @if(request()->hasAny(['status','search']))
                <a href="{{ route('admin.listings.index') }}" class="border border-white/10 text-white/60 hover:text-white px-4 py-2.5 rounded-xl text-sm transition-all">Reset</a>
            @endif
        </div>
    </form>

    <div class="bg-[#111113] border border-white/[0.08] rounded-2xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="border-b border-white/[0.08]">
                    <tr class="text-white/40 text-xs uppercase tracking-wider">
                        <th class="px-5 py-3.5 text-left">Listing</th>
                        <th class="px-4 py-3.5 text-left hidden md:table-cell">Owner</th>
                        <th class="px-4 py-3.5 text-right hidden sm:table-cell">Price</th>
                        <th class="px-4 py-3.5 text-center">Status</th>
                        <th class="px-4 py-3.5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/[0.04]">
                    @forelse($listings as $listing)
                    <tr class="hover:bg-white/[0.02] transition-colors">
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg overflow-hidden bg-white/5 flex-shrink-0">
                                    <img src="{{ $listing->image_url }}" class="w-full h-full object-cover" onerror="this.src='/images/room-placeholder.jpg'">
                                </div>
                                <div class="min-w-0">
                                    <div class="text-white font-medium text-sm truncate max-w-[200px]">{{ $listing->title }}</div>
                                    <div class="text-white/30 text-xs">{{ $listing->created_at->format('M d, Y') }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-4 hidden md:table-cell">
                            <div class="text-white/70 text-sm">{{ $listing->owner->name }}</div>
                            <div class="text-white/30 text-xs">{{ $listing->owner->email }}</div>
                        </td>
                        <td class="px-4 py-4 text-right hidden sm:table-cell">
                            <span class="text-orange-400 font-semibold">NPR {{ number_format($listing->price) }}</span>
                        </td>
                        <td class="px-4 py-4 text-center">{!! $listing->status_badge !!}</td>
                        <td class="px-4 py-4 text-right">
                            <a href="{{ route('admin.listings.show', $listing) }}"
                               class="border border-white/10 hover:border-orange-500/30 text-white/40 hover:text-orange-400 text-xs px-3 py-1.5 rounded-lg transition-all">Review →</a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-5 py-10 text-center text-white/30 text-sm">No listings found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-6">{{ $listings->links('partials.pagination') }}</div>
</div>
@endsection
