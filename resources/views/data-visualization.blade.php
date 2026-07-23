@extends('layouts.app')
@section('title', 'Data Visualization Dashboard')
@section('content')
<div class="container-fluid py-2">

    {{-- HEADER --}}
    <div class="glass-card mb-4 p-4 d-flex flex-column flex-xl-row justify-content-between align-items-xl-center gap-3">
        <div>
            <h1 class="h3 fw-bold mb-0" style="color:#0f172a !important;"><i class="fa-solid fa-chart-line me-2" style="color:#3b82f6;"></i>Data Visualization Dashboard</h1>
            <p class="mb-0 fw-semibold mt-1" style="color:#64748b;">Visualisasi tren makroekonomi, pertumbuhan GDP, inflasi, nilai tukar, dan skor risiko komposit</p>
        </div>
    </div>

    {{-- COUNTRY SELECTOR --}}
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

    {{-- Hidden sync input --}}
    <input type="hidden" id="filterCountry" value="{{ $country ?: '' }}">

    {{-- 4 CHARTS GRID --}}
    <div class="row g-4 mb-4">

        {{-- GDP Trend --}}
        <div class="col-12 col-xl-6">
            <div class="glass-card p-4 h-100 d-flex flex-column">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h6 class="fw-bold mb-0" style="color:#0f172a !important;"><i class="fa-solid fa-arrow-trend-up text-success me-2"></i>Grafik: GDP Trend</h6>
                        <p class="small text-muted mb-0" style="font-size:11px;">Pertumbuhan Produk Domestik Bruto global vs pasar berkembang</p>
                    </div>
                    <span class="badge badge-soft-success py-1 px-2" style="font-size:11px;">Macro Growth</span>
                </div>
                <div style="position:relative;height:220px;width:100%;">
                    <canvas id="chartGdpTrend"></canvas>
                </div>
            </div>
        </div>

        {{-- Inflation Trend --}}
        <div class="col-12 col-xl-6">
            <div class="glass-card p-4 h-100 d-flex flex-column">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h6 class="fw-bold mb-0" style="color:#0f172a !important;"><i class="fa-solid fa-chart-line text-warning me-2"></i>Grafik: Inflation Trend</h6>
                        <p class="small text-muted mb-0" style="font-size:11px;">Fluktuasi laju inflasi komposit dan syok harga terhadap target sentral</p>
                    </div>
                    <span class="badge badge-soft-warning py-1 px-2" style="font-size:11px;">Price Index</span>
                </div>
                <div style="position:relative;height:220px;width:100%;">
                    <canvas id="chartInflationTrend"></canvas>
                </div>
            </div>
        </div>

        {{-- Currency Trend --}}
        <div class="col-12 col-xl-6">
            <div class="glass-card p-4 h-100 d-flex flex-column">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h6 class="fw-bold mb-0" style="color:#0f172a !important;"><i class="fa-solid fa-coins text-info me-2"></i>Grafik: Currency Trend</h6>
                        <p class="small text-muted mb-0" style="font-size:11px;">Pergerakan kekuatan indeks mata uang (DXY) dan stabilitas pasar FX</p>
                    </div>
                    <span class="badge badge-soft-info py-1 px-2" style="font-size:11px;">FX Stability</span>
                </div>
                <div style="position:relative;height:220px;width:100%;">
                    <canvas id="chartCurrencyTrend"></canvas>
                </div>
            </div>
        </div>

        {{-- Risk Trend --}}
        <div class="col-12 col-xl-6">
            <div class="glass-card p-4 h-100 d-flex flex-column">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h6 class="fw-bold mb-0" style="color:#0f172a !important;"><i class="fa-solid fa-shield-halved text-danger me-2"></i>Grafik: Risk Trend</h6>
                        <p class="small text-muted mb-0" style="font-size:11px;">Tren skor risiko komposit global, logistik pelabuhan, dan makroekonomi</p>
                    </div>
                    <span class="badge badge-soft-danger py-1 px-2" style="font-size:11px;">Risk Composite</span>
                </div>
                <div style="position:relative;height:220px;width:100%;">
                    <canvas id="chartRiskTrend"></canvas>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
@push('scripts')
<script>
(function () {
    // Initial data from server
    var CHART_DATA = @json($chartData);
    var charts = {};

    if (typeof Chart !== 'undefined') {
        Chart.defaults.color = '#475569';
        Chart.defaults.font.family = "'Inter','Segoe UI',sans-serif";
    }

    var COMMON_OPTS = {
        responsive: true,
        maintainAspectRatio: false,
        interaction: { mode: 'index', intersect: false },
        plugins: {
            legend: { position: 'bottom', labels: { boxWidth: 10, padding: 10, font: { size: 10.5 }, color: '#475569' } }
        },
        scales: {
            x: { grid: { color: 'rgba(0,0,0,0.06)' }, ticks: { color: '#64748b', font: { weight: '600' } } },
            y: { grid: { color: 'rgba(0,0,0,0.06)' }, ticks: { color: '#64748b' } }
        }
    };

    function buildChart(id, datasets, extraOpts) {
        var ctx = document.getElementById(id);
        if (!ctx || typeof Chart === 'undefined') return;
        if (charts[id]) charts[id].destroy();
        var opts = JSON.parse(JSON.stringify(COMMON_OPTS));
        if (extraOpts) Object.assign(opts.scales.y.ticks, extraOpts);
        charts[id] = new Chart(ctx, {
            type: 'line',
            data: { labels: CHART_DATA.labels, datasets: datasets },
            options: opts
        });
    }

    function renderAll(data) {
        CHART_DATA = data;
        buildChart('chartGdpTrend',       data.gdp.datasets,       { callback: function(v){ return v + '%'; } });
        buildChart('chartInflationTrend', data.inflation.datasets, { callback: function(v){ return v + '%'; } });
        buildChart('chartCurrencyTrend',  data.currency.datasets,  {});
        buildChart('chartRiskTrend',      data.risk.datasets,      { callback: function(v){ return v + '/100'; } });
    }

    // Initial render
    document.addEventListener('DOMContentLoaded', function () {
        renderAll(CHART_DATA);

        // Polling: detect when dashboard.js updates #filterCountry
        var filterEl = document.getElementById('filterCountry');
        if (filterEl) {
            var lastVal = filterEl.value;
            setInterval(function () {
                var cur = filterEl.value;
                if (cur !== lastVal) {
                    lastVal = cur;
                    fetchCharts(cur);
                }
            }, 200);
        }

        // Patch selectCountry so charts refresh on country pick
        window.addEventListener('load', function () {
            var orig = window.selectCountry;
            window.selectCountry = async function (input) {
                if (typeof orig === 'function') await orig(input);
                var isReset = !input || input === 'Global / Semua Negara'
                    || input === 'Global' || input === 'Belum Dipilih' || input === '-';
                var name = isReset ? '' : input.toString().trim().replace(/\s*\(.*?\)/, '');
                if (filterEl) filterEl.value = name;
                fetchCharts(name);
            };
        });
    });

    function fetchCharts(country) {
        var url = '/api/data-visualization/charts';
        if (country && country.trim() !== '') {
            url += '?country=' + encodeURIComponent(country.trim());
        }
        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' } })
            .then(function (r) { return r.json(); })
            .then(function (json) { if (json.success && json.chartData) renderAll(json.chartData); })
            .catch(function (e) { console.error('Chart fetch error:', e); });
    }

    window.dvFetchCharts = fetchCharts;
})();
</script>
@endpush
