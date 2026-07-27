<x-auth-layout title="Register">
    <p class="text-center mb-6 text-xs text-gray-500 font-medium">Create your account</p>

    <!-- Tab Switcher (Matching Spec Image Screen 5) -->
    <div class="flex border-b border-gray-200 mb-6" x-data="{ role: 'user' }">
        <button type="button" @click="role = 'user'; document.getElementById('role-user').checked = true"
                class="w-1/2 text-center pb-2.5 font-bold text-sm"
                :class="role === 'user' ? 'text-[#00796B] border-b-2 border-[#00796B]' : 'text-gray-400 hover:text-gray-700'">Customer</button>
        <button type="button" @click="role = 'owner'; document.getElementById('role-owner').checked = true"
                class="w-1/2 text-center pb-2.5 font-bold text-sm"
                :class="role === 'owner' ? 'text-[#00796B] border-b-2 border-[#00796B]' : 'text-gray-400 hover:text-gray-700'">Owner</button>
    </div>

    @if($errors->any())
        <div class="bg-rose-50 border border-rose-200 rounded-2xl p-4 mb-6">
            <ul class="text-rose-600 text-xs font-medium space-y-1">
                @foreach($errors->all() as $e)
                    <li>• {{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf
        @if(request('ref'))
            <input type="hidden" name="referral_code" value="{{ request('ref') }}">
        @endif

        <div class="hidden">
            <input type="radio" name="role" id="role-user" value="user" {{ old('role','user')==='user'?'checked':'' }}>
            <input type="radio" name="role" id="role-owner" value="owner" {{ old('role')==='owner'?'checked':'' }}>
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-700 mb-1.5">Full Name</label>
            <input type="text" name="name" value="{{ old('name') }}" required autofocus class="input-airbnb text-sm" placeholder="Enter your full name">
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-700 mb-1.5">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required class="input-airbnb text-sm" placeholder="Enter your email">
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-700 mb-1.5">Phone</label>
            <input type="text" name="phone" value="{{ old('phone') }}" class="input-airbnb text-sm font-mono" placeholder="Enter your phone number">
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-700 mb-1.5">Password</label>
            <input type="password" name="password" required class="input-airbnb text-sm" placeholder="Create password">
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-700 mb-1.5">Confirm Password</label>
            <input type="password" name="password_confirmation" required class="input-airbnb text-sm" placeholder="Confirm your password">
        </div>

        <div class="flex items-center gap-2 pt-1">
            <input type="checkbox" id="terms" required class="w-4 h-4 rounded border-gray-300 text-[#00796B] focus:ring-[#00796B]/20 accent-[#00796B]">
            <label for="terms" class="text-xs text-gray-600 font-semibold cursor-pointer">I agree to the <a href="#" class="text-[#00796B] underline">Terms & Conditions</a></label>
        </div>

        <button type="submit" class="btn-teal w-full !py-3.5 text-sm font-extrabold shadow-md mt-2">
            Register
        </button>

        <p class="text-center text-xs text-gray-500 pt-2">
            Already have an account? <a href="{{ route('login') }}" class="text-[#00796B] font-extrabold hover:underline">Login</a>
        </p>
    </form>
</x-auth-layout>
