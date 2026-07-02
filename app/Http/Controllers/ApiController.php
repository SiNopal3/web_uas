<?php

namespace App\Http\Controllers;

use App\Models\Country; // Wajib dipanggil agar bisa mengambil data dari tabel countries
use Illuminate\Http\Request;

class ApiController extends Controller
{
    // Ini adalah fungsi yang dicari oleh Laravel tadi
    public function getCountries()
    {
        $countries = Country::all();
        
        return response()->json([
            'success' => true,
            'data' => $countries
        ]);
    }
}