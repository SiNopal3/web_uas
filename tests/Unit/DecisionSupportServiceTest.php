<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\DecisionSupportService;
use App\Services\PredictionService;
use App\Services\RiskScoringService;

class DecisionSupportServiceTest extends TestCase
{
    protected DecisionSupportService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $scoringService = new RiskScoringService();
        $predictionService = new PredictionService($scoringService);
        $this->service = new DecisionSupportService($predictionService);
    }

    public function test_get_expert_rules_catalog_returns_at_least_40_rules()
    {
        $catalog = $this->service->getExpertRulesCatalog();
        $this->assertIsArray($catalog);
        $this->assertGreaterThanOrEqual(40, count($catalog), 'Expert Rules Catalog must contain at least 40 rules.');
    }

    public function test_get_decision_support_data_returns_all_required_sections()
    {
        $data = $this->service->getDecisionSupportData();
        $this->assertIsArray($data);
        $this->assertArrayHasKey('header', $data);
        $this->assertArrayHasKey('kpi_cards', $data);
        $this->assertArrayHasKey('decision_matrix', $data);
        $this->assertArrayHasKey('recommended_actions', $data);
        $this->assertArrayHasKey('financial_impact', $data);
        $this->assertArrayHasKey('alternative_routes', $data);
        $this->assertArrayHasKey('alternative_suppliers', $data);
        $this->assertArrayHasKey('action_timeline', $data);
        $this->assertArrayHasKey('emergency_dashboard', $data);
        $this->assertArrayHasKey('strategy_comparison', $data);
        $this->assertArrayHasKey('executive_summary', $data);
        $this->assertArrayHasKey('charts', $data);
    }

    public function test_evaluate_expert_rules_triggers_actions()
    {
        // Mocking high-risk country data to trigger CRITICAL rules
        $mockCountries = [
            [
                'name' => 'China',
                'iso' => 'CN',
                'future_7d_risk' => 85.0,
                'current_risk' => 82.0,
                'delay_probability_score' => 75.0,
                'weather_risk' => 65.0,
                'inflation_risk' => 50.0,
                'currency_risk' => 45.0,
                'news_risk' => 60.0,
                'port_risk' => 70.0,
                'shipping_delay' => ['days' => '7 Days', 'level' => 'Critical']
            ]
        ];

        $evaluation = $this->service->evaluateExpertRules($mockCountries);
        $this->assertArrayHasKey('actions', $evaluation);
        $this->assertNotEmpty($evaluation['actions']);
        $this->assertEquals('CRITICAL', $evaluation['actions'][0]['priority']);
    }

    public function test_calculate_financial_impact_returns_valid_dollar_components()
    {
        $mockCountries = [
            [
                'future_7d_risk' => 70.0,
                'delay_probability_score' => 60.0,
                'weather_risk' => 50.0,
                'inflation_risk' => 40.0,
                'currency_risk' => 35.0,
                'port_risk' => 55.0
            ]
        ];

        $financials = $this->service->calculateFinancialImpact($mockCountries, ['RULE-001']);
        $this->assertArrayHasKey('total_exposure', $financials);
        $this->assertArrayHasKey('delay_cost', $financials);
        $this->assertArrayHasKey('insurance_cost', $financials);
        $this->assertArrayHasKey('breakdown_table', $financials);
        $this->assertGreaterThan(0, $financials['total_exposure']);
    }

    public function test_scenario_simulator_deltas_alter_decision_score_and_financials()
    {
        $baseData = $this->service->getDecisionSupportData();
        $simulatedData = $this->service->getDecisionSupportData([
            'weather_delta' => 30,
            'inflation_delta' => 20,
            'delay_delta' => 30,
            'prediction_delta' => 25
        ]);

        $this->assertTrue($simulatedData['simulation_applied']);
        $this->assertGreaterThan($baseData['kpi_cards']['decision_score'], $simulatedData['kpi_cards']['decision_score']);
        $this->assertGreaterThan($baseData['financial_impact']['total_exposure'], $simulatedData['financial_impact']['total_exposure']);
    }
}
