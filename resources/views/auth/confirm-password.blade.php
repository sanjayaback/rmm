<x-auth-layout title="Confirm Password">
    <h1 class="text-2xl font-black text-white text-center mb-1 font-heading">Confirm Password</h1>
    <p class="text-center mb-6 text-xs text-white/50 leading-relaxed">
        This is a secure area. Please confirm your password before continuing.
    </p>

    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-5">
        @csrf
        <div>
            <label class="block text-xs font-bold text-white/75 uppercase tracking-wider mb-2">Password</label>
            <input type="password" name="password" required autofocus class="input-airbnb text-sm" placeholder="Enter your password">
            @error('password')
                <p class="text-rose-400 text-xs font-semibold mt-1.5">{{ $message }}</p>
            @enderror
        </div>
        <button type="submit" class="btn-airbnb w-full !py-3.5 text-sm font-extrabold shadow-xl">
            Confirm Password
        </button>
    </form>
</x-auth-layout>
