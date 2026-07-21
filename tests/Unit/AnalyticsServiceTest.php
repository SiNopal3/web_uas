<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\AnalyticsService;
use App\Services\PredictionService;
use App\Services\DecisionSupportService;
use App\Services\RiskScoringService;
use App\Services\LexiconSentimentService;
use App\Http\Controllers\ApiController;

class AnalyticsServiceTest extends TestCase
{
    protected AnalyticsService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $scoringService = new RiskScoringService();
        $predictionService = new PredictionService($scoringService);
        $decisionService = new DecisionSupportService($predictionService);
        $sentimentService = new LexiconSentimentService();
        $apiController = new ApiController($scoringService, $sentimentService);
        $this->service = new AnalyticsService($predictionService, $decisionService, $scoringService, $apiController);
    }

    public function test_get_analytics_data_returns_all_required_sections()
    {
        $data = $this->service->getAnalyticsData();

        $this->assertIsArray($data);
        $this->assertArrayHasKey('header', $data);
        $this->assertArrayHasKey('kpi_cards', $data);
        $this->assertArrayHasKey('historical_trends', $data);
        $this->assertArrayHasKey('country_rankings', $data);
        $this->assertArrayHasKey('risk_distribution', $data);
        $this->assertArrayHasKey('weather_analytics', $data);
        $this->assertArrayHasKey('currency_analytics', $data);
        $this->assertArrayHasKey('news_analytics', $data);
        $this->assertArrayHasKey('forecast_analytics', $data);
        $this->assertArrayHasKey('heatmap_data', $data);
        $this->assertArrayHasKey('drill_down_map', $data);
        $this->assertArrayHasKey('operational_dashboard', $data);
        $this->assertArrayHasKey('executive_summary', $data);
        $this->assertArrayHasKey('charts', $data);
    }

    public function test_kpi_cards_count_and_structure()
    {
        $data = $this->service->getAnalyticsData();
        $kpiCards = $data['kpi_cards'];

        $this->assertIsArray($kpiCards);
        $this->assertCount(12, $kpiCards, 'Must contain exactly 12 Business Intelligence KPI cards.');
        
        $this->assertArrayHasKey('average_risk', $kpiCards);
        $this->assertArrayHasKey('highest_risk', $kpiCards);
        $this->assertArrayHasKey('lowest_risk', $kpiCards);
        $this->assertArrayHasKey('countries_monitored', $kpiCards);
        $this->assertArrayHasKey('average_inflation', $kpiCards);
        $this->assertArrayHasKey('currency_stability', $kpiCards);
        $this->assertArrayHasKey('weather_stability', $kpiCards);
        $this->assertArrayHasKey('average_news_sentiment', $kpiCards);
        $this->assertArrayHasKey('prediction_accuracy', $kpiCards);
        $this->assertArrayHasKey('decision_score', $kpiCards);
        $this->assertArrayHasKey('shipping_delay_index', $kpiCards);
        $this->assertArrayHasKey('operational_stability', $kpiCards);

        $this->assertEquals('Average Risk Score', $kpiCards['average_risk']['label']);
        $this->assertEquals('Highest Risk Country', $kpiCards['highest_risk']['label']);
        $this->assertEquals('Lowest Risk Country', $kpiCards['lowest_risk']['label']);
        $this->assertEquals('Countries Monitored', $kpiCards['countries_monitored']['label']);
    }

    public function test_country_rankings_contains_8_categories()
    {
        $data = $this->service->getAnalyticsData();
        $rankings = $data['country_rankings'];

        $this->assertArrayHasKey('highest_risk', $rankings);
        $this->assertArrayHasKey('lowest_risk', $rankings);
        $this->assertArrayHasKey('best_improvement', $rankings);
        $this->assertArrayHasKey('worst_decline', $rankings);
        $this->assertArrayHasKey('largest_currency_change', $rankings);
        $this->assertArrayHasKey('largest_inflation', $rankings);
        $this->assertArrayHasKey('best_news_sentiment', $rankings);
        $this->assertArrayHasKey('most_stable_country', $rankings);
    }

    public function test_filtering_by_country_and_period()
    {
        $filters = [
            'country' => 'China',
            'period' => '90d',
            'risk_level' => 'CRITICAL'
        ];

        $data = $this->service->getAnalyticsData($filters);

        $this->assertEquals('90d', $data['historical_trends']['active_period']);
        $this->assertNotEmpty($data['executive_summary']);
        $this->assertArrayHasKey('charts', $data);
    }

    public function test_php_rule_engine_narrative_summary()
    {
        $data = $this->service->getAnalyticsData();

        $this->assertIsString($data['executive_summary']);
        $this->assertNotEmpty($data['executive_summary']);
        $this->assertStringContainsString('intelligence', strtolower($data['executive_summary']));
    }
}
