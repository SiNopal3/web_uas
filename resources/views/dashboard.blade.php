@extends('layouts.app')

@section('title', 'Global Supply Chain Risk Dashboard')

@section('content')
<div class="container-fluid p-0">
    <!-- Top Selector and Country Header -->
    <div class="row align-items-center mb-4 g-3">
        <div class="col-12 col-xl-5">
            <div class="glass-card p-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div class="overflow-hidden text-truncate pe-2">
                    <span class="small text-uppercase fw-bold d-block text-truncate" style="color: #e2e8f0; letter-spacing: 0.8px;">Negara Analisis Aktif</span>
                    <h3 id="selectedCountryName" class="fw-bold text-white mb-0 mt-1 text-truncate">-</h3>
                </div>
                <div class="d-flex align-items-center gap-2 flex-wrap ms-auto">
                    <button id="addFavoriteBtn" class="btn btn-outline-warning fw-bold d-flex align-items-center gap-1.5 shadow-sm px-2 px-md-3 py-1.5 flex-shrink-0" onclick="addToWatchlist()" title="Tambahkan ke Daftar Pantauan Favorit" style="border-radius: 8px;">
                        <span style="font-size: 13px;">+ Favorit</span>
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-info fw-bold px-2 px-md-3 py-1.5 flex-shrink-0" onclick="window.resetToAdminFeed && window.resetToAdminFeed()" title="Reset pilihan negara & langsung buka dropdown untuk memilih negara baru" style="border-radius: 8px;">
                        <i class="fa-solid fa-rotate-left me-1"></i> Reset
                    </button>
                    <div class="text-end ms-1 flex-shrink-0">
                        <span id="selectedCountryRegion" class="badge bg-secondary mb-1 px-3 py-1 fw-bold text-white">-</span>
                        <div id="selectedCountryCurrency" class="text-warning small fw-bold mt-1" style="font-size: 14px;">-</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-xl-7" style="position: relative; z-index: 100;">
            <div class="glass-card p-3" style="overflow: visible !important;">
                <label for="countrySearchInput" class="form-label small mb-2 fw-bold d-block" style="color: #f8fafc; font-size: 13px;">
                    <i class="fa-solid fa-magnifying-glass me-1 text-warning"></i> Cari Negara:
                </label>
                <div style="position: relative;">
                    <input type="text" id="countrySearchInput" class="form-control bg-dark text-white border-secondary shadow-none" placeholder="Ketik nama atau awalan negara (contoh: Indonesia, Germany, Japan...)" autocomplete="off" style="border-radius: 6px;">
                    <div id="countryDropdownList" class="dropdown-menu w-100 bg-dark border border-warning shadow-lg p-0" style="position: absolute; top: 100%; left: 0; z-index: 9999; max-height: 280px; overflow-y: auto; display: none; margin-top: 4px; border-radius: 6px;"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- 4 Real-time Summary Cards -->
    <div class="row g-3 mb-4">
        <!-- Weather Card -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div id="weatherCard" class="glass-card h-100 d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <span class="small fw-bold" style="color: #e2e8f0;">Cuaca Maritim (Open-Meteo)</span>
                </div>
                <div class="my-2">
                    <h4 id="valWeatherTemp" class="fw-bold text-white mb-1">-</h4>
                    <div class="d-flex justify-content-between small mb-1" style="color: #cbd5e1; font-size: 11.5px;">
                        <span>Angin: <strong id="valWeatherWind" class="text-white">-</strong></span>
                        <span>Arah: <strong id="valWeatherWindDir" class="text-info">-</strong></span>
                    </div>
                    <div class="d-flex justify-content-between small mb-1" style="color: #cbd5e1; font-size: 11.5px;">
                        <span>Hujan: <strong id="valWeatherRain" class="text-white">-</strong></span>
                        <span>Lembap: <strong id="valWeatherHumidity" class="text-white">-</strong></span>
                    </div>
                    <div class="d-flex justify-content-between small" style="color: #cbd5e1; font-size: 11.5px;">
                        <span>Tutupan Awan: <strong id="valWeatherCloud" class="text-white">-</strong></span>
                    </div>
                </div>
                <div class="border-top pt-2 mt-2" style="font-size: 11px; color: #94a3b8; border-color: rgba(255,255,255,0.12) !important;">
                    Data satelit cuaca kelautan langsung
                </div>
            </div>
        </div>

        <!-- Economy Card -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div id="economyCard" class="glass-card h-100 d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <span class="small fw-bold" style="color: #e2e8f0;">Metrik Ekonomi (World Bank)</span>
                </div>
                <div class="my-2">
                    <h4 id="valEconGdp" class="fw-bold text-white mb-1">-</h4>
                    <div class="d-flex justify-content-between small" style="color: #cbd5e1;">
                        <span>Inflasi: <strong id="valEconInf" class="text-warning">-</strong></span>
                        <span>Pop: <strong id="valEconPop" class="text-white">-</strong></span>
                    </div>
                </div>
                <div class="border-top pt-2 mt-2" style="font-size: 11px; color: #94a3b8; border-color: rgba(255,255,255,0.12) !important;">
                    Indikator makroekonomi terverifikasi
                </div>
            </div>
        </div>

        <!-- Currency Card -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div id="currencyCard" class="glass-card h-100 d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <span class="small fw-bold" style="color: #e2e8f0;">Kurs Valuta (ExchangeRate-API)</span>
                </div>
                <div class="my-2">
                    <h5 id="valCurrencyRate" class="fw-bold text-warning mb-1">-</h5>
                    <div id="valCurrencyBase" class="small" style="color: #cbd5e1;">-</div>
                </div>
                <div class="border-top pt-2 mt-2" style="font-size: 11px; color: #94a3b8; border-color: rgba(255,255,255,0.12) !important;">
                    Pembaruan kurs valas terkini
                </div>
            </div>
        </div>

        <!-- AI Real-time Risk Score Card -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="glass-card h-100 d-flex flex-column justify-content-between" style="border: 1px solid rgba(224, 180, 114, 0.5);">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <span class="small fw-bold" style="color: #e2e8f0;">Skor Risiko Rantai Pasok AI</span>
                </div>
                <div class="my-2 d-flex align-items-center justify-content-between">
                    <div>
                        <h2 id="valTotalRiskScore" class="fw-bold text-white mb-0">-</h2>
                        <span class="small" style="color: #cbd5e1;">dari 100 poin</span>
                    </div>
                    <div>
                        <span id="valRiskStatusBadge" class="badge bg-secondary px-3 py-2 fw-bold text-white">-</span>
                    </div>
                </div>
                <div class="border-top pt-2 mt-2" style="font-size: 11px; color: #94a3b8; border-color: rgba(255,255,255,0.12) !important;">
                    Kalkulasi bobot deterministik AI
                </div>
            </div>
        </div>
    </div>


    <!-- 4 Dedicated Feature Entry Cards -->
    <div class="row g-4 mb-4">
        <div class="col-12 col-md-6 col-xl-3">
            <a href="{{ url('/ports') }}" onclick="return window.navigateToFeatureFromDashboard && window.navigateToFeatureFromDashboard(event, '{{ url('/ports') }}')" class="text-decoration-none">
                <div class="glass-card h-100 p-4 d-flex flex-column justify-content-between hover-gold" style="transition: all 0.3s; cursor: pointer;">
                    <div>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="badge bg-dark border border-secondary text-warning px-2 py-1">Leaflet.js + NGA</span>
                        </div>
                        <h5 class="fw-bold text-white mb-2">Peta Pelabuhan & Cuaca</h5>
                        <p class="small mb-0" style="color: #cbd5e1; line-height: 1.5;">
                            Pantau titik koordinat terminal pelabuhan logistik maritim dunia dan kondisi cuaca ekstrem secara interaktif.
                        </p>
                    </div>
                    <div class="mt-3 pt-3 border-top d-flex align-items-center justify-content-between small fw-bold text-warning" style="border-color: rgba(255,255,255,0.12) !important;">
                        <span>Buka Peta Maritim</span>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-12 col-md-6 col-xl-3">
            <a href="{{ url('/news-sentiment') }}" onclick="return window.navigateToFeatureFromDashboard && window.navigateToFeatureFromDashboard(event, '{{ url('/news-sentiment') }}')" class="text-decoration-none">
                <div class="glass-card h-100 p-4 d-flex flex-column justify-content-between hover-gold" style="transition: all 0.3s; cursor: pointer;">
                    <div>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="badge bg-dark border border-secondary text-info px-2 py-1">GNews + Native PHP</span>
                        </div>
                        <h5 class="fw-bold text-white mb-2">Berita & AI Sentimen</h5>
                        <p class="small mb-0" style="color: #cbd5e1; line-height: 1.5;">
                            Baca feed berita logistik global terkini & uji kamus kata positif/negatif AI Lexicon Sentiment Engine buatan sendiri.
                        </p>
                    </div>
                    <div class="mt-3 pt-3 border-top d-flex align-items-center justify-content-between small fw-bold text-info" style="border-color: rgba(255,255,255,0.12) !important;">
                        <span>Uji Sentimen AI</span>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-12 col-md-6 col-xl-3">
            <a href="{{ url('/watchlist') }}" onclick="return window.navigateToFeatureFromDashboard && window.navigateToFeatureFromDashboard(event, '{{ url('/watchlist') }}')" class="text-decoration-none">
                <div class="glass-card h-100 p-4 d-flex flex-column justify-content-between hover-gold" style="transition: all 0.3s; cursor: pointer;">
                    <div>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="badge bg-dark border border-secondary text-warning px-2 py-1">Database Persisted</span>
                        </div>
                        <h5 class="fw-bold text-white mb-2">Watchlist Favorit</h5>
                        <p class="small mb-0" style="color: #cbd5e1; line-height: 1.5;">
                            Simpan, pantau, dan kelola daftar negara favorit yang terisolasi secara aman di database server untuk akun Anda.
                        </p>
                    </div>
                    <div class="mt-3 pt-3 border-top d-flex align-items-center justify-content-between small fw-bold text-warning" style="border-color: rgba(255,255,255,0.12) !important;">
                        <span>Kelola Watchlist</span>
                    </div>
                </div>
            </a>
        </div>

    </div>
</div>
@endsection