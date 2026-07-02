<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// 5 REST API Internal yang Wajib Dibuat
Route::get('/countries', [ApiController::class, 'getCountries']); // [cite: 232]
Route::get('/risk', [ApiController::class, 'getRisk']);           // [cite: 233]
Route::get('/ports', [ApiController::class, 'getPorts']);         // [cite: 234]
Route::get('/news', [ApiController::class, 'getNews']);           // [cite: 235]
Route::get('/currency', [ApiController::class, 'getCurrency']);   // [cite: 236]
// Rute Integrasi API Eksternal
Route::get('/external/country/{name}', [ApiController::class, 'getCountryProfile']);
// Rute untuk mengecek cuaca berdasarkan Latitude dan Longitude
Route::get('/external/weather/{lat}/{lng}', [ApiController::class, 'getWeather']);

// Rute untuk mengecek kurs mata uang (misalnya USD atau IDR)
Route::get('/external/currency/{base_currency}', [ApiController::class, 'getExchangeRate']);
// Rute untuk mengambil data ekonomi dari World Bank (gunakan 2 huruf kode negara, misal: ID, US, DE)
Route::get('/external/economy/{country_code}', [ApiController::class, 'getWorldBankData']);

// Rute untuk mencari berita berdasarkan topik
Route::get('/external/news/{topic}', [ApiController::class, 'getGlobalNews']);