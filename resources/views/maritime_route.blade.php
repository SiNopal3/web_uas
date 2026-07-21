@extends('layouts.app')

@section('title', 'Route & Delay Simulation - RiskIntel Hub')

@section('content')
<div class="container-fluid p-0">

    {{-- Header --}}
    <div class="mb-4">
        <div class="glass-card p-4 d-flex align-items-center justify-content-between flex-wrap gap-3"
             style="border-left: 4px solid #d97706;">
            <div>
                <span class="badge fw-bold mb-2 px-3 py-1"
                      style="background:#fef3c7;color:#92400e;border:1px solid #fde68a;">
                    MARITIME FREIGHT SIMULATION
                </span>
                <h2 class="fw-bold mb-1">Route & Delay Simulation</h2>
                <p class="small mb-0" style="color:#64748b;">
                    Calculate sea freight distance, normal transit time, and dynamic delay penalty
                    based on weather, geopolitics, and port congestion conditions.
                </p>
            </div>
        </div>
    </div>

    {{-- Simulator + Map --}}
    <div class="row g-4 mb-4">

        {{-- Control Form --}}
        <div class="col-12 col-xl-5">
            <div class="glass-card p-4 d-flex flex-column">

                <div class="pb-3 mb-3 border-bottom" style="border-color:#e2e8f0 !important;">
                    <h5 class="fw-bold mb-1">
                        <i class="fa-solid fa-route me-2" style="color:#d97706;"></i>
                        Route Configuration
                    </h5>
                    <p class="small mb-0" style="color:#64748b;">
                        Select origin and destination ports.
                    </p>
                </div>

                <form id="maritimeSimForm">
                    {{-- Origin --}}
                    <div class="row g-3 mb-3">
                        <div class="col-12 col-md-6">
                            <label class="form-label small fw-bold mb-1" style="color:#3b82f6;">
                                <i class="fa-solid fa-flag-checkered me-1"></i> Origin Country:
                            </label>
                            <select id="originCountrySelect" class="form-select small"></select>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label small fw-bold mb-1" style="color:#3b82f6;">
                                <i class="fa-solid fa-anchor me-1"></i> Origin Port:
                            </label>
                            <select id="originPortSelect" class="form-select small"></select>
                        </div>
                    </div>

                    {{-- Destination --}}
                    <div class="row g-3 mb-4">
                        <div class="col-12 col-md-6">
                            <label class="form-label small fw-bold mb-1" style="color:#ef4444;">
                                <i class="fa-solid fa-flag me-1"></i> Destination Country:
                            </label>
                            <select id="destCountrySelect" class="form-select small"></select>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label small fw-bold mb-1" style="color:#ef4444;">
                                <i class="fa-solid fa-anchor me-1"></i> Destination Port:
                            </label>
                            <select id="destPortSelect" class="form-select small"></select>
                        </div>
                    </div>

                    {{-- Risk Engine Sync --}}
                    <div class="p-3 rounded mb-2"
                         style="background:#eff6ff;border:1px solid #bfdbfe;">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <div class="d-flex align-items-center">
                                <i class="fa-solid fa-satellite-dish me-2" style="color:#3b82f6;"></i>
                                <span class="fw-bold small" style="color:#1e40af;">
                                    Risk Engine Sync:
                                    <span id="autoSyncCountryName" style="color:#d97706;">-</span>
                                </span>
                            </div>
                            <span class="badge fw-bold"
                                  style="background:#dbeafe;color:#1d4ed8;border:1px solid #bfdbfe;">
                                <i class="fa-solid fa-link me-1"></i> Auto-Connected
                            </span>
                        </div>
                        <p class="small mb-2" style="color:#475569;font-size:12px;">
                            External risk index (maritime weather, geopolitics/blockade, port congestion,
                            and FX volatility) automatically adjusts with country risk score from
                            the Country Dashboard.
                        </p>
                        <div id="syncedIndicatorsBadge"
                             class="d-flex flex-wrap gap-2 pt-2 border-top"
                             style="border-color:#bfdbfe !important;font-size:11.5px;">
                            <span class="badge" style="background:#f0f9ff;color:#0369a1;border:1px solid #bae6fd;">
                                <i class="fa-solid fa-cloud-showers-heavy me-1"></i>
                                Weather: <strong id="syncWeatherVal">-</strong>
                            </span>
                            <span class="badge" style="background:#fef2f2;color:#b91c1c;border:1px solid #fecaca;">
                                <i class="fa-solid fa-globe me-1"></i>
                                Geopolitics: <strong id="syncNewsVal">-</strong>
                            </span>
                            <span class="badge" style="background:#fffbeb;color:#92400e;border:1px solid #fde68a;">
                                <i class="fa-solid fa-boxes-stacked me-1"></i>
                                Congestion: <strong id="syncInflationVal">-</strong>
                            </span>
                            <span class="badge" style="background:#f0fdf4;color:#166534;border:1px solid #bbf7d0;">
                                <i class="fa-solid fa-money-bill-transfer me-1"></i>
                                FX: <strong id="syncCurrencyVal">-</strong>
                            </span>
                        </div>
                    </div>
                </form>

                <div class="pt-3 mt-3 border-top small" style="border-color:#e2e8f0 !important;color:#64748b;">
                    <i class="fa-solid fa-circle-info me-1" style="color:#d97706;"></i>
                    Maritime route line (dashed polyline) is calculated based on geodesic port coordinates.
                </div>
            </div>
        </div>

        {{-- Map --}}
        <div class="col-12 col-xl-7">
            <div class="glass-card p-4 d-flex flex-column" style="min-height:520px;">
                <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
                    <div>
                        <h5 class="fw-bold mb-0">
                            <i class="fa-solid fa-map-location-dot me-2" style="color:#3b82f6;"></i>
                            Maritime Route Map
                        </h5>
                        <span class="small" style="color:#64748b;">
                            Displays dashed route line from origin to destination port.
                        </span>
                    </div>
                    <div id="simRiskCategoryBadge"
                         class="badge px-3 py-2 fs-6 fw-bold"
                         style="background:#dcfce7;color:#166534;border:1px solid #bbf7d0;">
                        <i class="fa-solid fa-shield me-1"></i> - (Select Countries)
                    </div>
                </div>

                <div id="maritimeRouteMapContainer"
                     class="flex-grow-1 rounded overflow-hidden"
                     style="min-height:420px;position:relative;border:1px solid #e2e8f0;">
                    <div id="maritimeRouteMap"
                         style="width:100%;height:100%;min-height:420px;background:#f8fafc;">
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- KPI Cards --}}
    <div class="row g-4 mb-4">
        <div class="col-12 col-md-3">
            <div class="glass-card p-3 text-center h-100"
                 style="border-left:4px solid #3b82f6;">
                <span class="small d-block text-uppercase fw-bold mb-2" style="color:#64748b;font-size:11px;">
                    <i class="fa-solid fa-ruler-horizontal me-1"></i> Maritime Distance
                </span>
                <h3 id="simDistanceNm" class="fw-bold my-1" style="color:#3b82f6;">- NM</h3>
                <span id="simDistanceKm" class="small" style="color:#94a3b8;">- km</span>
            </div>
        </div>
        <div class="col-12 col-md-3">
            <div class="glass-card p-3 text-center h-100"
                 style="border-left:4px solid #64748b;">
                <span class="small d-block text-uppercase fw-bold mb-2" style="color:#64748b;font-size:11px;">
                    <i class="fa-solid fa-stopwatch me-1"></i> Normal Transit Time
                </span>
                <h3 id="simBaseDuration" class="fw-bold my-1" style="color:#0f172a;">- Days</h3>
                <span class="small" style="color:#94a3b8;">Without external risk</span>
            </div>
        </div>
        <div class="col-12 col-md-3">
            <div class="glass-card p-3 text-center h-100"
                 style="border-left:4px solid #d97706;background:#fffbeb;">
                <span class="small d-block text-uppercase fw-bold mb-2" style="color:#d97706;font-size:11px;">
                    <i class="fa-solid fa-triangle-exclamation me-1"></i> Delay Penalty
                </span>
                <h3 id="simDelayDuration" class="fw-bold my-1" style="color:#d97706;">+0 Days</h3>
                <span class="small" style="color:#94a3b8;">Based on Risk Engine score</span>
            </div>
        </div>
        <div class="col-12 col-md-3">
            <div class="glass-card p-3 text-center h-100"
                 style="border-left:4px solid #10b981;background:#f0fdf4;">
                <span class="small d-block text-uppercase fw-bold mb-2" style="color:#10b981;font-size:11px;">
                    <i class="fa-solid fa-calendar-check me-1"></i> Estimated Arrival (ETA)
                </span>
                <h3 id="simTotalDuration" class="fw-bold my-1" style="color:#10b981;">- Days</h3>
                <span class="small" style="color:#94a3b8;">Total sea transit time</span>
            </div>
        </div>
    </div>

    {{-- Breakdown --}}
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="glass-card p-4">
                <h5 class="fw-bold mb-3">
                    <i class="fa-solid fa-clipboard-list me-2" style="color:#d97706;"></i>
                    Delay Cause Analysis & Logistics Mitigation Recommendations
                </h5>
                <div id="simBreakdownList">
                    <div class="p-3 rounded small" style="background:#f8fafc;border:1px solid #e2e8f0;color:#94a3b8;">
                        Select origin and destination countries to load maritime risk calculation...
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script src="{{ asset('js/maritime-route.js') }}"></script>
@endpush
