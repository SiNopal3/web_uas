<?php

namespace App\Http\Controllers;

// Memanggil semua Model yang dibutuhkan
use App\Models\Country; 
use App\Models\Port;
use App\Models\RiskScore;
use Illuminate\Support\Facades\DB; // Memanggil DB facade untuk news_cache
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ApiController extends Controller
{
    // 1. Endpoint Negara
    public function getCountries()
    {
        $countries = Country::all();
        return response()->json([
            'success' => true,
            'data' => $countries
        ]);
    }

    // 2. Endpoint Pelabuhan
    public function getPorts()
    {
        $ports = Port::all();
        return response()->json([
            'success' => true,
            'data' => $ports
        ]);
    }

    // 3. Endpoint Mata Uang (Disiapkan untuk Fase 3)
    public function getCurrency()
    {
        return response()->json([
            'success' => true,
            'message' => 'Data kurs real-time akan diintegrasikan dengan ExchangeRate API nanti.'
        ]);
    }

    // 4. Endpoint Skor Risiko
    public function getRisk()
    {
        $risks = RiskScore::all();
        return response()->json([
            'success' => true,
            'data' => $risks
        ]);
    }

    // 5. Endpoint Berita
    public function getNews()
    {
        $news = DB::table('news_cache')->get();
        return response()->json([
            'success' => true,
            'data' => $news
        ]);
    }
    // 6. Endpoint Integrasi REST Countries API
    public function getCountryProfile($name)
    {
        // 1. Tambahkan identitas User-Agent & matikan verifikasi SSL (solusi jitu di XAMPP)
        // Menembak URL API Eksternal REST Countries versi terbaru (v5)
    $response = Http::withHeaders([
        'User-Agent' => 'SupplyChainApp/1.0'
    ])->withoutVerifying()->get("https://restcountries.com/v5/name/" . $name);
        
        if ($response->successful()) {
            $json = $response->json();
            
            // 2. Defensive Programming: Pastikan $json tidak kosong dan punya index ke-0
            if (!empty($json) && isset($json[0])) {
                $data = $json[0]; 
                
                return response()->json([
                    'success' => true,
                    'country' => $data['name']['common'],
                    'region' => $data['region'],
                    'languages' => $data['languages'] ?? null,
                    'currencies' => $data['currencies'] ?? null,
                    'raw_data' => $data 
                ]);
            } else {
                // 3. JIKA TETAP GAGAL, TAMPILKAN JAWABAN ASLI DARI API KE LAYAR BROWSER
                return response()->json([
                    'success' => false,
                    'message' => 'API berhasil dihubungi, tapi format datanya tidak memiliki index [0].',
                    'isi_asli_dari_api' => $json 
                ]);
            }
        }

        return response()->json([
            'success' => false,
            'message' => 'Gagal mengambil data dari API eksternal (Status: ' . $response->status() . ')'
        ], $response->status() === 200 ? 404 : $response->status());
    }
    // 7. Endpoint Integrasi Cuaca Global (Open-Meteo API)
    public function getWeather($lat, $lng)
    {
        // Mengambil data temperatur, curah hujan, dan kecepatan angin [cite: 48-50]
        $url = "https://api.open-meteo.com/v1/forecast?latitude={$lat}&longitude={$lng}&current=temperature_2m,rain,wind_speed_10m";
        
        $response = Http::withHeaders([
            'User-Agent' => 'SupplyChainApp/1.0'
        ])->withoutVerifying()->get($url);

        if ($response->successful()) {
            return response()->json([
                'success' => true,
                'data' => $response->json()['current'] ?? null,
                'raw_data' => $response->json()
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Gagal mengambil data cuaca'
        ], 500);
    }

    // 8. Endpoint Integrasi ExchangeRate API
    public function getExchangeRate($base_currency)
    {
        // Mengambil data kurs real-time [cite: 73]
        $url = "https://open.er-api.com/v6/latest/" . strtoupper($base_currency);
        
        $response = Http::withHeaders([
            'User-Agent' => 'SupplyChainApp/1.0'
        ])->withoutVerifying()->get($url);

        if ($response->successful()) {
            return response()->json([
                'success' => true,
                'base' => $base_currency,
                'rates' => $response->json()['rates'] ?? null
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Gagal mengambil data kurs mata uang'
        ], 500);
    }
    // 9. Endpoint Integrasi World Bank API (Contoh: Mengambil GDP)
    public function getWorldBankData($country_code)
    {
        // Indikator NY.GDP.MKTP.CD adalah kode resmi World Bank untuk GDP
        $url = "https://api.worldbank.org/v2/country/{$country_code}/indicator/NY.GDP.MKTP.CD?format=json";
        
        $response = Http::withHeaders([
            'User-Agent' => 'SupplyChainApp/1.0'
        ])->withoutVerifying()->get($url);

        // Data World Bank formatnya sedikit unik, datanya ada di index ke-[1]
        if ($response->successful() && isset($response->json()[1])) {
            return response()->json([
                'success' => true,
                'country_code' => strtoupper($country_code),
                'gdp_data' => $response->json()[1][0] ?? null // Mengambil data tahun terbaru
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Gagal mengambil data dari World Bank'
        ], 500);
    }

    // 10. Endpoint Integrasi GNews API
    public function getGlobalNews($topic)
    {
        // TODO: Kita butuh API Key gratis dari gnews.io nanti
        $apiKey = 'TARUH_API_KEY_GNEWS_DISINI_NANTI'; 
        
        // Mencari berita berdasarkan topik (misal: economy, logistics, geopolitics)
        $url = "https://gnews.io/api/v4/search?q={$topic}&lang=en&apikey={$apiKey}";
        
        $response = Http::withoutVerifying()->get($url);

        if ($response->successful()) {
            return response()->json([
                'success' => true,
                'articles' => $response->json()['articles'] ?? []
            ]);
        }

        // Tampilkan pesan asli dari GNews jika error (biasanya karena API Key salah)
        return response()->json([
            'success' => false,
            'message' => 'Gagal mengambil berita',
            'error_detail' => $response->json()
        ], 500);
    }
}