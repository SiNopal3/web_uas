<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\RiskScoringService;

class RiskScoringUnitTest extends TestCase
{
    /**
     * Uji akurasi kalkulasi rumus bobot:
     * Weather 30%, Inflation 20%, News 40%, Currency 10%
     */
    public function test_calculate_score_returns_correct_weighted_total(): void
    {
        $service = new RiskScoringService();
        
        // Weather: 50 * 0.3 = 15
        // Inflation: 40 * 0.2 = 8
        // News: 80 * 0.4 = 32
        // Currency: 20 * 0.1 = 2
        // Total = 15 + 8 + 32 + 2 = 57 (MEDIUM RISK)
        $result = $service->calculateScore(50, 40, 80, 20);

        $this->assertEquals(57.0, $result['total_risk']);
        $this->assertEquals('MEDIUM RISK', $result['category']);
        $this->assertEquals('warning', $result['status_color']);
    }

    /**
     * Uji penentuan kategori risiko tinggi (>= 65) dan rendah (< 35).
     */
    public function test_risk_category_classification_thresholds(): void
    {
        $service = new RiskScoringService();

        // High risk check (100 * 0.3 + 100 * 0.2 + 100 * 0.4 + 100 * 0.1 = 100)
        $high = $service->calculateScore(100, 100, 100, 100);
        $this->assertEquals('HIGH RISK', $high['category']);
        $this->assertEquals('danger', $high['status_color']);

        // Low risk check (10 * 0.3 + 10 * 0.2 + 10 * 0.4 + 10 * 0.1 = 10)
        $low = $service->calculateScore(10, 10, 10, 10);
        $this->assertEquals('LOW RISK', $low['category']);
        $this->assertEquals('success', $low['status_color']);
    }

    /**
     * Uji pembatasan (clamping) nilai di luar batas 0 - 100.
     */
    public function test_input_clamping_to_valid_range(): void
    {
        $service = new RiskScoringService();
        
        // Nilai ekstrem di atas 100 harus dibatasi ke 100
        $result = $service->calculateScore(250, -50, 150, -10);
        
        // Weather 100 * 0.3 = 30
        // Inflation 0 * 0.2 = 0
        // News 100 * 0.4 = 40
        // Currency 0 * 0.1 = 0
        // Total = 70 (HIGH RISK)
        $this->assertEquals(70.0, $result['total_risk']);
    }
}
