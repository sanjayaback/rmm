<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name','email','password','otp_code','otp_expires_at','role','phone',
        'referral_code','referred_by','is_active'
    ];
    protected $hidden = ['password','remember_token','otp_code'];

    protected function casts(): array {
        return [
            'email_verified_at' => 'datetime',
            'otp_expires_at'    => 'datetime',
            'password'          => 'hashed',
            'is_active'         => 'boolean',
        ];
    }

    protected static function boot(): void {
        parent::boot();
        static::creating(function (User $user) {
            if (empty($user->referral_code)) {
                $user->referral_code = strtoupper(Str::random(8));
            }
        });
    }

    public function generateOtp(): string {
        $code = (string) random_int(100000, 999999);
        $this->update([
            'otp_code' => Hash::make($code),
            'otp_expires_at' => now()->addMinutes(10),
        ]);
        return $code;
    }

    public function verifyOtp(string $code): bool {
        if (!$this->otp_code || !$this->otp_expires_at) return false;
        if (now()->greaterThan($this->otp_expires_at)) return false;
        return Hash::check(trim($code), $this->otp_code);
    }

    public function clearOtp(): void {
        $this->update([
            'otp_code' => null,
            'otp_expires_at' => null,
        ]);
    }

    // Role helpers
    public function isAdmin(): bool { return $this->role === 'admin'; }
    public function isOwner(): bool { return $this->role === 'owner'; }
    public function isUser(): bool  { return $this->role === 'user'; }

    // Relationships
    public function listings()  { return $this->hasMany(Listing::class); }
    public function unlocks()   { return $this->hasMany(Unlock::class); }
    public function bookings()  { return $this->hasMany(Booking::class); }
    public function referrals() { return $this->hasMany(Referral::class, 'referrer_id'); }
    public function referredBy(){ return $this->belongsTo(User::class, 'referred_by'); }

    public function unlockedListings() {
        return $this->belongsToMany(Listing::class, 'unlocks')
            ->wherePivot('payment_status', 'completed')
            ->withPivot(['amount_paid','paid_at','transaction_id'])
            ->withTimestamps();
    }

    public function hasUnlocked(int $listingId): bool {
        return $this->unlocks()
            ->where('listing_id', $listingId)
            ->where('payment_status', 'completed')
            ->exists();
    }

    public function getReferralUrlAttribute(): string {
        return route('register', ['ref' => $this->referral_code]);
    }
}
