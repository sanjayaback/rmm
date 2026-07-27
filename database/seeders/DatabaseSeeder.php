<?php

namespace Database\Seeders;

use App\Models\Listing;
use App\Models\Unlock;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Admin ──────────────────────────────────────────────────
        $admin = User::create([
            'name'     => 'Admin User',
            'email'    => 'admin@roomrent.com',
            'password' => Hash::make('password'),
            'role'     => 'admin',
            'phone'    => '9800000000',
        ]);

        // ── Owners ─────────────────────────────────────────────────
        $owner1 = User::create([
            'name'     => 'Ram Sharma',
            'email'    => 'owner@roomrent.com',
            'password' => Hash::make('password'),
            'role'     => 'owner',
            'phone'    => '9841000001',
        ]);

        $owner2 = User::create([
            'name'     => 'Sita Thapa',
            'email'    => 'owner2@roomrent.com',
            'password' => Hash::make('password'),
            'role'     => 'owner',
            'phone'    => '9841000002',
        ]);

        // ── Regular User ───────────────────────────────────────────
        $user = User::create([
            'name'     => 'Test User',
            'email'    => 'user@roomrent.com',
            'password' => Hash::make('password'),
            'role'     => 'user',
            'phone'    => '9841000003',
        ]);

        // ── Sample Listings ────────────────────────────────────────
        $listings = [
            [
                'user_id'       => $owner1->id,
                'title'         => 'Cozy Single Room in Thamel',
                'description'   => 'A bright and clean single room located in the heart of Thamel. Close to restaurants, cafes, and public transport. Ideal for students and working professionals. The room comes fully furnished with a bed, wardrobe, and study table. Quiet floor, no pets.',
                'price'         => 8000,
                'city'          => 'Kathmandu',
                'area'          => 'Thamel',
                'exact_address' => 'House No. 23, Thamel Marg, Kathmandu-29',
                'phone'         => '9841000001',
                'lat'           => 27.7150,
                'lng'           => 85.3123,
                'approx_lat'    => 27.7153,
                'approx_lng'    => 85.3127,
                'status'        => 'approved',
                'unlock_fee'    => 50,
                'bedrooms'      => 1,
                'bathrooms'     => 1,
                'room_type'     => 'single',
                'amenities'     => ['WiFi', 'Attached Bathroom', 'Hot Water', 'Furnished'],
                'is_available'  => true,
                'views'         => 142,
            ],
            [
                'user_id'       => $owner1->id,
                'title'         => 'Spacious Double Room in Lazimpat',
                'description'   => 'Large double room with attached bathroom in a quiet residential area of Lazimpat. Perfect for couples or two friends sharing. The flat has a shared kitchen and a cozy living area. Walking distance from embassies, international schools, and supermarkets.',
                'price'         => 15000,
                'city'          => 'Kathmandu',
                'area'          => 'Lazimpat',
                'exact_address' => 'Flat 4B, Lazimpat Heights Apartments, Lazimpat-2',
                'phone'         => '9841000001',
                'lat'           => 27.7230,
                'lng'           => 85.3180,
                'approx_lat'    => 27.7233,
                'approx_lng'    => 85.3183,
                'status'        => 'approved',
                'unlock_fee'    => 50,
                'bedrooms'      => 2,
                'bathrooms'     => 1,
                'room_type'     => 'double',
                'amenities'     => ['WiFi', 'Kitchen', 'Balcony', 'Security', 'Parking'],
                'is_available'  => true,
                'views'         => 87,
            ],
            [
                'user_id'       => $owner2->id,
                'title'         => 'Modern 2BHK Apartment in Baneshwor',
                'description'   => 'Fully furnished 2BHK apartment in a prime location of New Baneshwor. Modern kitchen with appliances, spacious living room, two well-lit bedrooms. Close to shopping centres, hospitals, and bus stops. 24/7 security, power backup, and lift access.',
                'price'         => 25000,
                'city'          => 'Kathmandu',
                'area'          => 'Baneshwor',
                'exact_address' => 'Tower C, Sunrise Apartments, New Baneshwor-10',
                'phone'         => '9841000002',
                'lat'           => 27.6938,
                'lng'           => 85.3384,
                'approx_lat'    => 27.6940,
                'approx_lng'    => 85.3388,
                'status'        => 'approved',
                'unlock_fee'    => 75,
                'bedrooms'      => 2,
                'bathrooms'     => 2,
                'room_type'     => 'apartment',
                'amenities'     => ['WiFi', 'Generator', 'Parking', 'Security', 'Lift', 'AC'],
                'is_available'  => true,
                'views'         => 203,
            ],
            [
                'user_id'       => $owner2->id,
                'title'         => 'Budget Hostel Room in Koteshwor',
                'description'   => 'Affordable hostel-style room shared with up to 3 others. Clean shared bathrooms, a common study area, and a shared kitchen. Best suited for students on a tight budget. Regular cleaning service, fast WiFi, and water supply all day included in rent.',
                'price'         => 4500,
                'city'          => 'Kathmandu',
                'area'          => 'Koteshwor',
                'exact_address' => 'Near Koteshwor Chowk, House No. 77, Koteshwor-32',
                'phone'         => '9841000002',
                'lat'           => 27.6834,
                'lng'           => 85.3564,
                'approx_lat'    => 27.6836,
                'approx_lng'    => 85.3568,
                'status'        => 'approved',
                'unlock_fee'    => 30,
                'bedrooms'      => 1,
                'bathrooms'     => 1,
                'room_type'     => 'hostel',
                'amenities'     => ['WiFi', 'Kitchen', 'Laundry'],
                'is_available'  => true,
                'views'         => 56,
            ],
            [
                'user_id'       => $owner1->id,
                'title'         => 'Furnished Room in Patan Durbar Area',
                'description'   => 'Nicely furnished single room in a traditional Newari house just steps from Patan Durbar Square. Culturally rich neighborhood with multiple restaurants and cafes nearby. Very quiet environment — ideal for researchers, artists, or creative professionals.',
                'price'         => 9000,
                'city'          => 'Lalitpur',
                'area'          => 'Patan',
                'exact_address' => 'Mangal Bazaar, Near Patan Museum, Lalitpur-3',
                'phone'         => '9841000001',
                'lat'           => 27.6736,
                'lng'           => 85.3248,
                'approx_lat'    => 27.6739,
                'approx_lng'    => 85.3251,
                'status'        => 'approved',
                'unlock_fee'    => 50,
                'bedrooms'      => 1,
                'bathrooms'     => 1,
                'room_type'     => 'single',
                'amenities'     => ['WiFi', 'Hot Water', 'Balcony', 'Furnished'],
                'is_available'  => true,
                'views'         => 34,
            ],
            [
                'user_id'       => $owner2->id,
                'title'         => 'New Listing — Awaiting Admin Approval',
                'description'   => 'A freshly posted listing that is currently under review by the RoomRent team. Once an admin approves it, this room will become visible on the map and available for browsing.',
                'price'         => 10000,
                'city'          => 'Bhaktapur',
                'area'          => 'Suryavinayak',
                'exact_address' => 'Suryavinayak Road, Bhaktapur-5',
                'phone'         => '9841000002',
                'lat'           => 27.6717,
                'lng'           => 85.4298,
                'approx_lat'    => 27.6719,
                'approx_lng'    => 85.4301,
                'status'        => 'pending',
                'unlock_fee'    => 50,
                'bedrooms'      => 1,
                'bathrooms'     => 1,
                'room_type'     => 'single',
                'amenities'     => ['WiFi'],
                'is_available'  => true,
                'views'         => 0,
            ],
        ];

        foreach ($listings as $data) {
            Listing::create($data);
        }

        // ── Sample unlock for test user ────────────────────────────
        Unlock::create([
            'user_id'        => $user->id,
            'listing_id'     => 1,
            'amount_paid'    => 50,
            'payment_method' => 'fake',
            'transaction_id' => 'TXN-SEED000001',
            'payment_status' => 'completed',
            'paid_at'        => now()->subDays(2),
        ]);
    }
}
