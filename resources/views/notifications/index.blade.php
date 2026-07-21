@extends('layouts.app')

@section('title', 'Smart Notification & Alert Center')

@section('content')
<div class="container-fluid py-2" style="color: #f8fafc;">
    <!-- SECTION 1: NOTIFICATION HEADER & LIVE STATUS BAR -->
    <div class="glass-card mb-4 p-4 border-start border-warning border-4 shadow-lg d-flex flex-column flex-xl-row justify-content-between align-items-xl-center gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <i class="fa-solid fa-bell bi bi-bell-fill text-warning fs-3"></i>
                <h1 class="h3 fw-bold mb-0 text-white">Enterprise Smart Notification Center</h1>
                <span class="badge bg-warning text-dark fw-bold px-2 py-1 small">GRAFANA ALERT ENGINE v3.5</span>
                <span id="unreadBadgeHeader" class="badge bg-danger rounded-pill px-3 py-1 fw-bold">{{ $data['header']['unread_count'] }} UNREAD</span>
            </div>
            <p class="text-muted mb-0 fw-semibold">Real-Time Global Supply Chain Anomaly Detection &bull; 50+ Rule-Based Expert Monitoring Systems</p>
        </div>

        <div class="d-flex flex-column flex-md-row align-items-md-center gap-3">
            <!-- System Time & Sync Status -->
            <div class="d-flex align-items-center gap-2">
                <div class="badge bg-success-subtle text-success border border-success px-3 py-2 d-flex align-items-center">
                    <span class="spinner-grow spinner-grow-sm text-success me-2" role="status" style="width: 8px; height: 8px;"></span>
                    <span id="notifSystemStatus">{{ $data['header']['system_status'] }}</span>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-sm btn-outline-info d-flex align-items-center gap-1" onclick="window.notifExportCsv()">
                    <i class="fa-solid fa-file-csv"></i> Export CSV
                </button>
                <button type="button" class="btn btn-sm btn-outline-warning d-flex align-items-center gap-1" onclick="window.print()">
                    <i class="fa-solid fa-print"></i> Print Profile
                </button>
            </div>
        </div>
    </div>

    <!-- SECTION 2: NOTIFICATION STATISTICS CARDS -->
    <div class="row g-3 mb-4">
        <!-- Card 1: Total Alerts -->
        <div class="col-6 col-md-3 col-xl-3">
            <div class="glass-card p-3 d-flex align-items-center justify-content-between h-100 border-start border-info border-3">
                <div>
                    <div class="text-muted small fw-bold text-uppercase">Total Notifications</div>
                    <div class="fs-4 fw-bold text-white mt-1" id="statTotal">{{ number_format($data['statistics']['total_notifications']) }}</div>
                    <div class="small text-info mt-1"><i class="fa-solid fa-database me-1"></i> Recorded history</div>
                </div>
                <div class="rounded-circle p-3 d-flex align-items-center justify-content-center" style="background: rgba(13, 202, 240, 0.15); width: 54px; height: 54px;">
                    <i class="fa-solid fa-layer-group text-info fs-4"></i>
                </div>
            </div>
        </div>

        <!-- Card 2: Unread Alerts -->
        <div class="col-6 col-md-3 col-xl-3">
            <div class="glass-card p-3 d-flex align-items-center justify-content-between h-100 border-start border-danger border-3">
                <div>
                    <div class="text-muted small fw-bold text-uppercase">Unread Alerts</div>
                    <div class="fs-4 fw-bold text-danger mt-1" id="statUnread">{{ number_format($data['statistics']['unread']) }}</div>
                    <div class="small text-danger mt-1"><i class="fa-solid fa-circle-exclamation me-1"></i> Requires review</div>
                </div>
                <div class="rounded-circle p-3 d-flex align-items-center justify-content-center" style="background: rgba(220, 53, 69, 0.15); width: 54px; height: 54px;">
                    <i class="fa-solid fa-bell text-danger fs-4"></i>
                </div>
            </div>
        </div>

        <!-- Card 3: Critical Incidents -->
        <div class="col-6 col-md-3 col-xl-3">
            <div class="glass-card p-3 d-flex align-items-center justify-content-between h-100 border-start border-danger border-3" style="background: rgba(220, 53, 69, 0.08);">
                <div>
                    <div class="text-muted small fw-bold text-uppercase">Critical Priority</div>
                    <div class="fs-4 fw-bold text-white mt-1" id="statCritical">{{ number_format($data['statistics']['critical']) }}</div>
                    <div class="small text-danger mt-1"><i class="fa-solid fa-triangle-exclamation me-1"></i> Emergency action</div>
                </div>
                <div class="rounded-circle p-3 d-flex align-items-center justify-content-center" style="background: rgba(220, 53, 69, 0.25); width: 54px; height: 54px;">
                    <i class="fa-solid fa-radiation text-danger fs-4"></i>
                </div>
            </div>
        </div>

        <!-- Card 4: High Warnings -->
        <div class="col-6 col-md-3 col-xl-3">
            <div class="glass-card p-3 d-flex align-items-center justify-content-between h-100 border-start border-warning border-3">
                <div>
                    <div class="text-muted small fw-bold text-uppercase">Warning Alerts</div>
                    <div class="fs-4 fw-bold text-warning mt-1" id="statWarning">{{ number_format($data['statistics']['warning']) }}</div>
                    <div class="small text-warning mt-1"><i class="fa-solid fa-bolt me-1"></i> Elevated risk</div>
                </div>
                <div class="rounded-circle p-3 d-flex align-items-center justify-content-center" style="background: rgba(255, 193, 7, 0.15); width: 54px; height: 54px;">
                    <i class="fa-solid fa-triangle-exclamation text-warning fs-4"></i>
                </div>
            </div>
        </div>

        <!-- Card 5: Information & Updates -->
        <div class="col-6 col-md-3 col-xl-3">
            <div class="glass-card p-3 d-flex align-items-center justify-content-between h-100 border-start border-info border-3">
                <div>
                    <div class="text-muted small fw-bold text-uppercase">Information Feed</div>
                    <div class="fs-4 fw-bold text-white mt-1" id="statInfo">{{ number_format($data['statistics']['information']) }}</div>
                    <div class="small text-info mt-1"><i class="fa-solid fa-circle-info me-1"></i> System telemetry</div>
                </div>
                <div class="rounded-circle p-3 d-flex align-items-center justify-content-center" style="background: rgba(13, 202, 240, 0.15); width: 54px; height: 54px;">
                    <i class="fa-solid fa-info text-info fs-4"></i>
                </div>
            </div>
        </div>

        <!-- Card 6: Resolved Alerts -->
        <div class="col-6 col-md-3 col-xl-3">
            <div class="glass-card p-3 d-flex align-items-center justify-content-between h-100 border-start border-success border-3">
                <div>
                    <div class="text-muted small fw-bold text-uppercase">Resolved / Acknowledged</div>
                    <div class="fs-4 fw-bold text-success mt-1" id="statResolved">{{ number_format($data['statistics']['resolved']) }}</div>
                    <div class="small text-success mt-1"><i class="fa-solid fa-check-double me-1"></i> Handled cases</div>
                </div>
                <div class="rounded-circle p-3 d-flex align-items-center justify-content-center" style="background: rgba(25, 135, 84, 0.15); width: 54px; height: 54px;">
                    <i class="fa-solid fa-circle-check text-success fs-4"></i>
                </div>
            </div>
        </div>

        <!-- Card 7: Today's Alerts -->
        <div class="col-6 col-md-3 col-xl-3">
            <div class="glass-card p-3 d-flex align-items-center justify-content-between h-100 border-start border-primary border-3">
                <div>
                    <div class="text-muted small fw-bold text-uppercase">Today's Alerts</div>
                    <div class="fs-4 fw-bold text-white mt-1" id="statToday">{{ number_format($data['statistics']['todays_alerts']) }}</div>
                    <div class="small text-primary mt-1"><i class="fa-solid fa-calendar-day me-1"></i> Last 24 hours</div>
                </div>
                <div class="rounded-circle p-3 d-flex align-items-center justify-content-center" style="background: rgba(13, 110, 253, 0.15); width: 54px; height: 54px;">
                    <i class="fa-solid fa-clock-rotate-left text-primary fs-4"></i>
                </div>
            </div>
        </div>

        <!-- Card 8: Weekly Alerts -->
        <div class="col-6 col-md-3 col-xl-3">
            <div class="glass-card p-3 d-flex align-items-center justify-content-between h-100 border-start border-secondary border-3">
                <div>
                    <div class="text-muted small fw-bold text-uppercase">Weekly Volume</div>
                    <div class="fs-4 fw-bold text-white mt-1" id="statWeekly">{{ number_format($data['statistics']['weekly_alerts']) }}</div>
                    <div class="small text-muted mt-1"><i class="fa-solid fa-calendar-week me-1"></i> Rolling 7 days</div>
                </div>
                <div class="rounded-circle p-3 d-flex align-items-center justify-content-center" style="background: rgba(108, 117, 125, 0.15); width: 54px; height: 54px;">
                    <i class="fa-solid fa-chart-line text-secondary fs-4"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- SECTION 4: CRITICAL INCIDENT CENTER (RED PULSE ANIMATED) -->
    <div class="glass-card mb-4 p-4 border border-danger border-2 shadow-lg" style="background: linear-gradient(135deg, rgba(220, 53, 69, 0.18) 0%, rgba(15, 18, 24, 0.9) 100%);">
        <div class="d-flex align-items-center justify-content-between border-bottom border-danger border-opacity-50 pb-3 mb-3">
            <div class="d-flex align-items-center gap-3">
                <div class="position-relative d-flex align-items-center justify-content-center">
                    <span class="position-absolute spinner-ping bg-danger rounded-circle opacity-75" style="width: 32px; height: 32px; animation: ping 1.5s cubic-bezier(0, 0, 0.2, 1) infinite;"></span>
                    <div class="rounded-circle bg-danger text-white d-flex align-items-center justify-content-center shadow" style="width: 38px; height: 38px; z-index: 2;">
                        <i class="fa-solid fa-radiation fs-5"></i>
                    </div>
                </div>
                <div>
                    <h5 class="fw-bold text-white mb-0 d-flex align-items-center gap-2">
                        CRITICAL INCIDENT CENTER &bull; EMERGENCY ACTION REQUIRED
                        <span class="badge bg-danger rounded-pill px-2 py-1 small">ACTIVE PULSE</span>
                    </h5>
                    <div class="small text-light fw-semibold">High-severity supply chain anomalies crossing maximum risk thresholds across global corridors.</div>
                </div>
            </div>
            <div>
                <button class="btn btn-sm btn-outline-light" onclick="window.notifFilterByPriority('Critical')">
                    <i class="fa-solid fa-filter me-1"></i> Filter Critical Only
                </button>
            </div>
        </div>

        <div id="criticalIncidentContainer" class="row g-3">
            @forelse($data['critical_incidents'] as $incident)
            <div class="col-12 col-lg-6">
                <div class="p-3 rounded-3 border border-danger bg-dark bg-opacity-75 d-flex flex-column justify-content-between h-100 shadow-sm">
                    <div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge bg-danger text-white fw-bold px-2 py-1"><i class="fa-solid fa-triangle-exclamation me-1"></i> CRITICAL PRIORITY</span>
                            <span class="small text-warning fw-semibold"><i class="fa-solid fa-globe me-1"></i> {{ $incident['country'] }} &bull; {{ $incident['time_ago'] }}</span>
                        </div>
                        <h6 class="fw-bold text-white mb-1">{{ $incident['title'] }}</h6>
                        <p class="small text-light mb-2">{{ $incident['message'] }}</p>
                    </div>
                    <div class="d-flex justify-content-between align-items-center pt-2 border-top border-secondary border-opacity-25 mt-2">
                        <span class="badge bg-dark border border-danger text-danger small px-2 py-1">{{ $incident['category'] }}</span>
                        <div class="d-flex gap-2">
                            <button class="btn btn-xs btn-outline-light btn-sm py-0 px-2" onclick="window.notifShowDetail({{ json_encode($incident) }})">
                                <i class="fa-solid fa-magnifying-glass me-1"></i> Inspect
                            </button>
                            @if(!$incident['is_read'])
                            <button class="btn btn-xs btn-danger btn-sm py-0 px-2" onclick="window.notifMarkRead({{ $incident['id'] }})">
                                <i class="fa-solid fa-check me-1"></i> Acknowledge
                            </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-4">
                <i class="fa-solid fa-shield-halved text-success fs-1 mb-2"></i>
                <h6 class="text-white fw-bold mb-0">No Critical Incidents Active</h6>
                <p class="small text-muted mb-0">All global maritime corridors are operating within safe operational parameters.</p>
            </div>
            @endforelse
        </div>
    </div>

    <!-- SECTION 8: INTERACTIVE ALERT FILTERS & DEBOUNCED SEARCH -->
    <div class="glass-card mb-4 p-3 shadow-sm border border-secondary border-opacity-25">
        <div class="row g-2 align-items-center">
            <!-- Country Filter -->
            <div class="col-6 col-md-2">
                <label class="form-label small text-muted mb-1"><i class="fa-solid fa-globe me-1"></i> Corridor / Country</label>
                <select id="filterCountry" class="form-select form-select-sm bg-dark text-white border-secondary">
                    <option value="all">All Countries</option>
                    @foreach($data['alert_filters']['countries'] as $country)
                    <option value="{{ $country }}">{{ $country }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Priority Filter -->
            <div class="col-6 col-md-2">
                <label class="form-label small text-muted mb-1"><i class="fa-solid fa-layer-group me-1"></i> Priority Level</label>
                <select id="filterPriority" class="form-select form-select-sm bg-dark text-white border-secondary">
                    <option value="all">All Priorities</option>
                    @foreach($data['alert_filters']['priorities'] as $priority)
                    <option value="{{ $priority }}">{{ $priority }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Category Filter -->
            <div class="col-6 col-md-2">
                <label class="form-label small text-muted mb-1"><i class="fa-solid fa-tags me-1"></i> Category</label>
                <select id="filterCategory" class="form-select form-select-sm bg-dark text-white border-secondary">
                    <option value="all">All Categories</option>
                    @foreach($data['alert_filters']['categories'] as $category)
                    <option value="{{ $category }}">{{ $category }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Status Filter -->
            <div class="col-6 col-md-2">
                <label class="form-label small text-muted mb-1"><i class="fa-solid fa-filter me-1"></i> Status</label>
                <select id="filterStatus" class="form-select form-select-sm bg-dark text-white border-secondary">
                    <option value="all">All Statuses</option>
                    @foreach($data['alert_filters']['statuses'] as $status)
                    <option value="{{ $status }}">{{ $status }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Debounced Search Box -->
            <div class="col-12 col-md-3">
                <label class="form-label small text-muted mb-1"><i class="fa-solid fa-magnifying-glass me-1"></i> Debounced Search (300ms)</label>
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-dark border-secondary text-warning"><i class="fa-solid fa-search"></i></span>
                    <input type="text" id="notifSearchInput" class="form-control bg-dark text-white border-secondary" placeholder="Search title, country, reason..." autocomplete="off">
                </div>
            </div>

            <!-- Reset Filters -->
            <div class="col-12 col-md-1 d-flex align-items-end justify-content-md-end mt-3 mt-md-0">
                <button type="button" class="btn btn-sm btn-outline-secondary w-100 py-1" onclick="window.notifResetFilters()">
                    <i class="fa-solid fa-rotate me-1"></i> Reset
                </button>
            </div>
        </div>
    </div>

    <!-- SECTION 5, 6, 7: ANALYTICAL CHARTS & LEAFLET COUNTRY MAP -->
    <div class="row g-4 mb-4">
        <!-- Section 5: Alert Categories Pie Chart -->
        <div class="col-12 col-lg-4">
            <div class="glass-card p-4 h-100 d-flex flex-column justify-content-between">
                <div>
                    <h5 class="fw-bold text-white mb-1"><i class="fa-solid fa-chart-pie text-warning me-2"></i> Alert Categories Breakdown</h5>
                    <p class="small text-muted mb-3">Distribution of warnings across domain services.</p>
                </div>
                <div style="position: relative; height: 260px;">
                    <canvas id="alertCategoriesChart"></canvas>
                </div>
                <div class="mt-3 pt-2 border-top border-secondary border-opacity-25 d-flex justify-content-between small text-muted">
                    <span><i class="fa-solid fa-check text-success me-1"></i> Real-time sync</span>
                    <span>Multi-Domain Feed</span>
                </div>
            </div>
        </div>

        <!-- Section 6: Notification Timeline Line Chart -->
        <div class="col-12 col-lg-8">
            <div class="glass-card p-4 h-100 d-flex flex-column justify-content-between">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-3">
                    <div>
                        <h5 class="fw-bold text-white mb-1"><i class="fa-solid fa-chart-line text-info me-2"></i> Notification Frequency Timeline</h5>
                        <p class="small text-muted mb-0">Volume trajectory across hourly, daily, and weekly cycles.</p>
                    </div>
                    <div class="btn-group btn-group-sm" role="group">
                        <button type="button" class="btn btn-outline-info active" id="btnTimeHour" onclick="window.notifSwitchTimeline('per_hour', this)">Per Hour</button>
                        <button type="button" class="btn btn-outline-info" id="btnTimeDay" onclick="window.notifSwitchTimeline('per_day', this)">Per Day</button>
                        <button type="button" class="btn btn-outline-info" id="btnTimeWeek" onclick="window.notifSwitchTimeline('per_week', this)">Per Week</button>
                    </div>
                </div>
                <div style="position: relative; height: 260px;">
                    <canvas id="notificationTimelineChart"></canvas>
                </div>
                <div class="mt-3 pt-2 border-top border-secondary border-opacity-25 d-flex justify-content-between small text-muted">
                    <span><i class="fa-solid fa-satellite-dish text-info me-1"></i> Telemetry Pulse</span>
                    <span>Updated every 30 seconds</span>
                </div>
            </div>
        </div>
    </div>

    <!-- SECTION 7: COUNTRY ALERT MAP (LEAFLET) -->
    <div class="glass-card mb-4 p-4 shadow-lg">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h5 class="fw-bold text-white mb-1"><i class="fa-solid fa-map-location-dot text-success me-2"></i> Global Corridor Alert Status Map</h5>
                <p class="small text-muted mb-0">Geospatial visualization of alert intensity across 10 monitored supply chain hubs.</p>
            </div>
            <div class="d-flex align-items-center gap-3 small">
                <span class="d-flex align-items-center text-light"><i class="fa-solid fa-circle text-danger me-1"></i> Critical / High Risk</span>
                <span class="d-flex align-items-center text-light"><i class="fa-solid fa-circle text-warning me-1"></i> Moderate Warning</span>
                <span class="d-flex align-items-center text-light"><i class="fa-solid fa-circle text-success me-1"></i> Stable Flow</span>
            </div>
        </div>
        <div id="countryAlertMap" class="rounded-3 border border-secondary border-opacity-50 shadow-inner" style="height: 380px; width: 100%; background: #1a1e29;"></div>
    </div>

    <!-- SECTION 3: NOTIFICATION FEED TIMELINE & SECTION 10: QUICK ACTIONS -->
    <div class="glass-card mb-4 p-4 shadow-lg">
        <!-- Section 10: Quick Actions Toolbar inside Feed -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center pb-3 mb-3 border-bottom border-secondary border-opacity-25 gap-3">
            <div>
                <h5 class="fw-bold text-white mb-1"><i class="fa-solid fa-list-check text-info me-2"></i> Real-Time Notification Feed</h5>
                <p class="small text-muted mb-0">Live feed of all system alerts, expert evaluations, and telemetry checks.</p>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <button type="button" class="btn btn-sm btn-outline-success d-flex align-items-center gap-1" onclick="window.notifMarkAllRead()">
                    <i class="fa-solid fa-check-double"></i> Mark All as Read
                </button>
                <button type="button" class="btn btn-sm btn-outline-danger d-flex align-items-center gap-1" onclick="window.notifClearHistory()">
                    <i class="fa-solid fa-trash-can"></i> Clear History
                </button>
                <button type="button" class="btn btn-sm btn-outline-warning d-flex align-items-center gap-1" onclick="window.notifRefreshData()">
                    <i class="fa-solid fa-rotate-right"></i> Refresh Now
                </button>
            </div>
        </div>

        <!-- Feed List Container -->
        <div id="notificationFeedContainer" class="d-flex flex-column gap-3" style="max-height: 750px; overflow-y: auto;">
            @forelse($data['notification_feed'] as $item)
            <div class="p-3 rounded-3 border border-{{ $item['color'] }} bg-dark bg-opacity-50 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 transition-all notification-card {{ !$item['is_read'] ? 'border-2 shadow-sm' : 'opacity-75' }}" data-id="{{ $item['id'] }}">
                <div class="d-flex align-items-start gap-3">
                    <div class="rounded-circle p-3 d-flex align-items-center justify-content-center bg-{{ $item['color'] }} bg-opacity-25 border border-{{ $item['color'] }}" style="width: 48px; height: 48px; flex-shrink: 0;">
                        <i class="fa-solid {{ $item['icon'] }} text-{{ $item['color'] }} fs-5"></i>
                    </div>
                    <div>
                        <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                            <span class="badge {{ $item['priority_badge'] }} px-2 py-1">{{ strtoupper($item['priority']) }}</span>
                            <span class="badge bg-dark border border-secondary text-light px-2 py-1"><i class="fa-solid fa-globe me-1"></i> {{ $item['country'] }}</span>
                            <span class="badge bg-secondary text-white px-2 py-1">{{ $item['category'] }}</span>
                            @if(!$item['is_read'])
                            <span class="badge bg-danger text-white rounded-pill px-2 py-1">UNREAD</span>
                            @endif
                            <span class="small text-muted ms-auto ms-md-2"><i class="fa-regular fa-clock me-1"></i> {{ $item['created_at_formatted'] }} ({{ $item['time_ago'] }})</span>
                        </div>
                        <h6 class="fw-bold text-white mb-1">{{ $item['title'] }}</h6>
                        <p class="small text-light mb-1">{{ $item['message'] }}</p>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-2 align-self-end align-self-md-center flex-shrink-0">
                    <button type="button" class="btn btn-sm btn-outline-info" onclick="window.notifShowDetail({{ json_encode($item) }})" title="Inspect Metadata">
                        <i class="fa-solid fa-magnifying-glass"></i> Details
                    </button>
                    @if(!$item['is_read'])
                    <button type="button" class="btn btn-sm btn-outline-success" onclick="window.notifMarkRead({{ $item['id'] }})" title="Mark as Read">
                        <i class="fa-solid fa-check"></i>
                    </button>
                    @endif
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="window.notifDelete({{ $item['id'] }})" title="Delete Alert">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </div>
            </div>
            @empty
            <div class="text-center py-5">
                <i class="fa-solid fa-bell-slash text-muted fs-1 mb-3"></i>
                <h6 class="text-white fw-bold">No Notifications Found</h6>
                <p class="small text-muted">There are no notifications matching your current filter criteria.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

<!-- SECTION 9: NOTIFICATION DETAILS MODAL -->
<div class="modal fade" id="notificationDetailModal" tabindex="-1" aria-labelledby="notificationDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content glass-card border border-warning shadow-lg text-white" style="background: #151921;">
            <div class="modal-header border-bottom border-secondary border-opacity-50">
                <h5 class="modal-title fw-bold d-flex align-items-center gap-2" id="notificationDetailModalLabel">
                    <i class="fa-solid fa-circle-info text-warning"></i> Alert Telemetry & Expert Metadata
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-3 mb-4">
                    <div class="col-12 col-md-6">
                        <div class="p-3 rounded bg-dark border border-secondary border-opacity-25">
                            <div class="small text-muted text-uppercase fw-bold">Alert Title</div>
                            <div class="fs-6 fw-bold text-white mt-1" id="detailTitle">--</div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="p-3 rounded bg-dark border border-secondary border-opacity-25">
                            <div class="small text-muted text-uppercase fw-bold">Corridor / Hub</div>
                            <div class="fs-6 fw-bold text-warning mt-1" id="detailCountry">--</div>
                        </div>
                    </div>
                </div>

                <div class="p-3 rounded bg-dark border border-secondary border-opacity-25 mb-4">
                    <div class="small text-muted text-uppercase fw-bold mb-1">Full Message Description</div>
                    <div class="text-light" id="detailMessage">--</div>
                </div>

                <h6 class="fw-bold text-info mb-3"><i class="fa-solid fa-microchip me-2"></i> Rule Engine Diagnostics & Telemetry</h6>
                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <div class="p-3 rounded bg-dark border border-info border-opacity-25 h-100">
                            <div class="small text-info text-uppercase fw-bold"><i class="fa-solid fa-gear me-1"></i> Triggering Condition</div>
                            <div class="text-white mt-1 fw-semibold" id="detailRuleTrigger">--</div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="p-3 rounded bg-dark border border-warning border-opacity-25 h-100">
                            <div class="small text-warning text-uppercase fw-bold"><i class="fa-solid fa-magnifying-glass-chart me-1"></i> Root Cause Analysis</div>
                            <div class="text-white mt-1 fw-semibold" id="detailReason">--</div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="p-3 rounded bg-dark border border-success border-opacity-25">
                            <div class="small text-success text-uppercase fw-bold"><i class="fa-solid fa-clipboard-check me-1"></i> Expert Action Recommendation</div>
                            <div class="text-white mt-1 fw-semibold" id="detailRecommendation">--</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-top border-secondary border-opacity-50 p-3">
                <button type="button" class="btn btn-sm btn-secondary px-4" data-bs-dismiss="modal">Close Window</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<style>
@keyframes ping {
    75%, 100% {
        transform: scale(2);
        opacity: 0;
    }
}
.spinner-ping {
    animation: ping 1.5s cubic-bezier(0, 0, 0.2, 1) infinite;
}
.notification-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(0,0,0,0.4);
}
</style>
<script>
    window.INITIAL_NOTIFICATIONS_DATA = @json($data);
</script>
<script src="{{ asset('js/notifications.js') }}"></script>
@endpush
