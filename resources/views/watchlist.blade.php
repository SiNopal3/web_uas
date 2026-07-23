@extends('layouts.app')

@section('title', 'Favorites Watchlist - RiskIntel Hub')

@section('content')
<div class="container-fluid p-0">
    <!-- Top Selector and Country Header -->
    <div class="row align-items-center mb-4 g-3">
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

    <!-- Favorite Watchlist Management Card -->
    <div class="row mb-4">
        <div class="col-12">
            <div id="watchlistSection" class="glass-card p-4">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 border-bottom pb-3">
                    <div>
                        <h4 class="fw-bold text-dark mb-1">
                            <i class="fa-solid fa-bookmark text-primary me-2"></i> Favorites Watchlist (Database Persisted Sync)
                        </h4>
                        <span class="small text-muted">Save and monitor strategic countries tied securely to your enterprise user profile.</span>
                    </div>
                </div>

                <div id="watchlistContainer" class="d-flex flex-column gap-2" style="min-height: 220px;">
                    <div class="text-center small py-5 text-muted">
                        <i class="fa-solid fa-spinner fa-spin me-2 text-primary"></i> Loading server watchlist...
                    </div>
                </div>

                <div class="border-top pt-3 mt-4 d-flex justify-content-between align-items-center small text-muted">
                    <span><i class="fa-solid fa-lock text-success me-1"></i> Watchlist data is isolated per user account (`Auth::id()`).</span>
                    <span class="badge badge-soft-info">Sanctum Session Secured</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
