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