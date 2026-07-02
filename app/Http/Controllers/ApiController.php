<?php

namespace App\Http\Controllers;

// Memanggil semua Model yang dibutuhkan
use App\Models\Country; 
use App\Models\Port;
use App\Models\RiskScore;
use Illuminate\Support\Facades\DB; // Memanggil DB facade untuk news_cache
use Illuminate\Http\Request;

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
}