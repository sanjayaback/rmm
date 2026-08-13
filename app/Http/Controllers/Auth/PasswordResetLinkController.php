<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ], [
            'email.exists' => 'No account found with this email address.',
        ]);

        $user = User::where('email', $request->email)->first();
        $otp  = $user->generateOtp();

        session([
            'otp_user_email' => $user->email,
            'otp_purpose'    => 'password_reset',
            'otp_raw_code'   => $otp,
        ]);

        try {
            $user->notify(new \App\Notifications\SendOtpNotification($otp));
        } catch (\Throwable $e) {
            logger()->error('Password Reset OTP Email delivery failed: ' . $e->getMessage());
            session(['mail_delivery_failed' => true]);
        }

        return redirect()->route('otp.verify')->with('status', "A 6-digit OTP verification code has been sent to your email address.");
    }
}
