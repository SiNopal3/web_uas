<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\RiskScoringService;
use Illuminate\Support\Facades\Log;

class MaritimeRouteController extends Controller
{
    protected $riskScoringService;

    public function __construct(RiskScoringService $riskScoringService)
    {
        $this->riskScoringService = $riskScoringService;
    }

    /**
     * Tampilkan halaman simulator rute & keterlambatan maritim.
     */
    public function index()
    {
        return view('maritime_route');
    }

    /**
     * Hitung jarak maritim, durasi normal, dan penalti keterlambatan berdasarkan Risk Score Engine.
     */
    public function simulate(Request $request)
    {
        $validated = $request->validate([
            'origin_name' => 'required|string',
            'origin_lat' => 'required|numeric',
            'origin_lng' => 'required|numeric',
            'dest_name' => 'required|string',
            'dest_lat' => 'required|numeric',
            'dest_lng' => 'required|numeric',
            'origin_country' => 'nullable|string',
            'dest_country' => 'nullable|string',
            'vessel_speed_knots' => 'nullable|numeric|min:5|max:40',
            'weather_risk' => 'nullable|numeric|min:0|max:100',
            'inflation_risk' => 'nullable|numeric|min:0|max:100',
            'news_risk' => 'nullable|numeric|min:0|max:100',
            'currency_risk' => 'nullable|numeric|min:0|max:100',
        ]);

        $originLat = (float) $validated['origin_lat'];
        $originLng = (float) $validated['origin_lng'];
        $destLat = (float) $validated['dest_lat'];
        $destLng = (float) $validated['dest_lng'];
        $vesselSpeedKnots = (float) ($validated['vessel_speed_knots'] ?? 18.0); // Default 18 Knots (~800 km/hari)

        // Otomatis tersinkronisasi dengan skor risiko dari Country Dashboard jika tidak ada manual input
        $destCountryName = $validated['dest_country'] ?? $validated['dest_name'] ?? 'Netherlands';
        $countryRisk = $this->getCountryRiskIndicators($destCountryName);

        $weatherRisk = (float) ($validated['weather_risk'] ?? $countryRisk['weather_risk']);
        $inflationRisk = (float) ($validated['inflation_risk'] ?? $countryRisk['inflation_risk']);
        $newsRisk = (float) ($validated['news_risk'] ?? $countryRisk['news_risk']);
        $currencyRisk = (float) ($validated['currency_risk'] ?? $countryRisk['currency_risk']);

        // 1. Kalkulasi Jarak Geodesik (Haversine Formula) dalam km dan Nautical Miles (NM)
        $distanceKm = $this->calculateHaversineDistance($originLat, $originLng, $destLat, $destLng);
        $distanceNm = round($distanceKm / 1.852, 1);
        $distanceKmRound = round($distanceKm, 1);

        // 2. Kalkulasi Durasi Pelayaran Normal (Base Sea Voyage Duration)
        // Kecepatan kapal dalam mil laut per hari = knots * 24 jam
        $nmPerDay = max(120, $vesselSpeedKnots * 24);
        $baseDurationDays = round($distanceNm / $nmPerDay, 1);

        // 3. Integrasi Risk Score Engine
        $riskResult = $this->riskScoringService->calculateScore(
            $weatherRisk,
            $inflationRisk,
            $newsRisk,
            $currencyRisk
        );

        $totalRiskScore = $riskResult['total_risk'];
        $riskCategory = $riskResult['category'];
        $statusColor = $riskResult['status_color'];

        // 4. Model Keterlambatan Dinamis (Delay Impact Engine)
        // Cuaca Ekstrem (Badai Topan / gelombang tinggi maritim)
        $weatherDelay = 0;
        if ($weatherRisk >= 70) {
            $weatherDelay = round($baseDurationDays * 0.30 + 3.0, 1);
        } elseif ($weatherRisk >= 40) {
            $weatherDelay = round($baseDurationDays * 0.15 + 1.0, 1);
        }

        // Risiko Geopolitik & Sentimen Berita (Perang, bajak laut, blokade selat / pemutaran rute)
        $geopoliticalDelay = 0;
        if ($newsRisk >= 75) {
            $geopoliticalDelay = round($baseDurationDays * 0.40 + 5.0, 1);
        } elseif ($newsRisk >= 50) {
            $geopoliticalDelay = round($baseDurationDays * 0.20 + 2.0, 1);
        }

        // Kongesti Pelabuhan Tujuan & Inflasi (Antrean sandar, mogok buruh, bottleneck bea cukai)
        $portDelay = 0;
        if ($inflationRisk >= 70) {
            $portDelay = round($baseDurationDays * 0.25 + 3.0, 1);
        } elseif ($inflationRisk >= 45) {
            $portDelay = round($baseDurationDays * 0.10 + 1.5, 1);
        }

        $totalDelayDays = round($weatherDelay + $geopoliticalDelay + $portDelay, 1);
        $totalEstimatedDays = round($baseDurationDays + $totalDelayDays, 1);

        // 5. Alasan & Mitigasi Rinci
        $breakdownReasons = [];
        if ($weatherDelay > 0) {
            $breakdownReasons[] = [
                'type' => 'Cuaca Ekstrem / Gelombang Laut',
                'delay_days' => $weatherDelay,
                'description' => "Anomali cuaca maritim (skor {$weatherRisk}/100) memerlukan pengurangan kecepatan kapal dan penghindaran zona badai.",
                'mitigation' => 'Gunakan weather routing radar untuk mengalihkan lintasan ke perairan teduh.'
            ];
        }
        if ($geopoliticalDelay > 0) {
            $breakdownReasons[] = [
                'type' => 'Risiko Geopolitik & Keamanan Jalur',
                'delay_days' => $geopoliticalDelay,
                'description' => "Indeks kerawanan geopolitik tinggi (skor {$newsRisk}/100) berpotensi memicu pemutaran rute maritim atau menunggu konvoi keamanan laut.",
                'mitigation' => 'Siapkan rute alternatif & asuransi premi risiko perang (war risk surcharge).'
            ];
        }
        if ($portDelay > 0) {
            $breakdownReasons[] = [
                'type' => 'Kongesti Pelabuhan & Bottleneck Inflasi',
                'delay_days' => $portDelay,
                'description' => "Tekanan inflasi/ekonomi (skor {$inflationRisk}/100) menyebabkan peningkatan waktu tunggu bongkar muat di terminal tujuan.",
                'mitigation' => 'Pesan slot dermaga lebih awal atau pertimbangkan pelabuhan satelit terdekat.'
            ];
        }

        if (empty($breakdownReasons)) {
            $breakdownReasons[] = [
                'type' => 'Kondisi Pelayaran Kondusif',
                'delay_days' => 0,
                'description' => 'Seluruh parameter risiko maritim berada dalam ambang batas aman. Jalur laut stabil.',
                'mitigation' => 'Lanjutkan jadwal pelayaran normal sesuai standar operasional.'
            ];
        }

        return response()->json([
            'success' => true,
            'data' => [
                'origin' => [
                    'name' => $validated['origin_name'],
                    'lat' => $originLat,
                    'lng' => $originLng,
                ],
                'destination' => [
                    'name' => $validated['dest_name'],
                    'lat' => $destLat,
                    'lng' => $destLng,
                ],
                'distance_nm' => $distanceNm,
                'distance_km' => $distanceKmRound,
                'vessel_speed_knots' => $vesselSpeedKnots,
                'base_duration_days' => $baseDurationDays,
                'delay_days' => $totalDelayDays,
                'total_duration_days' => $totalEstimatedDays,
                'risk_engine' => [
                    'total_score' => $totalRiskScore,
                    'category' => $riskCategory,
                    'status_color' => $statusColor,
                    'weather_risk' => round($weatherRisk, 1),
                    'inflation_risk' => round($inflationRisk, 1),
                    'news_risk' => round($newsRisk, 1),
                    'currency_risk' => round($currencyRisk, 1),
                    'breakdown' => $riskResult['breakdown'],
                ],
                'delay_breakdown' => $breakdownReasons,
            ]
        ]);
    }

    /**
     * Dapatkan profil risiko negara tujuan dari database Country Dashboard atau formula konsisten.
     */
    private function getCountryRiskIndicators(string $countryName): array
    {
        // 1. Coba ambil dari Database Country Dashboard
        try {
            $dbCountry = \App\Models\Country::where('name', $countryName)->orWhere('iso_code', $countryName)->first();
            if ($dbCountry) {
                $latestRisk = \App\Models\RiskScore::where('country_id', $dbCountry->id)->latest()->first();
                if ($latestRisk && $latestRisk->total_risk > 0) {
                    return [
                        'weather_risk' => (float) $latestRisk->weather_risk,
                        'inflation_risk' => (float) $latestRisk->inflation_risk,
                        'news_risk' => (float) $latestRisk->news_sentiment_risk,
                        'currency_risk' => (float) $latestRisk->exchange_rate_risk,
                    ];
                }
            }
        } catch (\Throwable $e) {
            // Abaikan jika DB belum siap/error
        }

        // 2. Baseline countries dari Prediction / Executive Dashboard Service
        $baselines = [
            'Indonesia' => ['weather_risk' => 45.0, 'inflation_risk' => 32.0, 'currency_risk' => 28.0, 'news_risk' => 30.0],
            'China' => ['weather_risk' => 58.0, 'inflation_risk' => 25.0, 'currency_risk' => 35.0, 'news_risk' => 62.0],
            'Germany' => ['weather_risk' => 25.0, 'inflation_risk' => 42.0, 'currency_risk' => 20.0, 'news_risk' => 28.0],
            'USA' => ['weather_risk' => 48.0, 'inflation_risk' => 38.0, 'currency_risk' => 15.0, 'news_risk' => 45.0],
            'United States' => ['weather_risk' => 48.0, 'inflation_risk' => 38.0, 'currency_risk' => 15.0, 'news_risk' => 45.0],
            'Australia' => ['weather_risk' => 35.0, 'inflation_risk' => 28.0, 'currency_risk' => 22.0, 'news_risk' => 20.0],
            'Singapore' => ['weather_risk' => 30.0, 'inflation_risk' => 22.0, 'currency_risk' => 18.0, 'news_risk' => 15.0],
            'Japan' => ['weather_risk' => 65.0, 'inflation_risk' => 30.0, 'currency_risk' => 45.0, 'news_risk' => 35.0],
            'UK' => ['weather_risk' => 38.0, 'inflation_risk' => 45.0, 'currency_risk' => 25.0, 'news_risk' => 32.0],
            'United Kingdom' => ['weather_risk' => 38.0, 'inflation_risk' => 45.0, 'currency_risk' => 25.0, 'news_risk' => 32.0],
            'Netherlands' => ['weather_risk' => 28.0, 'inflation_risk' => 35.0, 'currency_risk' => 20.0, 'news_risk' => 22.0],
            'India' => ['weather_risk' => 55.0, 'inflation_risk' => 48.0, 'currency_risk' => 38.0, 'news_risk' => 42.0],
            'Taiwan' => ['weather_risk' => 60.0, 'inflation_risk' => 25.0, 'currency_risk' => 25.0, 'news_risk' => 75.0],
            'Ukraine' => ['weather_risk' => 40.0, 'inflation_risk' => 65.0, 'currency_risk' => 60.0, 'news_risk' => 90.0],
            'Russia' => ['weather_risk' => 55.0, 'inflation_risk' => 60.0, 'currency_risk' => 55.0, 'news_risk' => 85.0],
            'Israel' => ['weather_risk' => 25.0, 'inflation_risk' => 40.0, 'currency_risk' => 35.0, 'news_risk' => 88.0],
            'Egypt' => ['weather_risk' => 20.0, 'inflation_risk' => 65.0, 'currency_risk' => 70.0, 'news_risk' => 60.0],
            'South Africa' => ['weather_risk' => 45.0, 'inflation_risk' => 52.0, 'currency_risk' => 48.0, 'news_risk' => 40.0],
            'Brazil' => ['weather_risk' => 40.0, 'inflation_risk' => 48.0, 'currency_risk' => 45.0, 'news_risk' => 35.0],
        ];

        if (isset($baselines[$countryName])) {
            return $baselines[$countryName];
        }

        // 3. Deterministik konsisten berdasar nama negara (untuk 195 negara lainnya)
        $hash = crc32($countryName);
        return [
            'weather_risk' => round(20 + ($hash % 45), 1),
            'inflation_risk' => round(15 + (($hash >> 3) % 50), 1),
            'news_risk' => round(20 + (($hash >> 6) % 55), 1),
            'currency_risk' => round(10 + (($hash >> 9) % 40), 1),
        ];
    }

    /**
     * Hitung jarak lingkaran besar (Great Circle Distance) dengan rumus Haversine.
     */
    private function calculateHaversineDistance(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $earthRadiusKm = 6371.0;

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadiusKm * $c;
    }
}
