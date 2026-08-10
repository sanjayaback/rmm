<?php

namespace Tests\Feature;

use App\Models\Listing;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ListingTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_can_browse_approved_listings(): void
    {
        $owner = User::create([
            'name' => 'Owner',
            'email' => 'owner@example.com',
            'password' => bcrypt('password'),
            'role' => 'owner',
        ]);

        Listing::create([
            'user_id' => $owner->id,
            'title' => 'Approved Room',
            'description' => 'Great location',
            'price' => 8000,
            'city' => 'Kathmandu',
            'area' => 'Thamel',
            'exact_address' => 'House 1',
            'phone' => '9800000000',
            'lat' => 27.71, 'lng' => 85.31,
            'approx_lat' => 27.71, 'approx_lng' => 85.31,
            'status' => 'approved',
            'is_available' => true,
        ]);

        $response = $this->get('/browse');
        $response->assertStatus(200);
        $response->assertSee('Approved Room');
    }

    public function test_api_map_endpoint_returns_json(): void
    {
        $owner = User::create([
            'name' => 'Owner',
            'email' => 'owner2@example.com',
            'password' => bcrypt('password'),
            'role' => 'owner',
        ]);

        Listing::create([
            'user_id' => $owner->id,
            'title' => 'Map Listing',
            'description' => 'Great location',
            'price' => 8000,
            'city' => 'Kathmandu',
            'area' => 'Thamel',
            'exact_address' => 'House 1',
            'phone' => '9800000000',
            'lat' => 27.71, 'lng' => 85.31,
            'approx_lat' => 27.71, 'approx_lng' => 85.31,
            'status' => 'approved',
            'is_available' => true,
        ]);

        $response = $this->getJson('/api/listings/map');
        $response->assertStatus(200);
        $response->assertJsonFragment(['title' => 'Map Listing']);
    }

    public function test_owner_can_create_listing(): void
    {
        $owner = User::create([
            'name' => 'Owner',
            'email' => 'owner3@example.com',
            'password' => bcrypt('password'),
            'role' => 'owner',
        ]);

        $response = $this->actingAs($owner)->post('/listings', [
            'title' => 'New Room Post',
            'description' => 'Spacious room near college',
            'price' => 7500,
            'city' => 'Kathmandu',
            'area' => 'Baneshwor',
            'exact_address' => 'Baneshwor Chowk 45',
            'phone' => '9841112233',
            'lat' => 27.69,
            'lng' => 85.33,
            'bedrooms' => 1,
            'bathrooms' => 1,
            'room_type' => 'single',
        ]);

        $response->assertRedirect(route('listings.index'));
        $this->assertDatabaseHas('listings', [
            'title' => 'New Room Post',
            'status' => 'pending',
        ]);
    }

    public function test_admin_can_approve_listing(): void
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $owner = User::create([
            'name' => 'Owner',
            'email' => 'owner4@example.com',
            'password' => bcrypt('password'),
            'role' => 'owner',
        ]);

        $listing = Listing::create([
            'user_id' => $owner->id,
            'title' => 'Pending Listing',
            'description' => 'Desc',
            'price' => 10000,
            'city' => 'Kathmandu',
            'area' => 'Thamel',
            'exact_address' => 'Addr',
            'phone' => '9800000000',
            'lat' => 27.71, 'lng' => 85.31,
            'approx_lat' => 27.71, 'approx_lng' => 85.31,
            'status' => 'pending',
            'is_available' => true,
        ]);

        $response = $this->actingAs($admin)->post(route('admin.listings.approve', $listing));

        $response->assertRedirect();
        $this->assertEquals('approved', $listing->fresh()->status);
    }
}
