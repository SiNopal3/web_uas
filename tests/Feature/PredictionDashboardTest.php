<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class PredictionDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login_for_prediction_routes()
    {
        $responseIndex = $this->get('/prediction');
        $responseIndex->assertStatus(302);
        $responseIndex->assertRedirect('/login');

        $responseData = $this->get('/api/prediction/data');
        $responseData->assertStatus(302);
        $responseData->assertRedirect('/login');
    }

    public function test_authenticated_user_can_access_prediction_dashboard_view()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/prediction');

        $response->assertStatus(200);
        $response->assertViewIs('prediction.index');
        $response->assertViewHas('data');
    }

    public function test_authenticated_user_can_fetch_prediction_ajax_json()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->getJson('/api/prediction/data');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'data' => [
                'header',
                'kpi_cards',
                'timeline',
                'trend_analysis',
                'factors',
                'shipping_delay',
                'ranking_table',
                'heatmap',
                'summary',
                'charts'
            ]
        ]);
    }

    public function test_ajax_endpoint_processes_scenario_simulation_deltas()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->getJson('/api/prediction/data?weather_delta=25&news_delta=15');

        $response->assertStatus(200);
        $response->assertJsonPath('data.simulation_applied', true);
    }
}
