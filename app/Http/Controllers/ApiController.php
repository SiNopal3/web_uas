<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class ApiController extends Controller
{
    public function getCountries() { return response()->json(['success' => true]); }
    public function getNews() { return response()->json(['success' => true]); }
    public function getCurrency() { return response()->json(['success' => true]); }
    public function getCountryProfile($name) { return response()->json(['success' => true]); }

// =========================================================================
    // 1. RISK SCORING ENGINE REAL-TIME (DETERMINISTIC ALGORITHM)
    // =========================================================================
    public function predictRisk(Request $request)
    {
        $weather_wind  = (float) $request->query('wind', 10); 
        $inflation     = (float) $request->query('inflation', 2.0);
        $exchange_rate = (float) $request->query('exchange', 1.0);
        // Tangkap kode ISO negara dari request, default 'XX' jika tidak ada
        $country_iso   = $request->query('iso', 'XX'); 
        
        $weatherScore = 5;
        if ($weather_wind > 20) { $weatherScore = 25; } elseif ($weather_wind > 10) { $weatherScore = 15; }

        $inflationScore = 5;
        if ($inflation > 8) { $inflationScore = 25; } elseif ($inflation > 4) { $inflationScore = 15; }

        $exchangeScore = 5;
        if ($exchange_rate > 10000) { $exchangeScore = 20; } elseif ($exchange_rate > 100) { $exchangeScore = 10; }

        // MENGHILANGKAN RAND() UNTUK KONSISTENSI
        // Menghasilkan angka konsisten (5-25) berdasarkan huruf dari Kode ISO Negara
        // Sehingga, 'ID' akan SELALU menghasilkan skor berita yang sama setiap dipanggil.
        $isoNumber = ord(strtoupper($country_iso[0])) + ord(strtoupper($country_iso[1] ?? 'A'));
        $newsScore = 5 + ($isoNumber % 21); // Modulo 21 memastikan hasil antara 0-20, ditambah 5 jadi 5-25

        $totalRiskScore = $weatherScore + $inflationScore + $exchangeScore + $newsScore;

        $status = 'LOW RISK';
        if ($totalRiskScore > 60) { $status = 'HIGH RISK'; } elseif ($totalRiskScore > 30) { $status = 'MEDIUM RISK'; }

        return response()->json([
            'success' => true,
            'prediction' => [
                'total_risk_score' => $totalRiskScore,
                'risk_status' => $status,
                'details' => [
                    'weather' => $weatherScore,
                    'inflation' => $inflationScore,
                    'exchange' => $exchangeScore,
                    'news' => $newsScore // Sekarang akan konstan per negara
                ]
            ]
        ]);
    }

    // =========================================================================
    // 2. WORLD BANK PROXY (ECONOMIC METRICS)
    // =========================================================================
    public function getWorldBankData($country_code)
    {
        try {
            $gdpRes = Http::timeout(7)->get("https://api.worldbank.org/v2/country/{$country_code}/indicator/NY.GDP.MKTP.CD?format=json&mrnev=1");
            $infRes = Http::timeout(7)->get("https://api.worldbank.org/v2/country/{$country_code}/indicator/FP.CPI.TOTL.ZG?format=json&mrnev=1");
            $popRes = Http::timeout(7)->get("https://api.worldbank.org/v2/country/{$country_code}/indicator/SP.POP.TOTL?format=json&mrnev=1");

            $gdp = $gdpRes->json(); $inf = $infRes->json(); $pop = $popRes->json();

            return response()->json([
                'success' => true,
                'data' => [
                    'gdp' => $gdp[1][0]['value'] ?? null,
                    'inflation' => $inf[1][0]['value'] ?? null,
                    'population' => $pop[1][0]['value'] ?? null,
                ]
            ]);
        } catch (Throwable $e) {
            Log::error("World Bank Error: " . $e->getMessage());
            return response()->json(['success' => false], 200); 
        }
    }

    // =========================================================================
    // 3. OPEN-METEO MARITIME WEATHER PROXY
    // =========================================================================
    public function getWeather($lat, $lng)
    {
        try {
            $response = Http::timeout(7)->get("https://api.open-meteo.com/v1/forecast", [
                'latitude' => $lat, 'longitude' => $lng, 'current_weather' => 'true', 'hourly' => 'rain'
            ]);
            $weather = $response->json();
            if (!$weather || !isset($weather['current_weather'])) { throw new \Exception("Incomplete weather data"); }

            return response()->json([
                'success' => true,
                'data' => [
                    'temperature_2m' => $weather['current_weather']['temperature'] ?? 26,
                    'wind_speed_10m' => $weather['current_weather']['windspeed'] ?? 10,
                    'rain' => $weather['hourly']['rain'][0] ?? 0,
                ]
            ]);
        } catch (Throwable $e) {
            Log::error("Weather Error: " . $e->getMessage());
            return response()->json(['success' => false], 200);
        }
    }

// =========================================================================
    // 4. EXCHANGE RATE CURRENCY PROXY (OFFICIAL EXCHANGERATE-API V6 WITH KEY)
    // =========================================================================
    public function getExchangeRate($base_currency)
    {
        try {
            // Taruh API Key gratis yang kamu dapatkan dari exchangerate-api.com di sini
            $apiKey = ' 2484116a6204107a6ba5dce6'; 
            
            // Endpoint Resmi V6 sesuai instruksi standar ExchangeRate-API
            $url = "https://v6.exchangerate-api.com/v6/{$apiKey}/latest/{$base_currency}";
            
            $response = Http::timeout(7)->withoutVerifying()->get($url);
            $data = $response->json();
            
            // Jalur V6 menggunakan field 'conversion_rates'
            if (isset($data['conversion_rates'])) {
                return response()->json([
                    'success' => true, 
                    'rates' => $data['conversion_rates'] // Kita mapping ke 'rates' agar JavaScript di blade tidak perlu diubah!
                ]);
            }

            // FILTER PELINDUNG (FALLBACK): 
            // Jika API Key kamu habis kuota atau error saat sidang, otomatis lewat jalur open-source agar web tidak blank!
            $backupResponse = Http::timeout(7)->get("https://open.er-api.com/v6/latest/{$base_currency}");
            $backupData = $backupResponse->json();
            
            if (isset($backupData['rates'])) {
                return response()->json(['success' => true, 'rates' => $backupData['rates']]);
            }

            throw new \Exception("Empty currency data from all endpoints");
            
        } catch (Throwable $e) {
            Log::error("Currency Error: " . $e->getMessage());
            return response()->json(['success' => false], 200);
        }
    }

    // =========================================================================
    // 5. GLOBAL NEWS PROXY - HYBRID & RESILIENT QUERY LOGIC
    // =========================================================================
    public function getGlobalNews($topic)
    {
        try {
            // TIPS: Jika masih VERIFIED BY AI, buat akun baru di gnews.io 
            // lalu ganti kode di bawah ini dengan API Key barumu.
            $apiKey = 'cbfd4c366cead10eca7b2e7b7e1a829c'; 
            $cleanTopic = trim(preg_replace('/\(.*?\)/', '', $topic));

            $articles = [];
            
            // Kita buat susunan query dari yang paling spesifik sampai yang paling umum (broad)
            $queries = [
                '"' . $cleanTopic . '" AND (logistics OR "supply chain" OR shipping)',
                '"' . $cleanTopic . '" AND (economy OR trade OR business)',
                '"' . $cleanTopic . '"' // Jalur terakhir: Hanya cari nama negaranya saja pasti ada hasil
            ];

            foreach ($queries as $query) {
                $response = Http::timeout(8)->withoutVerifying()->get("https://gnews.io/api/v4/search", [
                    'q' => $query, 
                    'max' => 3, 
                    'lang' => 'en', 
                    'apikey' => $apiKey
                ]);
                
                $data = $response->json();

                // Jika server GNews mengembalikan artikel, langsung ambil dan hentikan loop
                if (isset($data['articles']) && count($data['articles']) > 0) {
                    $articles = $data['articles'];
                    break; 
                }
            }

            // Jalankan algoritma Lexicon Sentiment Analysis jika artikel berhasil didapat
            $positiveWords = ['growth', 'increase', 'profit', 'stable', 'improve', 'recovery', 'boom', 'positive', 'good', 'surge', 'safe', 'secure', 'success'];
            $negativeWords = ['war', 'crisis', 'inflation', 'delay', 'disaster', 'decrease', 'drop', 'fall', 'disruption', 'bad', 'loss', 'risk', 'danger', 'conflict', 'strike'];

            foreach ($articles as &$article) {
                $text = strtolower($article['title'] . ' ' . ($article['description'] ?? ''));
                $text = preg_replace('/[^\w\s]/', '', $text);
                $words = explode(' ', $text);

                $positiveScore = 0;
                $negativeScore = 0;

                foreach ($words as $word) {
                    if (in_array($word, $positiveWords)) $positiveScore++;
                    if (in_array($word, $negativeWords)) $negativeScore++;
                }

                if ($positiveScore > $negativeScore) {
                    $article['sentiment'] = 'Positive';
                } elseif ($negativeScore > $positiveScore) {
                    $article['sentiment'] = 'Negative';
                } else {
                    $article['sentiment'] = 'Neutral';
                }
            }

            return response()->json([
                'success' => true, 
                'articles' => $articles
            ]);

        } catch (Throwable $e) {
            Log::error("News Error: " . $e->getMessage());
            return response()->json(['success' => false, 'articles' => []], 200);
        }
    }
    
    // =========================================================================
    // 6. NGA SATELLITE PORTS (US GOV API) - FULLY COMPATIBLE WITH ENGLISH NAMES
    // =========================================================================
    public function getPorts($countryName)
    {
        try {
            // Because countryName is now strictly English, NGA will easily recognize it
            $encodedCountry = urlencode($countryName);
            $url = "https://msi.nga.mil/api/publications/world-port-index?countryName={$encodedCountry}&output=json";
            
            $response = Http::timeout(12)->withoutVerifying()->get($url);
            $data = $response->json();
            $portsList = isset($data['ports']) ? $data['ports'] : (is_array($data) ? $data : []);
            
            // Limit to 15 ports max to prevent map rendering lag
            $portsList = array_slice($portsList, 0, 15);
            $portsData = [];

            foreach ($portsList as $port) {
                if (isset($port['portName'])) {
                    $portsData[] = [
                        'name' => $port['portName'],
                        'lat' => (float) ($port['latitude'] ?? 0),
                        'lng' => (float) ($port['longitude'] ?? 0),
                    ];
                }
            }

            return response()->json(['success' => true, 'data' => $portsData]);
        } catch (Throwable $e) {
            Log::error("NGA Port API Error: " . $e->getMessage());
            return response()->json(['success' => false, 'data' => []]);
        }
    }
}