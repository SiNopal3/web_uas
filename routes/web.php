<?php

use Illuminate\Support\Facades\Route;

// Rute untuk menampilkan halaman depan (Dashboard)
Route::get('/', function () {
    return view('dashboard');
});