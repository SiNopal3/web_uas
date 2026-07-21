<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\PredictionService;
use App\Services\RiskScoringService;

class PredictionServiceTest extends TestCase
{
    protected PredictionService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $scoringService = new RiskScoringService();
        $this->service = new PredictionService($scoringService);
    }

    public function test_get_prediction_data_returns_forecast_calculations()
    {
        $data = $this->service->getPredictionData();
        $this->assertIsArray($data);
        $this->assertArrayHasKey('kpi_cards', $data);
        $this->assertArrayHasKey('future_7d_risk', $data['kpi_cards']);
        $this->assertArrayHasKey('timeline', $data);
        $this->assertNotEmpty($data['timeline']);
    }

    public function test_calculate_confidence_score_returns_valid_range()
    {
        $mockCountry = ['name' => 'USA', 'iso' => 'US'];
        $confidence = $this->service->calculateConfidenceScore($mockCountry);
        $this->assertGreaterThanOrEqual(0, $confidence);
        $this->assertLessThanOrEqual(100, $confidence);
    }

    public function test_classify_shipping_delay_returns_correct_level_and_days()
    {
        $critical = $this->service->classifyShippingDelay(75.0);
        $this->assertEquals('Critical', $critical['level']);
        $this->assertEquals('7 Days', $critical['days']);

        $low = $this->service->classifyShippingDelay(20.0);
        $this->assertEquals('Low', $low['level']);
        $this->assertEquals('1 Day', $low['days']);
    }

    public function test_generate_country_recommendation_returns_rule_based_advice()
    {
        $highRiskCountry = [
            'future_7d_risk' => 70.0,
            'weather_risk' => 65.0,
            'news_risk' => 50.0,
            'currency_risk' => 30.0,
            'delay_probability_score' => 70.0
        ];
        $recommendation = $this->service->generateCountryRecommendation($highRiskCountry);
        $this->assertStringContainsString('Monitor Weather', $recommendation);
    }

    public function test_scenario_simulation_deltas_alter_prediction_results()
    {
        $baselineData = $this->service->getPredictionData();
        $simulatedData = $this->service->getPredictionData([
            'weather_delta' => 30,
            'inflation_delta' => 20
        ]);

        $this->assertNotEquals($baselineData['kpi_cards']['current_risk'], $simulatedData['kpi_cards']['current_risk']);
        $this->assertTrue($simulatedData['simulation_applied']);
    }
}
