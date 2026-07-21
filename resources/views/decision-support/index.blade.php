@extends('layouts.app')

@section('title', 'Country Comparison Engine')

@section('content')
<div class="container-fluid py-2">
    <!-- MASTER CONTAINER: 1 KOTAK MENCAKUP SEMUA SUPER KOMPAKS & TANPA SCROLL -->
    <div class="glass-card p-3 p-xl-4 mb-3 border-top border-info border-4 shadow-lg" style="border-radius: 12px;">
        
        <!-- 1. HEADER SECTION (COMPACT & CLEAN) -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-2 pb-2 border-bottom" style="border-color: #e2e8f0 !important;">
            <div>
                <h1 class="h4 fw-bold mb-0 d-flex align-items-center" style="color:#0f172a !important;">
                    Country Comparison Engine
                </h1>
                <p class="mb-0 fw-semibold" style="font-size: 12px; color:#64748b;">Bandingkan 5 parameter fundamental &amp; rantai pasok antar negara dalam 1 tabel terpadu tanpa scroll.</p>
            </div>
        </div>

        <!-- 2. COMPARISON SELECTOR CONTROLS (SEARCH 195 COUNTRIES) -->
        <div class="p-3 px-4 rounded-3 mb-3 shadow-sm" style="background: #f8fafc; border: 1px solid #e2e8f0;">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-2">
                <div class="d-flex align-items-center gap-2">
                    <span class="text-uppercase fw-bold" style="font-size: 13px; letter-spacing: 0.8px; color:#3b82f6;">Perbandingan:</span>
                    <span id="comparisonTitleText" class="fw-bold" style="font-size: 15px; color:#d97706;">Belum Ada Negara Dipilih (Kosong)</span>
                </div>
                <div class="mt-2 mt-md-0">
                    <button type="button" id="btnResetComparison" class="btn btn-sm px-3 fw-bold" style="font-size: 12.5px; background:#eff6ff; color:#3b82f6; border:1px solid #bfdbfe; border-radius:6px;" title="Kosongkan pilihan negara agar tidak ada yang dibandingin">
                        <i class="fa-solid fa-rotate-left me-1"></i> Reset (Kosongkan Pilihan)
                    </button>
                </div>
            </div>

            <!-- SELECTOR SEARCHABLE INPUTS (195 COUNTRIES) -->
            <div class="row g-2 align-items-center">
                <!-- COUNTRY A SEARCH -->
                <div class="col-12 col-md-5">
                    <label for="searchCountryA" class="form-label fw-bold text-uppercase mb-1 d-flex justify-content-between" style="font-size: 12px; color:#3b82f6;">
                        <span><i class="fa-solid fa-flag me-1"></i> Negara 1 (Country A)</span>
                        <span class="fw-normal" style="font-size: 11.5px; color:#94a3b8;">Cari dari 195 Negara</span>
                    </label>
                    <div style="position: relative;">
                        <input type="text" id="searchCountryA" class="form-control shadow-none py-1 px-3 fw-bold" placeholder="Ketik nama/awalan negara 1 (contoh: Germany, Indonesia...)" autocomplete="off" style="border-radius: 6px; font-size: 13.5px; border-color:#3b82f6;">
                        <input type="hidden" id="selectCountryA" value="">
                        <div id="dropdownCountryA" class="dropdown-menu w-100 shadow-lg p-0" style="position: absolute; top: 100%; left: 0; z-index: 9999; max-height: 220px; overflow-y: auto; display: none; margin-top: 4px; border-radius: 6px; font-size: 13.5px; background:#ffffff; border:1px solid #bfdbfe;"></div>
                    </div>
                </div>

                <!-- SWAP BUTTON -->
                <div class="col-12 col-md-2 text-center pt-md-3">
                    <button type="button" id="btnSwapCountries" class="btn w-100 fw-bold shadow-sm" style="font-size: 12.5px; border-radius: 6px; background:#fffbeb; color:#d97706; border:1px solid #fde68a;" title="Tukar posisi negara 1 & 2">
                        <i class="fa-solid fa-right-left me-1"></i> Tukar (Swap)
                    </button>
                </div>

                <!-- COUNTRY B SEARCH -->
                <div class="col-12 col-md-5">
                    <label for="searchCountryB" class="form-label fw-bold text-uppercase mb-1 d-flex justify-content-between" style="font-size: 12px; color:#d97706;">
                        <span><i class="fa-solid fa-flag me-1"></i> Negara 2 (Country B)</span>
                        <span class="fw-normal" style="font-size: 11.5px; color:#94a3b8;">Cari dari 195 Negara</span>
                    </label>
                    <div style="position: relative;">
                        <input type="text" id="searchCountryB" class="form-control shadow-none py-1 px-3 fw-bold" placeholder="Ketik nama/awalan negara 2 (contoh: Australia, Japan...)" autocomplete="off" style="border-radius: 6px; font-size: 13.5px; border-color:#d97706;">
                        <input type="hidden" id="selectCountryB" value="">
                        <div id="dropdownCountryB" class="dropdown-menu w-100 shadow-lg p-0" style="position: absolute; top: 100%; left: 0; z-index: 9999; max-height: 220px; overflow-y: auto; display: none; margin-top: 4px; border-radius: 6px; font-size: 13.5px; background:#ffffff; border:1px solid #fde68a;"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 3. COMPACT MASTER COMPARISON TABLE -->
        <div class="table-responsive" style="overflow-x: auto;">
            <table class="table table-hover align-middle mb-0" style="--bs-table-bg: transparent; border-collapse: separate; border-spacing: 0 4px;">
                <thead>
                    <tr class="text-uppercase border-bottom fw-bold" style="color:#475569; border-color:#e2e8f0 !important; letter-spacing: 0.6px; font-size: 13px; background:#f8fafc;">
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

        <div class="mt-3 pt-3 border-top d-flex flex-wrap justify-content-between align-items-center" style="border-color: #e2e8f0 !important; font-size: 13px; color:#64748b;">
            <span><i class="fa-solid fa-database me-1" style="color:#3b82f6;"></i> Terhubung ke database 195 Negara Berdaulat Dunia.</span>
            <span><i class="fa-solid fa-bolt me-1" style="color:#d97706;"></i> Real-time Rule Engine &amp; FX Volatility</span>
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
