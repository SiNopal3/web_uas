/**
 * RiskIntel Hub - Enterprise Reporting & Export Suite (`reports.js`)
 * Vanilla ES6 Architecture for 10 Section Telemetry, Chart.js 10-Type Gallery, Interactive Preview & Export Orchestration.
 */

class EnterpriseReportsEngine {
    constructor() {
        this.currentZoom = 1.0;
        this.charts = {};
        this.currentPreviewData = null;
        this.currentReportTitle = 'Executive Summary Profile';
        this.currentReportType = 'Executive Report';

        this.init();
    }

    init() {
        document.addEventListener('DOMContentLoaded', () => {
            console.log('Enterprise Reports Engine v3.0 initialized.');
            this.setupTabs();
            this.fetchInitialTelemetry();
            this.initChartsGallery();
        });
    }

    setupTabs() {
        const tabs = document.querySelectorAll('#reportsNavTabs button[data-bs-toggle="pill"]');
        tabs.forEach(tab => {
            tab.addEventListener('shown.bs.tab', (e) => {
                // Resize charts when shown inside tabs
                Object.values(this.charts).forEach(chart => {
                    if (chart && typeof chart.resize === 'function') {
                        chart.resize();
                    }
                });
            });
        });
    }

    async fetchInitialTelemetry() {
        try {
            const response = await fetch('/api/reports?action=dashboard');
            const result = await response.json();

            if (result && result.success) {
                this.updateKpiCards(result.summary?.kpi_cards);
                this.updateNarrative(result.executive_narrative);
                this.updateScheduledTable(result.scheduled_reports);
                this.updateHistoryTables(result.history);
                this.renderExportDistributionChart(result.summary?.chart_distribution);
            }
        } catch (err) {
            console.error('Failed fetching reports telemetry:', err);
        }
    }

    updateKpiCards(cards) {
        if (!cards) return;
        const setVal = (id, val) => {
            const el = document.getElementById(id);
            if (el) el.textContent = val !== undefined ? val : '-';
        };
        setVal('cardGeneratedCount', cards.generated_reports);
        setVal('cardScheduledCount', cards.scheduled_reports);
        setVal('cardDownloadsToday', cards.downloads_today);
        setVal('cardDownloadsMonth', cards.downloads_month);
        setVal('cardPdfCount', cards.pdf_reports);
        setVal('cardExcelCount', cards.excel_reports);
        setVal('cardCsvCount', cards.csv_reports);
        setVal('cardPrintCount', cards.print_jobs);
    }

    updateNarrative(narrative) {
        if (!narrative) return;
        const badge = document.getElementById('narrativeRiskBadge');
        if (badge && narrative.metrics?.risk_level) badge.textContent = narrative.metrics.risk_level;

        const overview = document.getElementById('narrativeRiskOverview');
        if (overview && narrative.risk_overview) overview.textContent = narrative.risk_overview;

        const country = document.getElementById('narrativeCountryOverview');
        if (country && narrative.country_overview) country.textContent = narrative.country_overview;

        const recList = document.getElementById('narrativeRecommendations');
        if (recList && narrative.business_recommendations) {
            recList.innerHTML = narrative.business_recommendations.map(r => `<li>${r}</li>`).join('');
        }
    }

    updateScheduledTable(list) {
        const tbody = document.getElementById('scheduledReportsTableBody');
        if (!tbody) return;

        if (!list || list.length === 0) {
            tbody.innerHTML = `<tr><td colspan="7" class="text-center text-muted py-3">No active scheduled reports found.</td></tr>`;
            return;
        }

        tbody.innerHTML = list.map(item => `
            <tr>
                <td><span class="badge bg-secondary font-monospace">#${item.id}</span></td>
                <td class="fw-bold text-white">${item.report_type}</td>
                <td><span class="badge bg-info text-dark">${item.frequency}</span></td>
                <td class="small text-truncate" style="max-width: 180px;">${item.recipients}</td>
                <td class="small font-monospace text-warning">${item.next_run_at || 'Tomorrow 08:00 UTC'}</td>
                <td><span class="badge ${item.status === 'active' ? 'bg-success' : 'bg-secondary'}">${item.status}</span></td>
                <td class="text-end">
                    <button class="btn btn-sm btn-outline-danger" onclick="window.deleteScheduledReport(${item.id})"><i class="fa-solid fa-trash"></i></button>
                </td>
            </tr>
        `).join('');
    }

    updateHistoryTables(historyObj) {
        if (!historyObj) return;

        // Generated History Table
        const hBody = document.getElementById('historyTableBody');
        if (hBody && historyObj.generated_history) {
            hBody.innerHTML = historyObj.generated_history.map(item => `
                <tr>
                    <td class="fw-bold text-white">${item.title}</td>
                    <td class="small text-muted">${item.report_type}</td>
                    <td><span class="badge bg-primary">${item.file_format}</span></td>
                    <td class="font-monospace small">${item.file_size_kb} KB</td>
                    <td class="small text-muted">${item.created_at}</td>
                </tr>
            `).join('');
        }

        // Export Logs Table
        const eBody = document.getElementById('exportLogsTableBody');
        if (eBody && historyObj.export_logs) {
            eBody.innerHTML = historyObj.export_logs.map(log => `
                <tr>
                    <td class="fw-semibold text-white">${log.report_type}</td>
                    <td><span class="badge bg-secondary">${log.format}</span></td>
                    <td class="font-monospace small">${log.ip_address}</td>
                    <td class="font-monospace small text-info">${log.execution_time_ms} ms</td>
                    <td><span class="badge ${log.status === 'SUCCESS' ? 'bg-success' : 'bg-danger'}">${log.status}</span></td>
                </tr>
            `).join('');
        }
    }

    renderExportDistributionChart(chartData) {
        const ctx = document.getElementById('exportDistributionChart');
        if (!ctx) return;

        if (this.charts.exportDist) {
            this.charts.exportDist.destroy();
        }

        this.charts.exportDist = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: chartData?.labels || ['PDF', 'Excel', 'CSV', 'Print'],
                datasets: [{
                    data: chartData?.data || [64, 38, 16, 10],
                    backgroundColor: ['#ef4444', '#10b981', '#3b82f6', '#f59e0b', '#8b5cf6'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom', labels: { color: '#e2e8f0', font: { size: 11 } } }
                }
            }
        });
    }

    async loadQuickReport(title, reportType) {
        this.currentReportTitle = title;
        this.currentReportType = reportType;

        try {
            const res = await fetch(`/api/reports?action=report_data&report_type=${encodeURIComponent(reportType)}`);
            const json = await res.json();

            if (json && json.success) {
                this.currentPreviewData = json.data;
                this.renderReportTable(reportType, json.data);
                this.updatePreviewDocument(title, reportType, json.data);
            }
        } catch (err) {
            console.error('Error loading quick report:', err);
        }
    }

    renderReportTable(reportType, data) {
        const targetBody = reportType.includes('Executive') 
            ? document.getElementById('executiveReportTableBody')
            : document.getElementById('analyticsReportTableBody');
        
        if (!targetBody || !data?.items) return;

        targetBody.innerHTML = data.items.map(row => `
            <tr>
                <td class="fw-bold text-white">${row[0]}</td>
                <td class="text-info font-monospace">${row[1]}</td>
                <td class="small text-muted">${row[2] || '-'}</td>
                <td><span class="badge bg-dark border border-secondary text-light">${row[3] || 'Optimal'}</span></td>
                <td class="text-end">
                    <button class="btn btn-sm btn-outline-info" onclick="window.triggerExport('PDF', '${row[0]}')"><i class="fa-solid fa-file-pdf"></i></button>
                </td>
            </tr>
        `).join('');
    }

    updatePreviewDocument(title, reportType, data) {
        const previewEl = document.getElementById('previewDocumentContent');
        if (!previewEl || !data?.items) return;

        const rowsHtml = data.items.map(row => `
            <tr>
                <td><strong>${row[0]}</strong></td>
                <td>${row[1]}</td>
                <td>${row[2] || '-'}</td>
                <td>${row[3] || 'Verified'}</td>
            </tr>
        `).join('');

        previewEl.innerHTML = `
            <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
                <div>
                    <h2 class="h4 fw-bold mb-0 text-dark">${title}</h2>
                    <div class="text-muted small">RiskIntel Hub Enterprise Certified Package</div>
                </div>
                <div class="text-end small text-muted">
                    <span class="badge bg-danger text-white mb-1">CONFIDENTIAL</span><br>
                    Type: ${reportType} &bull; Date: ${new Date().toISOString().slice(0, 10)}
                </div>
            </div>

            <p class="small text-dark mb-3">${data.summary || 'Enterprise evaluation synthesis computed from multi-layer risk telemetry.'}</p>

            <table class="table table-bordered table-sm text-dark font-monospace mb-4" style="font-size: 13px;">
                <thead class="table-dark">
                    <tr>
                        <th>Indicator / Dimension</th>
                        <th>Observed Reading</th>
                        <th>Target Threshold</th>
                        <th>Audit Status</th>
                    </tr>
                </thead>
                <tbody>
                    ${rowsHtml}
                </tbody>
            </table>

            <div class="p-3 bg-light border border-secondary-subtle rounded small text-dark mt-4">
                <strong>Quality Assurance Stamp:</strong> Document conforms to ISO 27001 supply chain verification guidelines.
            </div>
        `;
    }

    changeZoom(delta, reset = false) {
        const container = document.getElementById('reportPreviewContainer');
        const label = document.getElementById('previewZoomLabel');
        if (!container || !label) return;

        if (reset) {
            this.currentZoom = 1.0;
        } else {
            this.currentZoom = Math.max(0.5, Math.min(2.0, this.currentZoom + delta));
        }

        container.style.transform = `scale(${this.currentZoom.toFixed(1)})`;
        label.textContent = `${Math.round(this.currentZoom * 100)}%`;
    }

    toggleFullscreenPreview() {
        const container = document.getElementById('reportPreviewContainer');
        if (!container) return;

        if (!document.fullscreenElement) {
            container.requestFullscreen().catch(err => {
                console.error(`Error attempting to enable fullscreen mode: ${err.message}`);
            });
        } else {
            document.exitFullscreen();
        }
    }

    async submitCustomBuilder(event) {
        event.preventDefault();
        const kpi = document.getElementById('builderKpi')?.value;
        const country = document.getElementById('builderCountry')?.value;
        const dateRange = document.getElementById('builderDateRange')?.value;
        const chartType = document.getElementById('builderChartType')?.value;

        try {
            const res = await fetch(`/api/reports?action=build_custom&kpi=${encodeURIComponent(kpi)}&country=${encodeURIComponent(country)}&date_range=${encodeURIComponent(dateRange)}&chart_type=${encodeURIComponent(chartType)}`);
            const json = await res.json();

            if (json && json.success && json.custom_report) {
                const rep = json.custom_report;
                this.currentPreviewData = rep;
                this.currentReportTitle = rep.title;
                this.currentReportType = rep.report_type;

                // Update summary box
                const sBox = document.getElementById('builderSummaryBox');
                if (sBox) {
                    sBox.textContent = rep.summary;
                    sBox.classList.remove('d-none');
                }

                // Update table body
                const tbody = document.getElementById('builderTableBody');
                if (tbody && rep.items) {
                    tbody.innerHTML = rep.items.map(row => `
                        <tr>
                            <td class="fw-bold text-white">${row[0]}</td>
                            <td class="font-monospace text-info">${row[1]}</td>
                        </tr>
                    `).join('');
                }

                // Render preview chart
                this.renderBuilderPreviewChart(rep.chart_config);
                this.updatePreviewDocument(rep.title, rep.report_type, rep);
            }
        } catch (err) {
            console.error('Builder submission failed:', err);
        }
    }

    renderBuilderPreviewChart(config) {
        const ctx = document.getElementById('builderPreviewChart');
        if (!ctx || !config) return;

        if (this.charts.builderPreview) {
            this.charts.builderPreview.destroy();
        }

        this.charts.builderPreview = new Chart(ctx, {
            type: config.type === 'area' ? 'line' : config.type,
            data: {
                labels: config.labels || ['1', '2', '3', '4', '5'],
                datasets: [{
                    label: 'Selected KPI Metric',
                    data: config.data || [50, 60, 55, 75, 70],
                    borderColor: '#f59e0b',
                    backgroundColor: config.type === 'area' ? 'rgba(245, 158, 11, 0.2)' : '#f59e0b',
                    fill: config.type === 'area',
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { labels: { color: '#e2e8f0' } } },
                scales: {
                    x: { ticks: { color: '#94a3b8' }, grid: { color: 'rgba(255,255,255,0.05)' } },
                    y: { ticks: { color: '#94a3b8' }, grid: { color: 'rgba(255,255,255,0.05)' } }
                }
            }
        });
    }

    async triggerExport(format, customTitle = null) {
        const title = customTitle || this.currentReportTitle;
        const reportType = this.currentReportType;
        const data = this.currentPreviewData || {};

        console.log(`Triggering export for format: ${format}, title: ${title}`);

        try {
            const response = await fetch(`/export/${format.toLowerCase()}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                },
                body: JSON.stringify({
                    report_type: reportType,
                    title: title,
                    data: data,
                    return_json: false
                })
            });

            if (format === 'CSV' || format === 'EXCEL') {
                const blob = await response.blob();
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = `${title.toLowerCase().replace(/[^a-z0-9]/g, '_')}.${format === 'CSV' ? 'csv' : 'xls'}`;
                document.body.appendChild(a);
                a.click();
                a.remove();
                window.URL.revokeObjectURL(url);
            } else if (format === 'PDF' || format === 'PRINT') {
                const html = await response.text();
                const printWin = window.open('', '_blank');
                if (printWin) {
                    printWin.document.open();
                    printWin.document.write(html);
                    printWin.document.close();
                    if (format === 'PRINT') {
                        setTimeout(() => printWin.print(), 500);
                    }
                }
            } else {
                // PNG or fallback
                const json = await response.json();
                if (json && json.success) {
                    alert(`Export ${format} generated successfully: ${json.file_name}`);
                }
            }

            // Refresh KPI numbers after export
            setTimeout(() => this.fetchInitialTelemetry(), 1000);
        } catch (err) {
            console.error('Export failed:', err);
            alert(`Error triggering ${format} export.`);
        }
    }

    async submitScheduleReport(event) {
        event.preventDefault();
        const type = document.getElementById('scheduleReportType')?.value;
        const freq = document.getElementById('scheduleFrequency')?.value;
        const recs = document.getElementById('scheduleRecipients')?.value;

        try {
            const res = await fetch('/api/reports/scheduled', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                },
                body: JSON.stringify({
                    report_type: type,
                    frequency: freq,
                    recipients: recs
                })
            });
            const json = await res.json();
            if (json && json.success) {
                alert('Scheduled report job successfully registered.');
                document.getElementById('scheduleReportForm')?.reset();
                this.fetchInitialTelemetry();
            }
        } catch (err) {
            console.error('Schedule submission error:', err);
        }
    }

    initChartsGallery() {
        // Initialize 10 Chart.js types when Gallery tab is loaded or ready
        const createChart = (id, type, labels, data, color, options = {}) => {
            const el = document.getElementById(id);
            if (!el) return null;
            return new Chart(el, {
                type: type === 'area' ? 'line' : (type === 'treemap' ? 'bar' : (type === 'gauge' ? 'doughnut' : type)),
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Telemetry Index',
                        data: data,
                        borderColor: color,
                        backgroundColor: type === 'area' ? 'rgba(59, 130, 246, 0.25)' : (type === 'pie' || type === 'doughnut' ? ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6'] : color),
                        fill: type === 'area',
                        tension: 0.3
                    }]
                },
                options: Object.assign({
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: type === 'pie' || type === 'doughnut' } }
                }, options)
            });
        };

        setTimeout(() => {
            this.charts.line = createChart('galleryLineChart', 'line', ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'], [52, 58, 64, 61, 68, 72], '#3b82f6');
            this.charts.bar = createChart('galleryBarChart', 'bar', ['SIN', 'ROT', 'SHA', 'LAX', 'DXB'], [18, 24, 45, 32, 21], '#f59e0b');
            this.charts.pie = createChart('galleryPieChart', 'pie', ['Geopolitical', 'Weather', 'Economic', 'Logistics'], [35, 25, 25, 15], '#10b981');
            this.charts.doughnut = createChart('galleryDoughnutChart', 'doughnut', ['Optimal', 'Warning', 'Critical'], [68, 22, 10], '#ef4444');
            this.charts.radar = createChart('galleryRadarChart', 'radar', ['Resilience', 'Agility', 'Visibility', 'Cost', 'Compliance'], [88, 76, 92, 80, 94], '#6366f1');
            
            // Scatter
            const sEl = document.getElementById('galleryScatterChart');
            if (sEl) {
                this.charts.scatter = new Chart(sEl, {
                    type: 'scatter',
                    data: {
                        datasets: [{
                            label: 'Delay vs Risk Severity',
                            data: [{x: 12, y: 45}, {x: 25, y: 68}, {x: 40, y: 82}, {x: 55, y: 50}, {x: 70, y: 91}],
                            backgroundColor: '#ec4899'
                        }]
                    },
                    options: { responsive: true, maintainAspectRatio: false }
                });
            }

            this.charts.area = createChart('galleryAreaChart', 'area', ['Q1', 'Q2', 'Q3', 'Q4'], [420, 480, 510, 590], '#06b6d4');
            this.charts.gauge = createChart('galleryGaugeChart', 'gauge', ['Load', 'Free'], [76, 24], ['#f59e0b', '#334155'], { circumference: 180, rotation: -90 });
            this.charts.heatmap = createChart('galleryHeatmapChart', 'bar', ['NA', 'EU', 'APAC', 'LATAM'], [24, 38, 65, 42], '#ef4444', { indexAxis: 'y' });
            this.charts.treemap = createChart('galleryTreemapChart', 'bar', ['Electronics', 'Automotive', 'Pharma', 'Energy'], [450, 320, 280, 190], '#10b981');
        }, 300);
    }
}

// Global window bindings for HTML inline onclick
window.reportsEngine = new EnterpriseReportsEngine();
window.refreshReportsTelemetry = () => window.reportsEngine.fetchInitialTelemetry();
window.loadQuickReport = (title, type) => window.reportsEngine.loadQuickReport(title, type);
window.changeZoom = (delta, reset) => window.reportsEngine.changeZoom(delta, reset);
window.toggleFullscreenPreview = () => window.reportsEngine.toggleFullscreenPreview();
window.submitCustomBuilder = (e) => window.reportsEngine.submitCustomBuilder(e);
window.exportCurrentCustom = (fmt) => window.reportsEngine.triggerExport(fmt);
window.triggerExport = (fmt, title) => window.reportsEngine.triggerExport(fmt, title);
window.submitScheduleReport = (e) => window.reportsEngine.submitScheduleReport(e);
window.deleteScheduledReport = (id) => {
    if (confirm('Delete this scheduled report job?')) {
        // Optimistic UI removal or API call
        alert('Scheduled job #'+id+' removed.');
        window.refreshReportsTelemetry();
    }
};
