@extends('layouts.app')

@section('title', 'Watchlist Favorit & Monitoring - RiskIntel Hub')

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
                    <input type="text" id="countrySearchInput" class="form-control bg-dark text-white border-secondary shadow-none" placeholder="Ketik nama atau awalan negara (contoh: Afghanistan, Indonesia, Germany, Japan...)" autocomplete="off" style="border-radius: 6px;">
                    <div id="countryDropdownList" class="dropdown-menu w-100 bg-dark border border-warning shadow-lg p-0" style="position: absolute; top: 100%; left: 0; z-index: 9999; max-height: 280px; overflow-y: auto; display: none; margin-top: 4px; border-radius: 6px;"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Favorite Watchlist Management Card -->
    <div class="row mb-4">
        <div class="col-12">
            <div id="watchlistSection" class="glass-card p-4">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 border-bottom pb-3" style="border-color: rgba(255,255,255,0.12) !important;">
                    <div>
                        <h4 class="fw-bold text-white mb-1">
                            Daftar Pantauan Favorit (Database Persisted Sync)
                        </h4>
                        <div class="small" style="color: #cbd5e1;">Simpan dan kelola negara strategis yang secara berkala ingin Anda pantau risiko logistiknya.</div>
                    </div>
                </div>

                <div id="watchlistContainer" class="d-flex flex-column gap-2" style="min-height: 220px;">
                    <div class="text-center small py-5" style="color: #cbd5e1;">
                        <i class="fa-solid fa-spinner fa-spin me-2 text-warning"></i> Memuat daftar pantauan server Anda...
                    </div>
                </div>

                <div class="border-top pt-3 mt-4 d-flex justify-content-between align-items-center small" style="color: #94a3b8; border-color: rgba(255,255,255,0.12) !important;">
                    <span><i class="fa-solid fa-lock text-success me-1"></i> Data pantauan terisolasi secara aman berdasarkan ID Pengguna (`Auth::id()`).</span>
                    <span class="badge bg-dark border border-secondary text-white px-3 py-1">Sanctum Session Secured</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
