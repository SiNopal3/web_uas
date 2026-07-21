<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SystemReportTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_system_reports()
    {
        $response = $this->get('/reports/system');
        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_can_access_system_reports()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/reports/system');
        $response->assertStatus(200);
    }

    public function test_authenticated_user_can_fetch_executive_and_analytics_subsystems()
    {
        $user = User::factory()->create();

        $resExec = $this->actingAs($user)->get('/reports/executive');
        $resExec->assertStatus(200);

        $resAnal = $this->actingAs($user)->get('/reports/analytics');
        $resAnal->assertStatus(200);
    }
}
