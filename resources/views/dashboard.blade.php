@extends('layouts.app')

@section('title', 'Global Supply Chain Risk Dashboard')

@section('content')
<div class="container-fluid p-0">
    <!-- Top Selector & Active Country Bar -->
    <div class="row align-items-center mb-4 g-3">
        <div class="col-12 col-xl-5">
            <div class="glass-card p-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div class="overflow-hidden text-truncate pe-2">
                    <span class="small text-uppercase fw-semibold d-block text-truncate text-muted" style="letter-spacing: 0.05em; font-size: 11px !important;">Active Target Country</span>
                    <h3 id="selectedCountryName" class="fw-bold text-dark mb-0 mt-0.5 text-truncate" style="font-size: 16px !important;"><span class="text-muted fw-normal" style="font-size: 14px;">Select a country to begin monitoring</span></h3>
                </div>
                <div class="d-flex align-items-center gap-2 flex-wrap ms-auto">
                    <button id="addFavoriteBtn" class="btn btn-outline-secondary btn-sm fw-semibold d-flex align-items-center gap-1.5 px-3 py-1.5 flex-shrink-0" onclick="addToWatchlist()" title="Add to Favorites Watchlist">
                        <i class="fa-regular fa-star text-warning"></i>
                        <span>+ Favorite</span>
                    </button>
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
        <div class="col-12 col-xl-7" style="position: relative; z-index: 100;">
            <div class="glass-card p-3" style="overflow: visible !important;">
                <label for="countrySearchInput" class="form-label small mb-1.5 fw-semibold d-block text-slate-700">
                    Search Sovereign Country:
                </label>
                <div style="position: relative;">
                    <i class="fa-solid fa-magnifying-glass text-muted" style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); font-size: 13.5px; z-index: 5;"></i>
                    <input type="text" id="countrySearchInput" class="form-control ps-5" placeholder="Search country..." autocomplete="off" style="height: 44px; border-radius: 8px; font-size: 13.5px;">
                    <div id="countryDropdownList" class="dropdown-menu w-100 p-0 border-0" style="position: absolute; top: 100%; left: 0; z-index: 9999; max-height: 340px; overflow-y: auto; display: none; margin-top: 6px; border-radius: 10px; background: #ffffff; box-shadow: 0 10px 30px rgba(15, 23, 42, 0.15) !important;"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- 4 Real-time KPI Summary Cards -->
    <div class="row g-3 mb-4">
        <!-- Weather Card -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div id="weatherCard" class="glass-card h-100 d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="small fw-semibold text-muted">Maritime Weather</span>
                    <span class="badge badge-soft-info"><i class="fa-solid fa-cloud-sun me-1"></i> Open-Meteo</span>
                </div>
                <div class="my-1">
                    <h3 id="valWeatherTemp" class="fw-bold text-dark mb-1">-</h3>
                    <div class="d-flex justify-content-between small mb-1 text-muted">
                        <span>Wind: <strong id="valWeatherWind" class="text-dark">-</strong></span>
                        <span>Dir: <strong id="valWeatherWindDir" class="text-primary">-</strong></span>
                    </div>
                    <div class="d-flex justify-content-between small mb-1 text-muted">
                        <span>Rain: <strong id="valWeatherRain" class="text-dark">-</strong></span>
                        <span>Humidity: <strong id="valWeatherHumidity" class="text-dark">-</strong></span>
                    </div>
                    <div class="d-flex justify-content-between small text-muted">
                        <span>Clouds: <strong id="valWeatherCloud" class="text-dark">-</strong></span>
                    </div>
                </div>
                <div class="border-top pt-2 mt-2 text-muted" style="font-size: 11px !important;">
                    Live oceanic satellite weather telemetry
                </div>
            </div>
        </div>

        <!-- Economy Card -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div id="economyCard" class="glass-card h-100 d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="small fw-semibold text-muted">Economic Metrics</span>
                    <span class="badge badge-soft-secondary"><i class="fa-solid fa-building-columns me-1"></i> World Bank</span>
                </div>
                <div class="my-1">
                    <h3 id="valEconGdp" class="fw-bold text-dark mb-1">-</h3>
                    <div class="d-flex justify-content-between small text-muted">
                        <span>Inflation: <strong id="valEconInf" class="text-warning">-</strong></span>
                        <span>Population: <strong id="valEconPop" class="text-dark">-</strong></span>
                    </div>
                </div>
                <div class="border-top pt-2 mt-2 text-muted" style="font-size: 11px !important;">
                    Verified macroeconomic indicators
                </div>
            </div>
        </div>

        <!-- Currency Card -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div id="currencyCard" class="glass-card h-100 d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="small fw-semibold text-muted">Exchange Rate</span>
                    <span class="badge badge-soft-warning"><i class="fa-solid fa-coins me-1"></i> Valas API</span>
                </div>
                <div class="my-1">
                    <h4 id="valCurrencyRate" class="fw-bold text-dark mb-1">-</h4>
                    <div id="valCurrencyBase" class="small text-muted">-</div>
                </div>
                <div class="border-top pt-2 mt-2 text-muted" style="font-size: 11px !important;">
                    Real-time FX currency conversion rates
                </div>
            </div>
        </div>

        <!-- AI Real-time Risk Score Card -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="glass-card h-100 d-flex flex-column justify-content-between" style="border-left: 4px solid var(--primary);">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="small fw-semibold text-muted">AI Supply Chain Risk Score</span>
                    <span class="badge badge-soft-info">AI Engine</span>
                </div>
                <div class="my-1 d-flex align-items-center justify-content-between">
                    <div>
                        <h2 id="valTotalRiskScore" class="fw-bold text-dark mb-0">-</h2>
                        <span class="small text-muted">out of 100 pts</span>
                    </div>
                    <div>
                        <span id="valRiskStatusBadge" class="badge badge-soft-secondary px-3 py-1.5 fw-semibold">-</span>
                    </div>
                </div>
                <div class="border-top pt-2 mt-2 text-muted" style="font-size: 11px !important;">
                    Deterministic multi-source weighting model
                </div>
            </div>
        </div>
    </div>

    <!-- 3 Feature Navigation Shortcut Cards -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-md-4">
            <a href="{{ url('/ports') }}" onclick="return window.navigateToFeatureFromDashboard && window.navigateToFeatureFromDashboard(event, '{{ url('/ports') }}')" class="text-decoration-none">
                <div class="glass-card h-100 p-3.5 d-flex flex-column justify-content-between" style="cursor: pointer;">
                    <div>
                        <div class="d-flex justify-content-between align-items-center mb-2.5">
                            <span class="badge badge-soft-info"><i class="fa-solid fa-ship me-1"></i> Leaflet.js + NGA</span>
                        </div>
                        <h5 class="fw-bold text-dark mb-1">Ports & Weather Map</h5>
                        <p class="small text-muted mb-0" style="line-height: 1.5;">
                            Interactive geospatial terminal mapping with real-time marine weather overlays.
                        </p>
                    </div>
                    <div class="mt-3 pt-2.5 border-top d-flex align-items-center justify-content-between small fw-semibold text-primary">
                        <span>Open Maritime Hub</span>
                        <i class="fa-solid fa-arrow-right-long"></i>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-12 col-md-4">
            <a href="{{ url('/news-sentiment') }}" onclick="return window.navigateToFeatureFromDashboard && window.navigateToFeatureFromDashboard(event, '{{ url('/news-sentiment') }}')" class="text-decoration-none">
                <div class="glass-card h-100 p-3.5 d-flex flex-column justify-content-between" style="cursor: pointer;">
                    <div>
                        <div class="d-flex justify-content-between align-items-center mb-2.5">
                            <span class="badge badge-soft-secondary"><i class="fa-solid fa-newspaper me-1"></i> GNews + Native PHP</span>
                        </div>
                        <h5 class="fw-bold text-dark mb-1">News & AI Sentiment</h5>
                        <p class="small text-muted mb-0" style="line-height: 1.5;">
                            Global supply chain news intelligence & custom AI lexicon sentiment analysis engine.
                        </p>
                    </div>
                    <div class="mt-3 pt-2.5 border-top d-flex align-items-center justify-content-between small fw-semibold text-primary">
                        <span>Analyze Sentiment</span>
                        <i class="fa-solid fa-arrow-right-long"></i>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-12 col-md-4">
            <a href="{{ url('/watchlist') }}" onclick="return window.navigateToFeatureFromDashboard && window.navigateToFeatureFromDashboard(event, '{{ url('/watchlist') }}')" class="text-decoration-none">
                <div class="glass-card h-100 p-3.5 d-flex flex-column justify-content-between" style="cursor: pointer;">
                    <div>
                        <div class="d-flex justify-content-between align-items-center mb-2.5">
                            <span class="badge badge-soft-warning"><i class="fa-solid fa-bookmark me-1"></i> Database Sync</span>
                        </div>
                        <h5 class="fw-bold text-dark mb-1">Favorites Watchlist</h5>
                        <p class="small text-muted mb-0" style="line-height: 1.5;">
                            Manage persistent country monitoring lists securely tied to your enterprise account.
                        </p>
                    </div>
                    <div class="mt-3 pt-2.5 border-top d-flex align-items-center justify-content-between small fw-semibold text-primary">
                        <span>Manage Watchlist</span>
                        <i class="fa-solid fa-arrow-right-long"></i>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection