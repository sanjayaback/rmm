@extends('layouts.app')
@section('title','User Detail')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="mb-6">
        <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-2 text-white/50 hover:text-orange-400 text-sm mb-4 transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>All Users
        </a>
        <h1 class="font-display text-2xl font-bold text-white">User Detail</h1>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Profile sidebar --}}
        <div class="space-y-4">
            <div class="bg-[#111113] border border-white/[0.08] rounded-2xl p-6 text-center">
                <div class="w-16 h-16 rounded-full flex items-center justify-center text-white text-2xl font-bold mx-auto mb-3
                    {{ $user->isAdmin()?'bg-orange-500':($user->isOwner()?'bg-blue-500':'bg-white/15') }}">
                    {{ substr($user->name,0,1) }}
                </div>
                <h3 class="font-display font-semibold text-white">{{ $user->name }}</h3>
                <p class="text-white/40 text-sm">{{ $user->email }}</p>
                <div class="mt-2">
                    <span class="{{ $user->isAdmin()?'bg-orange-500/15 text-orange-400 border-orange-500/30':($user->isOwner()?'bg-green-500/15 text-green-400 border-green-500/30':'bg-yellow-500/15 text-yellow-400 border-yellow-500/30') }} text-xs px-2.5 py-0.5 rounded-full border font-medium">
                        {{ ucfirst($user->role) }}
                    </span>
                </div>
            </div>

            @if($user->id !== auth()->id())
            <div class="bg-[#111113] border border-white/[0.08] rounded-2xl p-4">
                <h4 class="text-white/50 text-xs font-semibold uppercase tracking-wider mb-3">Change Role</h4>
                <form action="{{ route('admin.users.role', $user) }}" method="POST" class="flex gap-2">
                    @csrf @method('PATCH')
                    <select name="role" class="bg-white/5 border border-white/10 rounded-xl px-3 py-2 text-white text-sm flex-1 focus:outline-none focus:border-orange-500/60">
                        @foreach(['admin','owner','user'] as $r)
                            <option value="{{ $r }}" {{ $user->role==$r?'selected':'' }}>{{ ucfirst($r) }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="bg-orange-500 hover:bg-orange-600 text-white text-sm px-3 py-2 rounded-xl transition-all">Save</button>
                </form>
            </div>

            <form action="{{ route('admin.users.toggle-status', $user) }}" method="POST">
                @csrf @method('PATCH')
                <button type="submit"
                        class="{{ $user->is_active ? 'bg-red-500/10 hover:bg-red-500/20 border-red-500/30 text-red-400' : 'bg-green-500/10 hover:bg-green-500/20 border-green-500/30 text-green-400' }} w-full border font-medium px-4 py-2.5 rounded-xl transition-all text-sm">
                    {{ $user->is_active ? '🚫 Deactivate Account' : '✓ Activate Account' }}
                </button>
            </form>
            @endif

            <div class="bg-[#111113] border border-white/[0.08] rounded-xl p-4 text-xs text-white/30 space-y-1.5">
                <div>Phone: {{ $user->phone ?? 'N/A' }}</div>
                <div>Joined: {{ $user->created_at->format('M d, Y') }}</div>
                <div>Referral: <code class="text-orange-400">{{ $user->referral_code }}</code></div>
                <div>Status: {{ $user->is_active ? 'Active' : 'Inactive' }}</div>
            </div>
        </div>

        {{-- Listings + unlocks --}}
        <div class="lg:col-span-2 space-y-5">
            <div class="bg-[#111113] border border-white/[0.08] rounded-2xl overflow-hidden">
                <div class="px-5 py-3.5 border-b border-white/[0.06]">
                    <h3 class="font-semibold text-white text-sm">Listings ({{ $user->listings->count() }})</h3>
                </div>
                @if($user->listings->count() > 0)
                <div class="divide-y divide-white/[0.04]">
                    @foreach($user->listings as $listing)
                    <div class="flex items-center justify-between px-5 py-3">
                        <div>
                            <div class="text-white/70 text-sm truncate max-w-[200px]">{{ $listing->title }}</div>
                            <div class="text-white/30 text-xs">NPR {{ number_format($listing->price) }}/mo</div>
                        </div>
                        <div class="flex items-center gap-2">
                            {!! $listing->status_badge !!}
                            <a href="{{ route('admin.listings.show', $listing) }}" class="text-orange-400 text-xs hover:underline">→</a>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="px-5 py-6 text-center text-white/30 text-sm">No listings</div>
                @endif
            </div>

            <div class="bg-[#111113] border border-white/[0.08] rounded-2xl overflow-hidden">
                <div class="px-5 py-3.5 border-b border-white/[0.06]">
                    <h3 class="font-semibold text-white text-sm">Unlock History ({{ $user->unlocks->count() }})</h3>
                </div>
                @if($user->unlocks->count() > 0)
                <div class="divide-y divide-white/[0.04]">
                    @foreach($user->unlocks->take(10) as $unlock)
                    <div class="flex items-center justify-between px-5 py-3">
                        <div>
                            <div class="text-white/70 text-sm truncate max-w-[200px]">{{ $unlock->listing->title ?? 'Deleted' }}</div>
                            <div class="text-white/30 text-xs">{{ $unlock->created_at->format('M d, Y') }}</div>
                        </div>
                        <div class="text-right">
                            <div class="text-{{ $unlock->isCompleted()?'green':'yellow' }}-400 text-xs font-medium">{{ ucfirst($unlock->payment_status) }}</div>
                            <div class="text-white/40 text-xs">NPR {{ number_format($unlock->amount_paid) }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="px-5 py-6 text-center text-white/30 text-sm">No unlocks</div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
