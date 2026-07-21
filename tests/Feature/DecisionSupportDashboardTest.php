<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class DecisionSupportDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login_for_decision_support_routes()
    {
        $responseIndex = $this->get('/decision-support');
        $responseIndex->assertStatus(302);
        $responseIndex->assertRedirect('/login');

        $responseData = $this->get('/api/decision-support/data');
        $responseData->assertStatus(302);
        $responseData->assertRedirect('/login');
    }

    public function test_authenticated_user_can_access_decision_support_dashboard_view()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/decision-support');

        $response->assertStatus(200);
        $response->assertViewIs('decision-support.index');
        $response->assertViewHas('data');
    }

    public function test_authenticated_user_can_fetch_decision_support_ajax_json()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->getJson('/api/decision-support/data');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'data' => [
                'header',
                'kpi_cards',
                'decision_matrix',
                'recommended_actions',
                'financial_impact',
                'alternative_routes',
                'alternative_suppliers',
                'action_timeline',
                'emergency_dashboard',
                'strategy_comparison',
                'executive_summary',
                'charts',
                'rules_catalog_count',
                'rules_triggered_count',
                'simulation_applied'
            ]
        ]);
        $response->assertJson(['success' => true]);
    }

    public function test_scenario_simulator_query_parameters_alter_response_data()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->getJson('/api/decision-support/data?weather_delta=25&delay_delta=20');

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'data' => [
                'simulation_applied' => true
            ]
        ]);
    }
}
