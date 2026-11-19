<?php

namespace Tests\Feature;

use App\Models\Provider;
use App\Models\User;
use Tests\TestCase;

class AdminTest extends TestCase
{
    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get('/admin');
        $response->assertRedirect('/login');
    }

    public function test_non_admin_gets_forbidden_status(): void
    {
        $customer = User::where('role', 'customer')->first();
        if (!$customer) {
            $customer = User::factory()->create(['role' => 'customer']);
        }

        $response = $this->actingAs($customer)->get('/admin');
        $response->assertStatus(403);
    }

    public function test_admin_can_access_dashboard(): void
    {
        $admin = User::where('role', 'admin')->first();
        $this->assertNotNull($admin, 'Admin user should exist from seeding.');

        $response = $this->actingAs($admin)->get('/admin');
        $response->assertStatus(200);
        $response->assertSee('Operational Overview');
        $response->assertSee('Urban');
    }

    public function test_admin_can_access_providers_list(): void
    {
        $admin = User::where('role', 'admin')->first();

        $response = $this->actingAs($admin)->get('/admin/providers');
        $response->assertStatus(200);
        $response->assertSee('Professionals &amp; KYC Hub', false);
    }

    public function test_admin_can_view_provider_detail_and_kyc(): void
    {
        $admin = User::where('role', 'admin')->first();
        $provider = Provider::first();
        $this->assertNotNull($provider, 'At least one provider should exist from seeding.');

        $response = $this->actingAs($admin)->get("/admin/providers/{$provider->user_id}");
        $response->assertStatus(200);
        $response->assertSee('Provider Profile &amp; KYC Audit', false);
    }

    public function test_admin_can_access_services_catalog(): void
    {
        $admin = User::where('role', 'admin')->first();

        $response = $this->actingAs($admin)->get('/admin/services');
        $response->assertStatus(200);
        $response->assertSee('Marketplace Services Catalog');
    }

    public function test_admin_can_access_bookings_dispatch(): void
    {
        $admin = User::where('role', 'admin')->first();

        $response = $this->actingAs($admin)->get('/admin/bookings');
        $response->assertStatus(200);
        $response->assertSee('Live Booking &amp; Dispatch Control', false);
    }

    public function test_admin_can_access_support_tickets(): void
    {
        $admin = User::where('role', 'admin')->first();

        $response = $this->actingAs($admin)->get('/admin/tickets');
        $response->assertStatus(200);
        $response->assertSee('Support Tickets &amp; Disputes', false);
    }

    public function test_navigation_hides_admin_console_from_guest(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertDontSee('Admin Console');
    }

    public function test_navigation_hides_admin_console_from_end_user(): void
    {
        $customer = User::where('role', 'customer')->first();
        $this->assertNotNull($customer);

        // Check landing page
        $response = $this->actingAs($customer)->get('/');
        $response->assertStatus(200);
        $response->assertDontSee('Admin Console');

        // Check user dashboard
        $dashResponse = $this->actingAs($customer)->get('/dashboard');
        $dashResponse->assertStatus(200);
        $dashResponse->assertDontSee('Admin Console');
    }

    public function test_navigation_shows_admin_console_to_admin(): void
    {
        $admin = User::where('role', 'admin')->first();
        $this->assertNotNull($admin);

        // Check landing page
        $response = $this->actingAs($admin)->get('/');
        $response->assertStatus(200);
        $response->assertSee('Admin Console');

        // Check dashboard
        $dashResponse = $this->actingAs($admin)->get('/dashboard');
        $dashResponse->assertStatus(200);
        $dashResponse->assertSee('Admin Console');
    }

    public function test_navigation_shows_admin_console_to_support(): void
    {
        $support = User::where('email', 'support@urbanapp.com')->first();
        if (!$support) {
            $support = User::create([
                'full_name' => 'Support Agent',
                'email' => 'support@urbanapp.com',
                'phone' => '+919999000000',
                'password' => bcrypt('password'),
                'role' => 'support',
                'status' => 'active',
            ]);
        }

        $response = $this->actingAs($support)->get('/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Admin Console');
    }
}
