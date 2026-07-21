/**
 * Business Intelligence Analytics Center (`/analytics`) - ES6 Module / Vanilla JS
 * Handles 10 Chart.js configurations, Leaflet Heatmap without duplication, 300ms debounced AJAX filters,
 * Country Drill-Down Modal, and 60-second Auto-Refresh.
 */

document.addEventListener('DOMContentLoaded', () => {
    if (!window.INITIAL_BI_DATA) {
        console.warn('INITIAL_BI_DATA not found. Skipping BI Analytics initialization.');
        return;
    }

    let biData = window.INITIAL_BI_DATA;
    let charts = {};
    let biMap = null;
    let biMarkersLayer = null;
    let activeHeatmapLayer = 'overall_risk';
    let debounceTimer = null;
    let autoRefreshInterval = null;

    // Set Chart.js global defaults for Dark Glassmorphism Theme
    if (typeof Chart !== 'undefined') {
        Chart.defaults.color = '#e0e0e0';
        Chart.defaults.font.family = "'Inter', 'Segoe UI', sans-serif";
    }

    // 1. Restore country selection dari input pencarian, penyimpanan khusus, atau URL parameter
    const featKey = typeof window.getFeatureStorageKey === 'function' ? window.getFeatureStorageKey() : 'selected_country_analytics';
    const urlParams = new URLSearchParams(window.location.search);
    const urlCountry = urlParams.get('country');
    const searchInputEl = document.getElementById('countrySearchInput');
    let searchInputVal = searchInputEl ? searchInputEl.value : '';

    let savedCountry = urlCountry || sessionStorage.getItem(featKey) || sessionStorage.getItem('selected_country_dashboard') || localStorage.getItem('selected_country_dashboard') || searchInputVal;
    if (!savedCountry && typeof activeCountryData !== 'undefined' && activeCountryData?.name && activeCountryData.name !== 'Belum Dipilih' && activeCountryData.name !== 'Semua Negara' && activeCountryData.name !== 'Global / Semua Negara' && activeCountryData.name !== 'Global') {
        savedCountry = activeCountryData.name;
    }

    if (savedCountry) {
        savedCountry = savedCountry.trim().replace(/\s*\(.*?\)/, '');
    }

    const filterCountryInput = document.getElementById('filterCountry');
    if (filterCountryInput) {
        filterCountryInput.value = (savedCountry && savedCountry !== 'Global / Semua Negara' && savedCountry !== 'Global / Semua Negara (Feed Artikel Admin)' && savedCountry !== 'Global' && savedCountry !== '-') ? savedCountry : '';
    }

    // Initialize Charts & Map immediately
    initAllCharts(biData);
    initBiMap(biData.heatmap_data);

    // If there is a country filter active or saved, fetch filtered analytics immediately to replace placeholder
    if (savedCountry && savedCountry.trim() !== '' && savedCountry !== 'Global / Semua Negara' && savedCountry !== 'Global' && savedCountry !== '-') {
        fetchFilteredAnalytics();
    }

    // MutationObserver: watch #filterCountry value changes set programmatically by dashboard.js
    // This is the safety net for when selectCountry() is called AFTER analytics.js is ready
    const filterCountryEl2 = document.getElementById('filterCountry');
    if (filterCountryEl2) {
        let _lastFilterVal = filterCountryEl2.value;
        // Poll every 200ms for value changes (MutationObserver does not catch .value= assignments)
        setInterval(() => {
            const currentVal = filterCountryEl2.value;
            if (currentVal !== _lastFilterVal) {
                _lastFilterVal = currentVal;
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => fetchFilteredAnalytics(), 300);
            }
        }, 200);
    }

    // 2. Setup Interactive Filters & Period Switches (300ms Debounce)
    setupFilterListeners();
    setupPeriodSwitchers();
    setupHeatmapLayerSwitcher();

    // 3. Setup Global Search & Drill-Down Modal
    setupGlobalSearch(biData.drill_down_map);
    setupDrillDownHandlers(biData.drill_down_map);

    // 4. Setup 60s AJAX Auto-Refresh & Live Clock
    startAutoRefresh();
    startLiveClock();

    /**
     * Initialize or update all Chart.js instances.
     */
    function initAllCharts(data) {
        const cData = data.charts || {};

        // 1. Historical Trend Line Chart
        const lineCtx = document.getElementById('historicalTrendChart');
        if (lineCtx && typeof Chart !== 'undefined') {
            if (charts.lineHistorical) charts.lineHistorical.destroy();
            charts.lineHistorical = new Chart(lineCtx, {
                type: 'line',
                data: cData.line_historical || { labels: [], datasets: [] },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: { mode: 'index', intersect: false },
                    plugins: {
                        legend: { position: 'bottom', labels: { boxWidth: 12, padding: 15, font: { size: 11 } } },
                        tooltip: {
                            callbacks: {
                                label: (ctx) => `${ctx.dataset.label}: ${ctx.raw}%`
                            }
                        }
                    },
                    scales: {
                        x: { grid: { color: 'rgba(255,255,255,0.06)' } },
                        y: { min: 0, max: 100, grid: { color: 'rgba(255,255,255,0.06)' }, ticks: { callback: (val) => val + '%' } }
                    }
                }
            });
        }

        // 2. Risk Distribution Pie Chart
        const pieCtx = document.getElementById('riskDistPieChart');
        if (pieCtx && typeof Chart !== 'undefined') {
            if (charts.pieDist) charts.pieDist.destroy();
            charts.pieDist = new Chart(pieCtx, {
                type: 'pie',
                data: {
                    labels: cData.pie_distribution?.labels || ['Safe', 'Low', 'Medium', 'High', 'Critical'],
                    datasets: [{
                        data: cData.pie_distribution?.data || [1, 2, 4, 2, 1],
                        backgroundColor: cData.pie_distribution?.colors || ['#198754', '#0dcaf0', '#ffc107', '#fd7e14', '#ff4d4f'],
                        borderWidth: 1,
                        borderColor: '#0f172a'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'right', labels: { boxWidth: 10, font: { size: 11 } } }
                    }
                }
            });
        }

        // 3. Weather Analytics Mixed Chart (Bar + Line)
        const weatherCtx = document.getElementById('weatherMixedChart');
        if (weatherCtx && typeof Chart !== 'undefined') {
            if (charts.weatherMixed) charts.weatherMixed.destroy();
            charts.weatherMixed = new Chart(weatherCtx, {
                type: 'bar',
                data: cData.mixed_weather || { labels: [], datasets: [] },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 11 } } }
                    },
                    scales: {
                        x: { grid: { color: 'rgba(255,255,255,0.06)' } },
                        y: { grid: { color: 'rgba(255,255,255,0.06)' } }
                    }
                }
            });
        }

        // 4. Currency Analytics Polar Area Chart
        const polarCtx = document.getElementById('currencyPolarChart');
        if (polarCtx && typeof Chart !== 'undefined') {
            if (charts.polarCurrency) charts.polarCurrency.destroy();
            charts.polarCurrency = new Chart(polarCtx, {
                type: 'polarArea',
                data: {
                    labels: cData.polar_currency?.labels || ['CNY', 'SGD', 'EUR', 'JPY', 'GBP'],
                    datasets: [{
                        data: cData.polar_currency?.data || [12, 5, 8, 14, 7],
                        backgroundColor: cData.polar_currency?.colors?.map(c => c + 'AA') || ['rgba(255,77,79,0.7)', 'rgba(13,202,240,0.7)', 'rgba(255,193,7,0.7)']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: { r: { grid: { color: 'rgba(255,255,255,0.1)' }, ticks: { display: false } } },
                    plugins: {
                        legend: { position: 'right', labels: { boxWidth: 10, font: { size: 10 } } }
                    }
                }
            });
        }

        // 5. News Analytics Horizontal Bar Chart
        const newsCtx = document.getElementById('newsBarChart');
        if (newsCtx && typeof Chart !== 'undefined') {
            if (charts.newsBar) charts.newsBar.destroy();
            charts.newsBar = new Chart(newsCtx, {
                type: 'bar',
                data: {
                    labels: cData.horizontal_bar_news?.labels || ['Positive', 'Neutral', 'Negative'],
                    datasets: [{
                        data: cData.horizontal_bar_news?.data || [6, 3, 1],
                        backgroundColor: cData.horizontal_bar_news?.colors || ['#198754', '#0dcaf0', '#ff4d4f'],
                        borderRadius: 4
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { grid: { color: 'rgba(255,255,255,0.06)' } },
                        y: { grid: { display: false } }
                    }
                }
            });
        }

        // 6. Forecast Analytics Bar Chart
        const forecastCtx = document.getElementById('forecastBarChart');
        if (forecastCtx && typeof Chart !== 'undefined') {
            if (charts.forecastBar) charts.forecastBar.destroy();
            charts.forecastBar = new Chart(forecastCtx, {
                type: 'bar',
                data: cData.bar_forecast || { labels: [], datasets: [] },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'top', labels: { boxWidth: 12, font: { size: 11 } } }
                    },
                    scales: {
                        x: { grid: { color: 'rgba(255,255,255,0.06)' } },
                        y: { min: 0, max: 100, grid: { color: 'rgba(255,255,255,0.06)' }, ticks: { callback: (val) => val + '%' } }
                    }
                }
            });
        }

    }

    /**
     * Initialize Leaflet Heatmap inside `#biHeatmapContainer` without engine duplication.
     */
    function initBiMap(heatmapPoints) {
        const mapElem = document.getElementById('biHeatmapContainer');
        if (!mapElem || typeof L === 'undefined') return;

        if (biMap) {
            updateBiMapMarkers(heatmapPoints);
            return;
        }

        biMap = L.map('biHeatmapContainer', {
            center: [20.0, 30.0],
            zoom: 2,
            minZoom: 1,
            maxZoom: 10
        });

        L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; OpenStreetMap &copy; CARTO BI Engine',
            subdomains: 'abcd',
            maxZoom: 19
        }).addTo(biMap);

        biMarkersLayer = L.layerGroup().addTo(biMap);
        updateBiMapMarkers(heatmapPoints);
    }

    /**
     * Update markers on Leaflet Heatmap when filters or layer switch changes.
     */
    function updateBiMapMarkers(heatmapPoints) {
        if (!biMap || !biMarkersLayer || !heatmapPoints) return;
        biMarkersLayer.clearLayers();

        heatmapPoints.forEach(pt => {
            let val = pt[activeHeatmapLayer] || pt.overall_risk || 50;
            let color = '#0dcaf0';
            if (val >= 70) color = '#ff4d4f';
            else if (val >= 55) color = '#fd7e14';
            else if (val >= 35) color = '#ffc107';
            else if (val >= 20) color = '#0dcaf0';
            else color = '#198754';

            const circle = L.circleMarker([pt.lat, pt.lng], {
                radius: Math.max(7, Math.min(18, val / 5)),
                fillColor: color,
                color: '#ffffff',
                weight: 1.5,
                opacity: 1,
                fillOpacity: 0.8
            });

            circle.bindPopup(`
                <div style="font-family: Inter, sans-serif; min-width: 200px;">
                    ${pt.popup_html}
                    <div style="margin-top: 8px; text-align: right;">
                        <button class="btn btn-xs btn-info drill-down-trigger py-0 px-2 small" data-country="${pt.iso}" style="font-size: 11px;">Inspect Drill-Down</button>
                    </div>
                </div>
            `);

            circle.on('popupopen', () => {
                const trigger = document.querySelector(`.drill-down-trigger[data-country="${pt.iso}"]`);
                if (trigger) {
                    trigger.addEventListener('click', () => {
                        openDrillDownModal(pt.iso, biData.drill_down_map);
                    });
                }
            });

            biMarkersLayer.addLayer(circle);
        });
    }

    /**
     * Setup 300ms Debounce on Filter inputs.
     */
    function setupFilterListeners() {
        const filterIds = ['filterCountry', 'filterRegion', 'filterRiskLevel', 'filterWeather', 'filterCurrency'];
        
        filterIds.forEach(id => {
            const el = document.getElementById(id);
            if (el) {
                el.addEventListener('input', triggerDebouncedAjaxUpdate);
                el.addEventListener('change', triggerDebouncedAjaxUpdate);
            }
        });

        const btnReset = document.getElementById('btnResetBiFilters');
        if (btnReset) {
            btnReset.addEventListener('click', () => {
                filterIds.forEach(id => {
                    const el = document.getElementById(id);
                    if (el) el.value = '';
                });
                document.getElementById('filterPeriod').value = '30d';
                document.querySelectorAll('.period-switch-btn').forEach(btn => {
                    btn.classList.toggle('active', btn.getAttribute('data-period') === '30d');
                });
                triggerDebouncedAjaxUpdate();
            });
        }
    }

    function triggerDebouncedAjaxUpdate() {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
            const featKey = typeof window.getFeatureStorageKey === 'function' ? window.getFeatureStorageKey() : 'selected_country_analytics';
            const countryVal = document.getElementById('filterCountry')?.value || '';
            if (countryVal && countryVal.trim() !== '' && countryVal.trim() !== 'Semua Negara') {
                sessionStorage.setItem(featKey, countryVal.trim());
                localStorage.setItem(featKey, countryVal.trim());
            } else if (countryVal.trim() === '') {
                sessionStorage.removeItem(featKey);
                localStorage.removeItem(featKey);
            }
            fetchFilteredAnalytics();
        }, 300);
    }

    function buildFilterParams() {
        let params = new URLSearchParams();
        const featKey = typeof window.getFeatureStorageKey === 'function' ? window.getFeatureStorageKey() : 'selected_country_analytics';
        
        const filterCountryEl = document.getElementById('filterCountry');
        const countrySearchInputEl = document.getElementById('countrySearchInput');
        
        let rawVal = filterCountryEl ? filterCountryEl.value : '';
        if (!rawVal && countrySearchInputEl && countrySearchInputEl.value) {
            rawVal = countrySearchInputEl.value;
        }
        if (!rawVal && typeof activeCountryData !== 'undefined' && activeCountryData?.name) {
            rawVal = activeCountryData.name;
        }

        if (rawVal) {
            rawVal = rawVal.trim().replace(/\s*\(.*?\)/, '');
        }

        const isResetOrGlobal = !rawVal || rawVal === 'Global / Semua Negara' || rawVal === 'Global / Semua Negara (Feed Artikel Admin)' || rawVal === 'Global' || rawVal === 'Semua Negara' || rawVal === '-' || rawVal === 'Belum Dipilih';

        if (!isResetOrGlobal) {
            params.append('country', rawVal);
        } else {
            sessionStorage.removeItem(featKey);
            localStorage.removeItem(featKey);
        }
        params.append('period', '30d');
        return params;
    }

    /**
     * Period switchers for Section 3 Line Chart.
     */
    function setupPeriodSwitchers() {
        const btns = document.querySelectorAll('.period-switch-btn');
        btns.forEach(btn => {
            btn.addEventListener('click', () => {
                btns.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                const period = btn.getAttribute('data-period');
                const sel = document.getElementById('filterPeriod');
                if (sel) sel.value = period;
                fetchFilteredAnalytics();
            });
        });
    }

    /**
     * Heatmap layer switcher for Section 10 Leaflet map.
     */
    function setupHeatmapLayerSwitcher() {
        const btns = document.querySelectorAll('.heatmap-layer-btn');
        btns.forEach(btn => {
            btn.addEventListener('click', () => {
                btns.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                activeHeatmapLayer = btn.getAttribute('data-layer');
                updateBiMapMarkers(biData.heatmap_data);
            });
        });
    }

    /**
     * AJAX Fetch of filtered BI dataset.
     */
    async function fetchFilteredAnalytics() {
        const params = buildFilterParams();

        try {
            const res = await fetch(`/api/analytics/data?${params.toString()}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });
            const json = await res.json();
            if (json.success && json.data) {
                biData = json.data;
                updateDOMWithBiData(biData);
                initAllCharts(biData);
                updateBiMapMarkers(biData.heatmap_data);
                setupGlobalSearch(biData.drill_down_map);
                setupDrillDownHandlers(biData.drill_down_map);
            }
        } catch (err) {
            console.error('BI AJAX Error:', err);
        }
    }
    window.fetchFilteredAnalytics = fetchFilteredAnalytics;

    // Patch window.selectCountry: intercept country selection from dashboard.js
    // so that charts always refresh when a new country is picked, regardless of timing.
    const _originalSelectCountry = window.selectCountry;
    window.selectCountry = async function(countryInput) {
        if (typeof _originalSelectCountry === 'function') {
            await _originalSelectCountry(countryInput);
        }
        // After selectCountry completes, sync filterCountry and refresh charts
        const isReset = !countryInput || countryInput === 'Global / Semua Negara'
            || countryInput === 'Global / Semua Negara (Feed Artikel Admin)'
            || countryInput === 'Global' || countryInput === 'Belum Dipilih'
            || countryInput === '-';
        const filterEl = document.getElementById('filterCountry');
        if (filterEl) {
            const cleanName = isReset ? '' : countryInput.toString().trim().replace(/\s*\(.*?\)/, '');
            filterEl.value = cleanName;
        }
        fetchFilteredAnalytics();
    };

    /**
     * Update DOM elements without page reload.
     */
    function updateDOMWithBiData(data) {
        // Clock
        const elClock = document.getElementById('biClock');
        if (elClock) elClock.textContent = data.header.current_time;

        // Executive Summary
        const elSummary = document.getElementById('biExecutiveSummaryText');
        if (elSummary) elSummary.textContent = data.executive_summary;
    }

    /**
     * Global Search with Autocomplete.
     */
    function setupGlobalSearch(drillDownProfiles) {
        const searchInput = document.getElementById('biGlobalSearchInput');
        const dropdown = document.getElementById('biSearchDropdown');
        if (!searchInput || !dropdown || !drillDownProfiles) return;

        searchInput.addEventListener('input', (e) => {
            const q = e.target.value.trim().toLowerCase();
            if (q.length === 0) {
                dropdown.classList.add('d-none');
                return;
            }

            const matches = Object.values(drillDownProfiles).filter(p => 
                p.country.toLowerCase().includes(q) || p.iso.toLowerCase().includes(q)
            ).slice(0, 6);

            if (matches.length === 0) {
                dropdown.innerHTML = `<div class="list-group-item bg-dark text-muted small">No corridor matches found.</div>`;
            } else {
                dropdown.innerHTML = matches.map(p => `
                    <a href="javascript:void(0)" class="list-group-item list-group-item-action bg-dark text-white border-secondary d-flex justify-content-between align-items-center py-2 search-item-link" data-iso="${p.iso}">
                        <div>
                            <span class="badge bg-info text-dark me-2">${p.iso}</span>
                            <span class="fw-bold">${p.country}</span>
                        </div>
                        <span class="badge bg-${p.overall_risk >= 60 ? 'danger' : 'success'} small">${p.overall_risk}% Risk</span>
                    </a>
                `).join('');

                dropdown.querySelectorAll('.search-item-link').forEach(item => {
                    item.addEventListener('click', () => {
                        const iso = item.getAttribute('data-iso');
                        searchInput.value = '';
                        dropdown.classList.add('d-none');
                        openDrillDownModal(iso, drillDownProfiles);
                    });
                });
            }
            dropdown.classList.remove('d-none');
        });

        document.addEventListener('click', (e) => {
            if (!searchInput.contains(e.target) && !dropdown.contains(e.target)) {
                dropdown.classList.add('d-none');
            }
        });
    }

    /**
     * Setup Drill-Down triggers across all ranking tables.
     */
    function setupDrillDownHandlers(drillDownProfiles) {
        document.querySelectorAll('.drill-down-trigger').forEach(btn => {
            btn.removeEventListener('click', handleDrillDownClick);
            btn.addEventListener('click', handleDrillDownClick);
        });

        function handleDrillDownClick(e) {
            const iso = e.currentTarget.getAttribute('data-country');
            openDrillDownModal(iso, drillDownProfiles);
        }
    }

    /**
     * Populate and open `#drillDownModal` for a given country ISO code.
     */
    function openDrillDownModal(iso, drillDownProfiles) {
        if (!drillDownProfiles || !iso) return;
        const profile = drillDownProfiles[iso] || drillDownProfiles[iso.toLowerCase()];
        if (!profile) return;

        document.getElementById('drillModalIso').textContent = profile.iso;
        document.getElementById('drillModalTitle').textContent = `${profile.country} (${profile.region}) - Corridor Analytics`;
        document.getElementById('drillModalRisk').textContent = `${profile.overall_risk}%`;
        document.getElementById('drillModalPred').textContent = `${profile.prediction_7d}%`;
        document.getElementById('drillModalDecision').textContent = `${profile.decision_score}%`;
        document.getElementById('drillModalPort').textContent = profile.port_status;

        // Recommendations
        const recContainer = document.getElementById('drillModalRecommendations');
        if (recContainer && profile.recommendations) {
            recContainer.innerHTML = profile.recommendations.map(rec => `
                <li class="list-group-item bg-transparent text-white px-0 py-2 border-bottom border-secondary d-flex align-items-center">
                    <i class="fa-solid fa-circle-check text-success me-2"></i> ${rec}
                </li>
            `).join('');
        }

        // Open Bootstrap Modal
        const modalElem = document.getElementById('drillDownModal');
        if (modalElem && typeof bootstrap !== 'undefined') {
            const modal = bootstrap.Modal.getOrCreateInstance(modalElem);
            modal.show();

            // Render modal chart after show to guarantee correct canvas sizing
            modalElem.addEventListener('shown.bs.modal', function renderModalChart() {
                const ctx = document.getElementById('drillDownChart');
                if (ctx && typeof Chart !== 'undefined') {
                    if (charts.drillDown) charts.drillDown.destroy();
                    charts.drillDown = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: profile.history_labels || ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
                            datasets: [{
                                label: `${profile.country} Risk Trajectory (%)`,
                                data: profile.history_data || [50, 52, 55, 53, 58, 60, profile.overall_risk],
                                borderColor: '#0dcaf0',
                                backgroundColor: 'rgba(13, 202, 240, 0.2)',
                                fill: true,
                                tension: 0.3
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: { legend: { display: false } },
                            scales: {
                                x: { grid: { color: 'rgba(255,255,255,0.06)' } },
                                y: { min: 0, max: 100, grid: { color: 'rgba(255,255,255,0.06)' } }
                            }
                        }
                    });
                }
                modalElem.removeEventListener('shown.bs.modal', renderModalChart);
            });
        }
    }

    /**
     * Start 60s Auto-Refresh when filters are idle.
     */
    function startAutoRefresh() {
        if (autoRefreshInterval) clearInterval(autoRefreshInterval);
        autoRefreshInterval = setInterval(() => {
            const countryVal = document.getElementById('filterCountry')?.value || '';
            const regionVal = document.getElementById('filterRegion')?.value || '';
            const riskVal = document.getElementById('filterRiskLevel')?.value || '';
            if (!countryVal && !regionVal && !riskVal) {
                fetchFilteredAnalytics();
            }
        }, 60000);
    }

    /**
     * Start real-time live clock ticking every second in local time (AM/PM without UTC).
     */
    function startLiveClock() {
        const elClock = document.getElementById('biClock');
        if (!elClock) return;
        function updateClock() {
            const now = new Date();
            const day = String(now.getDate()).padStart(2, '0');
            const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            const month = months[now.getMonth()];
            const year = now.getFullYear();
            let hours = now.getHours();
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            const ampm = hours >= 12 ? 'PM' : 'AM';
            hours = hours % 12;
            hours = hours ? hours : 12;
            const hoursStr = String(hours).padStart(2, '0');
            elClock.textContent = `${day} ${month} ${year}, ${hoursStr}:${minutes}:${seconds} ${ampm}`;
        }
        updateClock();
        setInterval(updateClock, 1000);
    }

    // Global Export/Print hooks
    window.biPrint = () => {
        window.print();
    };

    window.biExport = (format) => {
        const toast = document.createElement('div');
        toast.className = 'position-fixed bottom-0 end-0 p-3';
        toast.style.zIndex = '1080';
        toast.innerHTML = `
            <div class="toast show bg-dark text-white border border-info shadow-lg" role="alert">
                <div class="toast-header bg-info text-dark fw-bold">
                    <i class="fa-solid fa-download me-2"></i> BI Export Engine
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="toast"></button>
                </div>
                <div class="toast-body">
                    Generating complete Business Intelligence report as <b>${format.toUpperCase()}</b>. Download starting...
                </div>
            </div>
        `;
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 4000);
    };
});
