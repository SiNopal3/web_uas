@extends('layouts.app')

@section('title', 'Peta Terminal Pelabuhan & Cuaca Maritim - RiskIntel Hub')

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
                    <button type="button" class="btn btn-sm btn-outline-info fw-bold px-3 py-2 flex-shrink-0" onclick="window.resetToAdminFeed && window.resetToAdminFeed()" title="Reset pilihan negara & langsung buka dropdown untuk memilih negara baru" style="border-radius: 8px;">
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

    <!-- Weather Summary Banner -->
    <div class="row g-3 mb-4">
        <div class="col-12">
            <div id="weatherCard" class="glass-card p-4 d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-4">
                <div class="mb-2 mb-lg-0" style="min-width: 250px;">
                    <span class="small fw-bold d-block mb-1" style="color: #e2e8f0; font-size: 13px;">
                        Kondisi Cuaca Maritim & Badai (Open-Meteo Proxy)
                    </span>
                    <div class="d-flex align-items-baseline gap-2 mt-1">
                        <h2 id="valWeatherTemp" class="fw-bold text-white mb-0">-- °C</h2>
                        <span class="badge bg-info bg-opacity-25 text-info border border-info border-opacity-25 small">Suhu Udara</span>
                    </div>
                    <div class="small mt-1" style="color: #cbd5e1;">Pemantauan satelit kelautan langsung tanpa API Key eksternal di peramban.</div>
                </div>
                <div class="d-flex flex-nowrap align-items-center justify-content-between gap-3 p-3 rounded flex-grow-1 overflow-x-auto" style="background: rgba(0,0,0,0.38); border: 1px solid rgba(255,255,255,0.16); backdrop-filter: blur(8px);">
                    <div class="pe-3 border-end text-nowrap" style="border-color: rgba(255,255,255,0.16) !important;">
                        <span class="small d-block text-info fw-semibold" style="font-size: 11px;"><i class="fa-solid fa-wind me-1"></i> Kecepatan Angin:</span>
                        <strong id="valWeatherWind" class="text-white fs-6">-- m/s</strong>
                    </div>
                    <div class="pe-3 border-end text-nowrap" style="border-color: rgba(255,255,255,0.16) !important;">
                        <span class="small d-block text-info fw-semibold" style="font-size: 11px;"><i class="fa-solid fa-compass me-1"></i> Arah Angin:</span>
                        <strong id="valWeatherWindDir" class="text-white fs-6">--</strong>
                    </div>
                    <div class="pe-3 border-end text-nowrap" style="border-color: rgba(255,255,255,0.16) !important;">
                        <span class="small d-block text-info fw-semibold" style="font-size: 11px;"><i class="fa-solid fa-cloud-showers-heavy me-1"></i> Presipitasi:</span>
                        <strong id="valWeatherRain" class="text-white fs-6">-- mm/h</strong>
                    </div>
                    <div class="pe-3 border-end text-nowrap" style="border-color: rgba(255,255,255,0.16) !important;">
                        <span class="small d-block text-info fw-semibold" style="font-size: 11px;"><i class="fa-solid fa-droplet me-1"></i> Kelembapan:</span>
                        <strong id="valWeatherHumidity" class="text-white fs-6">-- %</strong>
                    </div>
                    <div class="text-nowrap">
                        <span class="small d-block text-info fw-semibold" style="font-size: 11px;"><i class="fa-solid fa-cloud me-1"></i> Tutupan Awan:</span>
                        <strong id="valWeatherCloud" class="text-white fs-6">-- %</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Map & Port Directory Side-by-Side Section (Ke Samping Peta) -->
    <div class="row g-4 mb-5 align-items-stretch">
        <!-- Left Side: Maritime Satellite Map -->
        <div class="col-12 col-lg-7 col-xl-8 d-flex">
            <div class="glass-card p-4 rounded w-100 d-flex flex-column shadow">
                <div class="mb-3">
                    <h5 class="fw-bold text-white mb-1">
                        Peta Terminal Pelabuhan Maritim Global (NGA Satellite Sync)
                    </h5>
                    <div class="small" style="color: #cbd5e1;">Memantau koordinat pelabuhan strategis dan rute logistik internasional secara real-time. Klik marker emas atau pilih dari daftar pelabuhan di samping.</div>
                </div>
                <div id="maritimeMap" class="flex-grow-1" style="min-height: 650px; width: 100%; border-radius: 14px; border: 1px solid rgba(255,255,255,0.22); box-shadow: 0 10px 30px rgba(0,0,0,0.5);"></div>
            </div>
        </div>

        <!-- Right Side: Port Directory Panel (Di Samping Peta) -->
        <div class="col-12 col-lg-5 col-xl-4 d-flex">
            <div class="glass-card p-4 rounded w-100 d-flex flex-column shadow" style="max-height: 750px;">
                <div class="border-bottom pb-3 mb-3 flex-shrink-0" style="border-color: rgba(255,255,255,0.12) !important;">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h5 class="fw-bold text-white mb-0" style="font-size: 16px;">
                            <i class="fa-solid fa-list-check text-warning me-2"></i> Direktori Pelabuhan
                        </h5>
                        <span class="badge bg-dark border border-warning text-warning small px-2 py-1"><span id="portCountBadge">0</span> Terdeteksi</span>
                    </div>
                    <div class="small" style="color: #cbd5e1; font-size: 12.5px;">Daftar terminal maritim di negara aktif. Gulir untuk melihat semua pelabuhan & klik fokus untuk mengarahkan satelit.</div>
                </div>

                <!-- Internal Vertical Scroll Container (Supaya halaman utama tidak scroll panjang ke bawah) -->
                <div id="portListContainer" class="d-flex flex-column gap-3 flex-grow-1 overflow-y-auto pe-2" style="max-height: 620px; scroll-behavior: smooth;">
                    <div class="w-100 text-center small py-5" style="color: #cbd5e1;">
                        <i class="fa-solid fa-spinner fa-spin me-2 text-warning"></i> Memuat keterangan terminal pelabuhan untuk negara yang dipilih...
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
