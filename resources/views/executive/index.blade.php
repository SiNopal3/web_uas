@extends('layouts.app')

@section('title', 'Executive Dashboard')

@section('content')
<div class="container-fluid py-2">
    <!-- SECTION 1: EXECUTIVE HEADER -->
    <div class="glass-card mb-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center border-start border-warning border-4">
        <div>
            <h1 class="h3 fw-bold mb-1 text-white">
                <i class="fa-solid fa-chart-pie bi bi-pie-chart-fill me-2 text-warning"></i> Executive Dashboard
            </h1>
            <p class="text-muted mb-0 fw-semibold">Global Supply Chain Risk Intelligence Overview</p>
        </div>
        <div class="mt-3 mt-md-0 text-md-end">
            <div class="small text-muted mt-1">
                <span class="badge bg-success-subtle text-success border border-success me-1">LIVE SYNC</span>
                Auto-refreshes every 60s
            </div>
        </div>
    </div>

    <!-- SECTION 2: KPI CARDS (6 ANIMATED KPI CARDS) -->
    <div class="row g-3 mb-4">
        <!-- KPI 1: Average Global Risk -->
        <div class="col-12 col-md-6 col-xl-2">
            <div class="glass-card h-100 d-flex flex-column justify-content-between position-relative overflow-hidden" style="transition: transform 0.2s;">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <span class="text-muted small fw-bold text-uppercase">Average Global Risk</span>
                    <i class="fa-solid fa-earth-americas bi bi-globe text-warning fs-5"></i>
                </div>
                <div>
                    <div id="kpiAvgRisk" class="h2 fw-bold text-white mb-1">{{ $data['kpi_cards']['average_global_risk'] }}%</div>
                    <span id="kpiAvgRiskBadge" class="badge bg-{{ $data['kpi_cards']['average_global_risk_color'] }} px-2 py-1">
                        {{ $data['kpi_cards']['average_global_risk_badge'] }}
                    </span>
                </div>
            </div>
        </div>

        <!-- KPI 2: Highest Risk Country -->
        <div class="col-12 col-md-6 col-xl-2">
            <div class="glass-card h-100 d-flex flex-column justify-content-between border-start border-danger border-3">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <span class="text-muted small fw-bold text-uppercase">Highest Risk Country</span>
                    <i class="fa-solid fa-triangle-exclamation bi bi-exclamation-triangle text-danger fs-5"></i>
                </div>
                <div>
                    <div id="kpiHighRiskCountry" class="h4 fw-bold text-white mb-0 text-truncate">{{ $data['kpi_cards']['highest_risk_country']['name'] }}</div>
                    <div id="kpiHighRiskScore" class="small fw-bold text-danger mt-1">Score: {{ $data['kpi_cards']['highest_risk_country']['score'] }}%</div>
                </div>
            </div>
        </div>

        <!-- KPI 3: Safest Country -->
        <div class="col-12 col-md-6 col-xl-2">
            <div class="glass-card h-100 d-flex flex-column justify-content-between border-start border-success border-3">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <span class="text-muted small fw-bold text-uppercase">Safest Country</span>
                    <i class="fa-solid fa-shield-halved bi bi-shield-check text-success fs-5"></i>
                </div>
                <div>
                    <div id="kpiSafestCountry" class="h4 fw-bold text-white mb-0 text-truncate">{{ $data['kpi_cards']['safest_country']['name'] }}</div>
                    <div id="kpiSafestScore" class="small fw-bold text-success mt-1">Score: {{ $data['kpi_cards']['safest_country']['score'] }}%</div>
                </div>
            </div>
        </div>

        <!-- KPI 4: Average Inflation -->
        <div class="col-12 col-md-6 col-xl-2">
            <div class="glass-card h-100 d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <span class="text-muted small fw-bold text-uppercase">Average Inflation</span>
                    <i class="fa-solid fa-chart-line bi bi-graph-up text-info fs-5"></i>
                </div>
                <div>
                    <div id="kpiAvgInflation" class="h2 fw-bold text-white mb-1">{{ $data['kpi_cards']['average_inflation'] }}%</div>
                    <span class="text-muted small">Global Corridor CPI</span>
                </div>
            </div>
        </div>

        <!-- KPI 5: Strongest Currency -->
        <div class="col-12 col-md-6 col-xl-2">
            <div class="glass-card h-100 d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <span class="text-muted small fw-bold text-uppercase">Strongest Currency</span>
                </div>
                <div>
                    <div id="kpiStrongCurrency" class="h4 fw-bold text-white mb-0">{{ $data['kpi_cards']['strongest_currency']['currency'] }}</div>
                    <div id="kpiStrongCountry" class="small text-muted text-truncate">{{ $data['kpi_cards']['strongest_currency']['country'] }}</div>
                </div>
            </div>
        </div>

        <!-- KPI 6: Countries Being Monitored -->
        <div class="col-12 col-md-6 col-xl-2">
            <div class="glass-card h-100 d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <span class="text-muted small fw-bold text-uppercase">Monitored Nations</span>
                    <i class="fa-solid fa-globe bi bi-globe2 text-light fs-5"></i>
                </div>
                <div>
                    <div id="kpiTotalCountries" class="h2 fw-bold text-white mb-1">{{ $data['kpi_cards']['total_monitored'] }}</div>
                    <span class="text-muted small">Active Hub Corridors</span>
                </div>
            </div>
        </div>
    </div>

    <!-- SECTION 3: GLOBAL RISK TREND & SECTION 7: WORLD RISK DISTRIBUTION -->
    <div class="row g-4 mb-4">
        <!-- Section 3: Historical Risk Trend Line Chart -->
        <div class="col-12 col-xl-8">
            <div class="glass-card h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold text-white mb-0">
                        <i class="fa-solid fa-chart-area bi bi-graph-up-arrow text-warning me-2"></i> Section 3: Global Risk Trend (Last 30 Days)
                    </h5>
                    <span class="badge bg-dark border border-secondary text-muted">Daily Index</span>
                </div>
                <div style="position: relative; height: 300px; width: 100%;">
                    <canvas id="globalRiskTrendChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Section 7: World Risk Distribution Doughnut Chart -->
        <div class="col-12 col-xl-4">
            <div class="glass-card h-100 d-flex flex-column">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold text-white mb-0">
                        <i class="fa-solid fa-chart-pie bi bi-pie-chart me-2 text-warning"></i> Section 7: Risk Distribution
                    </h5>
                    <span class="badge bg-dark border border-secondary text-muted">By Severity</span>
                </div>
                <div class="flex-grow-1 d-flex align-items-center justify-content-center" style="position: relative; min-height: 250px;">
                    <canvas id="worldRiskDistChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- SECTION 4: TOP 5 HIGH RISK COUNTRIES & SECTION 5: TOP 5 SAFEST COUNTRIES -->
    <div class="row g-4 mb-4">
        <!-- Section 4: Top 5 High Risk Countries -->
        <div class="col-12 col-xl-7">
            <div class="glass-card h-100 border-top border-danger border-3">
                <h5 class="fw-bold text-white mb-3">
                    <i class="fa-solid fa-triangle-exclamation bi bi-exclamation-triangle text-danger me-2"></i> Section 4: Top 5 High Risk Countries
                </h5>
                <div class="table-responsive">
                    <table class="table table-dark table-hover align-middle mb-0" style="--bs-table-bg: transparent;">
                        <thead>
                            <tr class="text-muted small text-uppercase border-bottom border-secondary">
                                <th scope="col">Rank</th>
                                <th scope="col">Country</th>
                                <th scope="col" class="text-center">Weather</th>
                                <th scope="col" class="text-center">Inflation</th>
                                <th scope="col" class="text-center">Currency</th>
                                <th scope="col" class="text-center">News</th>
                                <th scope="col" class="text-center">Final Score</th>
                                <th scope="col" class="text-end">Status</th>
                            </tr>
                        </thead>
                        <tbody id="highRiskTableBody">
                            @foreach($data['top_high_risk_countries'] as $row)
                            <tr class="border-bottom border-secondary" style="border-color: rgba(255,255,255,0.05) !important;">
                                <td class="fw-bold text-warning">#{{ $row['rank'] }}</td>
                                <td class="fw-bold text-white">{{ $row['name'] }} <span class="text-muted small">({{ $row['iso'] }})</span></td>
                                <td class="text-center">{{ $row['weather_risk'] }}%</td>
                                <td class="text-center">{{ $row['inflation_risk'] }}%</td>
                                <td class="text-center">{{ $row['currency_risk'] }}%</td>
                                <td class="text-center">{{ $row['news_risk'] }}%</td>
                                <td class="text-center fw-bold text-danger">{{ $row['final_risk_score'] }}%</td>
                                <td class="text-end">
                                    <span class="badge bg-{{ $row['status_color'] }}">{{ $row['status'] }}</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Section 5: Top 5 Safest Countries -->
        <div class="col-12 col-xl-5">
            <div class="glass-card h-100 border-top border-success border-3">
                <h5 class="fw-bold text-white mb-3">
                    <i class="fa-solid fa-shield-halved bi bi-shield-check text-success me-2"></i> Section 5: Top 5 Safest Countries
                </h5>
                <div class="table-responsive">
                    <table class="table table-dark table-hover align-middle mb-0" style="--bs-table-bg: transparent;">
                        <thead>
                            <tr class="text-muted small text-uppercase border-bottom border-secondary">
                                <th scope="col">Rank</th>
                                <th scope="col">Country</th>
                                <th scope="col" class="text-center">Final Score</th>
                                <th scope="col" class="text-end">Status</th>
                            </tr>
                        </thead>
                        <tbody id="safestTableBody">
                            @foreach($data['top_safest_countries'] as $row)
                            <tr class="border-bottom border-secondary" style="border-color: rgba(255,255,255,0.05) !important;">
                                <td class="fw-bold text-success">#{{ $row['rank'] }}</td>
                                <td class="fw-bold text-white">{{ $row['name'] }} <span class="text-muted small">({{ $row['iso'] }})</span></td>
                                <td class="text-center fw-bold text-success">{{ $row['final_risk_score'] }}%</td>
                                <td class="text-end">
                                    <span class="badge bg-{{ $row['status_color'] }}">{{ $row['status'] }}</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- SECTION 6: GLOBAL ALERT CENTER & SECTION 8: COUNTRY RISK COMPARISON -->
    <div class="row g-4 mb-4">
        <!-- Section 6: Global Alert Center -->
        <div class="col-12 col-xl-5">
            <div class="glass-card h-100 d-flex flex-column">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold text-white mb-0">
                        <i class="fa-solid fa-bell bi bi-bell text-warning me-2"></i> Section 6: Global Alert Center
                    </h5>
                    <span class="badge bg-danger text-white">{{ count($data['alerts']) }} Alerts</span>
                </div>
                <div id="alertCenterContainer" class="flex-grow-1 overflow-auto" style="max-height: 350px;">
                    @foreach($data['alerts'] as $alert)
                    <div class="alert alert-{{ $alert['color'] }} bg-{{ $alert['color'] }}-subtle border border-{{ $alert['color'] }} text-white d-flex align-items-start mb-2 py-2 px-3 shadow-sm" role="alert" style="border-radius: 10px; background: rgba({{ $alert['color'] === 'danger' ? '220,53,69' : ($alert['color'] === 'warning' ? '255,193,7' : '25,135,84') }}, 0.18) !important;">
                        <i class="fa-solid {{ $alert['color'] === 'danger' ? 'fa-circle-exclamation' : ($alert['color'] === 'warning' ? 'fa-triangle-exclamation' : 'fa-check-circle') }} mt-1 me-2 text-{{ $alert['color'] }} fs-6"></i>
                        <div>
                            <div class="fw-bold small text-{{ $alert['color'] }} text-uppercase">{{ $alert['type'] }} &bull; {{ $alert['level'] }}</div>
                            <div class="small text-white mt-1">{{ $alert['message'] }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Section 8: Country Risk Comparison Horizontal Bar Chart -->
        <div class="col-12 col-xl-7">
            <div class="glass-card h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold text-white mb-0">
                        <i class="fa-solid fa-chart-bar bi bi-bar-chart-fill text-warning me-2"></i> Section 8: Country Risk Comparison (Top 10)
                    </h5>
                    <span class="badge bg-dark border border-secondary text-muted">Risk Score (%)</span>
                </div>
                <div style="position: relative; height: 350px; width: 100%;">
                    <canvas id="countryRiskCompChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- SECTION 9: EXECUTIVE SUMMARY -->
    <div class="row">
        <div class="col-12">
            <div class="glass-card border-start border-info border-4 p-4">
                <div class="d-flex align-items-center mb-2">
                    <i class="fa-solid fa-file-lines bi bi-file-earmark-text text-info fs-4 me-3"></i>
                    <h5 class="fw-bold text-white mb-0">Section 9: Executive Summary (Automated Rule-Based Intelligence)</h5>
                </div>
                <p id="executiveSummaryText" class="text-white fs-6 mb-0 mt-2 lh-lg fw-medium" style="background: rgba(0,0,0,0.25); padding: 15px 20px; border-radius: 10px; border: 1px solid rgba(255,255,255,0.08);">
                    {{ $data['executive_summary'] }}
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    window.INITIAL_EXECUTIVE_DATA = @json($data);
</script>
<script src="{{ asset('js/executive.js') }}"></script>
@endpush
