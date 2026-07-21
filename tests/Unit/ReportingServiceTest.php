<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\ReportingService;
use App\Services\RiskScoringService;
use App\Services\SystemHealthService;
use App\Services\AuditLogService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReportingServiceTest extends TestCase
{
    use RefreshDatabase;

    protected ReportingService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(ReportingService::class);
    }

    public function test_get_dashboard_summary_returns_expected_structure()
    {
        $summary = $this->service->getDashboardSummary();

        $this->assertIsArray($summary);
        $this->assertArrayHasKey('kpi_cards', $summary);
        $this->assertArrayHasKey('chart_distribution', $summary);
        $this->assertArrayHasKey('generated_reports', $summary['kpi_cards']);
        $this->assertArrayHasKey('scheduled_reports', $summary['kpi_cards']);
    }

    public function test_get_report_data_handles_all_9_report_types()
    {
        $types = [
            'Executive Report',
            'Country Report',
            'Weather Report',
            'Prediction Report',
            'Risk Report',
            'Analytics Report',
            'Administration Report',
            'Notification Report',
            'System Health Report'
        ];

        foreach ($types as $type) {
            $data = $this->service->getReportData($type);
            $this->assertIsArray($data);
            $this->assertArrayHasKey('headers', $data);
            $this->assertArrayHasKey('items', $data);
            $this->assertArrayHasKey('summary', $data);
        }
    }

    public function test_build_custom_report_returns_configured_payload()
    {
        $params = [
            'kpi' => 'Port Congestion Latency',
            'country' => 'Singapore',
            'date_range' => 'Last 30 Days',
            'chart_type' => 'Bar'
        ];

        $result = $this->service->buildCustomReport($params);

        $this->assertIsArray($result);
        $this->assertEquals('Custom Interactive Report', $result['report_type']);
        $this->assertArrayHasKey('chart_config', $result);
        $this->assertEquals('bar', $result['chart_config']['type']);
    }

    public function test_generate_rule_based_executive_narrative_returns_recommendations()
    {
        $narrative = $this->service->generateRuleBasedExecutiveNarrative(['country' => 'Japan']);

        $this->assertIsArray($narrative);
        $this->assertArrayHasKey('risk_overview', $narrative);
        $this->assertArrayHasKey('country_overview', $narrative);
        $this->assertArrayHasKey('business_recommendations', $narrative);
        $this->assertNotEmpty($narrative['business_recommendations']);
    }

    public function test_get_charts_gallery_data_returns_all_10_chart_types()
    {
        $gallery = $this->service->getChartsGalleryData();

        $expectedKeys = ['line', 'bar', 'pie', 'doughnut', 'radar', 'scatter', 'area', 'gauge', 'heatmap', 'treemap'];
        foreach ($expectedKeys as $key) {
            $this->assertArrayHasKey($key, $gallery);
        }
    }
}
