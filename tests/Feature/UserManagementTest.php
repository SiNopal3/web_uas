<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        app(\App\Services\UserManagementService::class)->ensureDefaultRolesAndPermissions();

        $adminRole = Role::where('name', 'Administrator')->first();
        $this->admin = User::factory()->create([
            'username' => 'admin',
            'email' => 'admin@riskintel.com',
            'role_id' => $adminRole?->id,
        ]);
        if ($adminRole) {
            $this->admin->roles()->attach($adminRole->id);
        }
    }

    public function test_administrator_can_create_new_enterprise_user(): void
    {
        $payload = [
            'name' => 'David Manager',
            'email' => 'david@riskintel.com',
            'username' => 'david_mgr',
            'password' => 'SecretPass123!',
            'role' => 'Manager',
            'status' => 'active'
        ];

        $response = $this->actingAs($this->admin)->postJson('/admin/users', $payload);
        $response->assertStatus(200)
                 ->assertJson(['success' => true]);

        $this->assertDatabaseHas('users', [
            'email' => 'david@riskintel.com',
            'username' => 'david_mgr',
            'status' => 'active'
        ]);

        $newUser = User::where('email', 'david@riskintel.com')->first();
        $this->assertEquals('Manager', $newUser->role->name);
    }

    public function test_administrator_can_update_user_role_and_status(): void
    {
        $viewerRole = Role::where('name', 'Viewer')->first();
        $targetUser = User::factory()->create([
            'name' => 'Elena Viewer',
            'email' => 'elena@riskintel.com',
            'role_id' => $viewerRole?->id,
            'status' => 'active'
        ]);

        $updatePayload = [
            'name' => 'Elena Senior Analyst',
            'email' => 'elena@riskintel.com',
            'username' => 'elena_anl',
            'role' => 'Analyst',
            'status' => 'suspended'
        ];

        $response = $this->actingAs($this->admin)->putJson('/admin/users/' . $targetUser->id, $updatePayload);
        $response->assertStatus(200)
                 ->assertJson(['success' => true]);

        $targetUser->refresh();
        $this->assertEquals('Elena Senior Analyst', $targetUser->name);
        $this->assertEquals('suspended', $targetUser->status);
        $this->assertEquals('Analyst', $targetUser->role->name);
    }

    public function test_administrator_can_delete_user(): void
    {
        $targetUser = User::factory()->create(['email' => 'to_delete@riskintel.com']);

        $response = $this->actingAs($this->admin)->deleteJson('/admin/users/' . $targetUser->id);
        $response->assertStatus(200)
                 ->assertJson(['success' => true]);

        $this->assertDatabaseMissing('users', ['id' => $targetUser->id]);
    }
}
