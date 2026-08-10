<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\SendOtpNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class OtpVerificationController extends Controller
{
    public function showOtpForm(Request $request): View|RedirectResponse
    {
        $email = session('otp_user_email');
        if (!$email) {
            return redirect()->route('login')->withErrors(['email' => 'Please log in or request a new verification code.']);
        }

        $user = User::where('email', $email)->first();
        if (!$user) {
            return redirect()->route('login')->withErrors(['email' => 'User account not found.']);
        }

        // Show code helper box in local environment when mail server is not active
        $devOtp = (app()->environment('local') || config('mail.default') === 'log') ? session('otp_raw_code') : null;

        return view('auth.verify-otp', [
            'email'   => $user->email,
            'devOtp'  => $devOtp,
            'purpose' => session('otp_purpose', 'signup'),
        ]);
    }

    public function verifyOtp(Request $request): RedirectResponse
    {
        $request->validate([
            'otp' => ['required', 'string', 'size:6'],
        ]);

        $email = session('otp_user_email');
        if (!$email) {
            return redirect()->route('login')->withErrors(['email' => 'Session expired. Please log in or register again.']);
        }

        $user = User::where('email', $email)->first();
        if (!$user || !$user->verifyOtp($request->otp)) {
            return back()->withErrors(['otp' => 'Invalid or expired 6-digit verification code. Please check your code and try again.']);
        }

        $purpose = session('otp_purpose', 'signup');

        if ($purpose === 'signup') {
            $user->markEmailAsVerified();
            $user->clearOtp();
            session()->forget(['otp_user_email', 'otp_purpose']);

            Auth::login($user);

            return redirect()->route('dashboard')->with('success', 'Account verified successfully! Welcome to Rentivo.');
        }

        // Password Reset Workflow
        session(['otp_verified_user_id' => $user->id]);

        return redirect()->route('password.reset.otp')->with('success', 'Verification code confirmed. Please enter your new password.');
    }

    public function resendOtp(Request $request): RedirectResponse
    {
        $email = session('otp_user_email');
        if (!$email) {
            return redirect()->route('login');
        }

        $user = User::where('email', $email)->first();
        if ($user) {
            $otp = $user->generateOtp();
            session(['otp_raw_code' => $otp]);
            try {
                $user->notify(new SendOtpNotification($otp));
            } catch (\Throwable $e) {
                logger()->error('Resend OTP Email delivery failed: ' . $e->getMessage());
            }

            $msg = 'A new 6-digit verification code has been generated and sent to your email address.';
            if (app()->environment('local') || config('mail.default') === 'log') {
                $msg .= " (Local Testing Code: {$otp})";
            }

            return back()->with('status', $msg);
        }

        return back()->withErrors(['email' => 'Unable to resend verification code.']);
    }
}
