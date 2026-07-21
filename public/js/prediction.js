/**
 * prediction.js
 * Enterprise Prediction Dashboard Interactive Engine & 60s AJAX Auto-Refresh
 * Reuses Chart.js, Leaflet, and ES6 architecture with smooth Scenario Simulation.
 */

document.addEventListener('DOMContentLoaded', () => {
    const initialData = window.INITIAL_PREDICTION_DATA;
    if (!initialData) return;

    // Storage for Chart.js & Leaflet map instances
    let lineChart = null;
    let radarChart = null;
    let gaugeChart = null;
    let leafletMap = null;
    let mapMarkersLayer = null;
    let debounceTimer = null;

    // Initialize charts and map
    initCharts(initialData.charts);
    initMap(initialData.heatmap);

    // Setup interactive Scenario Simulator sliders
    setupSimulatorListeners();

    // Setup 60s AJAX auto-refresh
    setInterval(() => {
        // Only auto-refresh if user hasn't active non-zero sliders
        const w = parseInt(document.getElementById('sliderWeather')?.value || 0);
        const i = parseInt(document.getElementById('sliderInflation')?.value || 0);
        const c = parseInt(document.getElementById('sliderCurrency')?.value || 0);
        const n = parseInt(document.getElementById('sliderNews')?.value || 0);
        if (w === 0 && i === 0 && c === 0 && n === 0) {
            fetchAndRefreshData();
        }
    }, 60000);

    /**
     * Initialize Chart.js charts with dark theme aesthetics
     */
    function initCharts(chartData) {
        if (typeof Chart === 'undefined') {
            console.error('Chart.js library not loaded!');
            return;
        }

        Chart.defaults.color = '#cbd5e1';
        Chart.defaults.borderColor = 'rgba(255, 255, 255, 0.08)';
        Chart.defaults.font.family = "'Inter', sans-serif";

        // 1. Prediction Forecast Line Chart
        const lineEl = document.getElementById('predictionLineChart');
        if (lineEl) {
            const ctxLine = lineEl.getContext('2d');
            const gradLine = ctxLine.createLinearGradient(0, 0, 0, 300);
            gradLine.addColorStop(0, 'rgba(23, 162, 184, 0.45)');
            gradLine.addColorStop(1, 'rgba(23, 162, 184, 0.01)');

            lineChart = new Chart(ctxLine, {
                type: 'line',
                data: {
                    labels: chartData.line_chart.labels,
                    datasets: [{
                        label: 'Projected Risk (%)',
                        data: chartData.line_chart.data,
                        borderColor: '#17a2b8',
                        backgroundColor: gradLine,
                        borderWidth: 3,
                        pointBackgroundColor: '#17a2b8',
                        pointBorderColor: '#ffffff',
                        pointHoverRadius: 6,
                        fill: true,
                        tension: 0.35
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: 'rgba(27, 30, 37, 0.95)',
                            titleColor: '#ffffff',
                            bodyColor: '#17a2b8',
                            borderColor: 'rgba(255, 255, 255, 0.15)',
                            borderWidth: 1,
                            padding: 12
                        }
                    },
                    scales: {
                        y: {
                            min: 0,
                            max: 100,
                            grid: { color: 'rgba(255, 255, 255, 0.06)' },
                            ticks: { callback: val => val + '%' }
                        },
                        x: {
                            grid: { display: false }
                        }
                    }
                }
            });
        }

        // 2. Prediction Factors Breakdown Radar Chart
        const radarEl = document.getElementById('predictionRadarChart');
        if (radarEl) {
            radarChart = new Chart(radarEl.getContext('2d'), {
                type: 'radar',
                data: {
                    labels: chartData.radar_chart.labels,
                    datasets: [
                        {
                            label: 'Current Baseline (%)',
                            data: chartData.radar_chart.current_data,
                            borderColor: '#6c757d',
                            backgroundColor: 'rgba(108, 117, 125, 0.2)',
                            borderWidth: 2
                        },
                        {
                            label: 'Predicted Value (%)',
                            data: chartData.radar_chart.predicted_data,
                            borderColor: '#17a2b8',
                            backgroundColor: 'rgba(23, 162, 184, 0.35)',
                            borderWidth: 2.5
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: { color: '#ffffff', font: { size: 11 } }
                        }
                    },
                    scales: {
                        r: {
                            min: 0,
                            max: 100,
                            ticks: { display: false },
                            grid: { color: 'rgba(255, 255, 255, 0.1)' },
                            angleLines: { color: 'rgba(255, 255, 255, 0.1)' },
                            pointLabels: { color: '#ffffff', font: { weight: '600', size: 11 } }
                        }
                    }
                }
            });
        }

        // 3. Shipping Delay Gauge Chart (Semi-circle Doughnut)
        const gaugeEl = document.getElementById('delayGaugeChart');
        if (gaugeEl) {
            gaugeChart = new Chart(gaugeEl.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: ['Delay Probability Score', 'Remaining Buffer'],
                    datasets: [{
                        data: [chartData.gauge_chart.score, chartData.gauge_chart.remain],
                        backgroundColor: [
                            getHexColor(chartData.gauge_chart.color),
                            'rgba(255, 255, 255, 0.08)'
                        ],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    circumference: 180,
                    rotation: -90,
                    cutout: '75%',
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: ctx => `${ctx.label}: ${ctx.raw}%`
                            }
                        }
                    }
                }
            });
        }
    }

    /**
     * Initialize Leaflet Heatmap
     */
    function initMap(heatmapData) {
        const mapEl = document.getElementById('predictionHeatmap');
        if (!mapEl || typeof L === 'undefined') return;

        // Initialize map centered on global maritime corridor
        leafletMap = L.map('predictionHeatmap').setView([20.0, 80.0], 2);

        // Light tile layer
        L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; OpenStreetMap contributors &copy; CARTO',
            subdomains: 'abcd',
            maxZoom: 18
        }).addTo(leafletMap);

        mapMarkersLayer = L.layerGroup().addTo(leafletMap);
        updateMapMarkers(heatmapData);
    }

    /**
     * Update markers on Leaflet Heatmap
     */
    function updateMapMarkers(heatmapData) {
        if (!mapMarkersLayer || !heatmapData) return;
        mapMarkersLayer.clearLayers();

        heatmapData.forEach(row => {
            const circle = L.circleMarker([row.lat, row.lng], {
                radius: Math.max(7, Math.min(16, row.future_risk / 5)),
                fillColor: row.color,
                color: '#ffffff',
                weight: 1.5,
                opacity: 0.9,
                fillOpacity: 0.75
            });

            const popupContent = `
                <div style="background: #1b1e25; color: #fff; padding: 10px; border-radius: 6px; font-family: 'Inter', sans-serif; min-width: 170px;">
                    <div style="font-weight: 700; font-size: 13px; color: #17a2b8; margin-bottom: 4px;">
                        ${escapeHtml(row.name)} (${escapeHtml(row.iso)})
                    </div>
                    <div style="font-size: 11px; color: #cbd5e1;">Current Risk: <strong>${row.current_risk}%</strong></div>
                    <div style="font-size: 11px; color: #fff;">Future 7D Risk: <strong style="color: ${row.color};">${row.future_risk}%</strong></div>
                    <div style="font-size: 11px; margin-top: 4px; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 4px;">
                        Trend: <strong>${escapeHtml(row.trend)}</strong>
                    </div>
                </div>
            `;
            circle.bindPopup(popupContent);
            mapMarkersLayer.addLayer(circle);
        });
    }

    /**
     * Setup Scenario Simulator slider event listeners
     */
    function setupSimulatorListeners() {
        const sliders = ['sliderWeather', 'sliderInflation', 'sliderCurrency', 'sliderNews'];
        sliders.forEach(id => {
            const el = document.getElementById(id);
            if (el) {
                el.addEventListener('input', () => {
                    updateSliderLabels();
                    scheduleDebouncedFetch();
                });
            }
        });

        const resetBtn = document.getElementById('btnResetSimulation');
        if (resetBtn) {
            resetBtn.addEventListener('click', () => {
                sliders.forEach(id => {
                    const el = document.getElementById(id);
                    if (el) el.value = 0;
                });
                updateSliderLabels();
                fetchAndRefreshData();
            });
        }
    }

    function updateSliderLabels() {
        const w = document.getElementById('sliderWeather')?.value || 0;
        const i = document.getElementById('sliderInflation')?.value || 0;
        const c = document.getElementById('sliderCurrency')?.value || 0;
        const n = document.getElementById('sliderNews')?.value || 0;

        setLabelText('labelWeatherDelta', w);
        setLabelText('labelInflationDelta', i);
        setLabelText('labelCurrencyDelta', c);
        setLabelText('labelNewsDelta', n);
    }

    function setLabelText(id, val) {
        const el = document.getElementById(id);
        if (!el) return;
        const v = parseInt(val);
        el.textContent = (v > 0 ? '+' : '') + v + '%';
        el.className = v > 0 ? 'text-danger fw-bold' : (v < 0 ? 'text-success fw-bold' : 'text-info fw-bold');
    }

    function scheduleDebouncedFetch() {
        if (debounceTimer) clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
            fetchAndRefreshData();
        }, 300);
    }

    /**
     * Fetch updated prediction data from JSON endpoint and smoothly refresh UI
     */
    async function fetchAndRefreshData() {
        const w = document.getElementById('sliderWeather')?.value || 0;
        const i = document.getElementById('sliderInflation')?.value || 0;
        const c = document.getElementById('sliderCurrency')?.value || 0;
        const n = document.getElementById('sliderNews')?.value || 0;

        const url = `/api/prediction/data?weather_delta=${w}&inflation_delta=${i}&currency_delta=${c}&news_delta=${n}`;

        try {
            const response = await fetch(url, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!response.ok) return;
            const resData = await response.json();
            if (!resData.success || !resData.data) return;

            const d = resData.data;

            // 1. Update Header
            const clockEl = document.getElementById('predictionClock');
            if (clockEl && d.header.timestamp) clockEl.textContent = d.header.timestamp;

            // 2. Update KPI Cards
            updateElementText('kpiCurrentRisk', `${d.kpi_cards.current_risk}%`);
            updateBadge('kpiCurrentBadge', d.kpi_cards.current_risk_color, 'Baseline Global');

            updateElementText('kpiTomorrowRisk', `${d.kpi_cards.tomorrow_risk}%`);
            updateBadge('kpiTomorrowBadge', d.kpi_cards.tomorrow_risk_color, '+24 Hours Projection');

            updateElementText('kpi7dRisk', `${d.kpi_cards.future_7d_risk}%`);
            updateBadge('kpi7dBadge', d.kpi_cards.future_7d_risk_color, 'Moving Average Target');

            updateElementText('kpi30dRisk', `${d.kpi_cards.future_30d_risk}%`);
            updateBadge('kpi30dBadge', d.kpi_cards.future_30d_risk_color, 'Long-Term Outlook');

            updateElementText('kpiConfidence', `${d.kpi_cards.confidence_score}%`);
            updateElementText('kpiDelayProb', d.kpi_cards.shipping_delay_prob);
            updateBadge('kpiDelayBadge', d.kpi_cards.shipping_delay_color, 'Maritime Congestion Index');

            updateElementText('kpiCurrencyStab', `${d.kpi_cards.currency_stability}%`);
            updateElementText('kpiWeatherStab', `${d.kpi_cards.weather_stability}%`);

            // 3. Update Trend Analysis Section
            const trendBadge = document.getElementById('trendBadge');
            if (trendBadge) {
                trendBadge.textContent = `${d.trend_analysis.status} ${d.trend_analysis.arrow}`;
                trendBadge.className = `badge bg-${d.trend_analysis.color} px-3 py-2 fs-6`;
            }
            updateElementText('trendArrowBig', d.trend_analysis.arrow);
            const trendArrowEl = document.getElementById('trendArrowBig');
            if (trendArrowEl) trendArrowEl.className = `display-1 fw-bold text-${d.trend_analysis.color} mb-2`;

            updateElementText('trendStatusText', `${d.trend_analysis.status} Outlook`);
            updateElementText('trendDiffText', `Projected Shift: ${d.trend_analysis.difference >= 0 ? '+' : ''}${d.trend_analysis.difference}% over 7 days`);
            updateElementText('trendExplanationText', d.trend_analysis.explanation);

            // 4. Update Timeline Section
            const timelineContainer = document.getElementById('timelineContainer');
            if (timelineContainer && d.timeline) {
                timelineContainer.innerHTML = d.timeline.map(item => `
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                        <div class="p-3 h-100 d-flex flex-column justify-content-between" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08); border-radius: 10px; border-left: 4px solid var(--bs-${item.color});">
                            <div>
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="fw-bold text-info small">${escapeHtml(item.label)}</span>
                                    <span class="badge bg-${item.color}">${item.score}%</span>
                                </div>
                                <div class="small text-muted mb-2">${escapeHtml(item.date)} &bull; <strong class="text-white">${escapeHtml(item.level)}</strong></div>
                                <p class="small text-light mb-0" style="font-size: 11.5px; opacity: 0.85;">${escapeHtml(item.reason)}</p>
                            </div>
                        </div>
                    </div>
                `).join('');
            }

            // 5. Update Factors Table
            const factorsBody = document.getElementById('factorsTableBody');
            if (factorsBody && d.factors) {
                factorsBody.innerHTML = d.factors.map(fac => `
                    <tr class="border-bottom border-secondary" style="border-color: rgba(255,255,255,0.05) !important;">
                        <td class="fw-semibold text-white small">
                            <i class="fa-solid ${fac.icon} me-1"></i> ${escapeHtml(fac.factor)}
                        </td>
                        <td class="text-center small">${fac.current}%</td>
                        <td class="text-center small fw-bold text-info">${fac.predicted}%</td>
                        <td class="text-end fw-bold text-warning">${fac.impact}%</td>
                    </tr>
                `).join('');
            }

            // 6. Update Shipping Delay Details
            const badgeUpper = document.getElementById('delayBadgeUpper');
            if (badgeUpper && d.shipping_delay) {
                badgeUpper.textContent = `${d.shipping_delay.level} Probability`;
                badgeUpper.className = `badge bg-${d.shipping_delay.color} px-2 py-1`;
            }
            updateElementText('delayDaysText', d.shipping_delay.days);
            const daysTextEl = document.getElementById('delayDaysText');
            if (daysTextEl) daysTextEl.className = `display-6 fw-bold text-${d.shipping_delay.color} mb-1`;
            updateElementText('delayScoreText', `${d.shipping_delay.score}%`);

            const levelBadge = document.getElementById('delayLevelBadge');
            if (levelBadge) {
                levelBadge.textContent = `Status: ${d.shipping_delay.level}`;
                levelBadge.className = `badge bg-${d.shipping_delay.color}-subtle text-${d.shipping_delay.color} border border-${d.shipping_delay.color} px-3 py-1`;
            }

            // 7. Update Summary
            updateElementText('predictionSummaryText', d.summary);

            // 8. Update Ranking Table
            const rankingBody = document.getElementById('rankingTableBody');
            if (rankingBody && d.ranking_table) {
                rankingBody.innerHTML = d.ranking_table.map(row => {
                    const diffText = `${row.difference > 0 ? '+' : ''}${row.difference}%`;
                    const diffColor = row.difference > 0 ? 'text-danger' : (row.difference < 0 ? 'text-success' : 'text-muted');
                    const futureColor = row.future_risk >= 65 ? 'danger' : (row.future_risk >= 35 ? 'warning' : 'success');
                    return `
                        <tr class="border-bottom border-secondary" style="border-color: rgba(255,255,255,0.05) !important;">
                            <td class="fw-bold text-info">#${row.rank}</td>
                            <td class="fw-bold text-white">${escapeHtml(row.name)} <span class="text-muted small">(${escapeHtml(row.iso)})</span></td>
                            <td class="text-center">${row.current_risk}%</td>
                            <td class="text-center fw-bold text-${futureColor}">${row.future_risk}%</td>
                            <td class="text-center small ${diffColor}">${diffText}</td>
                            <td class="text-center">
                                <span class="badge bg-${row.trend_color}">${row.trend_arrow} ${escapeHtml(row.trend)}</span>
                            </td>
                            <td class="text-end small text-light">${escapeHtml(row.recommendation)}</td>
                        </tr>
                    `;
                }).join('');
            }

            // 9. Update Chart Datasets cleanly
            if (lineChart && d.charts.line_chart) {
                lineChart.data.labels = d.charts.line_chart.labels;
                lineChart.data.datasets[0].data = d.charts.line_chart.data;
                lineChart.update('none');
            }

            if (radarChart && d.charts.radar_chart) {
                radarChart.data.labels = d.charts.radar_chart.labels;
                radarChart.data.datasets[0].data = d.charts.radar_chart.current_data;
                radarChart.data.datasets[1].data = d.charts.radar_chart.predicted_data;
                radarChart.update('none');
            }

            if (gaugeChart && d.charts.gauge_chart) {
                gaugeChart.data.datasets[0].data = [d.charts.gauge_chart.score, d.charts.gauge_chart.remain];
                gaugeChart.data.datasets[0].backgroundColor[0] = getHexColor(d.charts.gauge_chart.color);
                gaugeChart.update('none');
            }

            // 10. Update Leaflet Heatmap Markers
            if (d.heatmap) {
                updateMapMarkers(d.heatmap);
            }

        } catch (e) {
            console.warn('Prediction AJAX update encountered delay:', e);
        }
    }

    function updateElementText(id, text) {
        const el = document.getElementById(id);
        if (el && text !== undefined) el.textContent = text;
    }

    function updateBadge(id, color, text) {
        const el = document.getElementById(id);
        if (el) {
            if (text !== undefined) el.textContent = text;
            if (color !== undefined) el.className = `badge bg-${color}`;
        }
    }

    function getHexColor(statusColor) {
        if (statusColor === 'danger') return '#dc3545';
        if (statusColor === 'warning') return '#ffc107';
        if (statusColor === 'info') return '#17a2b8';
        return '#198754';
    }

    function escapeHtml(str) {
        if (!str) return '';
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }
});
