<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController; // Pastikan baris ini ada

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Ini rute buatanmu
Route::get('/countries', [ApiController::class, 'getCountries']);