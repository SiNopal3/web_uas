<?php

namespace Tests\Unit;

use App\Services\SystemHealthService;
use Tests\TestCase;

class SystemHealthTest extends TestCase
{
    protected SystemHealthService $healthService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->healthService = app(SystemHealthService::class);
    }

    public function test_get_system_diagnostics_returns_comprehensive_telemetry(): void
    {
        $diagnostics = $this->healthService->getSystemHealth();

        $this->assertArrayHasKey('cpu_usage', $diagnostics);
        $this->assertArrayHasKey('memory', $diagnostics);
        $this->assertArrayHasKey('storage', $diagnostics);
        $this->assertArrayHasKey('uptime', $diagnostics);
        $this->assertArrayHasKey('database', $diagnostics);

        $this->assertIsNumeric($diagnostics['cpu_usage']);
        $this->assertArrayHasKey('percentage', $diagnostics['memory']);
    }

    public function test_api_proxies_status_format_and_latencies(): void
    {
        $proxies = $this->healthService->getApiMonitoring();
        $this->assertArrayHasKey('open_meteo', $proxies);
        $this->assertArrayHasKey('world_bank', $proxies);
        $this->assertArrayHasKey('exchange_rate', $proxies);
        $this->assertArrayHasKey('gnews', $proxies);

        foreach ($proxies as $key => $info) {
            $this->assertArrayHasKey('status', $info);
            $this->assertArrayHasKey('latency_ms', $info);
            $this->assertEquals('Online', $info['status']);
        }
    }
}
