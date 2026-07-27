<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class NewPasswordController extends Controller
{
    public function create(Request $request): View|RedirectResponse
    {
        $userId = session('otp_verified_user_id');
        $user   = $userId ? User::find($userId) : null;

        return view('auth.reset-password', [
            'request' => $request,
            'user'    => $user,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $userId = session('otp_verified_user_id');

        if ($userId) {
            $request->validate([
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]);

            $user = User::findOrFail($userId);
            $user->forceFill([
                'password' => Hash::make($request->password),
                'remember_token' => Str::random(60),
            ])->save();

            $user->clearOtp();
            session()->forget(['otp_user_email', 'otp_verified_user_id']);

            event(new PasswordReset($user));

            return redirect()->route('login')->with('success', 'Your password has been reset successfully! Please sign in with your new password.');
        }

        // Token fallback
        $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::where('email', $request->email)->first();
        if ($user) {
            $user->forceFill([
                'password' => Hash::make($request->password),
            ])->save();
            return redirect()->route('login')->with('success', 'Password reset successfully.');
        }

        return back()->withErrors(['email' => 'Unable to reset password.']);
    }
}
