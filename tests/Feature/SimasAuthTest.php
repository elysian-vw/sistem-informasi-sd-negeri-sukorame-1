<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SimasAuthTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test if landing page renders successfully when database is empty/refreshed.
     */
    public function test_landing_page_renders_successfully(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee(config('app.name'));
    }

    /**
     * Test that user can render login screen.
     */
    public function test_login_screen_renders_successfully(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }

    /**
     * Test admin login redirects to admin dashboard.
     */
    public function test_admin_login_redirects_to_admin_dashboard(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $response = $this->post('/login', [
            'email' => $admin->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($admin);
    }

    /**
     * Test kepala sekolah login redirects to kepala sekolah dashboard.
     */
    public function test_kepala_sekolah_login_redirects_to_kepala_sekolah_dashboard(): void
    {
        $kepsek = User::factory()->create([
            'role' => 'kepala_sekolah',
        ]);

        $response = $this->post('/login', [
            'email' => $kepsek->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('kepala_sekolah.dashboard'));
        $this->assertAuthenticatedAs($kepsek);
    }

    /**
     * Test role middleware blocks unauthorized access.
     */
    public function test_role_middleware_blocks_unauthorized_access(): void
    {
        // Create a regular student user
        $student = User::factory()->create([
            'role' => 'siswa',
        ]);

        // Attempting to access admin dashboard as a student should return 403 Forbidden
        $response = $this->actingAs($student)->get('/admin/dashboard');
        $response->assertStatus(403);
    }
}
