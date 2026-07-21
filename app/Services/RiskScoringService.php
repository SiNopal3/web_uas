<?php

namespace App\Services;

use App\Models\RiskScore;
use App\Models\Country;
use Illuminate\Support\Facades\Log;

class RiskScoringService
{
    /**
     * Menghitung dan memvalidasi skor risiko rantai pasok global berbobot.
     *
     * Bobot Formula:
     * - Weather Risk: 30% (0.30)
     * - Inflation Risk: 20% (0.20)
     * - News Sentiment Risk: 40% (0.40)
     * - Currency Risk: 10% (0.10)
     */
    public function calculateScore(
        float $weatherRisk,
        float $inflationRisk,
        float $newsRisk,
        float $currencyRisk,
        ?int $countryId = null
    ): array {
        // Normalisasi dan batasi setiap komponen ke rentang 0 - 100
        $weatherRisk = max(0, min(100, $weatherRisk));
        $inflationRisk = max(0, min(100, $inflationRisk));
        $newsRisk = max(0, min(100, $newsRisk));
        $currencyRisk = max(0, min(100, $currencyRisk));

        // Hitung total skor berbobot
        $totalRisk = ($weatherRisk * 0.30) + ($inflationRisk * 0.20) + ($newsRisk * 0.40) + ($currencyRisk * 0.10);
        $totalRisk = round(max(0, min(100, $totalRisk)), 1);

        // Tentukan kategori risiko
        if ($totalRisk >= 65) {
            $category = 'HIGH RISK';
            $statusColor = 'danger';
        } elseif ($totalRisk >= 35) {
            $category = 'MEDIUM RISK';
            $statusColor = 'warning';
        } else {
            $category = 'LOW RISK';
            $statusColor = 'success';
        }

        // Simpan ke database jika countryId tersedia
        if ($countryId) {
            try {
                RiskScore::create([
                    'country_id' => $countryId,
                    'weather_risk' => round($weatherRisk, 1),
                    'inflation_risk' => round($inflationRisk, 1),
                    'exchange_rate_risk' => round($currencyRisk, 1),
                    'news_sentiment_risk' => round($newsRisk, 1),
                    'total_risk' => $totalRisk,
                ]);
            } catch (\Throwable $e) {
                Log::error('Gagal menyimpan catatan riwayat RiskScore: ' . $e->getMessage());
            }
        }

        return [
            'total_risk' => $totalRisk,
            'category' => $category,
            'status_color' => $statusColor,
            'breakdown' => [
                'weather' => round($weatherRisk, 1),
                'inflation' => round($inflationRisk, 1),
                'news' => round($newsRisk, 1),
                'currency' => round($currencyRisk, 1),
            ]
        ];
    }

    /**
     * Menghitung perkiraan risiko berdasarkan indikator mentah dari eksternal API.
     */
    public function estimateFromRawIndicators(
        float $windSpeed,
        float $rainMm,
        float $inflationRate,
        string $sentimentCategory,
        float $exchangeRateChangePercent
    ): array {
        // 1. Weather risk (berdasarkan kecepatan angin & curah hujan)
        $weatherRisk = min(100, ($windSpeed * 1.5) + ($rainMm * 2.5));

        // 2. Inflation risk (inflasi > 10% dianggap risiko tinggi 100)
        $inflationRisk = min(100, max(0, $inflationRate * 10));

        // 3. News sentiment risk
        $newsRisk = match (strtoupper($sentimentCategory)) {
            'NEGATIVE', 'CRITICAL', 'HIGH' => 85.0,
            'NEUTRAL', 'MEDIUM' => 45.0,
            'POSITIVE', 'LOW' => 15.0,
            default => 50.0,
        };

        // 4. Currency risk (fluktuasi persentase absolut)
        $currencyRisk = min(100, abs($exchangeRateChangePercent) * 12.0);

        return $this->calculateScore($weatherRisk, $inflationRisk, $newsRisk, $currencyRisk);
    }
}
