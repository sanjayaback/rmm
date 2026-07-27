<x-auth-layout title="Verify Email">
    <h1 class="text-2xl font-black text-white text-center mb-1 font-heading">Verify Email Address</h1>
    <p class="text-center mb-6 text-xs text-white/50 leading-relaxed">
        Thanks for joining RoomRent! Please click on the verification link sent to your email to activate your account.
    </p>

    @if (session('status') == 'verification-link-sent')
        <div class="bg-emerald-500/15 border border-emerald-500/30 rounded-2xl p-4 mb-6 text-center text-xs text-emerald-300 font-semibold">
            A new verification link has been sent to your email address.
        </div>
    @endif

    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit" class="btn-airbnb w-full !py-3.5 text-sm font-extrabold shadow-xl">
            Resend Verification Email
        </button>
    </form>

    <form method="POST" action="{{ route('logout') }}" class="mt-4 text-center">
        @csrf
        <button type="submit" class="text-xs text-white/40 hover:text-rose-400 font-semibold transition-colors">
            Log Out
        </button>
    </form>
</x-auth-layout>
