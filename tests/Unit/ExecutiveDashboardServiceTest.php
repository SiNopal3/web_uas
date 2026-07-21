<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\ExecutiveDashboardService;
use App\Services\RiskScoringService;

class ExecutiveDashboardServiceTest extends TestCase
{
    protected ExecutiveDashboardService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $scoringService = new RiskScoringService();
        $this->service = new ExecutiveDashboardService($scoringService);
    }

    public function test_collect_countries_data_returns_monitored_nations()
    {
        $countries = $this->service->collectCountriesData();
        $this->assertIsArray($countries);
        $this->assertNotEmpty($countries);
        $this->assertArrayHasKey('final_risk_score', $countries[0]);
        $this->assertArrayHasKey('status', $countries[0]);
    }

    public function test_calculate_averages_returns_valid_metrics()
    {
        $mockCountries = [
            ['final_risk_score' => 40.0, 'inflation_rate' => 3.0],
            ['final_risk_score' => 60.0, 'inflation_rate' => 5.0]
        ];

        $averages = $this->service->calculateAverages($mockCountries);

        $this->assertEquals(50.0, $averages['global_risk']);
        $this->assertEquals(4.0, $averages['inflation']);
    }

    public function test_calculate_rankings_orders_high_risk_and_safest_correctly()
    {
        $mockCountries = [
            ['name' => 'CountryA', 'final_risk_score' => 30.0, 'status' => 'LOW RISK', 'status_color' => 'success', 'currency' => 'USD', 'exchange_rate' => 1.0],
            ['name' => 'CountryB', 'final_risk_score' => 75.0, 'status' => 'HIGH RISK', 'status_color' => 'danger', 'currency' => 'EUR', 'exchange_rate' => 0.92]
        ];

        $rankings = $this->service->calculateRankings($mockCountries);

        $this->assertEquals('CountryB', $rankings['top_high_risk'][0]['name']);
        $this->assertEquals('CountryA', $rankings['top_safest'][0]['name']);
        $this->assertEquals('CountryB', $rankings['highest_risk_country']['name']);
        $this->assertEquals('CountryA', $rankings['safest_country']['name']);
    }

    public function test_generate_alerts_creates_appropriate_alert_types()
    {
        $mockCountries = [
            ['name' => 'StormLand', 'weather_risk' => 50.0, 'inflation_rate' => 2.0, 'news_risk' => 30.0, 'currency_risk' => 10.0, 'currency' => 'USD']
        ];

        $alerts = $this->service->generateAlerts($mockCountries);

        $this->assertNotEmpty($alerts);
        $this->assertEquals('Weather Alert', $alerts[0]['type']);
        $this->assertEquals('Critical', $alerts[0]['level']);
    }

    public function test_generate_executive_summary_returns_rule_based_text()
    {
        $countries = $this->service->collectCountriesData();
        $averages = ['global_risk' => 42.5, 'inflation' => 3.8];
        $rankings = $this->service->calculateRankings($countries);

        $summary = $this->service->generateExecutiveSummary($countries, $averages, $rankings);

        $this->assertIsString($summary);
        $this->assertStringContainsString('42.5%', $summary);
    }
}
