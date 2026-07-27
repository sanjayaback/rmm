<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Referral;
use App\Models\User;
use App\Notifications\SendOtpNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users'],
            'phone'    => ['nullable', 'string', 'max:20'],
            'role'     => ['nullable', 'in:user,owner'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Resolve referral code
        $referredBy = null;
        if ($request->filled('referral_code')) {
            $referrer   = User::where('referral_code', $request->referral_code)->first();
            $referredBy = $referrer?->id;
        }

        $user = User::create([
            'name'        => $request->name,
            'email'       => $request->email,
            'phone'       => $request->phone,
            'password'    => Hash::make($request->password),
            'role'        => in_array($request->role, ['user','owner']) ? $request->role : 'user',
            'referred_by' => $referredBy,
        ]);

        if ($referredBy) {
            Referral::create([
                'referrer_id' => $referredBy,
                'referred_id' => $user->id,
                'status'      => 'pending',
            ]);
        }

        event(new Registered($user));

        // Generate 6-digit OTP code and send email notification
        $otp = $user->generateOtp();
        
        try {
            $user->notify(new SendOtpNotification($otp));
        } catch (\Throwable $e) {
            // Log mail failure cleanly in production without breaking user flow
            logger()->error('OTP Email delivery failed: ' . $e->getMessage());
        }

        session([
            'otp_user_email' => $user->email,
            'otp_purpose'    => 'signup',
        ]);

        return redirect()->route('otp.verify')->with('status', 'Account created! A 6-digit security verification code has been sent to your email address.');
    }
}
