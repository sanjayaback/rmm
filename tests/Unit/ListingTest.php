<?php

namespace Tests\Unit;

use App\Models\Listing;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ListingTest extends TestCase
{
    use RefreshDatabase;

    public function test_listing_public_data_excludes_sensitive_contact_info(): void
    {
        $owner = User::create([
            'name' => 'Landlord',
            'email' => 'landlord@example.com',
            'password' => 'password123',
            'role' => 'owner',
        ]);

        $listing = Listing::create([
            'user_id' => $owner->id,
            'title' => 'Luxury Flat',
            'description' => 'Great view',
            'price' => 20000,
            'city' => 'Kathmandu',
            'area' => 'Lazimpat',
            'exact_address' => 'Secret House #99',
            'phone' => '9841999999',
            'lat' => 27.7200,
            'lng' => 85.3200,
            'approx_lat' => 27.7203,
            'approx_lng' => 85.3203,
            'status' => 'approved',
            'unlock_fee' => 50,
            'bedrooms' => 2,
            'bathrooms' => 1,
            'room_type' => 'double',
            'is_available' => true,
        ]);

        $publicData = $listing->getPublicData();

        $this->assertArrayHasKey('title', $publicData);
        $this->assertArrayHasKey('approx_lat', $publicData);
        $this->assertArrayNotHasKey('exact_address', $publicData);
        $this->assertArrayNotHasKey('phone', $publicData);
    }

    public function test_listing_scopes_filter_correctly(): void
    {
        $owner = User::create([
            'name' => 'Landlord',
            'email' => 'landlord2@example.com',
            'password' => 'password123',
            'role' => 'owner',
        ]);

        Listing::create([
            'user_id' => $owner->id,
            'title' => 'Approved Listing',
            'description' => 'Desc',
            'price' => 10000,
            'city' => 'Kathmandu',
            'area' => 'Thamel',
            'exact_address' => 'Addr',
            'phone' => '9800000000',
            'lat' => 27.71, 'lng' => 85.31,
            'approx_lat' => 27.71, 'approx_lng' => 85.31,
            'status' => 'approved',
            'is_available' => true,
        ]);

        Listing::create([
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

        $this->assertEquals(1, Listing::public()->count());
        $this->assertEquals('Approved Listing', Listing::public()->first()->title);
    }
}
