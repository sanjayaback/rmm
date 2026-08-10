@extends('layouts.app')

@section('title', 'Site Settings CRM — Admin')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-black text-gray-900 tracking-tight">⚙️ Site Settings CRM</h1>
            <p class="text-sm text-gray-500 mt-1">Manage global platform configurations, support contacts, and payment defaults.</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 bg-gray-100 text-gray-700 font-bold rounded-xl text-sm hover:bg-gray-200 transition">
            ← Back to Admin
        </a>
    </div>

    <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-6">
        @csrf

        <!-- General Settings -->
        <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
            <h2 class="text-lg font-bold text-gray-900 mb-4 border-b pb-2">General Info</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Platform Name</label>
                    <input type="text" name="site_name" value="{{ old('site_name', $settings['site_name']) }}" required
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#00796B] focus:border-transparent outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Support Email</label>
                    <input type="email" name="support_email" value="{{ old('support_email', $settings['support_email']) }}" required
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#00796B] focus:border-transparent outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Support Phone</label>
                    <input type="text" name="support_phone" value="{{ old('support_phone', $settings['support_phone']) }}" required
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#00796B] focus:border-transparent outline-none">
                </div>
            </div>
        </div>

        <!-- Pricing & Economics -->
        <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
            <h2 class="text-lg font-bold text-gray-900 mb-4 border-b pb-2">Platform Economics & Fees</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Default Listing Unlock Fee (NPR)</label>
                    <input type="number" step="1" min="0" name="default_unlock_fee" value="{{ old('default_unlock_fee', $settings['default_unlock_fee']) }}" required
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#00796B] focus:border-transparent outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Referral Reward (NPR)</label>
                    <input type="number" step="1" min="0" name="referral_reward" value="{{ old('referral_reward', $settings['referral_reward']) }}" required
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#00796B] focus:border-transparent outline-none">
                </div>
            </div>
        </div>

        <!-- Gateway Mode -->
        <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
            <h2 class="text-lg font-bold text-gray-900 mb-4 border-b pb-2">Khalti Gateway Options</h2>
            <label class="flex items-center space-x-3 cursor-pointer">
                <input type="checkbox" name="khalti_fake_mode" value="1" {{ $settings['khalti_fake_mode'] ? 'checked' : '' }}
                       class="w-5 h-5 text-[#00796B] rounded border-gray-300 focus:ring-[#00796B]">
                <div>
                    <span class="text-sm font-bold text-gray-900">Enable Simulated Payment Test Mode</span>
                    <p class="text-xs text-gray-500">Allow instant simulated payments without real Khalti API calls (useful for development/testing).</p>
                </div>
            </label>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="px-6 py-3 bg-[#00796B] text-white font-bold rounded-xl hover:bg-[#004D40] transition shadow-md">
                Save Settings
            </button>
        </div>
    </form>
</div>
@endsection
