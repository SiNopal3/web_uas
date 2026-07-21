@extends('layouts.app')

@section('title', 'Country Comparison Engine')

@section('content')
<div class="container-fluid py-2">
    <!-- MASTER CONTAINER: 1 KOTAK MENCAKUP SEMUA SUPER KOMPAKS & TANPA SCROLL -->
    <div class="glass-card p-3 p-xl-4 mb-3 border-top border-info border-4 shadow-lg" style="border-radius: 12px; background: rgba(15, 23, 42, 0.9); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.1);">
        
        <!-- 1. HEADER SECTION (COMPACT & CLEAN) -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-2 pb-2 border-bottom border-secondary" style="border-color: rgba(255, 255, 255, 0.1) !important;">
            <div>
                <h1 class="h4 fw-bold mb-0 text-white d-flex align-items-center">
                    Country Comparison Engine
                </h1>
                <p class="text-muted mb-0 fw-semibold" style="font-size: 12px;">Bandingkan 5 parameter fundamental &amp; rantai pasok antar negara dalam 1 tabel terpadu tanpa scroll.</p>
            </div>
        </div>

        <!-- 2. COMPARISON SELECTOR CONTROLS (SEARCH 195 COUNTRIES) -->
        <div class="p-3 px-4 rounded-3 mb-3 border border-secondary shadow-sm" style="background: rgba(255, 255, 255, 0.04); border-color: rgba(255, 255, 255, 0.12) !important;">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-2">
                <div class="d-flex align-items-center gap-2">
                    <span class="text-uppercase fw-bold text-info" style="font-size: 13px; letter-spacing: 0.8px;">Perbandingan:</span>
                    <span id="comparisonTitleText" class="fw-bold text-warning" style="font-size: 15px;">Belum Ada Negara Dipilih (Kosong)</span>
                </div>
                <div class="mt-2 mt-md-0">
                    <button type="button" id="btnResetComparison" class="btn btn-outline-info btn-sm py-1.5 px-3 fw-bold shadow-sm" style="font-size: 12.5px;" title="Kosongkan pilihan negara agar tidak ada yang dibandingin">
                        <i class="fa-solid fa-rotate-left me-1"></i> Reset (Kosongkan Pilihan)
                    </button>
                </div>
            </div>

            <!-- SELECTOR SEARCHABLE INPUTS (195 COUNTRIES) -->
            <div class="row g-2 align-items-center">
                <!-- COUNTRY A SEARCH -->
                <div class="col-12 col-md-5">
                    <label for="searchCountryA" class="form-label fw-bold text-info text-uppercase mb-1 d-flex justify-content-between" style="font-size: 12px;">
                        <span><i class="fa-solid fa-flag me-1"></i> Negara 1 (Country A)</span>
                        <span class="text-muted fw-normal" style="font-size: 11.5px;">Cari dari 195 Negara</span>
                    </label>
                    <div style="position: relative;">
                        <input type="text" id="searchCountryA" class="form-control bg-dark text-white border-info shadow-none py-1.5 px-3 fw-bold" placeholder="Ketik nama/awalan negara 1 (contoh: Germany, Indonesia...)" autocomplete="off" style="border-radius: 6px; font-size: 13.5px;">
                        <input type="hidden" id="selectCountryA" value="">
                        <div id="dropdownCountryA" class="dropdown-menu w-100 bg-dark border border-info shadow-lg p-0" style="position: absolute; top: 100%; left: 0; z-index: 9999; max-height: 220px; overflow-y: auto; display: none; margin-top: 4px; border-radius: 6px; font-size: 13.5px;"></div>
                    </div>
                </div>

                <!-- SWAP BUTTON -->
                <div class="col-12 col-md-2 text-center pt-md-3">
                    <button type="button" id="btnSwapCountries" class="btn btn-dark border border-warning text-warning w-100 py-1.5 fw-bold shadow-sm" style="font-size: 12.5px; border-radius: 6px;" title="Tukar posisi negara 1 & 2">
                        <i class="fa-solid fa-right-left me-1"></i> Tukar (Swap)
                    </button>
                </div>

                <!-- COUNTRY B SEARCH -->
                <div class="col-12 col-md-5">
                    <label for="searchCountryB" class="form-label fw-bold text-warning text-uppercase mb-1 d-flex justify-content-between" style="font-size: 12px;">
                        <span><i class="fa-solid fa-flag me-1"></i> Negara 2 (Country B)</span>
                        <span class="text-muted fw-normal" style="font-size: 11.5px;">Cari dari 195 Negara</span>
                    </label>
                    <div style="position: relative;">
                        <input type="text" id="searchCountryB" class="form-control bg-dark text-white border-warning shadow-none py-1.5 px-3 fw-bold" placeholder="Ketik nama/awalan negara 2 (contoh: Australia, Japan...)" autocomplete="off" style="border-radius: 6px; font-size: 13.5px;">
                        <input type="hidden" id="selectCountryB" value="">
                        <div id="dropdownCountryB" class="dropdown-menu w-100 bg-dark border border-warning shadow-lg p-0" style="position: absolute; top: 100%; left: 0; z-index: 9999; max-height: 220px; overflow-y: auto; display: none; margin-top: 4px; border-radius: 6px; font-size: 13.5px;"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 3. COMPACT MASTER COMPARISON TABLE -->
        <div class="table-responsive" style="overflow-x: auto;">
            <table class="table table-dark table-hover align-middle mb-0" style="--bs-table-bg: transparent; border-collapse: separate; border-spacing: 0 6px;">
                <thead>
                    <tr class="text-light text-uppercase border-bottom border-secondary fw-bold" style="border-color: rgba(255, 255, 255, 0.25) !important; letter-spacing: 0.6px; font-size: 13.5px;">
                        <th style="width: 26%; padding: 10px 14px;">Parameter &amp; Kategori</th>
                        <th style="width: 27%; padding: 10px 14px;" class="text-center" id="thCountryA">PILIH NEGARA 1 ❓</th>
                        <th style="width: 27%; padding: 10px 14px;" class="text-center" id="thCountryB">PILIH NEGARA 2 ❓</th>
                        <th style="width: 20%; padding: 10px 14px;" class="text-end">Δ Selisih &amp; Status</th>
                    </tr>
                </thead>
                <tbody id="comparisonMainTableBody">
                    <!-- Populated via JS with ultra-compact rows -->
                </tbody>
            </table>
        </div>

        <div class="mt-3 pt-3 border-top border-secondary d-flex flex-wrap justify-content-between align-items-center text-muted" style="border-color: rgba(255, 255, 255, 0.15) !important; font-size: 14px;">
            <span><i class="fa-solid fa-database text-info me-1"></i> Terhubung ke database 195 Negara Berdaulat Dunia.</span>
            <span><i class="fa-solid fa-bolt text-warning me-1"></i> Real-time Rule Engine &amp; FX Volatility</span>
        </div>

    </div>
    <!-- END MASTER CONTAINER -->
</div>
@endsection

@push('scripts')
<script>
    window.INITIAL_DSS_DATA = @json($data);
</script>
<script src="{{ asset('js/decision-support.js') }}"></script>
@endpush
