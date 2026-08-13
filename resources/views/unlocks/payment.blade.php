@extends('layouts.app')
@section('title','Unlock Room Contact Details')

@section('content')
<div class="max-w-lg mx-auto px-4 sm:px-6 py-12">

    <!-- Header -->
    <div class="text-center mb-8">
        <div class="w-16 h-16 bg-[#FF385C]/10 border border-[#FF385C]/30 rounded-3xl flex items-center justify-center mx-auto mb-4 text-2xl text-[#FF385C]">
            🔓
        </div>
        <h1 class="text-2xl font-extrabold text-white tracking-tight">Unlock Listing Details</h1>
        <p class="text-white/60 text-xs mt-1">One-time payment unlocks landlord phone number & exact location</p>
    </div>

    <!-- Room Summary Card -->
    <div class="card-airbnb p-5 mb-6 flex gap-4 items-center">
        <div class="w-20 h-20 rounded-2xl overflow-hidden bg-[#1C1C22] shrink-0 border border-white/10">
            <img src="{{ $listing->image_url }}" class="w-full h-full object-cover" onerror="this.onerror=null; this.src='/images/room-placeholder.jpg'">
        </div>
        <div class="flex-1 min-w-0">
            <span class="badge-rose mb-1">{{ $listing->room_type_label }}</span>
            <h3 class="font-bold text-white text-sm truncate">{{ $listing->title }}</h3>
            <p class="text-white/50 text-xs">{{ $listing->area }}, {{ $listing->city }}</p>
            <p class="text-[#FF385C] font-extrabold text-sm mt-1">NPR {{ number_format($listing->price) }} <span class="text-white/40 font-normal text-xs">/ mo</span></p>
        </div>
    </div>

    <!-- Checkout Card -->
    <div class="card-airbnb p-6 sm:p-8 shadow-2xl">
        <h3 class="text-white font-bold text-sm mb-4">Included in this unlock:</h3>
        <div class="space-y-3 mb-6">
            <div class="flex items-center gap-3 text-xs text-white/80">
                <span class="w-5 h-5 bg-emerald-500/15 text-emerald-400 rounded-full flex items-center justify-center font-bold text-xs shrink-0">✓</span>
                <span>Direct landlord phone number for calls & WhatsApp</span>
            </div>
            <div class="flex items-center gap-3 text-xs text-white/80">
                <span class="w-5 h-5 bg-emerald-500/15 text-emerald-400 rounded-full flex items-center justify-center font-bold text-xs shrink-0">✓</span>
                <span>Exact street address & precise map coordinates</span>
            </div>
            <div class="flex items-center gap-3 text-xs text-white/80">
                <span class="w-5 h-5 bg-emerald-500/15 text-emerald-400 rounded-full flex items-center justify-center font-bold text-xs shrink-0">✓</span>
                <span>Lifetime access — no recurring subscription fees</span>
            </div>
        </div>

        <div class="border-t border-white/10 my-6"></div>

        <div class="flex justify-between items-center mb-6">
            <div>
                <span class="text-white font-bold text-sm block">Total Unlock Fee</span>
                <span class="text-white/40 text-xs">Includes all taxes & fees</span>
            </div>
            <span class="text-2xl font-extrabold text-[#FF385C]">NPR {{ number_format($listing->unlock_fee) }}</span>
        </div>

        @if($isFakeMode)
            <div class="bg-amber-500/10 border border-amber-500/30 rounded-xl p-3 mb-5 text-xs text-amber-300">
                ⚡ <strong>Development Test Mode Active</strong> — Simulated payment (no money will be deducted)
            </div>
            <form action="{{ route('unlocks.process', [$listing, $unlock]) }}" method="POST">
                @csrf
                <input type="hidden" name="token" value="fake-dev-token-{{ $unlock->id }}">
                <button type="submit" class="btn-airbnb w-full !py-3.5 text-sm font-bold shadow-xl">
                    💳 Complete Test Payment of NPR {{ number_format($listing->unlock_fee) }}
                </button>
            </form>
        @else
            <!-- Real Khalti Gateway Button -->
            <button id="khalti-btn" type="button"
                    class="w-full bg-[#5C2D91] hover:bg-[#4B227A] text-white font-bold py-3.5 rounded-2xl shadow-xl transition-all flex items-center justify-center gap-2 text-sm">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M20 4H4c-1.11 0-2 .89-2 2v12c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V6c0-1.11-.89-2-2-2zm0 14H4v-6h16v6zm0-10H4V6h16v2z"/></svg>
                Pay via Khalti Digital Wallet
            </button>

            <form id="khalti-confirm" action="{{ route('unlocks.process', [$listing, $unlock]) }}" method="POST" class="hidden">
                @csrf
                <input type="hidden" name="token" id="khalti-token">
            </form>

            <script src="https://khalti.com/static/khalti-checkout.js"></script>
            <script>
            var checkout = new KhaltiCheckout({
                publicKey: "{{ $khaltiPublicKey }}",
                productIdentity: "listing-{{ $listing->id }}",
                productName: "{{ addslashes($listing->title) }}",
                productUrl: "{{ route('listings.show', $listing) }}",
                eventHandler: {
                    onSuccess(payload) {
                        document.getElementById('khalti-token').value = payload.token;
                        document.getElementById('khalti-confirm').submit();
                    },
                    onError(err) { alert('Payment failed: ' + (err.detail||'Unknown error')); },
                    onClose() {}
                },
                paymentPreference: ["KHALTI","EBANKING","MOBILE_BANKING","CONNECT_IPS","SCT"],
            });
            document.getElementById('khalti-btn').addEventListener('click', () => {
                checkout.show({ amount: {{ (int)($listing->unlock_fee * 100) }} });
            });
            </script>
        @endif

        <p class="text-center text-white/40 text-[11px] mt-4">🔒 Secured 256-bit encrypted transaction</p>
    </div>

    <div class="text-center mt-6">
        <a href="{{ route('listings.show', $listing) }}" class="text-white/50 hover:text-white text-xs transition-colors">← Cancel & return to listing</a>
    </div>
</div>
@endsection
