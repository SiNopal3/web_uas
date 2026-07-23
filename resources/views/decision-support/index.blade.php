@extends('layouts.app')

@section('title', 'Country Comparison Engine')

@section('content')
<div class="container-fluid py-2">
    <!-- MASTER CONTAINER -->
    <div class="glass-card p-3 p-xl-4 mb-3" style="border-radius: 12px;">
        
        <!-- 1. HEADER SECTION -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3 pb-2 border-bottom" style="border-color: #e2e8f0 !important;">
            <div>
                <h1 class="h4 fw-bold mb-0 d-flex align-items-center" style="color:#0f172a !important;">
                    <i class="fa-solid fa-code-compare me-2 text-primary"></i> Country Comparison Engine
                </h1>
                <p class="mb-0 fw-semibold mt-1" style="font-size: 12.5px; color:#64748b;">Bandingkan 5 parameter fundamental &amp; rantai pasok antar negara dalam 1 tabel terpadu tanpa scroll.</p>
            </div>
        </div>

        <!-- 2. COMPARISON SELECTOR CONTROLS (ENTERPRISE COUNTRY SELECTOR) -->
        <div class="glass-card p-3 p-xl-4 mb-4" style="background: #ffffff; border: 1px solid #e2e8f0;">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3">
                <div class="d-flex align-items-center gap-2">
                    <span class="text-uppercase fw-semibold" style="font-size: 12px; letter-spacing: 0.05em; color:#475569;">Target Comparison:</span>
                    <span id="comparisonTitleText" class="fw-bold" style="font-size: 15px; color:#2563eb;">Select 2 Sovereign Countries</span>
                </div>
                <div class="mt-2 mt-md-0">
                    <button type="button" id="btnResetComparison" class="btn btn-secondary btn-sm px-3 fw-semibold" title="Reset Country Selection">
                        <i class="fa-solid fa-rotate-left me-1"></i> Reset Selection
                    </button>
                </div>
            </div>

            <!-- SELECTOR SEARCHABLE INPUTS (195 SOVEREIGN COUNTRIES) -->
            <div class="row g-3 align-items-center country-selector-row">
                <!-- COUNTRY A SEARCH -->
                <div class="col-12 col-md-5 country-selector-card">
                    <label for="searchCountryA" class="form-label small mb-1.5 fw-semibold d-block text-slate-700">
                        <i class="fa-solid fa-flag text-primary me-1"></i> Country A (Target 1):
                    </label>
                    <div style="position: relative;">
                        <i class="fa-solid fa-magnifying-glass" style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #475569 !important; font-size: 14px; z-index: 5; pointer-events: none;"></i>
                        <input type="text" id="searchCountryA" class="form-control ps-5" placeholder="Search Country A..." autocomplete="off" style="height: 44px; border-radius: 8px; font-size: 13.5px;">
                        <input type="hidden" id="selectCountryA" value="">
                        <div id="dropdownCountryA" class="dropdown-menu country-dropdown-menu" style="display: none;"></div>
                    </div>
                </div>

                <!-- SWAP BUTTON -->
                <div class="col-12 col-md-2 text-center pt-md-4">
                    <button type="button" id="btnSwapCountries" class="btn btn-secondary w-100 fw-semibold shadow-sm" style="height: 44px; font-size: 13px;" title="Swap Country A & Country B">
                        <i class="fa-solid fa-right-left me-1"></i> Swap
                    </button>
                </div>

                <!-- COUNTRY B SEARCH -->
                <div class="col-12 col-md-5 country-selector-card">
                    <label for="searchCountryB" class="form-label small mb-1.5 fw-semibold d-block text-slate-700">
                        <i class="fa-solid fa-flag text-warning me-1"></i> Country B (Target 2):
                    </label>
                    <div style="position: relative;">
                        <i class="fa-solid fa-magnifying-glass" style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #475569 !important; font-size: 14px; z-index: 5; pointer-events: none;"></i>
                        <input type="text" id="searchCountryB" class="form-control ps-5" placeholder="Search Country B..." autocomplete="off" style="height: 44px; border-radius: 8px; font-size: 13.5px;">
                        <input type="hidden" id="selectCountryB" value="">
                        <div id="dropdownCountryB" class="dropdown-menu country-dropdown-menu" style="display: none;"></div>
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
