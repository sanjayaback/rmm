<?php

namespace Tests\Feature;

use App\Models\Listing;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GlobalSearchTest extends TestCase
{
    use RefreshDatabase;

    public function test_global_search_page_renders_with_query_and_listings(): void
    {
        $owner = User::create([
            'name' => 'Owner',
            'email' => 'owner@example.com',
            'password' => bcrypt('password'),
            'role' => 'owner',
        ]);

        Listing::create([
            'user_id' => $owner->id,
            'title' => 'Cozy Room in Thamel',
            'description' => 'Bright single room',
            'price' => 8000,
            'city' => 'Kathmandu',
            'area' => 'Thamel',
            'exact_address' => 'House 55',
            'phone' => '9800000000',
            'lat' => 27.71, 'lng' => 85.31,
            'approx_lat' => 27.71, 'approx_lng' => 85.31,
            'status' => 'approved',
            'is_available' => true,
        ]);

        $response = $this->get('/search?q=Thamel');
        $response->assertStatus(200);
        $response->assertSee('Cozy Room in Thamel');
    }

    public function test_global_search_api_returns_json_structure(): void
    {
        $owner = User::create([
            'name' => 'Owner',
            'email' => 'owner2@example.com',
            'password' => bcrypt('password'),
            'role' => 'owner',
        ]);

        Listing::create([
            'user_id' => $owner->id,
            'title' => 'Flat in Lazimpat',
            'description' => 'Near embassy',
            'price' => 15000,
            'city' => 'Kathmandu',
            'area' => 'Lazimpat',
            'exact_address' => 'House 99',
            'phone' => '9800000000',
            'lat' => 27.72, 'lng' => 85.32,
            'approx_lat' => 27.72, 'approx_lng' => 85.32,
            'status' => 'approved',
            'is_available' => true,
        ]);

        $response = $this->getJson('/api/search?q=Lazimpat');
        $response->assertStatus(200);
        $response->assertJsonStructure(['listings', 'cities', 'areas']);
        $response->assertJsonFragment(['title' => 'Flat in Lazimpat']);
    }
}
