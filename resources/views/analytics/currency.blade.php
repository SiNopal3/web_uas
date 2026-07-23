@extends('layouts.app')

@section('title', 'Currency Impact Dashboard')

@section('content')
<div class="container-fluid p-0">
    <!-- Header Section -->
    <div class="d-flex align-items-center justify-content-between mb-4 pb-3 border-bottom">
        <div>
            <h3 class="fw-bold text-dark mb-1">Currency Impact Dashboard</h3>
            <p class="small text-muted mb-0">Foreign Exchange Volatility & Real-Time Currency Rate Trends (195 Sovereign Countries)</p>
        </div>
    </div>

    <!-- Top Selector -->
    <div class="row align-items-center mb-4 g-3">
        <!-- Selected Country & Currency Card -->
        <div class="col-12 col-xl-5">
            <div class="glass-card p-3 d-flex align-items-center justify-content-between flex-wrap gap-2 h-100">
                <div class="overflow-hidden text-truncate pe-2">
                    <span class="small text-uppercase fw-semibold d-block text-truncate text-muted" style="letter-spacing: 0.05em; font-size: 11px !important;">Active Target Country</span>
                    <h3 id="selectedCountryName" class="fw-bold text-dark mb-0 mt-0.5 text-truncate" style="font-size: 20px !important;">-</h3>
                </div>
                <div class="d-flex align-items-center gap-2 flex-wrap ms-auto">
                    <button type="button" class="btn btn-secondary btn-sm fw-semibold px-3 py-1.5 flex-shrink-0" onclick="(window.resetCurrToGlobal && window.resetCurrToGlobal()) || (window.resetToAdminFeed && window.resetToAdminFeed())" title="Reset Country Selection">
                        <i class="fa-solid fa-rotate-left me-1"></i> Reset
                    </button>
                    <div class="text-end ms-1 flex-shrink-0">
                        <span id="selectedCountryRegion" class="badge badge-soft-secondary px-2.5 py-1 fw-semibold text-slate-700">-</span>
                        <div id="selectedCountryCurrency" class="small fw-semibold mt-1 text-primary" style="font-size: 13px !important;">-</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search Input -->
        <div class="col-12 col-xl-7" style="position: relative; z-index: 100;">
            <div class="glass-card p-3" style="overflow: visible !important;">
                <label for="countrySearchInput" class="form-label small mb-1.5 fw-semibold d-block text-slate-700">
                    <i class="fa-solid fa-magnifying-glass me-1 text-primary"></i> Search Sovereign Country:
                </label>
                <div style="position: relative;">
                    <input type="text" id="countrySearchInput" class="form-control" placeholder="Type country name or prefix (e.g. Afghanistan, Indonesia, Germany, Japan...)" autocomplete="off">
                    <div id="countryDropdownList" class="dropdown-menu w-100 p-0 shadow-lg" style="position: absolute; top: 100%; left: 0; z-index: 9999; max-height: 280px; overflow-y: auto; display: none; margin-top: 4px; border-radius: 8px;"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- MAIN CHART SECTION -->
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="glass-card p-4 h-100 d-flex flex-column justify-content-between">
                <div>
                    <!-- Header Chart & Period Buttons -->
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3 pb-3 border-bottom gap-2">
                        <div>
                            <h4 class="fw-bold text-dark mb-1">
                                <i class="fa-solid fa-chart-area text-primary me-2"></i> Exchange Rate Trend (<span id="chartCurrTitle">-</span>)
                            </h4>
                            <span class="small text-muted">Historical FX exchange rate movements against US Dollar (USD) visualized via <span class="badge badge-soft-info">Chart.js</span></span>
                        </div>
                        <div class="btn-group btn-group-sm" role="group">
                            <button type="button" class="btn btn-outline-secondary period-btn active" data-period="30">30 Days</button>
                            <button type="button" class="btn btn-outline-secondary period-btn" data-period="90">90 Days</button>
                            <button type="button" class="btn btn-outline-secondary period-btn" data-period="180">6 Months</button>
                            <button type="button" class="btn btn-outline-secondary period-btn" data-period="365">1 Year</button>
                        </div>
                    </div>

                    <!-- Canvas Container -->
                    <div style="height: 420px; width: 100%; position: relative; background: #ffffff; padding: 15px; border-radius: 8px; border: 1px solid #e2e8f0;">
                        <canvas id="currencyTrendChartCanvas"></canvas>
                    </div>
                </div>

                <!-- Rate High/Low/Avg Metrics -->
                <div class="row g-3 mt-3 pt-3 border-top text-center small">
                    <div class="col-12 col-md-4">
                        <div class="p-3 rounded-2" style="background: #f8fafc; border: 1px solid #e2e8f0;">
                            <span class="text-muted d-block fw-semibold mb-1" style="font-size: 11.5px;"><i class="fa-solid fa-arrow-trend-up text-danger me-1"></i> Highest Rate (High):</span>
                            <strong class="text-danger fs-5" id="statHighRate">-</strong>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="p-3 rounded-2" style="background: #f8fafc; border: 1px solid #e2e8f0;">
                            <span class="text-muted d-block fw-semibold mb-1" style="font-size: 11.5px;"><i class="fa-solid fa-arrow-trend-down text-success me-1"></i> Lowest Rate (Low):</span>
                            <strong class="text-success fs-5" id="statLowRate">-</strong>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="p-3 rounded-2" style="background: #f8fafc; border: 1px solid #e2e8f0;">
                            <span class="text-muted d-block fw-semibold mb-1" style="font-size: 11.5px;"><i class="fa-solid fa-scale-balanced text-primary me-1"></i> Period Average (Avg):</span>
                            <strong class="text-primary fs-5" id="statAvgRate">-</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/currency_dashboard.js') }}"></script>
@endpush
