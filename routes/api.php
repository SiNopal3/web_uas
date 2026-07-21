<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\RiskEngineController;
use App\Http\Controllers\WatchlistController;

// Rute Bawaan
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Rute Lokal Dashboard (Data dari database MySQL)
    Route::get('/countries', [ApiController::class, 'getCountries']); 
    Route::get('/risk', [ApiController::class, 'getRisk']);           
    Route::get('/ports', [ApiController::class, 'getPorts']);         
    Route::get('/news', [ApiController::class, 'getNews']);           
    Route::get('/currency', [ApiController::class, 'getCurrency']);   

    // RUTE JEMBATAN PROXY REAL-TIME
    Route::get('/external/country/{name}', [ApiController::class, 'getCountryProfile']);
    Route::get('/external/weather/{lat}/{lng}', [ApiController::class, 'getWeather']);
    Route::get('/external/currency/{base_currency}', [ApiController::class, 'getExchangeRate']);
    Route::get('/external/economy/{country_code}', [ApiController::class, 'getWorldBankData']);
    Route::get('/external/news/{topic}', [ApiController::class, 'getGlobalNews']);
    Route::get('/external/ports/{country}', [ApiController::class, 'getPorts']);

    // Rute AI Risk Engine
    Route::get('/ai/sentiment', [RiskEngineController::class, 'analyzeSentiment']);
    Route::get('/ai/predict-risk', [ApiController::class, 'predictRisk']);

    // Rute Watchlist (Favorite Monitoring - database persisted)
    Route::get('/watchlist', [WatchlistController::class, 'index']);
    Route::post('/watchlist', [WatchlistController::class, 'store']);
    Route::delete('/watchlist/{id}', [WatchlistController::class, 'destroy']);
});