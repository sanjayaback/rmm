<x-auth-layout title="Login">
    <p class="text-center mb-6 text-xs text-gray-500 font-medium">Find your perfect room</p>

    <!-- Tab Switcher (Matching Spec Image Screen 4) -->
    <div class="flex border-b border-gray-200 mb-6">
        <a href="{{ route('login') }}" class="w-1/2 text-center pb-2.5 font-bold text-sm text-[#00796B] border-b-2 border-[#00796B]">Login</a>
        <a href="{{ route('register') }}" class="w-1/2 text-center pb-2.5 font-bold text-sm text-gray-400 hover:text-gray-700">Register</a>
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

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf
        <div>
            <label class="block text-xs font-bold text-gray-700 mb-1.5">Email or Phone</label>
            <input type="email" name="email" value="{{ old('email') }}" required autofocus
                   class="input-airbnb text-sm" placeholder="Enter email or phone">
        </div>

        <div>
            <div class="flex items-center justify-between mb-1.5">
                <label class="block text-xs font-bold text-gray-700">Password</label>
                @if(Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-xs text-[#00796B] font-bold hover:underline">Forgot password?</a>
                @endif
            </div>
            <input type="password" name="password" required class="input-airbnb text-sm" placeholder="Enter password">
        </div>

        <div class="flex items-center gap-2">
            <input type="checkbox" name="remember" id="remember"
                   class="w-4 h-4 rounded border-gray-300 text-[#00796B] focus:ring-[#00796B]/20 accent-[#00796B]">
            <label for="remember" class="text-xs text-gray-600 font-semibold cursor-pointer">Remember me</label>
        </div>

        <button type="submit" class="btn-teal w-full !py-3.5 text-sm font-extrabold shadow-md">
            Login
        </button>

        <!-- Social Login Divider (Matching Spec Image Screen 4) -->
        <div class="relative flex py-2 items-center">
            <div class="flex-grow border-t border-gray-200"></div>
            <span class="flex-shrink mx-3 text-gray-400 text-xs font-semibold">or continue with</span>
            <div class="flex-grow border-t border-gray-200"></div>
        </div>

        <div class="flex gap-3">
            <button type="button" class="w-1/2 py-2.5 px-4 bg-white border border-gray-200 rounded-2xl flex items-center justify-center gap-2 text-xs font-bold text-gray-700 hover:bg-gray-50 shadow-sm">
                <span class="text-red-500 font-black">G</span> Google
            </button>
            <button type="button" class="w-1/2 py-2.5 px-4 bg-white border border-gray-200 rounded-2xl flex items-center justify-center gap-2 text-xs font-bold text-gray-700 hover:bg-gray-50 shadow-sm">
                <span class="text-blue-600 font-black">f</span> Facebook
            </button>
        </div>

        <p class="text-center text-xs text-gray-500 pt-3">
            Don't have an account? <a href="{{ route('register') }}" class="text-[#00796B] font-extrabold hover:underline">Register</a>
        </p>
    </form>
</x-auth-layout>
