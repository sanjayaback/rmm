<x-auth-layout title="Forgot Password">
    <h1 class="text-2xl font-black text-white text-center mb-1 font-heading">Reset Password</h1>
    <p class="text-center mb-6 text-xs text-white/50">Enter your account email to receive a password reset link</p>

    @if($errors->any())
        <div class="bg-rose-500/10 border border-rose-500/30 rounded-2xl p-4 mb-6">
            <ul class="text-rose-400 text-xs font-medium space-y-1">
                @foreach($errors->all() as $e)
                    <li>• {{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
        @csrf
        <div>
            <label class="block text-xs font-bold text-white/75 uppercase tracking-wider mb-2">Email Address</label>
            <input type="email" name="email" value="{{ old('email') }}" required autofocus class="input-airbnb text-sm" placeholder="you@example.com">
        </div>

        <button type="submit" class="btn-airbnb w-full !py-3.5 text-sm font-extrabold shadow-xl">
            Send Reset Link
        </button>

        <p class="text-center text-xs text-white/50 pt-2">
            <a href="{{ route('login') }}" class="text-[#FF385C] font-bold hover:underline">← Back to Log In</a>
        </p>
    </form>
</x-auth-layout>
