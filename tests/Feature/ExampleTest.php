<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test home page returns 200 OK.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    /**
     * Test gallery page returns 200 OK.
     */
    public function test_gallery_page_loads(): void
    {
        $response = $this->get('/gallery');
        $response->assertStatus(200);
    }

    /**
     * Test admin dashboard redirects guest to login.
     */
    public function test_admin_dashboard_requires_authentication(): void
    {
        $response = $this->get('/admin');
        $response->assertRedirect('/login');
    }

    /**
     * Test authenticated admin can access dashboard.
     */
    public function test_authenticated_admin_can_access_dashboard(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@gallery.com',
        ]);

        $response = $this->actingAs($admin)->get('/admin');
        $response->assertStatus(200);
    }
}
