<?php

namespace Tests\Unit;

use App\Models\Listing;
use App\Models\Unlock;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_generates_referral_code_on_creation(): void
    {
        $user = User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'role' => 'user',
        ]);

        $this->assertNotEmpty($user->referral_code);
        $this->assertEquals(8, strlen($user->referral_code));
    }

    public function test_otp_generation_and_hashed_verification(): void
    {
        $user = User::create([
            'name' => 'Alice Smith',
            'email' => 'alice@example.com',
            'password' => 'password123',
            'role' => 'user',
        ]);

        $otp = $user->generateOtp();

        $this->assertEquals(6, strlen($otp));
        $this->assertNotEquals($otp, $user->otp_code, 'OTP should be stored hashed, not plaintext');
        $this->assertTrue($user->verifyOtp($otp));
        $this->assertFalse($user->verifyOtp('000000'));

        $user->clearOtp();
        $this->assertNull($user->otp_code);
        $this->assertNull($user->otp_expires_at);
    }

    public function test_user_role_helpers(): void
    {
        $admin = new User(['role' => 'admin']);
        $owner = new User(['role' => 'owner']);
        $user  = new User(['role' => 'user']);

        $this->assertTrue($admin->isAdmin());
        $this->assertFalse($admin->isOwner());

        $this->assertTrue($owner->isOwner());
        $this->assertFalse($owner->isAdmin());

        $this->assertTrue($user->isUser());
    }

    public function test_has_unlocked_returns_correct_status(): void
    {
        $owner = User::create([
            'name' => 'Owner',
            'email' => 'owner@example.com',
            'password' => 'password123',
            'role' => 'owner',
        ]);

        $tenant = User::create([
            'name' => 'Tenant',
            'email' => 'tenant@example.com',
            'password' => 'password123',
            'role' => 'user',
        ]);

        $listing = Listing::create([
            'user_id' => $owner->id,
            'title' => 'Sample Room',
            'description' => 'Clean room',
            'price' => 10000,
            'city' => 'Kathmandu',
            'area' => 'Thamel',
            'exact_address' => 'Thamel Marg 12',
            'phone' => '9800000000',
            'lat' => 27.7150,
            'lng' => 85.3120,
            'approx_lat' => 27.7153,
            'approx_lng' => 85.3123,
            'status' => 'approved',
        ]);

        $this->assertFalse($tenant->hasUnlocked($listing->id));

        Unlock::create([
            'user_id' => $tenant->id,
            'listing_id' => $listing->id,
            'amount_paid' => 50,
            'payment_method' => 'fake',
            'payment_status' => 'completed',
        ]);

        $this->assertTrue($tenant->fresh()->hasUnlocked($listing->id));
    }
}
