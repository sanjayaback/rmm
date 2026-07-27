@extends('layouts.app')
@section('title','Profile Settings')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="mb-8">
        <h1 class="font-display text-3xl font-bold text-white">Profile Settings</h1>
        <p class="text-white/50 mt-1">Manage your account information</p>
    </div>

    {{-- Profile update --}}
    <div class="bg-[#111113] border border-white/[0.08] rounded-2xl p-6 mb-5">
        <h2 class="font-display font-semibold text-white mb-4">Personal Information</h2>
        <form method="POST" action="{{ route('profile.update') }}" class="space-y-4">
            @csrf @method('PATCH')
            <div>
                <label class="block text-sm text-white/60 mb-1.5">Name</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                       class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-orange-500/60 transition-all @error('name') border-red-500/60 @enderror">
                @error('name')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm text-white/60 mb-1.5">Email</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                       class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-orange-500/60 transition-all @error('email') border-red-500/60 @enderror">
                @error('email')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm text-white/60 mb-1.5">Phone</label>
                <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                       class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-white/25 focus:outline-none focus:border-orange-500/60 transition-all"
                       placeholder="98XXXXXXXX">
            </div>
            <div class="flex items-center gap-3">
                <button type="submit"
                        class="bg-orange-500 hover:bg-orange-600 text-white font-semibold px-6 py-2.5 rounded-xl transition-all text-sm">Save Changes</button>
                @if(session('status') === 'profile-updated')
                    <span class="text-green-400 text-sm">✓ Saved!</span>
                @endif
            </div>
        </form>
    </div>

    {{-- Password --}}
    <div class="bg-[#111113] border border-white/[0.08] rounded-2xl p-6 mb-5">
        <h2 class="font-display font-semibold text-white mb-4">Change Password</h2>
        <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
            @csrf @method('PUT')
            <div>
                <label class="block text-sm text-white/60 mb-1.5">Current Password</label>
                <input type="password" name="current_password"
                       class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-orange-500/60 transition-all @error('current_password','updatePassword') border-red-500/60 @enderror">
                @error('current_password','updatePassword')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm text-white/60 mb-1.5">New Password</label>
                <input type="password" name="password"
                       class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-orange-500/60 transition-all @error('password','updatePassword') border-red-500/60 @enderror">
                @error('password','updatePassword')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm text-white/60 mb-1.5">Confirm New Password</label>
                <input type="password" name="password_confirmation"
                       class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-orange-500/60 transition-all">
            </div>
            <div class="flex items-center gap-3">
                <button type="submit"
                        class="bg-orange-500 hover:bg-orange-600 text-white font-semibold px-6 py-2.5 rounded-xl transition-all text-sm">Update Password</button>
                @if(session('status') === 'password-updated')
                    <span class="text-green-400 text-sm">✓ Updated!</span>
                @endif
            </div>
        </form>
    </div>

    {{-- Account info --}}
    <div class="bg-[#111113] border border-white/[0.08] rounded-2xl p-5 mb-5">
        <div class="flex justify-between items-center text-sm mb-3">
            <span class="text-white/40">Role</span>
            <span class="{{ $user->isAdmin()?'bg-orange-500/15 text-orange-400 border-orange-500/30':($user->isOwner()?'bg-green-500/15 text-green-400 border-green-500/30':'bg-yellow-500/15 text-yellow-400 border-yellow-500/30') }} text-xs px-2.5 py-0.5 rounded-full border">{{ ucfirst($user->role) }}</span>
        </div>
        <div class="flex justify-between items-center text-sm mb-3">
            <span class="text-white/40">Referral Code</span>
            <code class="text-orange-400 bg-orange-500/10 px-2 py-0.5 rounded-lg text-xs">{{ $user->referral_code }}</code>
        </div>
        <div class="flex justify-between items-center text-sm">
            <span class="text-white/40">Member Since</span>
            <span class="text-white/60 text-sm">{{ $user->created_at->format('M d, Y') }}</span>
        </div>
    </div>

    {{-- Delete account --}}
    <div class="bg-[#111113] border border-red-500/15 rounded-2xl p-6">
        <h2 class="font-display font-semibold text-white mb-1">Delete Account</h2>
        <p class="text-white/50 text-sm mb-4">Permanently delete your account and all data. This cannot be undone.</p>
        <form method="POST" action="{{ route('profile.destroy') }}"
              onsubmit="return confirm('Permanently delete your account?')">
            @csrf @method('DELETE')
            <input type="password" name="password" required
                   class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-white/25 focus:outline-none focus:border-red-500/60 transition-all mb-3 text-sm"
                   placeholder="Confirm your password">
            @error('password','userDeletion')<p class="text-red-400 text-xs mb-2">{{ $message }}</p>@enderror
            <button type="submit"
                    class="bg-red-500/10 hover:bg-red-500/20 border border-red-500/30 text-red-400 font-medium px-5 py-2 rounded-xl transition-all text-sm">Delete My Account</button>
        </form>
    </div>
</div>
@endsection
