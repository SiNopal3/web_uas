/**
 * executive.js
 * Executive Dashboard Interactive Visualization & 60s AJAX Auto-Refresh Engine
 * Reuses Chart.js, Vanilla ES6, and Glassmorphism Dark Theme aesthetics.
 */

document.addEventListener('DOMContentLoaded', () => {
    const initialData = window.INITIAL_EXECUTIVE_DATA;
    if (!initialData) return;

    // Chart instances storage
    let trendChart = null;
    let distChart = null;
    let compChart = null;

    // Initialize all charts when DOM loads
    initCharts(initialData.charts);

    // Set up AJAX Auto-Refresh every 60 seconds (60,000 ms)
    setInterval(refreshExecutiveData, 60000);

    /**
     * Initialize Chart.js charts with dark theme configuration
     */
    function initCharts(chartData) {
        if (typeof Chart === 'undefined') {
            console.error('Chart.js library not loaded!');
            return;
        }

        // Global Chart Defaults for Dark Theme
        Chart.defaults.color = '#cbd5e1';
        Chart.defaults.borderColor = 'rgba(255, 255, 255, 0.08)';
        Chart.defaults.font.family = "'Inter', sans-serif";

        // 1. Global Risk Trend (Line Chart)
        const trendEl = document.getElementById('globalRiskTrendChart');
        if (trendEl) {
            const ctxTrend = trendEl.getContext('2d');
            const gradientTrend = ctxTrend.createLinearGradient(0, 0, 0, 300);
            gradientTrend.addColorStop(0, 'rgba(224, 180, 114, 0.45)');
            gradientTrend.addColorStop(1, 'rgba(224, 180, 114, 0.01)');

            trendChart = new Chart(ctxTrend, {
                type: 'line',
                data: {
                    labels: chartData.global_risk_trend.labels,
                    datasets: [{
                        label: 'Global Risk Index (%)',
                        data: chartData.global_risk_trend.data,
                        borderColor: '#e0b472',
                        backgroundColor: gradientTrend,
                        borderWidth: 3,
                        pointBackgroundColor: '#e0b472',
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
                            bodyColor: '#e0b472',
                            borderColor: 'rgba(255, 255, 255, 0.15)',
                            borderWidth: 1,
                            padding: 12
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: false,
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

        // 2. World Risk Distribution (Doughnut Chart)
        const distEl = document.getElementById('worldRiskDistChart');
        if (distEl) {
            distChart = new Chart(distEl.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: chartData.world_risk_distribution.labels,
                    datasets: [{
                        data: chartData.world_risk_distribution.data,
                        backgroundColor: [
                            '#198754', // Low Risk (Green)
                            '#ffc107', // Medium Risk (Yellow)
                            '#dc3545', // High Risk (Red)
                            '#842029'  // Critical (Dark Red)
                        ],
                        borderColor: '#151921',
                        borderWidth: 3,
                        hoverOffset: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                color: '#ffffff',
                                font: { size: 11, weight: '500' },
                                padding: 12
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(27, 30, 37, 0.95)',
                            titleColor: '#ffffff',
                            bodyColor: '#ffffff',
                            borderColor: 'rgba(255, 255, 255, 0.15)',
                            borderWidth: 1
                        }
                    },
                    cutout: '68%'
                }
            });
        }

        // 3. Country Risk Comparison (Horizontal Bar Chart)
        const compEl = document.getElementById('countryRiskCompChart');
        if (compEl) {
            const barColors = chartData.country_risk_comparison.data.map(val => {
                if (val >= 65) return '#dc3545';
                if (val >= 35) return '#ffc107';
                return '#198754';
            });

            compChart = new Chart(compEl.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: chartData.country_risk_comparison.labels,
                    datasets: [{
                        label: 'Risk Score (%)',
                        data: chartData.country_risk_comparison.data,
                        backgroundColor: barColors,
                        borderRadius: 6,
                        borderWidth: 0
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: 'rgba(27, 30, 37, 0.95)',
                            titleColor: '#ffffff',
                            bodyColor: '#ffffff',
                            borderColor: 'rgba(255, 255, 255, 0.15)',
                            borderWidth: 1,
                            callbacks: {
                                label: ctx => `Score: ${ctx.raw}%`
                            }
                        }
                    },
                    scales: {
                        x: {
                            beginAtZero: true,
                            max: 100,
                            grid: { color: 'rgba(255, 255, 255, 0.06)' },
                            ticks: { callback: val => val + '%' }
                        },
                        y: {
                            grid: { display: false },
                            ticks: { color: '#ffffff', font: { weight: '600' } }
                        }
                    }
                }
            });
        }
    }

    /**
     * AJAX Auto-Refresh every 60 seconds without full page reload
     */
    async function refreshExecutiveData() {
        try {
            const response = await fetch('/api/executive/data', {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!response.ok) return;
            const resData = await response.json();
            if (!resData.success || !resData.data) return;

            const d = resData.data;

            // 1. Update Header Timestamp
            const clockEl = document.getElementById('executiveClock');
            if (clockEl && d.header.timestamp) {
                clockEl.textContent = d.header.timestamp;
            }

            // 2. Update KPI Cards
            const avgRiskEl = document.getElementById('kpiAvgRisk');
            const avgRiskBadgeEl = document.getElementById('kpiAvgRiskBadge');
            if (avgRiskEl && d.kpi_cards) {
                avgRiskEl.textContent = `${d.kpi_cards.average_global_risk}%`;
                if (avgRiskBadgeEl) {
                    avgRiskBadgeEl.textContent = d.kpi_cards.average_global_risk_badge;
                    avgRiskBadgeEl.className = `badge bg-${d.kpi_cards.average_global_risk_color} px-2 py-1`;
                }
            }

            const highCountryEl = document.getElementById('kpiHighRiskCountry');
            const highScoreEl = document.getElementById('kpiHighRiskScore');
            if (highCountryEl && d.kpi_cards.highest_risk_country) {
                highCountryEl.textContent = d.kpi_cards.highest_risk_country.name;
                highScoreEl.textContent = `Score: ${d.kpi_cards.highest_risk_country.score}%`;
            }

            const safeCountryEl = document.getElementById('kpiSafestCountry');
            const safeScoreEl = document.getElementById('kpiSafestScore');
            if (safeCountryEl && d.kpi_cards.safest_country) {
                safeCountryEl.textContent = d.kpi_cards.safest_country.name;
                safeScoreEl.textContent = `Score: ${d.kpi_cards.safest_country.score}%`;
            }

            const avgInfEl = document.getElementById('kpiAvgInflation');
            if (avgInfEl && d.kpi_cards.average_inflation !== undefined) {
                avgInfEl.textContent = `${d.kpi_cards.average_inflation}%`;
            }

            const strongCurEl = document.getElementById('kpiStrongCurrency');
            const strongCounEl = document.getElementById('kpiStrongCountry');
            if (strongCurEl && d.kpi_cards.strongest_currency) {
                strongCurEl.textContent = d.kpi_cards.strongest_currency.currency;
                strongCounEl.textContent = d.kpi_cards.strongest_currency.country;
            }

            const totalCounEl = document.getElementById('kpiTotalCountries');
            if (totalCounEl && d.kpi_cards.total_monitored !== undefined) {
                totalCounEl.textContent = d.kpi_cards.total_monitored;
            }

            // 3. Update Chart Datasets smoothly
            if (trendChart && d.charts.global_risk_trend) {
                trendChart.data.labels = d.charts.global_risk_trend.labels;
                trendChart.data.datasets[0].data = d.charts.global_risk_trend.data;
                trendChart.update('none');
            }

            if (distChart && d.charts.world_risk_distribution) {
                distChart.data.labels = d.charts.world_risk_distribution.labels;
                distChart.data.datasets[0].data = d.charts.world_risk_distribution.data;
                distChart.update('none');
            }

            if (compChart && d.charts.country_risk_comparison) {
                compChart.data.labels = d.charts.country_risk_comparison.labels;
                compChart.data.datasets[0].data = d.charts.country_risk_comparison.data;
                compChart.data.datasets[0].backgroundColor = d.charts.country_risk_comparison.data.map(val => {
                    if (val >= 65) return '#dc3545';
                    if (val >= 35) return '#ffc107';
                    return '#198754';
                });
                compChart.update('none');
            }

            // 4. Update Tables (High Risk & Safest)
            const highTableBody = document.getElementById('highRiskTableBody');
            if (highTableBody && d.top_high_risk_countries) {
                highTableBody.innerHTML = d.top_high_risk_countries.map(row => `
                    <tr class="border-bottom border-secondary" style="border-color: rgba(255,255,255,0.05) !important;">
                        <td class="fw-bold text-warning">#${row.rank}</td>
                        <td class="fw-bold text-white">${escapeHtml(row.name)} <span class="text-muted small">(${escapeHtml(row.iso)})</span></td>
                        <td class="text-center">${row.weather_risk}%</td>
                        <td class="text-center">${row.inflation_risk}%</td>
                        <td class="text-center">${row.currency_risk}%</td>
                        <td class="text-center">${row.news_risk}%</td>
                        <td class="text-center fw-bold text-danger">${row.final_risk_score}%</td>
                        <td class="text-end">
                            <span class="badge bg-${row.status_color}">${escapeHtml(row.status)}</span>
                        </td>
                    </tr>
                `).join('');
            }

            const safeTableBody = document.getElementById('safestTableBody');
            if (safeTableBody && d.top_safest_countries) {
                safeTableBody.innerHTML = d.top_safest_countries.map(row => `
                    <tr class="border-bottom border-secondary" style="border-color: rgba(255,255,255,0.05) !important;">
                        <td class="fw-bold text-success">#${row.rank}</td>
                        <td class="fw-bold text-white">${escapeHtml(row.name)} <span class="text-muted small">(${escapeHtml(row.iso)})</span></td>
                        <td class="text-center fw-bold text-success">${row.final_risk_score}%</td>
                        <td class="text-end">
                            <span class="badge bg-${row.status_color}">${escapeHtml(row.status)}</span>
                        </td>
                    </tr>
                `).join('');
            }

            // 5. Update Alert Center
            const alertContainer = document.getElementById('alertCenterContainer');
            if (alertContainer && d.alerts) {
                alertContainer.innerHTML = d.alerts.map(alert => `
                    <div class="alert alert-${alert.color} bg-${alert.color}-subtle border border-${alert.color} text-white d-flex align-items-start mb-2 py-2 px-3 shadow-sm" role="alert" style="border-radius: 10px; background: rgba(${alert.color === 'danger' ? '220,53,69' : (alert.color === 'warning' ? '255,193,7' : '25,135,84')}, 0.18) !important;">
                        <i class="fa-solid ${alert.color === 'danger' ? 'fa-circle-exclamation' : (alert.color === 'warning' ? 'fa-triangle-exclamation' : 'fa-check-circle')} mt-1 me-2 text-${alert.color} fs-6"></i>
                        <div>
                            <div class="fw-bold small text-${alert.color} text-uppercase">${escapeHtml(alert.type)} &bull; ${escapeHtml(alert.level)}</div>
                            <div class="small text-white mt-1">${escapeHtml(alert.message)}</div>
                        </div>
                    </div>
                `).join('');
            }

            // 6. Update Executive Summary
            const summaryEl = document.getElementById('executiveSummaryText');
            if (summaryEl && d.executive_summary) {
                summaryEl.textContent = d.executive_summary;
            }

        } catch (e) {
            console.warn('AJAX Auto-Refresh encountered transient network delay:', e);
        }
    }

    /**
     * Helper to prevent XSS during DOM updates
     */
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
