@extends('layouts.app')

@section('title', 'Data Visualization Dashboard')

@section('content')
<div class="container-fluid py-2" style="color: #f8fafc;">
    <!-- HEADER -->
    <div class="glass-card mb-4 p-4 border-start border-info border-4 shadow-lg d-flex flex-column flex-xl-row justify-content-between align-items-xl-center gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <h1 class="h3 fw-bold mb-0 text-white">Data Visualization Dashboard</h1>
            </div>
            <p class="text-muted mb-0 fw-semibold">Visualisasi tren makroekonomi, pertumbuhan produk domestik bruto, inflasi, nilai tukar, dan skor risiko komposit</p>
        </div>
    </div>

    <!-- TOP SELECTOR AND COUNTRY HEADER (PERSIS SEPERTI FITUR LAIN / DASHBOARD UTAMA) -->
    <div class="row align-items-center mb-4 g-3">
        <div class="col-12 col-xl-5">
            <div class="glass-card p-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div class="overflow-hidden text-truncate pe-2">
                    <span class="small text-uppercase fw-bold d-block text-truncate" style="color: #e2e8f0; letter-spacing: 0.8px;">Negara Analisis Aktif</span>
                    <h3 id="selectedCountryName" class="fw-bold text-white mb-0 mt-1 text-truncate">-</h3>
                </div>
                <div class="d-flex align-items-center gap-2 flex-wrap ms-auto">
                    <button type="button" class="btn btn-sm btn-outline-info fw-bold px-3 py-2 flex-shrink-0" onclick="window.resetToAdminFeed && window.resetToAdminFeed()" title="Reset pilihan negara & langsung buka dropdown untuk memilih negara baru" style="border-radius: 8px;">
                        <i class="fa-solid fa-rotate-left me-1"></i> Reset
                    </button>
                    <div class="text-end ms-1 flex-shrink-0">
                        <span id="selectedCountryRegion" class="badge bg-secondary mb-1 px-3 py-1 fw-bold text-white">-</span>
                        <div id="selectedCountryCurrency" class="text-warning small fw-bold mt-1" style="font-size: 14px;">-</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-xl-7" style="position: relative; z-index: 100;">
            <div class="glass-card p-3" style="overflow: visible !important;">
                <label for="countrySearchInput" class="form-label small mb-2 fw-bold d-block" style="color: #f8fafc; font-size: 13px;">
                    <i class="fa-solid fa-magnifying-glass me-1 text-warning"></i> Cari Negara:
                </label>
                <div style="position: relative;">
                    <input type="text" id="countrySearchInput" class="form-control bg-dark text-white border-secondary shadow-none" placeholder="Ketik nama atau awalan negara (contoh: Afghanistan, Indonesia, Germany, Japan...)" autocomplete="off" style="border-radius: 6px;">
                    <div id="countryDropdownList" class="dropdown-menu w-100 bg-dark border border-warning shadow-lg p-0" style="position: absolute; top: 100%; left: 0; z-index: 9999; max-height: 280px; overflow-y: auto; display: none; margin-top: 4px; border-radius: 6px;"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Hidden Country Filter for Data Visualization Dashboard Sync -->
    <input type="hidden" name="country" id="filterCountry" value="">

    <!-- 4 GRAFIK UTAMA (GDP Trend, Inflation Trend, Currency Trend, Risk Trend) -->
    <div class="row g-3 mb-4">
        <!-- 1. GDP Trend -->
        <div class="col-12 col-xl-6">
            <div class="glass-card p-3 h-100 d-flex flex-column justify-content-between border-top border-success border-3 shadow-lg">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div>
                        <h6 class="fw-bold text-white mb-0" style="font-size: 14px;"><i class="fa-solid fa-arrow-trend-up text-success me-2"></i> Grafik: GDP Trend</h6>
                        <p class="small text-muted mb-0" style="font-size: 11px;">Pertumbuhan Produk Domestik Bruto global vs pasar berkembang</p>
                    </div>
                    <span class="badge bg-success bg-opacity-25 text-success border border-success border-opacity-25 py-1 px-2" style="font-size: 11px;">Macro Growth</span>
                </div>
                <div style="position: relative; height: 180px; width: 100%;">
                    <canvas id="chartGdpTrend"></canvas>
                </div>
            </div>
        </div>

        <!-- 2. Inflation Trend -->
        <div class="col-12 col-xl-6">
            <div class="glass-card p-3 h-100 d-flex flex-column justify-content-between border-top border-warning border-3 shadow-lg">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div>
                        <h6 class="fw-bold text-white mb-0" style="font-size: 14px;"><i class="fa-solid fa-chart-line text-warning me-2"></i> Grafik: Inflation Trend</h6>
                        <p class="small text-muted mb-0" style="font-size: 11px;">Fluktuasi laju inflasi komposit dan syok harga terhadap target sentral</p>
                    </div>
                    <span class="badge bg-warning bg-opacity-25 text-warning border border-warning border-opacity-25 py-1 px-2" style="font-size: 11px;">Price Index</span>
                </div>
                <div style="position: relative; height: 180px; width: 100%;">
                    <canvas id="chartInflationTrend"></canvas>
                </div>
            </div>
        </div>

        <!-- 3. Currency Trend -->
        <div class="col-12 col-xl-6">
            <div class="glass-card p-3 h-100 d-flex flex-column justify-content-between border-top border-info border-3 shadow-lg">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div>
                        <h6 class="fw-bold text-white mb-0" style="font-size: 14px;"><i class="fa-solid fa-coins text-info me-2"></i> Grafik: Currency Trend</h6>
                        <p class="small text-muted mb-0" style="font-size: 11px;">Pergerakan kekuatan indeks mata uang (DXY) dan stabilitas pasar FX</p>
                    </div>
                    <span class="badge bg-info bg-opacity-25 text-info border border-info border-opacity-25 py-1 px-2" style="font-size: 11px;">FX Stability</span>
                </div>
                <div style="position: relative; height: 180px; width: 100%;">
                    <canvas id="chartCurrencyTrend"></canvas>
                </div>
            </div>
        </div>

        <!-- 4. Risk Trend -->
        <div class="col-12 col-xl-6">
            <div class="glass-card p-3 h-100 d-flex flex-column justify-content-between border-top border-danger border-3 shadow-lg">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div>
                        <h6 class="fw-bold text-white mb-0" style="font-size: 14px;"><i class="fa-solid fa-shield-halved text-danger me-2"></i> Grafik: Risk Trend</h6>
                        <p class="small text-muted mb-0" style="font-size: 11px;">Tren skor risiko komposit global, logistik pelabuhan, dan makroekonomi</p>
                    </div>
                    <span class="badge bg-danger bg-opacity-25 text-danger border border-danger border-opacity-25 py-1 px-2" style="font-size: 11px;">Risk Composite</span>
                </div>
                <div style="position: relative; height: 180px; width: 100%;">
                    <canvas id="chartRiskTrend"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Hidden containers kept for JavaScript safety compatibility -->
    <div class="d-none">
        <canvas id="historicalTrendChart"></canvas>
        <canvas id="riskDistPieChart"></canvas>
        <canvas id="weatherMixedChart"></canvas>
        <canvas id="currencyPolarChart"></canvas>
        <canvas id="newsBarChart"></canvas>
        <canvas id="forecastBarChart"></canvas>
        <div id="biHeatmapContainer"></div>
    </div>
</div>

<!-- DRILL DOWN ANALYTICS MODAL (Kept for compatibility when inspecting details) -->
<div class="modal fade" id="drillDownModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content glass-card border-secondary text-white">
            <div class="modal-header border-bottom border-secondary p-4">
                <div class="d-flex align-items-center gap-3">
                    <span id="modalCountryFlag" class="fs-1"></span>
                    <div>
                        <h4 id="modalCountryName" class="fw-bold mb-0 text-white">Country Profiling</h4>
                        <span id="modalCountryIso" class="badge bg-secondary">ISO</span>
                        <span id="modalCountryRegion" class="badge bg-info text-dark ms-1">Region</span>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-4">
                    <div class="col-12 col-md-4">
                        <div class="p-3 rounded bg-dark border border-secondary h-100">
                            <span class="small text-muted text-uppercase fw-bold d-block mb-1">Composite Risk Score</span>
                            <div class="d-flex align-items-baseline gap-2">
                                <h2 id="modalRiskScore" class="fw-bold mb-0 text-info">--/100</h2>
                                <span id="modalRiskBadge" class="badge bg-danger">Critical</span>
                            </div>
                            <hr class="border-secondary my-3">
                            <div class="d-flex justify-content-between small mb-2">
                                <span class="text-muted">7-Day Delay Probability:</span>
                                <strong id="modalDelayProb" class="text-white">--%</strong>
                            </div>
                            <div class="d-flex justify-content-between small mb-2">
                                <span class="text-muted">Expected Delay Duration:</span>
                                <strong id="modalExpectedDelay" class="text-warning">-- hrs</strong>
                            </div>
                            <div class="d-flex justify-content-between small">
                                <span class="text-muted">Primary Risk Driver:</span>
                                <strong id="modalPrimaryDriver" class="text-info">--</strong>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-8">
                        <div class="p-3 rounded bg-dark border border-secondary h-100">
                            <h6 class="fw-bold text-info mb-3"><i class="fa-solid fa-list-check me-2"></i> AI Executive Advisory & Actionable Mitigations</h6>
                            <ul id="modalAdvisoryList" class="list-unstyled small mb-0 d-flex flex-column gap-2"></ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-top border-secondary p-3">
                <button type="button" class="btn btn-outline-secondary btn-sm text-white" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    window.INITIAL_BI_DATA = @json($data);
</script>
<script src="{{ asset('js/analytics.js') }}"></script>
@endpush
