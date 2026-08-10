<?php

namespace Tests\Feature;

use App\Models\AuditLog;
use App\Models\Listing;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminCrmTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_update_site_settings(): void
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $response = $this->actingAs($admin)->post(route('admin.settings.update'), [
            'site_name' => 'Rentivo Nepal',
            'support_email' => 'help@rentivo.rent',
            'support_phone' => '+977 9841000000',
            'default_unlock_fee' => 75,
            'referral_reward' => 30,
            'khalti_fake_mode' => '1',
        ]);

        $response->assertRedirect();
        $this->assertEquals('Rentivo Nepal', Setting::get('site_name'));
        $this->assertEquals('help@rentivo.rent', Setting::get('support_email'));

        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $admin->id,
            'action' => 'admin_update_settings',
        ]);
    }

    public function test_admin_can_edit_listing_directly(): void
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin2@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $owner = User::create([
            'name' => 'Owner',
            'email' => 'owner@example.com',
            'password' => bcrypt('password'),
            'role' => 'owner',
        ]);

        $listing = Listing::create([
            'user_id' => $owner->id,
            'title' => 'Original Room',
            'description' => 'Original description',
            'price' => 8000,
            'city' => 'Kathmandu',
            'area' => 'Thamel',
            'exact_address' => 'House 12',
            'phone' => '9800000000',
            'lat' => 27.71, 'lng' => 85.31,
            'approx_lat' => 27.71, 'approx_lng' => 85.31,
            'status' => 'pending',
            'unlock_fee' => 50,
            'is_available' => true,
        ]);

        $response = $this->actingAs($admin)->put(route('admin.listings.update', $listing), [
            'title' => 'Updated by Admin',
            'description' => 'Updated description content',
            'price' => 9500,
            'unlock_fee' => 60,
            'city' => 'Kathmandu',
            'area' => 'Thamel',
            'exact_address' => 'House 12 Updated',
            'phone' => '9800000000',
            'room_type' => 'single',
            'status' => 'approved',
        ]);

        $response->assertRedirect(route('admin.listings.show', $listing));
        $this->assertEquals('Updated by Admin', $listing->fresh()->title);
        $this->assertEquals('approved', $listing->fresh()->status);

        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $admin->id,
            'action' => 'admin_update_listing',
            'target_id' => $listing->id,
        ]);
    }

    public function test_audit_logs_can_be_viewed_by_admin(): void
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin3@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        AuditLog::log('test_action', ['foo' => 'bar']);

        $response = $this->actingAs($admin)->get(route('admin.audit-logs.index'));
        $response->assertStatus(200);
        $response->assertSee('test_action');
    }
}
