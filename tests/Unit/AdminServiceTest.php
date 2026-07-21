<?php

namespace Tests\Unit;

use App\Services\AdminService;
use App\Models\User;
use App\Models\SystemSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminServiceTest extends TestCase
{
    use RefreshDatabase;

    protected AdminService $adminService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->adminService = app(AdminService::class);
    }

    public function test_get_dashboard_kpi_cards_returns_complete_telemetry_fields(): void
    {
        User::factory()->count(3)->create(['status' => 'active']);

        $kpi = $this->adminService->getDashboardKpiCards();

        $this->assertArrayHasKey('total_users', $kpi);
        $this->assertArrayHasKey('active_users', $kpi);
        $this->assertArrayHasKey('api_status', $kpi);
        $this->assertArrayHasKey('server_health', $kpi);
        $this->assertArrayHasKey('database_health', $kpi);
        $this->assertArrayHasKey('notification_count', $kpi);
        $this->assertArrayHasKey('system_uptime', $kpi);
        $this->assertArrayHasKey('storage_usage', $kpi);
        $this->assertEquals(3, $kpi['total_users']);
    }

    public function test_system_settings_management_and_updates(): void
    {
        $settings = $this->adminService->getSystemSettings();
        $this->assertArrayHasKey('theme', $settings);
        $this->assertEquals('dark_glassmorphism', $settings['theme']);

        $admin = User::factory()->create(['username' => 'admin']);
        $updated = $this->adminService->updateSystemSettings([
            'theme' => 'dark_high_contrast',
            'refresh_interval' => '15'
        ], $admin);

        $this->assertEquals('dark_high_contrast', $updated['theme']);
        $this->assertEquals('15', $updated['refresh_interval']);
    }

    public function test_generate_automatic_dashboard_summary_produces_structured_evaluation(): void
    {
        $summary = $this->adminService->generateAutomaticDashboardSummary();

        $this->assertArrayHasKey('status_badge', $summary);
        $this->assertArrayHasKey('timestamp', $summary);
        $this->assertArrayHasKey('system_health', $summary);
        $this->assertArrayHasKey('performance', $summary);
        $this->assertArrayHasKey('security', $summary);
        $this->assertArrayHasKey('recommendations', $summary);
        $this->assertIsArray($summary['recommendations']);
        $this->assertEquals('ENTERPRISE CERTIFIED HEALTHY', $summary['status_badge']);
    }
}
