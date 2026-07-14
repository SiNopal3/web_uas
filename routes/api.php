<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\RiskEngineController;

// Rute Bawaan
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Rute Lokal Dashboard
Route::get('/countries', [ApiController::class, 'getCountries']); 
Route::get('/risk', [ApiController::class, 'getRisk']);           
Route::get('/ports', [ApiController::class, 'getPorts']);         
Route::get('/news', [ApiController::class, 'getNews']);           
Route::get('/currency', [ApiController::class, 'getCurrency']);   

// RUTE JEMBATAN PROXY REAL-TIME (Yang baru kita buat)
Route::get('/external/country/{name}', [ApiController::class, 'getCountryProfile']);
Route::get('/external/weather/{lat}/{lng}', [ApiController::class, 'getWeather']);
Route::get('/external/currency/{base_currency}', [ApiController::class, 'getExchangeRate']);
Route::get('/external/economy/{country_code}', [ApiController::class, 'getWorldBankData']);
Route::get('/external/news/{topic}', [ApiController::class, 'getGlobalNews']);

// Rute AI Risk Engine
Route::get('/ai/sentiment', [RiskEngineController::class, 'analyzeSentiment']);
Route::get('/ai/predict-risk', [ApiController::class, 'predictRisk']);
Route::get('/external/ports/{country}', [ApiController::class, 'getPorts']);