/**
 * RiskIntel Hub - Enterprise Smart Notification & Alert Engine
 * Vanilla ES6 JavaScript & Modular Dashboard Engine
 */

(function () {
    'use strict';

    // Global State
    let currentData = window.INITIAL_NOTIFICATIONS_DATA || null;
    let categoriesChartInstance = null;
    let timelineChartInstance = null;
    let mapInstance = null;
    let mapLayerGroup = null;
    let currentTimelineType = 'per_hour';
    let debounceTimer = null;

    /**
     * Inisialisasi utama dasbor saat dokumen siap.
     */
    document.addEventListener('DOMContentLoaded', () => {
        if (!currentData) {
            fetchNotificationsData();
        } else {
            initDashboardComponents(currentData);
        }

        setupEventListeners();
        startAutoRefreshPolling(30000); // 30s Polling Auto-Refresh
    });

    /**
     * Pengaturan event listeners untuk input filter dan pencarian.
     */
    function setupEventListeners() {
        const countrySelect = document.getElementById('filterCountry');
        const prioritySelect = document.getElementById('filterPriority');
        const categorySelect = document.getElementById('filterCategory');
        const statusSelect = document.getElementById('filterStatus');
        const searchInput = document.getElementById('notifSearchInput');

        const triggerFilterChange = () => {
            fetchNotificationsData({
                country: countrySelect?.value || 'all',
                priority: prioritySelect?.value || 'all',
                category: categorySelect?.value || 'all',
                status: statusSelect?.value || 'all',
                search: searchInput?.value || ''
            });
        };

        if (countrySelect) countrySelect.addEventListener('change', triggerFilterChange);
        if (prioritySelect) prioritySelect.addEventListener('change', triggerFilterChange);
        if (categorySelect) categorySelect.addEventListener('change', triggerFilterChange);
        if (statusSelect) statusSelect.addEventListener('change', triggerFilterChange);

        if (searchInput) {
            searchInput.addEventListener('input', () => {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(triggerFilterChange, 300); // Debounce 300ms
            });
        }
    }

    /**
     * Memulai Polling berkala setiap 30 detik untuk sinkronisasi real-time.
     */
    function startAutoRefreshPolling(intervalMs) {
        setInterval(() => {
            fetchNotificationsData(getActiveFilters(), true); // silent reload
        }, intervalMs);
    }

    /**
     * Memperoleh filter aktif saat ini dari elemen DOM.
     */
    function getActiveFilters() {
        return {
            country: document.getElementById('filterCountry')?.value || 'all',
            priority: document.getElementById('filterPriority')?.value || 'all',
            category: document.getElementById('filterCategory')?.value || 'all',
            status: document.getElementById('filterStatus')?.value || 'all',
            search: document.getElementById('notifSearchInput')?.value || ''
        };
    }

    /**
     * Mengambil data notifikasi terbaru via AJAX JSON.
     */
    function fetchNotificationsData(filters = {}, isSilent = false) {
        const queryParams = new URLSearchParams();
        if (filters.country && filters.country !== 'all') queryParams.append('country', filters.country);
        if (filters.priority && filters.priority !== 'all') queryParams.append('priority', filters.priority);
        if (filters.category && filters.category !== 'all') queryParams.append('category', filters.category);
        if (filters.status && filters.status !== 'all') queryParams.append('status', filters.status);
        if (filters.search) queryParams.append('search', filters.search);

        if (!isSilent) {
            const statusElem = document.getElementById('notifSystemStatus');
            if (statusElem) statusElem.textContent = 'SYNCING...';
        }

        fetch(`/api/notifications?${queryParams.toString()}`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(json => {
            if (json.success && json.data) {
                currentData = json.data;
                window.notifData = currentData;
                updateDashboardDOM(currentData);
            }
        })
        .catch(error => {
            console.error('Error fetching notifications data:', error);
            const statusElem = document.getElementById('notifSystemStatus');
            if (statusElem) statusElem.textContent = 'CONNECTION ERROR';
        });
    }

    /**
     * Inisialisasi awal seluruh komponen dasbor (Chart, Peta, DOM).
     */
    function initDashboardComponents(data) {
        updateStatisticsCards(data.statistics);
        updateHeaderInfo(data.header);
        renderAlertCategoriesChart(data.alert_categories);
        renderNotificationTimelineChart(data.timeline_chart, currentTimelineType);
        initCountryAlertMap(data.country_alert_map);
    }

    /**
     * Memperbarui seluruh elemen DOM dasbor saat data baru diterima.
     */
    function updateDashboardDOM(data) {
        updateStatisticsCards(data.statistics);
        updateHeaderInfo(data.header);
        updateFeedContainer(data.notification_feed);
        updateCriticalIncidents(data.critical_incidents);

        if (categoriesChartInstance && data.alert_categories) {
            categoriesChartInstance.data.datasets[0].data = Object.values(data.alert_categories);
            categoriesChartInstance.update('none');
        } else if (data.alert_categories) {
            renderAlertCategoriesChart(data.alert_categories);
        }

        if (timelineChartInstance && data.timeline_chart && data.timeline_chart[currentTimelineType]) {
            timelineChartInstance.data.labels = data.timeline_chart[currentTimelineType].labels;
            timelineChartInstance.data.datasets[0].data = data.timeline_chart[currentTimelineType].data;
            timelineChartInstance.update('none');
        } else if (data.timeline_chart) {
            renderNotificationTimelineChart(data.timeline_chart, currentTimelineType);
        }

        if (mapInstance && data.country_alert_map) {
            updateMapMarkers(data.country_alert_map);
        } else if (data.country_alert_map) {
            initCountryAlertMap(data.country_alert_map);
        }
    }

    /**
     * Memperbarui kartu angka KPI statistik.
     */
    function updateStatisticsCards(stats) {
        if (!stats) return;
        const setVal = (id, val) => {
            const el = document.getElementById(id);
            if (el) el.textContent = new Intl.NumberFormat().format(val || 0);
        };

        setVal('statTotal', stats.total_notifications);
        setVal('statUnread', stats.unread);
        setVal('statCritical', stats.critical);
        setVal('statWarning', stats.warning);
        setVal('statInfo', stats.information);
        setVal('statResolved', stats.resolved);
        setVal('statToday', stats.todays_alerts);
        setVal('statWeekly', stats.weekly_alerts);
    }

    /**
     * Memperbarui informasi waktu dan badge unread di header.
     */
    function updateHeaderInfo(header) {
        if (!header) return;
        const clockEl = document.getElementById('notifClock');
        const badgeEl = document.getElementById('unreadBadgeHeader');
        const statusEl = document.getElementById('notifSystemStatus');

        if (clockEl) clockEl.textContent = header.current_time;
        if (badgeEl) badgeEl.textContent = `${header.unread_count} UNREAD`;
        if (statusEl) statusEl.textContent = header.system_status || 'LIVE SYNC ACTIVE';
    }

    /**
     * Memperbarui kontainer feed notifikasi (Section 3).
     */
    function updateFeedContainer(feed) {
        const container = document.getElementById('notificationFeedContainer');
        if (!container) return;

        if (!feed || feed.length === 0) {
            container.innerHTML = `
                <div class="text-center py-5">
                    <i class="fa-solid fa-bell-slash text-muted fs-1 mb-3"></i>
                    <h6 class="text-white fw-bold">No Notifications Found</h6>
                    <p class="small text-muted">There are no notifications matching your current filter criteria.</p>
                </div>
            `;
            return;
        }

        container.innerHTML = feed.map(item => `
            <div class="p-3 rounded-3 border border-${item.color} bg-dark bg-opacity-50 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 transition-all notification-card ${!item.is_read ? 'border-2 shadow-sm' : 'opacity-75'}" data-id="${item.id}">
                <div class="d-flex align-items-start gap-3">
                    <div class="rounded-circle p-3 d-flex align-items-center justify-content-center bg-${item.color} bg-opacity-25 border border-${item.color}" style="width: 48px; height: 48px; flex-shrink: 0;">
                        <i class="fa-solid ${item.icon} text-${item.color} fs-5"></i>
                    </div>
                    <div>
                        <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                            <span class="badge ${item.priority_badge} px-2 py-1">${item.priority.toUpperCase()}</span>
                            <span class="badge bg-dark border border-secondary text-light px-2 py-1"><i class="fa-solid fa-globe me-1"></i> ${item.country}</span>
                            <span class="badge bg-secondary text-white px-2 py-1">${item.category}</span>
                            ${!item.is_read ? '<span class="badge bg-danger text-white rounded-pill px-2 py-1">UNREAD</span>' : ''}
                            <span class="small text-muted ms-auto ms-md-2"><i class="fa-regular fa-clock me-1"></i> ${item.created_at_formatted} (${item.time_ago})</span>
                        </div>
                        <h6 class="fw-bold text-white mb-1">${escapeHtml(item.title)}</h6>
                        <p class="small text-light mb-1">${escapeHtml(item.message)}</p>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-2 align-self-end align-self-md-center flex-shrink-0">
                    <button type="button" class="btn btn-sm btn-outline-info" onclick='window.notifShowDetail(${JSON.stringify(item).replace(/'/g, "&#39;")})' title="Inspect Metadata">
                        <i class="fa-solid fa-magnifying-glass"></i> Details
                    </button>
                    ${!item.is_read ? `
                    <button type="button" class="btn btn-sm btn-outline-success" onclick="window.notifMarkRead(${item.id})" title="Mark as Read">
                        <i class="fa-solid fa-check"></i>
                    </button>
                    ` : ''}
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="window.notifDelete(${item.id})" title="Delete Alert">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </div>
            </div>
        `).join('');
    }

    /**
     * Memperbarui kontainer Critical Incident Center (Section 4).
     */
    function updateCriticalIncidents(incidents) {
        const container = document.getElementById('criticalIncidentContainer');
        if (!container) return;

        if (!incidents || incidents.length === 0) {
            container.innerHTML = `
                <div class="col-12 text-center py-4">
                    <i class="fa-solid fa-shield-halved text-success fs-1 mb-2"></i>
                    <h6 class="text-white fw-bold mb-0">No Critical Incidents Active</h6>
                    <p class="small text-muted mb-0">All global maritime corridors are operating within safe operational parameters.</p>
                </div>
            `;
            return;
        }

        container.innerHTML = incidents.map(incident => `
            <div class="col-12 col-lg-6">
                <div class="p-3 rounded-3 border border-danger bg-dark bg-opacity-75 d-flex flex-column justify-content-between h-100 shadow-sm">
                    <div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge bg-danger text-white fw-bold px-2 py-1"><i class="fa-solid fa-triangle-exclamation me-1"></i> CRITICAL PRIORITY</span>
                            <span class="small text-warning fw-semibold"><i class="fa-solid fa-globe me-1"></i> ${escapeHtml(incident.country)} &bull; ${incident.time_ago}</span>
                        </div>
                        <h6 class="fw-bold text-white mb-1">${escapeHtml(incident.title)}</h6>
                        <p class="small text-light mb-2">${escapeHtml(incident.message)}</p>
                    </div>
                    <div class="d-flex justify-content-between align-items-center pt-2 border-top border-secondary border-opacity-25 mt-2">
                        <span class="badge bg-dark border border-danger text-danger small px-2 py-1">${incident.category}</span>
                        <div class="d-flex gap-2">
                            <button class="btn btn-xs btn-outline-light btn-sm py-0 px-2" onclick='window.notifShowDetail(${JSON.stringify(incident).replace(/'/g, "&#39;")})'>
                                <i class="fa-solid fa-magnifying-glass me-1"></i> Inspect
                            </button>
                            ${!incident.is_read ? `
                            <button class="btn btn-xs btn-danger btn-sm py-0 px-2" onclick="window.notifMarkRead(${incident.id})">
                                <i class="fa-solid fa-check me-1"></i> Acknowledge
                            </button>
                            ` : ''}
                        </div>
                    </div>
                </div>
            </div>
        `).join('');
    }

    /**
     * Render diagram pie chart untuk kategori notifikasi (Section 5).
     */
    function renderAlertCategoriesChart(categoriesData) {
        const ctx = document.getElementById('alertCategoriesChart');
        if (!ctx || !categoriesData) return;

        if (categoriesChartInstance) {
            categoriesChartInstance.destroy();
        }

        const labels = Object.keys(categoriesData);
        const data = Object.values(categoriesData);
        const colors = [
            '#ff4d4f', '#fd7e14', '#ffc107', '#17a2b8', 
            '#6610f2', '#d63384', '#dc3545', '#6c757d'
        ];

        categoriesChartInstance = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    data: data,
                    backgroundColor: colors,
                    borderColor: '#151921',
                    borderWidth: 2,
                    hoverOffset: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            color: '#cbd5e1',
                            font: { family: 'Inter', size: 12, weight: '600' },
                            padding: 14,
                            usePointStyle: true
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(15, 18, 24, 0.95)',
                        titleColor: '#ffffff',
                        bodyColor: '#e2e8f0',
                        borderColor: 'rgba(255, 255, 255, 0.2)',
                        borderWidth: 1,
                        padding: 12
                    }
                },
                cutout: '62%'
            }
        });
    }

    /**
     * Render diagram line chart timeline (Section 6).
     */
    function renderNotificationTimelineChart(timelineData, type = 'per_hour') {
        const ctx = document.getElementById('notificationTimelineChart');
        if (!ctx || !timelineData || !timelineData[type]) return;

        if (timelineChartInstance) {
            timelineChartInstance.destroy();
        }

        const chartData = timelineData[type];
        const canvasCtx = ctx.getContext('2d');
        const gradient = canvasCtx.createLinearGradient(0, 0, 0, 260);
        gradient.addColorStop(0, 'rgba(13, 202, 240, 0.45)');
        gradient.addColorStop(1, 'rgba(13, 202, 240, 0.0)');

        timelineChartInstance = new Chart(ctx, {
            type: 'line',
            data: {
                labels: chartData.labels,
                datasets: [{
                    label: 'Alert Volume Trajectory',
                    data: chartData.data,
                    borderColor: '#0dcaf0',
                    backgroundColor: gradient,
                    borderWidth: 3,
                    fill: true,
                    tension: 0.35,
                    pointBackgroundColor: '#0dcaf0',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 7
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(15, 18, 24, 0.95)',
                        titleColor: '#ffffff',
                        bodyColor: '#0dcaf0',
                        borderColor: 'rgba(13, 202, 240, 0.4)',
                        borderWidth: 1,
                        padding: 12
                    }
                },
                scales: {
                    x: {
                        grid: { color: 'rgba(255, 255, 255, 0.06)' },
                        ticks: { color: '#cbd5e1', font: { family: 'Inter', size: 11 } }
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(255, 255, 255, 0.06)' },
                        ticks: { color: '#cbd5e1', font: { family: 'Inter', size: 11 } }
                    }
                }
            }
        });
    }

    /**
     * Inisialisasi peta Leaflet (Section 7).
     */
    function initCountryAlertMap(mapData) {
        const mapContainer = document.getElementById('countryAlertMap');
        if (!mapContainer || typeof L === 'undefined' || !mapData) return;

        if (mapInstance) {
            mapInstance.remove();
        }

        mapInstance = L.map('countryAlertMap', {
            center: [20.0, 15.0],
            zoom: 2,
            minZoom: 1,
            maxZoom: 8,
            zoomControl: true,
            attributionControl: false
        });

        L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
            maxZoom: 19,
            subdomains: 'abcd'
        }).addTo(mapInstance);

        mapLayerGroup = L.layerGroup().addTo(mapInstance);
        updateMapMarkers(mapData);
    }

    /**
     * Memperbarui marker penanda pada peta Leaflet.
     */
    function updateMapMarkers(mapData) {
        if (!mapLayerGroup || typeof L === 'undefined' || !mapData) return;

        mapLayerGroup.clearLayers();

        Object.entries(mapData).forEach(([countryName, info]) => {
            const circle = L.circleMarker([info.lat, info.lng], {
                radius: Math.min(18, Math.max(8, info.alerts_count * 2.5)),
                fillColor: info.hex_color,
                color: '#ffffff',
                weight: 2,
                opacity: 0.9,
                fillOpacity: 0.75
            });

            const popupContent = `
                <div style="font-family: 'Inter', sans-serif; color: #f8fafc; background: #151921; padding: 4px; min-width: 180px;">
                    <div style="font-weight: 700; font-size: 14px; border-bottom: 1px solid rgba(255,255,255,0.15); padding-bottom: 6px; margin-bottom: 6px; color: #e0b472;">
                        <i class="fa-solid fa-globe me-1"></i> ${escapeHtml(countryName)} Hub
                    </div>
                    <div style="font-size: 12px; margin-bottom: 4px;">
                        <span style="color: #cbd5e1;">Risk Score:</span> <strong style="color: ${info.hex_color};">${info.risk_score}%</strong>
                    </div>
                    <div style="font-size: 12px; margin-bottom: 4px;">
                        <span style="color: #cbd5e1;">Active Alerts:</span> <strong style="color: #ffffff;">${info.alerts_count}</strong>
                    </div>
                    <div style="font-size: 12px; margin-bottom: 8px;">
                        <span style="color: #cbd5e1;">Critical Incidents:</span> <strong style="color: #ff4d4f;">${info.critical_count}</strong>
                    </div>
                    <button class="btn btn-xs btn-outline-info w-100 py-1" style="font-size: 11px;" onclick="window.notifFilterByCountry('${escapeHtml(countryName)}')">
                        Filter Corridor Alerts
                    </button>
                </div>
            `;

            circle.bindPopup(popupContent);
            mapLayerGroup.addLayer(circle);
        });
    }

    /**
     * Helper untuk meloloskan karakter HTML (XSS prevention).
     */
    function escapeHtml(str) {
        if (typeof str !== 'string') return '';
        return str.replace(/&/g, '&amp;')
                  .replace(/</g, '&lt;')
                  .replace(/>/g, '&gt;')
                  .replace(/"/g, '&quot;')
                  .replace(/'/g, '&#039;');
    }

    /**
     * Mengambil header CSRF untuk panggilan AJAX POST/DELETE.
     */
    function getCsrfHeader() {
        const tokenElem = document.querySelector('meta[name="csrf-token"]');
        return tokenElem ? tokenElem.getAttribute('content') : '';
    }

    // ==========================================
    // WINDOW EXPOSED ACTIONS (API UI)
    // ==========================================

    window.notifRefreshData = function (isSilent = false) {
        fetchNotificationsData(getActiveFilters(), isSilent);
    };

    window.notifSwitchTimeline = function (type, buttonElem) {
        currentTimelineType = type;
        document.querySelectorAll('#btnTimeHour, #btnTimeDay, #btnTimeWeek').forEach(btn => btn.classList.remove('active'));
        if (buttonElem) buttonElem.classList.add('active');

        if (currentData && currentData.timeline_chart) {
            renderNotificationTimelineChart(currentData.timeline_chart, currentTimelineType);
        }
    };

    window.notifFilterByPriority = function (priority) {
        const el = document.getElementById('filterPriority');
        if (el) {
            el.value = priority;
            fetchNotificationsData(getActiveFilters());
        }
    };

    window.notifFilterByCountry = function (country) {
        const el = document.getElementById('filterCountry');
        if (el) {
            el.value = country;
            fetchNotificationsData(getActiveFilters());
        }
    };

    window.notifResetFilters = function () {
        const ids = ['filterCountry', 'filterPriority', 'filterCategory', 'filterStatus'];
        ids.forEach(id => {
            const el = document.getElementById(id);
            if (el) el.value = 'all';
        });
        const searchInput = document.getElementById('notifSearchInput');
        if (searchInput) searchInput.value = '';

        fetchNotificationsData({});
    };

    window.notifMarkRead = function (id) {
        fetch(`/api/notifications/read/${id}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': getCsrfHeader(),
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(json => {
            if (json.success) {
                fetchNotificationsData(getActiveFilters(), true);
            }
        });
    };

    window.notifMarkAllRead = function () {
        fetch('/api/notifications/read-all', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': getCsrfHeader(),
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(json => {
            if (json.success) {
                fetchNotificationsData(getActiveFilters(), true);
            }
        });
    };

    window.notifDelete = function (id) {
        fetch(`/api/notifications/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': getCsrfHeader(),
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(json => {
            if (json.success) {
                fetchNotificationsData(getActiveFilters(), true);
            }
        });
    };

    window.notifClearHistory = function () {
        if (!confirm('Are you sure you want to clear all notification history from your account?')) {
            return;
        }

        fetch('/api/notifications/clear', {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': getCsrfHeader(),
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(json => {
            if (json.success) {
                fetchNotificationsData(getActiveFilters(), true);
            }
        });
    };

    window.notifShowDetail = function (item) {
        const setTxt = (id, txt) => {
            const el = document.getElementById(id);
            if (el) el.textContent = txt || '--';
        };

        setTxt('detailTitle', item.title);
        setTxt('detailCountry', item.country);
        setTxt('detailMessage', item.message);

        const meta = item.metadata || {};
        setTxt('detailRuleTrigger', meta.rule_trigger || `Expert Rule Check #ALRT_${item.id}`);
        setTxt('detailReason', meta.reason || item.message);
        setTxt('detailRecommendation', meta.recommendation || 'Verify telemetry and maintain standard operational protocol.');

        const modalEl = document.getElementById('notificationDetailModal');
        if (modalEl && typeof bootstrap !== 'undefined') {
            const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
            modal.show();
        }
    };

    window.notifExportCsv = function () {
        if (!currentData || !currentData.notification_feed) return;

        const headers = ['ID', 'Title', 'Priority', 'Category', 'Country', 'Status', 'Message', 'Created At'];
        const rows = currentData.notification_feed.map(item => [
            item.id,
            `"${(item.title || '').replace(/"/g, '""')}"`,
            item.priority,
            item.category,
            item.country,
            item.status,
            `"${(item.message || '').replace(/"/g, '""')}"`,
            `"${item.created_at_formatted}"`
        ]);

        const csvContent = 'data:text/csv;charset=utf-8,' + [headers.join(','), ...rows.map(r => r.join(','))].join('\n');
        const encodedUri = encodeURI(csvContent);
        const link = document.createElement('a');
        link.setAttribute('href', encodedUri);
        link.setAttribute('download', `riskintel_notifications_export_${new Date().toISOString().slice(0,10)}.csv`);
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    };

})();
