@extends('layouts.app')

@section('title', 'Maritime Ports & Weather Map - RiskIntel Hub')

@section('content')
<div class="container-fluid p-0">
    <!-- Top Selector and Country Header -->
    <div class="row align-items-center mb-4 g-3 country-selector-row">
        <div class="col-12 col-xl-5">
            <div class="glass-card p-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div class="overflow-hidden text-truncate pe-2">
                    <span class="small text-uppercase fw-semibold d-block text-truncate text-muted" style="letter-spacing: 0.05em; font-size: 11px !important;">Active Target Country</span>
                    <h3 id="selectedCountryName" class="fw-bold text-dark mb-0 mt-0.5 text-truncate" style="font-size: 16px !important;"><span class="text-muted fw-normal" style="font-size: 14px;">Select a country to begin monitoring</span></h3>
                </div>
                <div class="d-flex align-items-center gap-2 flex-wrap ms-auto">
                    <button type="button" class="btn btn-secondary btn-sm fw-semibold px-3 py-1.5 flex-shrink-0" onclick="window.resetToAdminFeed && window.resetToAdminFeed()" title="Reset Country Selection">
                        <i class="fa-solid fa-rotate-left me-1"></i> Reset
                    </button>
                    <div class="text-end ms-1 flex-shrink-0">
                        <span id="selectedCountryRegion" class="badge badge-soft-secondary px-2.5 py-1 fw-semibold text-slate-700">Global</span>
                        <div id="selectedCountryCurrency" class="small fw-semibold mt-1 text-primary" style="font-size: 13px !important;">All Currencies</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-xl-7 country-selector-card">
            <div class="glass-card p-3" style="overflow: visible !important;">
                <label for="countrySearchInput" class="form-label small mb-1.5 fw-semibold d-block text-slate-700">
                    Search Sovereign Country:
                </label>
                <div style="position: relative;">
                    <i class="fa-solid fa-magnifying-glass" style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #475569 !important; font-size: 14px; z-index: 5; pointer-events: none;"></i>
                    <input type="text" id="countrySearchInput" class="form-control ps-5" placeholder="Search country..." autocomplete="off" style="height: 44px; border-radius: 8px; font-size: 13.5px;">
                    <div id="countryDropdownList" class="dropdown-menu country-dropdown-menu" style="display: none;"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Weather Summary Banner -->
    <div class="row g-3 mb-4">
        <div class="col-12">
            <div id="weatherCard" class="glass-card p-3.5 d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                <div class="mb-2 mb-lg-0" style="min-width: 240px;">
                    <span class="small fw-semibold d-block text-muted" style="font-size: 12px !important;">
                        Maritime Weather & Oceanic Conditions (Open-Meteo Proxy)
                    </span>
                    <div class="d-flex align-items-baseline gap-2 mt-1">
                        <h2 id="valWeatherTemp" class="fw-bold text-dark mb-0">-- °C</h2>
                        <span class="badge badge-soft-info">Air Temp</span>
                    </div>
                    <div class="small text-muted mt-1" style="font-size: 11.5px !important;">Direct oceanic satellite telemetry proxy monitoring</div>
                </div>
                <div class="d-flex flex-nowrap align-items-center justify-content-between gap-3 p-2.5 rounded-2 flex-grow-1 overflow-x-auto" style="background: #f8fafc; border: 1px solid #e2e8f0;">
                    <div class="pe-3 border-end text-nowrap">
                        <span class="small d-block text-muted fw-medium" style="font-size: 11px;"><i class="fa-solid fa-wind me-1 text-primary"></i> Wind Speed:</span>
                        <strong id="valWeatherWind" class="text-dark fs-6">-- m/s</strong>
                    </div>
                    <div class="pe-3 border-end text-nowrap">
                        <span class="small d-block text-muted fw-medium" style="font-size: 11px;"><i class="fa-solid fa-compass me-1 text-primary"></i> Wind Dir:</span>
                        <strong id="valWeatherWindDir" class="text-dark fs-6">--</strong>
                    </div>
                    <div class="pe-3 border-end text-nowrap">
                        <span class="small d-block text-muted fw-medium" style="font-size: 11px;"><i class="fa-solid fa-cloud-showers-heavy me-1 text-primary"></i> Rain:</span>
                        <strong id="valWeatherRain" class="text-dark fs-6">-- mm/h</strong>
                    </div>
                    <div class="pe-3 border-end text-nowrap">
                        <span class="small d-block text-muted fw-medium" style="font-size: 11px;"><i class="fa-solid fa-droplet me-1 text-primary"></i> Humidity:</span>
                        <strong id="valWeatherHumidity" class="text-dark fs-6">-- %</strong>
                    </div>
                    <div class="text-nowrap">
                        <span class="small d-block text-muted fw-medium" style="font-size: 11px;"><i class="fa-solid fa-cloud me-1 text-primary"></i> Cloud Cover:</span>
                        <strong id="valWeatherCloud" class="text-dark fs-6">-- %</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Map & Port Directory Side-by-Side Section -->
    <div class="row g-4 mb-4 align-items-stretch">
        <!-- Left Side: Maritime Satellite Map -->
        <div class="col-12 col-lg-7 col-xl-8 d-flex">
            <div class="glass-card p-3.5 rounded-3 w-100 d-flex flex-column">
                <div class="mb-3">
                    <h5 class="fw-bold text-dark mb-1">
                        Global Maritime Port Terminals Map (NGA World Port Index)
                    </h5>
                    <div class="small text-muted" style="line-height: 1.5;">Monitor strategic port coordinates and global trade lanes. Click markers or select from the directory.</div>
                </div>
                <div id="maritimeMap" class="flex-grow-1" style="min-height: 600px; width: 100%; border-radius: 8px; border: 1px solid #e2e8f0;"></div>
            </div>
        </div>

        <!-- Right Side: Port Directory Panel -->
        <div class="col-12 col-lg-5 col-xl-4 d-flex">
            <div class="glass-card p-3.5 rounded-3 w-100 d-flex flex-column" style="max-height: 700px;">
                <div class="border-bottom pb-3 mb-3 flex-shrink-0">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <h5 class="fw-bold text-dark mb-0" style="font-size: 15px;">
                            <i class="fa-solid fa-list-check text-primary me-2"></i> Port Directory
                        </h5>
                        <span class="badge badge-soft-info"><span id="portCountBadge">0</span> Detected</span>
                    </div>
                    <div class="small text-muted" style="font-size: 12px;">Active country terminal list. Click focus to center map camera.</div>
                </div>

                <!-- Internal Vertical Scroll Container -->
                <div id="portListContainer" class="d-flex flex-column gap-2.5 flex-grow-1 overflow-y-auto pe-1" style="max-height: 580px; scroll-behavior: smooth;">
                    <div class="w-100 text-center small py-5 text-muted">
                        <i class="fa-solid fa-spinner fa-spin me-2 text-primary"></i> Loading port terminals for selected country...
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
