<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Pastikan role default dan permission diinisialisasi untuk test
        app(\App\Services\UserManagementService::class)->ensureDefaultRolesAndPermissions();
    }

    public function test_guest_is_redirected_to_login_when_accessing_admin_routes(): void
    {
        $routes = ['/admin', '/admin/users', '/admin/system', '/admin/logs', '/admin/settings'];
        foreach ($routes as $route) {
            $response = $this->get($route);
            $response->assertRedirect('/login');
        }
    }

    public function test_non_admin_user_is_denied_or_redirected(): void
    {
        $viewerRole = Role::where('name', 'Viewer')->first();
        $user = User::factory()->create([
            'username' => 'regular_user',
            'role_id' => $viewerRole?->id,
        ]);

        $response = $this->actingAs($user)->get('/admin');
        $response->assertStatus(302); // Redirect back / root with error
    }

    public function test_administrator_can_access_all_enterprise_admin_pages(): void
    {
        $adminRole = Role::where('name', 'Administrator')->first();
        $admin = User::factory()->create([
            'username' => 'admin',
            'email' => 'admin@riskintel.com',
            'role_id' => $adminRole?->id,
        ]);
        if ($adminRole) {
            $admin->roles()->attach($adminRole->id);
        }

        $routes = ['/admin', '/admin/users', '/admin/system', '/admin/logs', '/admin/settings'];
        foreach ($routes as $route) {
            $response = $this->actingAs($admin)->get($route);
            $response->assertStatus(200);
            $response->assertViewIs('admin.index');
        }
    }

    public function test_administrator_can_fetch_dashboard_telemetry_json(): void
    {
        $admin = User::factory()->create(['username' => 'admin']);

        $response = $this->actingAs($admin)->getJson('/api/admin/dashboard');
        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'kpi' => [
                         'total_users',
                         'active_users',
                         'api_status',
                         'server_health',
                         'database_health',
                         'notification_count',
                         'system_uptime',
                         'storage_usage',
                         'cpu_usage',
                         'memory_usage'
                     ],
                     'summary',
                     'settings'
                 ]);
    }
}
