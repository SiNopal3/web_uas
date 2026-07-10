<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class ApiController extends Controller
{
    // Fungsi Bawaan (Kosongkan/Sesuaikan jika ada logic database)
    public function getCountries() { return response()->json(['success' => true]); }
    public function getRisk() { return response()->json(['success' => true]); }
    public function getPorts() { return response()->json(['success' => true]); }
    public function getNews() { return response()->json(['success' => true]); }
    public function getCurrency() { return response()->json(['success' => true]); }
    public function getCountryProfile($name) { return response()->json(['success' => true]); }

    // 1. PROXY REAL-TIME BANK DUNIA
    public function getWorldBankData($country_code)
    {
        try {
            $gdpRes = Http::timeout(7)->get("https://api.worldbank.org/v2/country/{$country_code}/indicator/NY.GDP.MKTP.CD?format=json&mrnev=1");
            $infRes = Http::timeout(7)->get("https://api.worldbank.org/v2/country/{$country_code}/indicator/FP.CPI.TOTL.ZG?format=json&mrnev=1");
            $popRes = Http::timeout(7)->get("https://api.worldbank.org/v2/country/{$country_code}/indicator/SP.POP.TOTL?format=json&mrnev=1");

            $gdp = $gdpRes->json();
            $inf = $infRes->json();
            $pop = $popRes->json();

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

    // 2. PROXY REAL-TIME CUACA
    public function getWeather($lat, $lng)
    {
        try {
            $response = Http::timeout(7)->get("https://api.open-meteo.com/v1/forecast", [
                'latitude' => $lat,
                'longitude' => $lng,
                'current_weather' => 'true',
                'hourly' => 'rain'
            ]);

            $weather = $response->json();

            if (!$weather || !isset($weather['current_weather'])) {
                throw new \Exception("Data cuaca tidak lengkap");
            }

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

    // 3. PROXY REAL-TIME KURS MATA UANG
    public function getExchangeRate($base_currency)
    {
        try {
            $response = Http::timeout(7)->get("https://open.er-api.com/v6/latest/{$base_currency}");
            $data = $response->json();
            
            if (!$data || !isset($data['rates'])) {
                throw new \Exception("Data kurs kosong");
            }

            return response()->json([
                'success' => true,
                'rates' => $data['rates']
            ]);
        } catch (Throwable $e) {
            Log::error("Currency Error: " . $e->getMessage());
            return response()->json(['success' => false], 200);
        }
    }

    // 4. PROXY REAL-TIME BERITA (API KEY SUDAH DIMASUKKAN)
    public function getGlobalNews($topic)
    {
        try {
            // API KEY Milik Sutan sudah aktif di sini
            $apiKey = 'cbfd4c366cead10eca7b2e7b7e1a829c'; 
            
            $response = Http::timeout(7)->get("https://gnews.io/api/v4/search", [
                'q' => $topic,
                'lang' => 'en',
                'max' => 3,
                'apikey' => $apiKey
            ]);
            
            $data = $response->json();

            return response()->json([
                'success' => true,
                'articles' => $data['articles'] ?? []
            ]);
        } catch (Throwable $e) {
            Log::error("News Error: " . $e->getMessage());
            return response()->json(['success' => false], 200);
        }
    }
}