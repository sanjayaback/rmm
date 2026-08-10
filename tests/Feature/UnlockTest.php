<?php

namespace Tests\Feature;

use App\Models\Listing;
use App\Models\Unlock;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UnlockTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_initiate_and_complete_unlock(): void
    {
        $owner = User::create([
            'name' => 'Landlord',
            'email' => 'landlord@example.com',
            'password' => bcrypt('password'),
            'role' => 'owner',
        ]);

        $tenant = User::create([
            'name' => 'Tenant User',
            'email' => 'tenant@example.com',
            'password' => bcrypt('password'),
            'role' => 'user',
        ]);

        $listing = Listing::create([
            'user_id' => $owner->id,
            'title' => 'Room for Rent',
            'description' => 'Nice room',
            'price' => 12000,
            'city' => 'Kathmandu',
            'area' => 'Lazimpat',
            'exact_address' => 'Lazimpat Marga House #14',
            'phone' => '9841987654',
            'lat' => 27.72, 'lng' => 85.32,
            'approx_lat' => 27.72, 'approx_lng' => 85.32,
            'status' => 'approved',
            'unlock_fee' => 50,
            'is_available' => true,
        ]);

        // 1. View listing page before unlock - exact details hidden
        $response = $this->actingAs($tenant)->get(route('listings.show', $listing));
        $response->assertStatus(200);
        $response->assertDontSee('Lazimpat Marga House #14');

        // 2. Initiate unlock
        $response = $this->actingAs($tenant)->post(route('unlocks.initiate', $listing));
        $this->assertDatabaseHas('unlocks', [
            'user_id' => $tenant->id,
            'listing_id' => $listing->id,
            'payment_status' => 'pending',
        ]);

        $unlock = Unlock::where('user_id', $tenant->id)->where('listing_id', $listing->id)->first();

        // 3. Process payment
        $response = $this->actingAs($tenant)->post(route('unlocks.process', [
            'listing' => $listing->id,
            'unlock' => $unlock->id,
        ]), [
            'token' => 'fake-test-token',
        ]);

        $response->assertRedirect(route('listings.show', $listing));
        $this->assertEquals('completed', $unlock->fresh()->payment_status);

        // 4. View listing page after unlock - exact details visible
        $response = $this->actingAs($tenant)->get(route('listings.show', $listing));
        $response->assertStatus(200);
        $response->assertSee('Lazimpat Marga House #14');
        $response->assertSee('9841987654');
    }
}
