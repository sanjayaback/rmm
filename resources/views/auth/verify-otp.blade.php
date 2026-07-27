<x-auth-layout title="Verify Security Code">
    <div class="text-center mb-6">
        <div class="w-12 h-12 bg-teal-50 text-[#00796B] rounded-2xl flex items-center justify-center text-xl mx-auto mb-3 border border-teal-100 shadow-sm">
            🔒
        </div>
        <h1 class="text-2xl font-black text-gray-900 font-heading mb-1">Verify Security Code</h1>
        <p class="text-xs text-gray-600">
            We sent a 6-digit OTP verification code to <strong class="text-gray-900 font-mono">{{ $email }}</strong>
        </p>
    </div>

    @if(session('status'))
        <div class="bg-teal-50 border border-teal-200 text-[#00796B] text-xs font-bold p-3.5 rounded-2xl mb-5 shadow-sm text-center">
            {{ session('status') }}
        </div>
    @endif

    @if(!empty($devOtp))
        <div class="bg-amber-50 border border-amber-200 rounded-2xl p-3.5 mb-5 text-center shadow-sm">
            <span class="text-amber-800 text-[11px] font-extrabold uppercase tracking-wider block mb-0.5">⚙️ Local Environment Testing Code</span>
            <div class="text-2xl font-black text-amber-900 tracking-[0.3em] font-mono select-all my-0.5">{{ $devOtp }}</div>
            <span class="text-amber-700 text-[10px]">Shown automatically in local dev mode when SMTP is set to log</span>
        </div>
    @endif

    @if($errors->any())
        <div class="bg-rose-50 border border-rose-200 rounded-2xl p-4 mb-6">
            <ul class="text-rose-600 text-xs font-medium space-y-1">
                @foreach($errors->all() as $e)
                    <li>• {{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('otp.process') }}" class="space-y-6">
        @csrf

        <div>
            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2 text-center">Enter 6-Digit Verification Code</label>
            <input type="text" name="otp" maxlength="6" pattern="[0-9]{6}" required autofocus
                   class="w-full bg-white border border-gray-300 rounded-2xl py-3.5 px-4 text-center font-mono text-2xl font-black tracking-[0.4em] text-[#00796B] placeholder-gray-300 focus:outline-none focus:border-[#00796B] focus:ring-2 focus:ring-[#00796B]/20 shadow-sm transition-all"
                   placeholder="123456" autocomplete="one-time-code">
        </div>

        <button type="submit" class="btn-teal w-full !py-3.5 text-sm font-extrabold shadow-md">
            Verify Code & Complete Registration →
        </button>
    </form>

    <div class="mt-6 pt-5 border-t border-gray-100 flex items-center justify-between text-xs text-gray-600">
        <span>Didn't receive the code?</span>
        <form method="POST" action="{{ route('otp.resend') }}">
            @csrf
            <button type="submit" class="text-[#00796B] font-extrabold hover:underline bg-transparent border-none cursor-pointer">
                Resend Code
            </button>
        </form>
    </div>
</x-auth-layout>
