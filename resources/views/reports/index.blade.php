@extends('layouts.app')

@section('title', 'Enterprise Reporting & Export Suite')

@section('content')
<div class="container-fluid py-2" style="color: #f8fafc;">
    <!-- SECTION 1: ENTERPRISE REPORTING DASHBOARD HEADER & KPI CARDS -->
    <div class="glass-card mb-4 p-4 border-start border-primary border-4 shadow-lg d-flex flex-column flex-xl-row justify-content-between align-items-xl-center gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <i class="fa-solid fa-file-export bi bi-file-earmark-bar-graph text-primary fs-3"></i>
                <h1 class="h3 fw-bold mb-0 text-white">Enterprise Reporting &amp; Export Suite</h1>
                <span class="badge bg-primary text-white fw-bold px-2 py-1 small">POWER BI / TABLEAU TIER</span>
            </div>
            <p class="text-muted mb-0 fw-semibold">Global Supply Chain Intelligence Certified Export &bull; ISO 27001 Multi-Format Publisher</p>
        </div>

        <div class="d-flex flex-wrap align-items-center gap-3">
            <div class="badge bg-success-subtle text-success border border-success px-3 py-2">
                <i class="fa-solid fa-circle-check me-1"></i> <span>Export Engine Ready</span>
            </div>
            <button type="button" class="btn btn-primary btn-sm fw-bold px-3 shadow-sm" onclick="window.refreshReportsTelemetry()">
                <i class="fa-solid fa-arrows-rotate me-1"></i> Refresh Telemetry
            </button>
        </div>
    </div>

    <!-- KPI Metric Cards (8 Cards) -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3 col-xl-3">
            <div class="glass-card p-3 border-top border-primary border-3 h-100">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-muted small fw-bold text-uppercase">Generated Reports</span>
                    <i class="fa-solid fa-file-lines text-primary fs-5"></i>
                </div>
                <h3 id="cardGeneratedCount" class="fw-bold text-white mb-0">{{ $summary['kpi_cards']['generated_reports'] ?? 128 }}</h3>
                <div class="small text-success mt-1"><i class="fa-solid fa-arrow-up me-1"></i>+14% vs last month</div>
            </div>
        </div>
        <div class="col-6 col-md-3 col-xl-3">
            <div class="glass-card p-3 border-top border-warning border-3 h-100">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-muted small fw-bold text-uppercase">Scheduled Reports</span>
                    <i class="fa-solid fa-clock text-warning fs-5"></i>
                </div>
                <h3 id="cardScheduledCount" class="fw-bold text-white mb-0">{{ $summary['kpi_cards']['scheduled_reports'] ?? 12 }}</h3>
                <div class="small text-info mt-1"><i class="fa-solid fa-sync me-1"></i>Automated cron jobs</div>
            </div>
        </div>
        <div class="col-6 col-md-3 col-xl-3">
            <div class="glass-card p-3 border-top border-info border-3 h-100">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-muted small fw-bold text-uppercase">Downloads Today</span>
                    <i class="fa-solid fa-download text-info fs-5"></i>
                </div>
                <h3 id="cardDownloadsToday" class="fw-bold text-white mb-0">{{ $summary['kpi_cards']['downloads_today'] ?? 18 }}</h3>
                <div class="small text-muted mt-1">Across all file formats</div>
            </div>
        </div>
        <div class="col-6 col-md-3 col-xl-3">
            <div class="glass-card p-3 border-top border-success border-3 h-100">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-muted small fw-bold text-uppercase">Downloads This Month</span>
                    <i class="fa-solid fa-cloud-arrow-down text-success fs-5"></i>
                </div>
                <h3 id="cardDownloadsMonth" class="fw-bold text-white mb-0">{{ $summary['kpi_cards']['downloads_month'] ?? 84 }}</h3>
                <div class="small text-success mt-1"><i class="fa-solid fa-check me-1"></i>99.9% success rate</div>
            </div>
        </div>
        <div class="col-6 col-md-3 col-xl-3">
            <div class="glass-card p-3 border-top border-danger border-3 h-100">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-muted small fw-bold text-uppercase">PDF Reports</span>
                    <i class="fa-solid fa-file-pdf text-danger fs-5"></i>
                </div>
                <h3 id="cardPdfCount" class="fw-bold text-white mb-0">{{ $summary['kpi_cards']['pdf_reports'] ?? 64 }}</h3>
                <div class="small text-muted mt-1">Corporate print layout</div>
            </div>
        </div>
        <div class="col-6 col-md-3 col-xl-3">
            <div class="glass-card p-3 border-top border-success border-3 h-100">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-muted small fw-bold text-uppercase">Excel Reports</span>
                    <i class="fa-solid fa-file-excel text-success fs-5"></i>
                </div>
                <h3 id="cardExcelCount" class="fw-bold text-white mb-0">{{ $summary['kpi_cards']['excel_reports'] ?? 38 }}</h3>
                <div class="small text-muted mt-1">Multi-sheet packages</div>
            </div>
        </div>
        <div class="col-6 col-md-3 col-xl-3">
            <div class="glass-card p-3 border-top border-info border-3 h-100">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-muted small fw-bold text-uppercase">CSV Reports</span>
                    <i class="fa-solid fa-file-csv text-info fs-5"></i>
                </div>
                <h3 id="cardCsvCount" class="fw-bold text-white mb-0">{{ $summary['kpi_cards']['csv_reports'] ?? 16 }}</h3>
                <div class="small text-muted mt-1">RFC 4180 BOM encoded</div>
            </div>
        </div>
        <div class="col-6 col-md-3 col-xl-3">
            <div class="glass-card p-3 border-top border-light border-3 h-100">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-muted small fw-bold text-uppercase">Print Jobs</span>
                    <i class="fa-solid fa-print text-light fs-5"></i>
                </div>
                <h3 id="cardPrintCount" class="fw-bold text-white mb-0">{{ $summary['kpi_cards']['print_jobs'] ?? 10 }}</h3>
                <div class="small text-muted mt-1">Direct browser print modal</div>
            </div>
        </div>
    </div>

    <!-- Navigation Tabs for 10 Sections -->
    <ul class="nav nav-pills mb-4 gap-2 flex-wrap" id="reportsNavTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active fw-bold px-3 py-2" id="tab-overview-btn" data-bs-toggle="pill" data-bs-target="#tab-overview" type="button" role="tab">
                <i class="fa-solid fa-chart-line me-1"></i> Overview &amp; Executive Summary
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link fw-bold px-3 py-2" id="tab-executive-btn" data-bs-toggle="pill" data-bs-target="#tab-executive" type="button" role="tab">
                <i class="fa-solid fa-briefcase me-1"></i> Executive Reports
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link fw-bold px-3 py-2" id="tab-analytics-btn" data-bs-toggle="pill" data-bs-target="#tab-analytics" type="button" role="tab">
                <i class="fa-solid fa-magnifying-glass-chart me-1"></i> Analytics Reports
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link fw-bold px-3 py-2" id="tab-builder-btn" data-bs-toggle="pill" data-bs-target="#tab-builder" type="button" role="tab">
                <i class="fa-solid fa-wand-magic-sparkles me-1"></i> Interactive Builder
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link fw-bold px-3 py-2" id="tab-gallery-btn" data-bs-toggle="pill" data-bs-target="#tab-gallery" type="button" role="tab">
                <i class="fa-solid fa-shapes me-1"></i> Charts Gallery (10 Types)
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link fw-bold px-3 py-2" id="tab-preview-btn" data-bs-toggle="pill" data-bs-target="#tab-preview" type="button" role="tab">
                <i class="fa-solid fa-eye me-1"></i> Report Preview &amp; Export
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link fw-bold px-3 py-2" id="tab-scheduled-btn" data-bs-toggle="pill" data-bs-target="#tab-scheduled" type="button" role="tab">
                <i class="fa-solid fa-calendar-check me-1"></i> Scheduled Reports
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link fw-bold px-3 py-2" id="tab-history-btn" data-bs-toggle="pill" data-bs-target="#tab-history" type="button" role="tab">
                <i class="fa-solid fa-clock-rotate-left me-1"></i> History &amp; Audit Logs
            </button>
        </li>
    </ul>

    <!-- Tab Content Area -->
    <div class="tab-content" id="reportsTabContent">
        
        <!-- SUB-TAB 1: OVERVIEW & EXECUTIVE SUMMARY (SECTION 10 included) -->
        <div class="tab-pane fade show active" id="tab-overview" role="tabpanel">
            <div class="row g-4">
                <div class="col-12 col-xl-8">
                    <!-- SECTION 10: Executive Summary Narrative -->
                    <div class="glass-card p-4 border-top border-warning border-3 mb-4 shadow">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="h5 fw-bold text-white mb-0"><i class="fa-solid fa-award text-warning me-2"></i>SECTION 10: Rule-based PHP Executive Narrative</h4>
                            <span id="narrativeRiskBadge" class="badge bg-danger text-white px-3 py-2">MODERATE TO ELEVATED</span>
                        </div>
                        <p class="text-muted small mb-3">AI &amp; Rule-based synthesis generated from real-time macro indicators, weather congestion, and port latency data.</p>
                        
                        <div class="p-3 rounded bg-dark border border-secondary mb-3">
                            <h5 class="small fw-bold text-warning text-uppercase mb-2"><i class="fa-solid fa-globe me-1"></i> Risk Overview</h5>
                            <p id="narrativeRiskOverview" class="small mb-0 text-light">Loading executive narrative evaluation...</p>
                        </div>

                        <div class="p-3 rounded bg-dark border border-secondary mb-3">
                            <h5 class="small fw-bold text-info text-uppercase mb-2"><i class="fa-solid fa-flag me-1"></i> Country Overview</h5>
                            <p id="narrativeCountryOverview" class="small mb-0 text-light">Loading territory assessment...</p>
                        </div>

                        <div class="p-3 rounded bg-dark border border-secondary">
                            <h5 class="small fw-bold text-success text-uppercase mb-2"><i class="fa-solid fa-list-check me-1"></i> Strategic Business Recommendations</h5>
                            <ul id="narrativeRecommendations" class="small mb-0 text-light ps-3">
                                <li>Loading actionable supply chain directives...</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-xl-4">
                    <div class="glass-card p-4 border-top border-info border-3 mb-4 shadow h-100">
                        <h4 class="h5 fw-bold text-white mb-3"><i class="fa-solid fa-chart-pie text-info me-2"></i>Export Format Distribution</h4>
                        <div style="position: relative; height: 260px;">
                            <canvas id="exportDistributionChart"></canvas>
                        </div>
                        <div class="mt-3 text-center small text-muted">Real-time export format breakdown</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- SUB-TAB 2: EXECUTIVE REPORTS (SECTION 2) -->
        <div class="tab-pane fade" id="tab-executive" role="tabpanel">
            <div class="glass-card p-4 border-top border-primary border-3 mb-4 shadow">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
                    <div>
                        <h3 class="h5 fw-bold text-white mb-1"><i class="fa-solid fa-briefcase text-primary me-2"></i>SECTION 2: Executive Reports Generator</h3>
                        <p class="text-muted small mb-0">Select high-level executive report packages and trigger immediate corporate export or detailed preview.</p>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <button class="btn btn-outline-primary btn-sm fw-bold" onclick="window.loadQuickReport('Executive Summary', 'Executive Report')">Executive Summary</button>
                        <button class="btn btn-outline-primary btn-sm fw-bold" onclick="window.loadQuickReport('Weekly Report', 'Executive Report')">Weekly Report</button>
                        <button class="btn btn-outline-primary btn-sm fw-bold" onclick="window.loadQuickReport('Monthly Report', 'Executive Report')">Monthly Report</button>
                        <button class="btn btn-outline-primary btn-sm fw-bold" onclick="window.loadQuickReport('Quarterly Report', 'Executive Report')">Quarterly Report</button>
                        <button class="btn btn-outline-primary btn-sm fw-bold" onclick="window.loadQuickReport('Annual Report', 'Executive Report')">Annual Report</button>
                    </div>
                </div>

                <!-- Quick Preview Table -->
                <div class="table-responsive">
                    <table class="table table-dark table-hover border border-secondary align-middle">
                        <thead class="table-active">
                            <tr>
                                <th>Executive Indicator</th>
                                <th>Status / Evaluation</th>
                                <th>Target Benchmark</th>
                                <th>Variance</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody id="executiveReportTableBody">
                            <tr><td colspan="5" class="text-center text-muted py-4">Select an executive report option above to load tabular metrics.</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- SUB-TAB 3: ANALYTICS REPORTS (SECTION 3) -->
        <div class="tab-pane fade" id="tab-analytics" role="tabpanel">
            <div class="glass-card p-4 border-top border-info border-3 mb-4 shadow">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
                    <div>
                        <h3 class="h5 fw-bold text-white mb-1"><i class="fa-solid fa-magnifying-glass-chart text-info me-2"></i>SECTION 3: Analytics Reports Suite</h3>
                        <p class="text-muted small mb-0">Deep-dive analytical dimensions across risk, weather, foreign exchange, ports, and news sentiment.</p>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <button class="btn btn-outline-info btn-sm fw-bold" onclick="window.loadQuickReport('Risk Trend Analysis', 'Analytics Report')">Risk Trend</button>
                        <button class="btn btn-outline-info btn-sm fw-bold" onclick="window.loadQuickReport('AI Prediction Forecast', 'Prediction Report')">Prediction</button>
                        <button class="btn btn-outline-info btn-sm fw-bold" onclick="window.loadQuickReport('Global Weather & Ports', 'Weather Report')">Weather &amp; Ports</button>
                        <button class="btn btn-outline-info btn-sm fw-bold" onclick="window.loadQuickReport('Currency & Inflation Spread', 'Analytics Report')">Currency / Inflation</button>
                        <button class="btn btn-outline-info btn-sm fw-bold" onclick="window.loadQuickReport('News Sentiment Index', 'Analytics Report')">News Sentiment</button>
                        <button class="btn btn-outline-info btn-sm fw-bold" onclick="window.loadQuickReport('Full BI Synthesis', 'Analytics Report')">Business Intelligence</button>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-dark table-hover border border-secondary align-middle">
                        <thead class="table-active">
                            <tr>
                                <th>Analytics Dimension</th>
                                <th>Sample Size / Scope</th>
                                <th>Computed Outcome</th>
                                <th>BI Insight</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody id="analyticsReportTableBody">
                            <tr><td colspan="5" class="text-center text-muted py-4">Select an analytics dimension above to load detailed evaluation rows.</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- SUB-TAB 4: INTERACTIVE REPORT BUILDER (SECTION 4) -->
        <div class="tab-pane fade" id="tab-builder" role="tabpanel">
            <div class="row g-4">
                <div class="col-12 col-xl-4">
                    <div class="glass-card p-4 border-top border-warning border-3 shadow">
                        <h3 class="h5 fw-bold text-white mb-3"><i class="fa-solid fa-sliders text-warning me-2"></i>SECTION 4: Report Builder Form</h3>
                        <form id="interactiveBuilderForm" onsubmit="window.submitCustomBuilder(event)">
                            <div class="mb-3">
                                <label class="form-label small text-muted fw-bold">Select Key Performance Indicator (KPI)</label>
                                <select id="builderKpi" class="form-select bg-dark text-white border-secondary">
                                    <option value="Composite Risk Score">Composite Risk Score</option>
                                    <option value="Port Congestion Latency">Port Congestion Latency</option>
                                    <option value="Foreign Exchange Volatility">Foreign Exchange Volatility</option>
                                    <option value="Supplier On-Time Delivery">Supplier On-Time Delivery</option>
                                    <option value="News AI Lexicon Sentiment">News AI Lexicon Sentiment</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small text-muted fw-bold">Select Country / Corridor</label>
                                <select id="builderCountry" class="form-select bg-dark text-white border-secondary">
                                    <option value="Global Aggregate">Global Aggregate (All Regions)</option>
                                    <option value="Singapore">Singapore (SEA Hub)</option>
                                    <option value="Rotterdam">Rotterdam (Europe Hub)</option>
                                    <option value="Shanghai">Shanghai (East Asia Hub)</option>
                                    <option value="Los Angeles">Los Angeles (North America Hub)</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small text-muted fw-bold">Select Date Range Horizon</label>
                                <select id="builderDateRange" class="form-select bg-dark text-white border-secondary">
                                    <option value="Last 7 Days">Last 7 Days</option>
                                    <option value="Last 30 Days">Last 30 Days</option>
                                    <option value="Last 90 Days (Quarter)">Last 90 Days (Quarter)</option>
                                    <option value="Last 365 Days (Annual)">Last 365 Days (Annual)</option>
                                </select>
                            </div>

                            <div class="mb-4">
                                <label class="form-label small text-muted fw-bold">Select Chart Visualization Type</label>
                                <select id="builderChartType" class="form-select bg-dark text-white border-secondary">
                                    <option value="Bar">Bar Chart</option>
                                    <option value="Line">Line Chart</option>
                                    <option value="Pie">Pie Chart</option>
                                    <option value="Radar">Radar Chart</option>
                                    <option value="Area">Area Chart</option>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-warning text-dark fw-bold w-100 shadow-sm">
                                <i class="fa-solid fa-wand-magic-sparkles me-2"></i> Generate Custom Report
                            </button>
                        </form>
                    </div>
                </div>

                <div class="col-12 col-xl-8">
                    <div class="glass-card p-4 border-top border-light border-3 shadow h-100 d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 id="builderPreviewTitle" class="h5 fw-bold text-white mb-0">Custom Report Preview Area</h4>
                            <div class="btn-group btn-group-sm">
                                <button class="btn btn-outline-info" onclick="window.exportCurrentCustom('PDF')"><i class="fa-solid fa-file-pdf me-1"></i> PDF</button>
                                <button class="btn btn-outline-success" onclick="window.exportCurrentCustom('EXCEL')"><i class="fa-solid fa-file-excel me-1"></i> Excel</button>
                                <button class="btn btn-outline-light" onclick="window.exportCurrentCustom('PRINT')"><i class="fa-solid fa-print me-1"></i> Print</button>
                            </div>
                        </div>

                        <div id="builderSummaryBox" class="p-3 bg-dark border border-secondary rounded mb-3 small text-light d-none"></div>

                        <div style="position: relative; height: 280px; margin-bottom: 20px;">
                            <canvas id="builderPreviewChart"></canvas>
                        </div>

                        <div class="table-responsive flex-grow-1">
                            <table class="table table-dark table-sm table-hover border border-secondary align-middle mb-0">
                                <thead class="table-active">
                                    <tr>
                                        <th>Parameter / Indicator</th>
                                        <th>Evaluated Result</th>
                                    </tr>
                                </thead>
                                <tbody id="builderTableBody">
                                    <tr><td colspan="2" class="text-center text-muted py-3">Configure form on the left and click 'Generate Custom Report' to preview results.</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- SUB-TAB 5: CHARTS GALLERY (SECTION 5 - 10 TYPES) -->
        <div class="tab-pane fade" id="tab-gallery" role="tabpanel">
            <div class="glass-card p-4 border-top border-primary border-3 mb-4 shadow">
                <h3 class="h5 fw-bold text-white mb-1"><i class="fa-solid fa-shapes text-primary me-2"></i>SECTION 5: Charts Gallery (10 Interactive Types)</h3>
                <p class="text-muted small mb-4">Comprehensive visualization showcase representing all 10 Chart.js enterprise rendering engines.</p>

                <div class="row g-4">
                    <!-- 1. Line Chart -->
                    <div class="col-12 col-md-6 col-xl-4">
                        <div class="p-3 bg-dark border border-secondary rounded shadow-sm">
                            <h5 class="small fw-bold text-info mb-2">1. Line Chart (Risk Trend)</h5>
                            <div style="position: relative; height: 200px;"><canvas id="galleryLineChart"></canvas></div>
                        </div>
                    </div>
                    <!-- 2. Bar Chart -->
                    <div class="col-12 col-md-6 col-xl-4">
                        <div class="p-3 bg-dark border border-secondary rounded shadow-sm">
                            <h5 class="small fw-bold text-warning mb-2">2. Bar Chart (Port Congestion)</h5>
                            <div style="position: relative; height: 200px;"><canvas id="galleryBarChart"></canvas></div>
                        </div>
                    </div>
                    <!-- 3. Pie Chart -->
                    <div class="col-12 col-md-6 col-xl-4">
                        <div class="p-3 bg-dark border border-secondary rounded shadow-sm">
                            <h5 class="small fw-bold text-success mb-2">3. Pie Chart (Risk Factors)</h5>
                            <div style="position: relative; height: 200px;"><canvas id="galleryPieChart"></canvas></div>
                        </div>
                    </div>
                    <!-- 4. Doughnut Chart -->
                    <div class="col-12 col-md-6 col-xl-4">
                        <div class="p-3 bg-dark border border-secondary rounded shadow-sm">
                            <h5 class="small fw-bold text-danger mb-2">4. Doughnut Chart (Corridor Safety)</h5>
                            <div style="position: relative; height: 200px;"><canvas id="galleryDoughnutChart"></canvas></div>
                        </div>
                    </div>
                    <!-- 5. Radar Chart -->
                    <div class="col-12 col-md-6 col-xl-4">
                        <div class="p-3 bg-dark border border-secondary rounded shadow-sm">
                            <h5 class="small fw-bold text-primary mb-2">5. Radar Chart (Maturity Index)</h5>
                            <div style="position: relative; height: 200px;"><canvas id="galleryRadarChart"></canvas></div>
                        </div>
                    </div>
                    <!-- 6. Scatter Chart -->
                    <div class="col-12 col-md-6 col-xl-4">
                        <div class="p-3 bg-dark border border-secondary rounded shadow-sm">
                            <h5 class="small fw-bold text-light mb-2">6. Scatter Chart (Delay Correlation)</h5>
                            <div style="position: relative; height: 200px;"><canvas id="galleryScatterChart"></canvas></div>
                        </div>
                    </div>
                    <!-- 7. Area Chart -->
                    <div class="col-12 col-md-6 col-xl-4">
                        <div class="p-3 bg-dark border border-secondary rounded shadow-sm">
                            <h5 class="small fw-bold text-info mb-2">7. Area Chart (Throughput Volume)</h5>
                            <div style="position: relative; height: 200px;"><canvas id="galleryAreaChart"></canvas></div>
                        </div>
                    </div>
                    <!-- 8. Gauge Chart -->
                    <div class="col-12 col-md-6 col-xl-4">
                        <div class="p-3 bg-dark border border-secondary rounded shadow-sm">
                            <h5 class="small fw-bold text-warning mb-2">8. Gauge Chart (System Load)</h5>
                            <div style="position: relative; height: 200px;"><canvas id="galleryGaugeChart"></canvas></div>
                        </div>
                    </div>
                    <!-- 9. Heatmap Chart -->
                    <div class="col-12 col-md-6 col-xl-4">
                        <div class="p-3 bg-dark border border-secondary rounded shadow-sm">
                            <h5 class="small fw-bold text-danger mb-2">9. Heatmap Chart (Regional Intensity)</h5>
                            <div style="position: relative; height: 200px;"><canvas id="galleryHeatmapChart"></canvas></div>
                        </div>
                    </div>
                    <!-- 10. Treemap Chart -->
                    <div class="col-12 col-md-6 col-xl-4">
                        <div class="p-3 bg-dark border border-secondary rounded shadow-sm">
                            <h5 class="small fw-bold text-success mb-2">10. Treemap Chart (Sector Exposure)</h5>
                            <div style="position: relative; height: 200px;"><canvas id="galleryTreemapChart"></canvas></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- SUB-TAB 6: REPORT PREVIEW & EXPORT CENTER (SECTION 6 & 7) -->
        <div class="tab-pane fade" id="tab-preview" role="tabpanel">
            <div class="glass-card p-4 border-top border-success border-3 mb-4 shadow">
                <!-- SECTION 7: Export Center Toolbar -->
                <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 pb-3 mb-4 border-bottom border-secondary">
                    <div>
                        <h3 class="h5 fw-bold text-white mb-1"><i class="fa-solid fa-eye text-success me-2"></i>SECTION 6 &amp; 7: Report Preview &amp; Export Center</h3>
                        <p class="text-muted small mb-0">Inspect document layout with responsive zoom controls, or trigger instant corporate downloads.</p>
                    </div>

                    <div class="d-flex flex-wrap align-items-center gap-2">
                        <div class="btn-group btn-group-sm me-2" role="group" aria-label="Zoom controls">
                            <button type="button" class="btn btn-outline-secondary text-white" onclick="window.changeZoom(-0.1)" title="Zoom Out"><i class="fa-solid fa-magnifying-glass-minus"></i></button>
                            <button type="button" id="previewZoomLabel" class="btn btn-outline-secondary text-white fw-bold disabled">100%</button>
                            <button type="button" class="btn btn-outline-secondary text-white" onclick="window.changeZoom(0.1)" title="Zoom In"><i class="fa-solid fa-magnifying-glass-plus"></i></button>
                            <button type="button" class="btn btn-outline-secondary text-white" onclick="window.changeZoom(0, true)" title="Reset Zoom"><i class="fa-solid fa-rotate-left"></i></button>
                            <button type="button" class="btn btn-outline-secondary text-white" onclick="window.toggleFullscreenPreview()" title="Toggle Fullscreen"><i class="fa-solid fa-expand"></i></button>
                        </div>

                        <!-- Export Buttons -->
                        <button type="button" class="btn btn-danger btn-sm fw-bold px-3 shadow-sm" onclick="window.triggerExport('PDF')"><i class="fa-solid fa-file-pdf me-1"></i> PDF Export</button>
                        <button type="button" class="btn btn-success btn-sm fw-bold px-3 shadow-sm" onclick="window.triggerExport('EXCEL')"><i class="fa-solid fa-file-excel me-1"></i> Excel Export</button>
                        <button type="button" class="btn btn-info btn-sm fw-bold text-dark px-3 shadow-sm" onclick="window.triggerExport('CSV')"><i class="fa-solid fa-file-csv me-1"></i> CSV Export</button>
                        <button type="button" class="btn btn-secondary btn-sm fw-bold text-white px-3 shadow-sm" onclick="window.triggerExport('PNG')"><i class="fa-solid fa-file-image me-1"></i> PNG Export</button>
                        <button type="button" class="btn btn-warning btn-sm fw-bold text-dark px-3 shadow-sm" onclick="window.triggerExport('PRINT')"><i class="fa-solid fa-print me-1"></i> Print Job</button>
                    </div>
                </div>

                <!-- Preview Canvas/Document Container -->
                <div id="reportPreviewContainer" class="p-4 bg-white text-dark rounded shadow-lg overflow-auto" style="min-height: 550px; transition: transform 0.2s ease; transform-origin: top center;">
                    <!-- Default preview loaded by JS -->
                    <div id="previewDocumentContent">
                        <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
                            <div>
                                <h2 class="h4 fw-bold mb-0 text-dark">RiskIntel Hub Executive Profile</h2>
                                <div class="text-muted small">Global Supply Chain Risk Intelligence Platform</div>
                            </div>
                            <div class="text-end small text-muted">
                                <span class="badge bg-danger text-white mb-1">CONFIDENTIAL</span><br>
                                Date: {{ now()->format('Y-m-d') }} &bull; Author: Enterprise Admin
                            </div>
                        </div>

                        <h4 class="h6 fw-bold text-dark mb-3">Report Scope: Global Supply Chain Assessment</h4>
                        <table class="table table-bordered table-sm text-dark font-monospace mb-4" style="font-size: 13px;">
                            <thead class="table-dark">
                                <tr><th>Key Metric / Parameter</th><th>Current Reading</th><th>Status Benchmark</th></tr>
                            </thead>
                            <tbody>
                                <tr><td>Composite Risk Index</td><td>64.2 / 100</td><td>Moderate Risk</td></tr>
                                <tr><td>Active High-Risk Corridors</td><td>4 Corridors</td><td>Alert Active</td></tr>
                                <tr><td>On-Time Delivery Rate</td><td>94.8%</td><td>Within Tolerance</td></tr>
                                <tr><td>System Availability</td><td>99.98%</td><td>ISO 27001 Certified</td></tr>
                            </tbody>
                        </table>

                        <div class="p-3 bg-light border border-secondary-subtle rounded small text-dark mb-3">
                            <strong>Executive Narrative:</strong> Supply chain operations continue to demonstrate high resilience despite localized weather delays across Southeast Asian shipping corridors.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- SUB-TAB 7: SCHEDULED REPORTS (SECTION 8) -->
        <div class="tab-pane fade" id="tab-scheduled" role="tabpanel">
            <div class="row g-4">
                <div class="col-12 col-xl-4">
                    <div class="glass-card p-4 border-top border-warning border-3 shadow">
                        <h3 class="h5 fw-bold text-white mb-3"><i class="fa-solid fa-calendar-plus text-warning me-2"></i>Schedule New Report</h3>
                        <form id="scheduleReportForm" onsubmit="window.submitScheduleReport(event)">
                            <div class="mb-3">
                                <label class="form-label small text-muted fw-bold">Report Type</label>
                                <select id="scheduleReportType" class="form-select bg-dark text-white border-secondary">
                                    <option value="Executive Report">Executive Report</option>
                                    <option value="Country Report">Country Report</option>
                                    <option value="Weather Report">Weather Report</option>
                                    <option value="Prediction Report">Prediction Report</option>
                                    <option value="System Health Report">System Health Report</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small text-muted fw-bold">Delivery Frequency</label>
                                <select id="scheduleFrequency" class="form-select bg-dark text-white border-secondary">
                                    <option value="Daily">Daily (Every morning at 08:00 UTC)</option>
                                    <option value="Weekly">Weekly (Every Monday morning)</option>
                                    <option value="Monthly">Monthly (1st of every month)</option>
                                    <option value="Quarterly">Quarterly (Start of Q1/Q2/Q3/Q4)</option>
                                    <option value="Annual">Annual (Year-end summary)</option>
                                </select>
                            </div>

                            <div class="mb-4">
                                <label class="form-label small text-muted fw-bold">Recipient Emails (comma separated)</label>
                                <input type="text" id="scheduleRecipients" class="form-control bg-dark text-white border-secondary" placeholder="executives@riskintel.com, devops@riskintel.com" required>
                            </div>

                            <button type="submit" class="btn btn-warning text-dark fw-bold w-100 shadow-sm">
                                <i class="fa-solid fa-calendar-check me-2"></i> Save Scheduled Job
                            </button>
                        </form>
                    </div>
                </div>

                <div class="col-12 col-xl-8">
                    <div class="glass-card p-4 border-top border-info border-3 shadow h-100">
                        <h3 class="h5 fw-bold text-white mb-3"><i class="fa-solid fa-list-check text-info me-2"></i>SECTION 8: Active Scheduled Cron Jobs</h3>
                        <div class="table-responsive">
                            <table class="table table-dark table-hover border border-secondary align-middle">
                                <thead class="table-active">
                                    <tr>
                                        <th>ID</th>
                                        <th>Report Type</th>
                                        <th>Frequency</th>
                                        <th>Recipients</th>
                                        <th>Next Run At</th>
                                        <th>Status</th>
                                        <th class="text-end">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="scheduledReportsTableBody">
                                    <tr><td colspan="7" class="text-center text-muted py-3">Loading active scheduled report jobs...</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- SUB-TAB 8: HISTORY & AUDIT LOGS (SECTION 9) -->
        <div class="tab-pane fade" id="tab-history" role="tabpanel">
            <div class="row g-4">
                <div class="col-12 col-xl-6">
                    <div class="glass-card p-4 border-top border-primary border-3 shadow h-100">
                        <h3 class="h5 fw-bold text-white mb-3"><i class="fa-solid fa-clock-rotate-left text-primary me-2"></i>SECTION 9: Report Generation History</h3>
                        <div class="table-responsive">
                            <table class="table table-dark table-sm table-hover border border-secondary align-middle">
                                <thead class="table-active">
                                    <tr>
                                        <th>Title</th>
                                        <th>Type</th>
                                        <th>Format</th>
                                        <th>Size (KB)</th>
                                        <th>Created</th>
                                    </tr>
                                </thead>
                                <tbody id="historyTableBody">
                                    <tr><td colspan="5" class="text-center text-muted py-3">Loading historical report generations...</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-xl-6">
                    <div class="glass-card p-4 border-top border-danger border-3 shadow h-100">
                        <h3 class="h5 fw-bold text-white mb-3"><i class="fa-solid fa-shield-halved text-danger me-2"></i>Export Security Audit Logs</h3>
                        <div class="table-responsive">
                            <table class="table table-dark table-sm table-hover border border-secondary align-middle">
                                <thead class="table-active">
                                    <tr>
                                        <th>Report Type</th>
                                        <th>Format</th>
                                        <th>IP Address</th>
                                        <th>Time (ms)</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody id="exportLogsTableBody">
                                    <tr><td colspan="5" class="text-center text-muted py-3">Loading security export audit events...</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="{{ asset('js/reports.js') }}"></script>
@endpush
