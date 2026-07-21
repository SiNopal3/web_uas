<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class ExecutiveDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login_for_executive_routes()
    {
        $responseIndex = $this->get('/executive');
        $responseIndex->assertStatus(302);
        $responseIndex->assertRedirect('/login');

        $responseData = $this->get('/api/executive/data');
        $responseData->assertStatus(302);
        $responseData->assertRedirect('/login');
    }

    public function test_authenticated_user_can_access_executive_dashboard_view()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/executive');

        $response->assertStatus(200);
        $response->assertViewIs('executive.index');
        $response->assertViewHas('data');
    }

    public function test_authenticated_user_can_fetch_executive_ajax_data()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->getJson('/api/executive/data');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'data' => [
                'header',
                'kpi_cards',
                'charts',
                'top_high_risk_countries',
                'top_safest_countries',
                'alerts',
                'executive_summary'
            ]
        ]);
    }
}
