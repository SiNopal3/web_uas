@extends('layouts.app')

@section('title', 'Enterprise Prediction Dashboard')

@section('content')
<div class="container-fluid py-2">
    <!-- SECTION 1: PREDICTION HEADER -->
    <div class="glass-card mb-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center border-start border-info border-4">
        <div>
            <h1 class="h3 fw-bold mb-1 text-white">
                <i class="fa-solid fa-wand-magic-sparkles bi bi-magic me-2 text-info"></i> Enterprise Risk Forecast
            </h1>
            <p class="text-muted mb-0 fw-semibold">Supply Chain Risk Prediction Dashboard</p>
        </div>
        <div class="mt-3 mt-md-0 text-md-end">
            <div class="small text-muted mt-1">
                <span class="badge bg-info-subtle text-info border border-info me-1">FORECAST PERIOD</span>
                {{ $data['header']['forecast_period'] }}
            </div>
        </div>
    </div>

    <!-- SECTION 2: PREDICTION KPI CARDS (8 ANIMATED CARDS) -->
    <div class="row g-3 mb-4">
        <!-- Card 1: Current Risk -->
        <div class="col-12 col-sm-6 col-md-4 col-xl-3">
            <div class="glass-card h-100 d-flex flex-column justify-content-between position-relative overflow-hidden border-start border-{{ $data['kpi_cards']['current_risk_color'] }} border-3" style="transition: transform 0.2s;">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <span class="text-muted small fw-bold text-uppercase">1. Current Risk</span>
                    <i class="fa-solid fa-gauge-high bi bi-speedometer2 text-{{ $data['kpi_cards']['current_risk_color'] }} fs-5"></i>
                </div>
                <div>
                    <div id="kpiCurrentRisk" class="h2 fw-bold text-white mb-1">{{ $data['kpi_cards']['current_risk'] }}%</div>
                    <span id="kpiCurrentBadge" class="badge bg-{{ $data['kpi_cards']['current_risk_color'] }}">Baseline Global</span>
                </div>
            </div>
        </div>

        <!-- Card 2: Predicted Risk Tomorrow -->
        <div class="col-12 col-sm-6 col-md-4 col-xl-3">
            <div class="glass-card h-100 d-flex flex-column justify-content-between border-start border-{{ $data['kpi_cards']['tomorrow_risk_color'] }} border-3">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <span class="text-muted small fw-bold text-uppercase">2. Tomorrow Forecast</span>
                    <i class="fa-solid fa-calendar-day bi bi-calendar-check text-{{ $data['kpi_cards']['tomorrow_risk_color'] }} fs-5"></i>
                </div>
                <div>
                    <div id="kpiTomorrowRisk" class="h2 fw-bold text-white mb-1">{{ $data['kpi_cards']['tomorrow_risk'] }}%</div>
                    <span id="kpiTomorrowBadge" class="badge bg-{{ $data['kpi_cards']['tomorrow_risk_color'] }}">+24 Hours Projection</span>
                </div>
            </div>
        </div>

        <!-- Card 3: Predicted Risk Next 7 Days -->
        <div class="col-12 col-sm-6 col-md-4 col-xl-3">
            <div class="glass-card h-100 d-flex flex-column justify-content-between border-start border-{{ $data['kpi_cards']['future_7d_risk_color'] }} border-3">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <span class="text-muted small fw-bold text-uppercase">3. Next 7 Days Risk</span>
                    <i class="fa-solid fa-calendar-week bi bi-calendar-week text-{{ $data['kpi_cards']['future_7d_risk_color'] }} fs-5"></i>
                </div>
                <div>
                    <div id="kpi7dRisk" class="h2 fw-bold text-white mb-1">{{ $data['kpi_cards']['future_7d_risk'] }}%</div>
                    <span id="kpi7dBadge" class="badge bg-{{ $data['kpi_cards']['future_7d_risk_color'] }}">Moving Average Target</span>
                </div>
            </div>
        </div>

        <!-- Card 4: Predicted Risk Next 30 Days -->
        <div class="col-12 col-sm-6 col-md-4 col-xl-3">
            <div class="glass-card h-100 d-flex flex-column justify-content-between border-start border-{{ $data['kpi_cards']['future_30d_risk_color'] }} border-3">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <span class="text-muted small fw-bold text-uppercase">4. Next 30 Days Risk</span>
                    <i class="fa-solid fa-calendar-days bi bi-calendar-range text-{{ $data['kpi_cards']['future_30d_risk_color'] }} fs-5"></i>
                </div>
                <div>
                    <div id="kpi30dRisk" class="h2 fw-bold text-white mb-1">{{ $data['kpi_cards']['future_30d_risk'] }}%</div>
                    <span id="kpi30dBadge" class="badge bg-{{ $data['kpi_cards']['future_30d_risk_color'] }}">Long-Term Outlook</span>
                </div>
            </div>
        </div>

        <!-- Card 5: Confidence Score -->
        <div class="col-12 col-sm-6 col-md-4 col-xl-3">
            <div class="glass-card h-100 d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <span class="text-muted small fw-bold text-uppercase">5. Confidence Score</span>
                    <i class="fa-solid fa-shield-halved bi bi-shield-check text-info fs-5"></i>
                </div>
                <div>
                    <div id="kpiConfidence" class="h2 fw-bold text-white mb-1">{{ $data['kpi_cards']['confidence_score'] }}%</div>
                    <span class="text-muted small">Data Completeness &amp; Multi-API</span>
                </div>
            </div>
        </div>

        <!-- Card 6: Shipping Delay Probability -->
        <div class="col-12 col-sm-6 col-md-4 col-xl-3">
            <div class="glass-card h-100 d-flex flex-column justify-content-between border-start border-{{ $data['kpi_cards']['shipping_delay_color'] }} border-3">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <span class="text-muted small fw-bold text-uppercase">6. Delay Probability</span>
                    <i class="fa-solid fa-ship bi bi-water text-{{ $data['kpi_cards']['shipping_delay_color'] }} fs-5"></i>
                </div>
                <div>
                    <div id="kpiDelayProb" class="h2 fw-bold text-white mb-1">{{ $data['kpi_cards']['shipping_delay_prob'] }}</div>
                    <span id="kpiDelayBadge" class="badge bg-{{ $data['kpi_cards']['shipping_delay_color'] }}">Maritime Congestion Index</span>
                </div>
            </div>
        </div>

        <!-- Card 7: Expected Currency Stability -->
        <div class="col-12 col-sm-6 col-md-4 col-xl-3">
            <div class="glass-card h-100 d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <span class="text-muted small fw-bold text-uppercase">7. Currency Stability</span>
                </div>
                <div>
                    <div id="kpiCurrencyStab" class="h2 fw-bold text-white mb-1">{{ $data['kpi_cards']['currency_stability'] }}%</div>
                    <span class="text-muted small">Global Exchange Corridor</span>
                </div>
            </div>
        </div>

        <!-- Card 8: Expected Weather Stability -->
        <div class="col-12 col-sm-6 col-md-4 col-xl-3">
            <div class="glass-card h-100 d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <span class="text-muted small fw-bold text-uppercase">8. Weather Stability</span>
                    <i class="fa-solid fa-cloud-sun bi bi-cloud-sun text-warning fs-5"></i>
                </div>
                <div>
                    <div id="kpiWeatherStab" class="h2 fw-bold text-white mb-1">{{ $data['kpi_cards']['weather_stability'] }}%</div>
                    <span class="text-muted small">Open-Meteo Maritime Tracking</span>
                </div>
            </div>
        </div>
    </div>

    <!-- SECTION 11: SCENARIO SIMULATOR (SLIDERS INTERAKTIF AJAX) -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="glass-card border-top border-info border-3 p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h5 class="fw-bold text-white mb-1">
                            <i class="fa-solid fa-sliders bi bi-sliders text-info me-2"></i> Section 11: Interactive Scenario Simulator
                        </h5>
                        <p class="text-muted small mb-0">Slide the parameters below to test hypotheticals. Charts, timeline, and recommendations update instantly via AJAX.</p>
                    </div>
                    <div>
                        <button id="btnResetSimulation" class="btn btn-outline-secondary btn-sm px-3">
                            <i class="fa-solid fa-rotate-left me-1"></i> Reset Baseline
                        </button>
                    </div>
                </div>
                <div class="row g-4">
                    <div class="col-12 col-md-3">
                        <label class="form-label small fw-bold text-uppercase text-light d-flex justify-content-between">
                            <span>Weather Volatility Delta</span>
                            <span id="labelWeatherDelta" class="text-info fw-bold">0%</span>
                        </label>
                        <input type="range" class="form-range sim-slider" id="sliderWeather" min="-40" max="40" step="1" value="0">
                        <div class="d-flex justify-content-between small text-muted">
                            <span>-40% (Calm)</span>
                            <span>+40% (Storms)</span>
                        </div>
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="form-label small fw-bold text-uppercase text-light d-flex justify-content-between">
                            <span>Inflation Rate Delta</span>
                            <span id="labelInflationDelta" class="text-info fw-bold">0%</span>
                        </label>
                        <input type="range" class="form-range sim-slider" id="sliderInflation" min="-30" max="30" step="1" value="0">
                        <div class="d-flex justify-content-between small text-muted">
                            <span>-30% (Deflation)</span>
                            <span>+30% (Spike)</span>
                        </div>
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="form-label small fw-bold text-uppercase text-light d-flex justify-content-between">
                            <span>Currency Shock Delta</span>
                            <span id="labelCurrencyDelta" class="text-info fw-bold">0%</span>
                        </label>
                        <input type="range" class="form-range sim-slider" id="sliderCurrency" min="-30" max="30" step="1" value="0">
                        <div class="d-flex justify-content-between small text-muted">
                            <span>-30% (Strong)</span>
                            <span>+30% (Deval)</span>
                        </div>
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="form-label small fw-bold text-uppercase text-light d-flex justify-content-between">
                            <span>News Geopolitics Delta</span>
                            <span id="labelNewsDelta" class="text-info fw-bold">0%</span>
                        </label>
                        <input type="range" class="form-range sim-slider" id="sliderNews" min="-40" max="40" step="1" value="0">
                        <div class="d-flex justify-content-between small text-muted">
                            <span>-40% (Peace)</span>
                            <span>+40% (Conflict)</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SECTION 3: FORECAST CHART & SECTION 5: RISK TREND ANALYSIS -->
    <div class="row g-4 mb-4">
        <!-- Section 3: Forecast Line Chart -->
        <div class="col-12 col-xl-8">
            <div class="glass-card h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold text-white mb-0">
                        <i class="fa-solid fa-chart-line bi bi-graph-up text-info me-2"></i> Section 3: Forecast Chart (+30 Days Trajectory)
                    </h5>
                    <span class="badge bg-dark border border-secondary text-muted">Moving Average &amp; Weighted Projection</span>
                </div>
                <div style="position: relative; height: 320px; width: 100%;">
                    <canvas id="predictionLineChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Section 5: Risk Trend Analysis -->
        <div class="col-12 col-xl-4">
            <div class="glass-card h-100 d-flex flex-column justify-content-between border-top border-{{ $data['trend_analysis']['color'] }} border-3">
                <div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold text-white mb-0">
                            <i class="fa-solid fa-compass bi bi-compass text-warning me-2"></i> Section 5: Risk Trend Analysis
                        </h5>
                        <span id="trendBadge" class="badge bg-{{ $data['trend_analysis']['color'] }} px-3 py-2 fs-6">
                            {{ $data['trend_analysis']['status'] }} {{ $data['trend_analysis']['arrow'] }}
                        </span>
                    </div>
                    <div class="text-center my-4 py-2">
                        <div id="trendArrowBig" class="display-1 fw-bold text-{{ $data['trend_analysis']['color'] }} mb-2">
                            {{ $data['trend_analysis']['arrow'] }}
                        </div>
                        <div id="trendStatusText" class="h3 fw-bold text-white mb-1">
                            {{ $data['trend_analysis']['status'] }} Outlook
                        </div>
                        <div id="trendDiffText" class="small fw-semibold text-muted">
                            Projected Shift: {{ $data['trend_analysis']['difference'] >= 0 ? '+' : '' }}{{ $data['trend_analysis']['difference'] }}% over 7 days
                        </div>
                    </div>
                    <p id="trendExplanationText" class="text-light small lh-lg mb-0" style="background: rgba(0,0,0,0.25); padding: 12px 16px; border-radius: 8px; border: 1px solid rgba(255,255,255,0.06);">
                        {{ $data['trend_analysis']['explanation'] }}
                    </p>
                </div>
                <div class="text-end small text-muted mt-2">
                    <i class="fa-solid fa-circle-check text-info me-1"></i> Rule-Based Trend Engine
                </div>
            </div>
        </div>
    </div>

    <!-- SECTION 4: PREDICTION TIMELINE -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="glass-card">
                <h5 class="fw-bold text-white mb-3">
                    <i class="fa-solid fa-timeline bi bi-clock-history text-info me-2"></i> Section 4: Prediction Timeline (Today &rarr; +30 Days)
                </h5>
                <div class="row g-3" id="timelineContainer">
                    @foreach($data['timeline'] as $item)
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                        <div class="p-3 h-100 d-flex flex-column justify-content-between" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08); border-radius: 10px; border-left: 4px solid var(--bs-{{ $item['color'] }});">
                            <div>
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="fw-bold text-info small">{{ $item['label'] }}</span>
                                    <span class="badge bg-{{ $item['color'] }}">{{ $item['score'] }}%</span>
                                </div>
                                <div class="small text-muted mb-2">{{ $item['date'] }} &bull; <strong class="text-white">{{ $item['level'] }}</strong></div>
                                <p class="small text-light mb-0" style="font-size: 11.5px; opacity: 0.85;">{{ $item['reason'] }}</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- SECTION 6: PREDICTION FACTORS & SECTION 7: SHIPPING DELAY PREDICTION -->
    <div class="row g-4 mb-4">
        <!-- Section 6: Prediction Factors Breakdown & Radar Chart -->
        <div class="col-12 col-xl-7">
            <div class="glass-card h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold text-white mb-0">
                        <i class="fa-solid fa-layer-group bi bi-layers text-info me-2"></i> Section 6: Prediction Factors Breakdown
                    </h5>
                    <span class="badge bg-dark border border-secondary text-muted">Weighted Contribution</span>
                </div>
                <div class="row g-3 align-items-center">
                    <div class="col-12 col-md-6">
                        <div class="table-responsive">
                            <table class="table table-dark table-sm align-middle mb-0" style="--bs-table-bg: transparent;">
                                <thead>
                                    <tr class="text-muted small text-uppercase border-bottom border-secondary">
                                        <th>Factor</th>
                                        <th class="text-center">Current</th>
                                        <th class="text-center">Pred.</th>
                                        <th class="text-end">Impact %</th>
                                    </tr>
                                </thead>
                                <tbody id="factorsTableBody">
                                    @foreach($data['factors'] as $fac)
                                    <tr class="border-bottom border-secondary" style="border-color: rgba(255,255,255,0.05) !important;">
                                        <td class="fw-semibold text-white small">
                                            <i class="fa-solid {{ $fac['icon'] }} me-1"></i> {{ $fac['factor'] }}
                                        </td>
                                        <td class="text-center small">{{ $fac['current'] }}%</td>
                                        <td class="text-center small fw-bold text-info">{{ $fac['predicted'] }}%</td>
                                        <td class="text-end fw-bold text-warning">{{ $fac['impact'] }}%</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div style="position: relative; height: 260px; width: 100%;">
                            <canvas id="predictionRadarChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 7: Shipping Delay Prediction Gauge -->
        <div class="col-12 col-xl-5">
            <div class="glass-card h-100 d-flex flex-column border-top border-{{ $data['shipping_delay']['color'] }} border-3">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold text-white mb-0">
                        <i class="fa-solid fa-anchor-circle-exclamation bi bi-exclamation-triangle text-{{ $data['shipping_delay']['color'] }} me-2"></i> Section 7: Shipping Delay Prediction
                    </h5>
                    <span id="delayBadgeUpper" class="badge bg-{{ $data['shipping_delay']['color'] }} px-2 py-1">{{ $data['shipping_delay']['level'] }} Probability</span>
                </div>
                <div class="row g-3 align-items-center flex-grow-1">
                    <div class="col-6 text-center">
                        <div style="position: relative; height: 180px; width: 100%; display: flex; align-items: center; justify-content: center;">
                            <canvas id="delayGaugeChart"></canvas>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 rounded" style="background: rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.08);">
                            <div class="small text-muted text-uppercase fw-bold">Estimated Delay</div>
                            <div id="delayDaysText" class="display-6 fw-bold text-{{ $data['shipping_delay']['color'] }} mb-1">
                                {{ $data['shipping_delay']['days'] }}
                            </div>
                            <div class="small text-light mb-2">Maritime Port &amp; Weather Impact Score: <strong id="delayScoreText" class="text-white">{{ $data['shipping_delay']['score'] }}%</strong></div>
                            <span id="delayLevelBadge" class="badge bg-{{ $data['shipping_delay']['color'] }}-subtle text-{{ $data['shipping_delay']['color'] }} border border-{{ $data['shipping_delay']['color'] }} px-3 py-1">
                                Status: {{ $data['shipping_delay']['level'] }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SECTION 8: BUSINESS RECOMMENDATIONS & SECTION 12: PREDICTION SUMMARY -->
    <div class="row g-4 mb-4">
        <!-- Section 8: Business Recommendation (Automated Rule-Based Cards) -->
        <div class="col-12 col-xl-6">
            <div class="glass-card h-100 border-start border-warning border-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold text-white mb-0">
                        <i class="fa-solid fa-clipboard-check bi bi-check-circle text-warning me-2"></i> Section 8: Business Recommendations
                    </h5>
                    <span class="badge bg-dark border border-secondary text-muted">Automated Rules</span>
                </div>
                <div class="row g-2">
                    <div class="col-6">
                        <div class="p-3 rounded d-flex align-items-center" style="background: rgba(255,193,7,0.12); border: 1px solid rgba(255,193,7,0.3);">
                            <i class="fa-solid fa-clock-rotate-left fs-4 text-warning me-3"></i>
                            <div>
                                <div class="fw-bold small text-warning">Delay Shipment</div>
                                <div class="small text-light">Hold departures in red corridors</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 rounded d-flex align-items-center" style="background: rgba(23,162,184,0.12); border: 1px solid rgba(23,162,184,0.3);">
                            <i class="fa-solid fa-shield-virus fs-4 text-info me-3"></i>
                            <div>
                                <div class="fw-bold small text-info">Increase Insurance</div>
                                <div class="small text-light">Expand cargo coverage by 20%</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 rounded d-flex align-items-center" style="background: rgba(40,167,69,0.12); border: 1px solid rgba(40,167,69,0.3);">
                            <i class="fa-solid fa-money-bill-trend-up fs-4 text-success me-3"></i>
                            <div>
                                <div class="fw-bold small text-success">Monitor Currency</div>
                                <div class="small text-light">Lock hedging for JPY &amp; EUR</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 rounded d-flex align-items-center" style="background: rgba(220,53,69,0.12); border: 1px solid rgba(220,53,69,0.3);">
                            <i class="fa-solid fa-route fs-4 text-danger me-3"></i>
                            <div>
                                <div class="fw-bold small text-danger">Use Alternative Port</div>
                                <div class="small text-light">Reroute high risk cargo paths</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 12: Prediction Summary (Automated Executive Narrative) -->
        <div class="col-12 col-xl-6">
            <div class="glass-card h-100 border-start border-info border-4 d-flex flex-column justify-content-between">
                <div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold text-white mb-0">
                            <i class="fa-solid fa-file-lines bi bi-file-earmark-text text-info me-2"></i> Section 12: Prediction Summary
                        </h5>
                        <span class="badge bg-info text-dark fw-bold">PHP Rule Engine</span>
                    </div>
                    <p id="predictionSummaryText" class="text-white fs-6 mb-0 lh-lg fw-medium" style="background: rgba(0,0,0,0.25); padding: 16px 20px; border-radius: 10px; border: 1px solid rgba(255,255,255,0.08);">
                        {{ $data['summary'] }}
                    </p>
                </div>
                <div class="mt-3 text-end small text-muted">
                    <i class="fa-solid fa-brain text-info me-1"></i> Rule-Based Intelligence &bull; No OpenAI Dependency
                </div>
            </div>
        </div>
    </div>

    <!-- SECTION 9: COUNTRY PREDICTION RANKING & SECTION 10: PREDICTION HEATMAP -->
    <div class="row g-4 mb-4">
        <!-- Section 9: Country Prediction Ranking Table (Top 10) -->
        <div class="col-12 col-xl-7">
            <div class="glass-card h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold text-white mb-0">
                        <i class="fa-solid fa-ranking-star bi bi-trophy text-warning me-2"></i> Section 9: Country Prediction Ranking (Top 10)
                    </h5>
                    <span class="badge bg-dark border border-secondary text-muted">7-Day Forecast Order</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-dark table-hover align-middle mb-0" style="--bs-table-bg: transparent;">
                        <thead>
                            <tr class="text-muted small text-uppercase border-bottom border-secondary">
                                <th>Rank</th>
                                <th>Country</th>
                                <th class="text-center">Current</th>
                                <th class="text-center">Future (7D)</th>
                                <th class="text-center">Difference</th>
                                <th class="text-center">Trend</th>
                                <th class="text-end">Recommendation</th>
                            </tr>
                        </thead>
                        <tbody id="rankingTableBody">
                            @foreach($data['ranking_table'] as $row)
                            <tr class="border-bottom border-secondary" style="border-color: rgba(255,255,255,0.05) !important;">
                                <td class="fw-bold text-info">#{{ $row['rank'] }}</td>
                                <td class="fw-bold text-white">{{ $row['name'] }} <span class="text-muted small">({{ $row['iso'] }})</span></td>
                                <td class="text-center">{{ $row['current_risk'] }}%</td>
                                <td class="text-center fw-bold text-{{ $row['future_risk'] >= 65 ? 'danger' : ($row['future_risk'] >= 35 ? 'warning' : 'success') }}">{{ $row['future_risk'] }}%</td>
                                <td class="text-center small {{ $row['difference'] > 0 ? 'text-danger' : ($row['difference'] < 0 ? 'text-success' : 'text-muted') }}">
                                    {{ $row['difference'] > 0 ? '+' : '' }}{{ $row['difference'] }}%
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-{{ $row['trend_color'] }}">{{ $row['trend_arrow'] }} {{ $row['trend'] }}</span>
                                </td>
                                <td class="text-end small text-light">{{ $row['recommendation'] }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Section 10: Prediction Heatmap (Leaflet Map) -->
        <div class="col-12 col-xl-5">
            <div class="glass-card h-100 d-flex flex-column">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold text-white mb-0">
                        <i class="fa-solid fa-map-location-dot bi bi-map text-info me-2"></i> Section 10: Prediction Heatmap
                    </h5>
                    <span class="badge bg-dark border border-secondary text-muted">Corridor Severity</span>
                </div>
                <div id="predictionHeatmap" class="flex-grow-1 rounded border border-secondary" style="min-height: 380px; width: 100%; z-index: 1;"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    window.INITIAL_PREDICTION_DATA = @json($data);
</script>
<script src="{{ asset('js/prediction.js') }}"></script>
@endpush
