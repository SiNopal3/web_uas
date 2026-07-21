<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReportDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_reports_dashboard()
    {
        $response = $this->get('/reports');
        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_can_access_reports_dashboard()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/reports');
        $response->assertStatus(200);
        $response->assertViewIs('reports.index');
        $response->assertViewHas('summary');
    }

    public function test_api_reports_endpoint_returns_json_telemetry()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->getJson('/api/reports?action=dashboard');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'summary' => [
                'kpi_cards',
                'chart_distribution'
            ],
            'charts_gallery',
            'scheduled_reports',
            'history',
            'executive_narrative'
        ]);
    }

    public function test_user_can_store_scheduled_report_via_api()
    {
        $user = User::factory()->create();

        $payload = [
            'report_type' => 'Executive Report',
            'frequency' => 'Weekly',
            'recipients' => 'test@riskintel.com'
        ];

        $response = $this->actingAs($user)->postJson('/api/reports/scheduled', $payload);
        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Scheduled report successfully created.'
        ]);
        $this->assertDatabaseHas('scheduled_reports', [
            'report_type' => 'Executive Report',
            'frequency' => 'Weekly'
        ]);
    }
}
