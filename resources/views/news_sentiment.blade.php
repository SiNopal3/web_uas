@extends('layouts.app')

@section('title', 'News Intelligence & AI Sentiment - RiskIntel Hub')

@section('content')
<div class="container-fluid p-0">
    <!-- Top Selector and Country Header -->
    <div class="row align-items-center mb-4 g-3">
        <div class="col-12 col-xl-5">
            <div class="glass-card p-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div class="overflow-hidden text-truncate pe-2">
                    <span class="small text-uppercase fw-semibold d-block text-truncate text-muted" style="letter-spacing: 0.05em; font-size: 11px !important;">Active Target Country</span>
                    <h3 id="selectedCountryName" class="fw-bold text-dark mb-0 mt-0.5 text-truncate" style="font-size: 20px !important;">-</h3>
                </div>
                <div class="d-flex align-items-center gap-2 flex-wrap ms-auto">
                    <button type="button" class="btn btn-secondary btn-sm fw-semibold px-3 py-1.5 flex-shrink-0" onclick="window.resetToAdminFeed && window.resetToAdminFeed()" title="Reset Country Selection">
                        <i class="fa-solid fa-rotate-left me-1"></i> Reset
                    </button>
                    <div class="text-end ms-1 flex-shrink-0">
                        <span id="selectedCountryRegion" class="badge badge-soft-secondary px-2.5 py-1 fw-semibold text-slate-700">-</span>
                        <div id="selectedCountryCurrency" class="small fw-semibold mt-1 text-primary" style="font-size: 13px !important;">-</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-xl-7" style="position: relative; z-index: 100;">
            <div class="glass-card p-3" style="overflow: visible !important;">
                <label for="countrySearchInput" class="form-label small mb-1.5 fw-semibold d-block text-slate-700">
                    <i class="fa-solid fa-magnifying-glass me-1 text-primary"></i> Search Sovereign Country:
                </label>
                <div style="position: relative;">
                    <input type="text" id="countrySearchInput" class="form-control" placeholder="Type country name or prefix (e.g. Indonesia, Germany, Japan...)" autocomplete="off">
                    <div id="countryDropdownList" class="dropdown-menu w-100 p-0 shadow-lg" style="position: absolute; top: 100%; left: 0; z-index: 9999; max-height: 280px; overflow-y: auto; display: none; margin-top: 4px; border-radius: 8px;"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <!-- Global News Feed Section -->
        <div class="col-12">
            <div id="newsCard" class="glass-card p-4 h-100 d-flex flex-column">
                <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-3 flex-wrap gap-2">
                    <div>
                        <h4 class="fw-bold text-dark mb-1">
                            News Intelligence & AI Sentiment Analysis Feed (GNews API Sync)
                        </h4>
                        <span class="small text-muted">Displays curated admin risk reports when no country is selected, or live global news per target country.</span>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <span id="newsCountBadge" class="badge badge-soft-info">3 Articles per Country</span>
                    </div>
                </div>
                <div id="newsListContainer" class="row g-3 pt-1">
                    <div class="col-12 text-center small py-5 text-muted">
                        <i class="fa-solid fa-spinner fa-spin me-2 text-primary"></i> Loading global logistics articles...
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
