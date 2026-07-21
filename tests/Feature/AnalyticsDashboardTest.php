<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class AnalyticsDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login_for_analytics_routes()
    {
        $responseIndex = $this->get('/analytics');
        $responseIndex->assertStatus(302);
        $responseIndex->assertRedirect('/login');

        $responseData = $this->get('/api/analytics/data');
        $responseData->assertStatus(302);
        $responseData->assertRedirect('/login');
    }

    public function test_authenticated_user_can_access_analytics_dashboard_view()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/analytics');

        $response->assertStatus(200);
        $response->assertViewIs('analytics.currency');
        $response->assertViewHas('data');
    }

    public function test_authenticated_user_can_fetch_analytics_ajax_json()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->getJson('/api/analytics/data');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'data' => [
                'header',
                'kpi_cards',
                'historical_trends',
                'country_rankings',
                'risk_distribution',
                'weather_analytics',
                'currency_analytics',
                'news_analytics',
                'forecast_analytics',
                'heatmap_data',
                'drill_down_map',
                'operational_dashboard',
                'executive_summary',
                'charts'
            ]
        ]);
        $response->assertJson(['success' => true]);
    }

    public function test_analytics_filter_parameters_alter_response_data()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->getJson('/api/analytics/data?period=180d&risk_level=CRITICAL');

        $response->assertStatus(200);
        $response->assertJsonPath('data.historical_trends.active_period', '180d');
        $response->assertJson(['success' => true]);
    }
}
