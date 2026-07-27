<x-auth-layout title="Reset Password">
    <h1 class="text-2xl font-black text-white text-center mb-1 font-heading">New Password</h1>
    <p class="text-center mb-6 text-xs text-white/50">Choose a strong new password for your account</p>

    @if($errors->any())
        <div class="bg-rose-500/10 border border-rose-500/30 rounded-2xl p-4 mb-6">
            <ul class="text-rose-400 text-xs font-medium space-y-1">
                @foreach($errors->all() as $e)
                    <li>• {{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('password.store') }}" class="space-y-4">
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div>
            <label class="block text-xs font-bold text-white/75 uppercase tracking-wider mb-1.5">Email Address</label>
            <input type="email" name="email" value="{{ old('email', $request->email) }}" required class="input-airbnb text-sm">
        </div>

        <div>
            <label class="block text-xs font-bold text-white/75 uppercase tracking-wider mb-1.5">New Password</label>
            <input type="password" name="password" required autofocus class="input-airbnb text-sm" placeholder="Min. 8 characters">
        </div>

        <div>
            <label class="block text-xs font-bold text-white/75 uppercase tracking-wider mb-1.5">Confirm New Password</label>
            <input type="password" name="password_confirmation" required class="input-airbnb text-sm" placeholder="Repeat new password">
        </div>

        <button type="submit" class="btn-airbnb w-full !py-3.5 text-sm font-extrabold shadow-xl mt-2">
            Update Password
        </button>
    </form>
</x-auth-layout>
