/**
 * Maritime Route & Delay Simulator Engine (Sea Freight Only)
 * Mengelola peta eksklusif dengan garis patah-patah (dashed polyline), dynamic port loading, dan integrasi Risk Score Engine.
 */

let maritimeRouteMap = null;
let routeLayerGroup = null;

const MARITIME_COUNTRIES_LIST = [
    { name: "Afghanistan", iso: "AF", lat: 33.9391, lng: 67.7100 },
    { name: "Albania", iso: "AL", lat: 41.1533, lng: 20.1683 },
    { name: "Algeria", iso: "DZ", lat: 28.0339, lng: 1.6596 },
    { name: "Angola", iso: "AO", lat: -11.2027, lng: 17.8739 },
    { name: "Argentina", iso: "AR", lat: -38.4161, lng: -63.6167 },
    { name: "Australia", iso: "AU", lat: -25.2744, lng: 133.7751 },
    { name: "Bahrain", iso: "BH", lat: 26.0667, lng: 50.5577 },
    { name: "Bangladesh", iso: "BD", lat: 23.6850, lng: 90.3563 },
    { name: "Belgium", iso: "BE", lat: 50.8503, lng: 4.3517 },
    { name: "Brazil", iso: "BR", lat: -14.2350, lng: -51.9253 },
    { name: "Canada", iso: "CA", lat: 56.1304, lng: -106.3468 },
    { name: "Chile", iso: "CL", lat: -35.6751, lng: -71.5430 },
    { name: "China", iso: "CN", lat: 35.8617, lng: 104.1954 },
    { name: "Colombia", iso: "CO", lat: 4.5709, lng: -74.2973 },
    { name: "Denmark", iso: "DK", lat: 56.2639, lng: 9.5018 },
    { name: "Egypt", iso: "EG", lat: 26.8206, lng: 30.8025 },
    { name: "Finland", iso: "FI", lat: 61.9241, lng: 25.7482 },
    { name: "France", iso: "FR", lat: 46.2276, lng: 2.2137 },
    { name: "Germany", iso: "DE", lat: 51.1657, lng: 10.4515 },
    { name: "Greece", iso: "GR", lat: 39.0742, lng: 21.8243 },
    { name: "India", iso: "IN", lat: 20.5937, lng: 78.9629 },
    { name: "Indonesia", iso: "ID", lat: -0.7893, lng: 113.9213 },
    { name: "Iran", iso: "IR", lat: 32.4279, lng: 53.6880 },
    { name: "Iraq", iso: "IQ", lat: 33.2232, lng: 43.6793 },
    { name: "Israel", iso: "IL", lat: 31.0461, lng: 34.8516 },
    { name: "Italy", iso: "IT", lat: 41.8719, lng: 12.5674 },
    { name: "Japan", iso: "JP", lat: 36.2048, lng: 138.2529 },
    { name: "Jordan", iso: "JO", lat: 30.5852, lng: 36.2384 },
    { name: "Kenya", iso: "KE", lat: -0.0236, lng: 37.9062 },
    { name: "Kuwait", iso: "KW", lat: 29.3117, lng: 47.4818 },
    { name: "Malaysia", iso: "MY", lat: 4.2105, lng: 101.9758 },
    { name: "Mexico", iso: "MX", lat: 23.6345, lng: -102.5528 },
    { name: "Morocco", iso: "MA", lat: 31.7917, lng: -7.0926 },
    { name: "Netherlands", iso: "NL", lat: 52.1326, lng: 5.2913 },
    { name: "New Zealand", iso: "NZ", lat: -40.9006, lng: 174.8860 },
    { name: "Nigeria", iso: "NG", lat: 9.0820, lng: 8.6753 },
    { name: "Norway", iso: "NO", lat: 60.4720, lng: 8.4689 },
    { name: "Oman", iso: "OM", lat: 21.4735, lng: 55.9754 },
    { name: "Pakistan", iso: "PK", lat: 30.3753, lng: 69.3451 },
    { name: "Panama", iso: "PA", lat: 8.5379, lng: -80.7821 },
    { name: "Peru", iso: "PE", lat: -9.1900, lng: -75.0152 },
    { name: "Philippines", iso: "PH", lat: 12.8797, lng: 121.7740 },
    { name: "Poland", iso: "PL", lat: 51.9194, lng: 19.1451 },
    { name: "Portugal", iso: "PT", lat: 39.3999, lng: -8.2245 },
    { name: "Qatar", iso: "QA", lat: 25.3548, lng: 51.1839 },
    { name: "Russia", iso: "RU", lat: 61.5240, lng: 105.3188 },
    { name: "Saudi Arabia", iso: "SA", lat: 23.8859, lng: 45.0792 },
    { name: "Singapore", iso: "SG", lat: 1.3521, lng: 103.8198 },
    { name: "South Africa", iso: "ZA", lat: -30.5595, lng: 22.9375 },
    { name: "South Korea", iso: "KR", lat: 35.9078, lng: 127.7669 },
    { name: "Spain", iso: "ES", lat: 40.4637, lng: -3.7492 },
    { name: "Sri Lanka", iso: "LK", lat: 7.8731, lng: 80.7718 },
    { name: "Sweden", iso: "SE", lat: 60.1282, lng: 18.6435 },
    { name: "Taiwan", iso: "TW", lat: 23.6978, lng: 120.9605 },
    { name: "Tanzania", iso: "TZ", lat: -6.3690, lng: 34.8888 },
    { name: "Thailand", iso: "TH", lat: 15.8700, lng: 100.9925 },
    { name: "Turkey", iso: "TR", lat: 38.9637, lng: 35.2433 },
    { name: "Ukraine", iso: "UA", lat: 48.3794, lng: 31.1656 },
    { name: "United Arab Emirates", iso: "AE", lat: 23.4241, lng: 53.8478 },
    { name: "United Kingdom", iso: "GB", lat: 55.3781, lng: -3.4360 },
    { name: "United States", iso: "US", lat: 37.0902, lng: -95.7129 },
    { name: "Vietnam", iso: "VN", lat: 14.0583, lng: 108.2772 }
];

document.addEventListener('DOMContentLoaded', function() {
    initMaritimeRouteMap();
    populateCountryDropdowns();
    setupSimulatorEventListeners();
});

function initMaritimeRouteMap() {
    const mapEl = document.getElementById('maritimeRouteMap');
    if (!mapEl || typeof L === 'undefined') return;

    maritimeRouteMap = L.map('maritimeRouteMap', {
        center: [15.0, 85.0],
        zoom: 3,
        zoomControl: true,
        attributionControl: false
    });

    L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
        maxZoom: 18,
        subdomains: 'abcd',
        attribution: '&copy; CartoDB'
    }).addTo(maritimeRouteMap);

    routeLayerGroup = L.layerGroup().addTo(maritimeRouteMap);
}

function populateCountryDropdowns() {
    const originSelect = document.getElementById('originCountrySelect');
    const destSelect = document.getElementById('destCountrySelect');
    if (!originSelect || !destSelect) return;

    // Add blank placeholder first
    const blankOrigin = document.createElement('option');
    blankOrigin.value = '';
    blankOrigin.textContent = '- Select Origin Country -';
    blankOrigin.selected = true;
    blankOrigin.disabled = false;
    originSelect.appendChild(blankOrigin);

    const blankDest = document.createElement('option');
    blankDest.value = '';
    blankDest.textContent = '- Select Destination Country -';
    blankDest.selected = true;
    blankDest.disabled = false;
    destSelect.appendChild(blankDest);

    // Populate sorted country list — no default selected
    const sorted = [...MARITIME_COUNTRIES_LIST].sort((a, b) => a.name.localeCompare(b.name));
    sorted.forEach(c => {
        const optOrigin = document.createElement('option');
        optOrigin.value = c.name;
        optOrigin.textContent = `${c.name} (${c.iso})`;
        originSelect.appendChild(optOrigin);

        const optDest = document.createElement('option');
        optDest.value = c.name;
        optDest.textContent = `${c.name} (${c.iso})`;
        destSelect.appendChild(optDest);
    });

    // Also reset port dropdowns to blank
    const originPortSelect = document.getElementById('originPortSelect');
    const destPortSelect = document.getElementById('destPortSelect');
    if (originPortSelect) {
        originPortSelect.innerHTML = '<option value="">- Select origin country first -</option>';
    }
    if (destPortSelect) {
        destPortSelect.innerHTML = '<option value="">- Select destination country first -</option>';
    }

    // Reset Risk Engine Sync display
    const syncNameEl = document.getElementById('autoSyncCountryName');
    if (syncNameEl) syncNameEl.textContent = '-';
    const syncW = document.getElementById('syncWeatherVal');
    const syncN = document.getElementById('syncNewsVal');
    const syncI = document.getElementById('syncInflationVal');
    const syncC = document.getElementById('syncCurrencyVal');
    if (syncW) syncW.textContent = '-';
    if (syncN) syncN.textContent = '-';
    if (syncI) syncI.textContent = '-';
    if (syncC) syncC.textContent = '-';

    // Reset risk badge
    const riskBadgeEl = document.getElementById('simRiskCategoryBadge');
    if (riskBadgeEl) {
        riskBadgeEl.className = 'badge bg-secondary px-3 py-2 fs-6 fw-bold';
        riskBadgeEl.innerHTML = '<i class="fa-solid fa-shield me-1"></i> - (Select Countries)';
    }

    // Do NOT auto-load ports or run simulation on page load
}

async function loadPortsForSimulator(side, countryName, triggerCalcAfter = false) {
    const portSelect = document.getElementById(`${side}PortSelect`);
    if (!portSelect) return;

    portSelect.innerHTML = `<option value="">Memuat pelabuhan maritim ${countryName}...</option>`;
    portSelect.disabled = true;

    try {
        const response = await fetch(`/api/external/ports/${encodeURIComponent(countryName)}`);
        if (!response.ok) throw new Error("Gagal memuat pelabuhan");

        const result = await response.json();
        const ports = result.data || [];

        portSelect.innerHTML = '';
        if (ports.length === 0) {
            // Fallback jika tidak ada spesifik
            const opt = document.createElement('option');
            opt.value = `0,0`;
            opt.textContent = `${countryName} Main Sea Terminal (0.00, 0.00)`;
            portSelect.appendChild(opt);
        } else {
            ports.forEach((p, idx) => {
                const opt = document.createElement('option');
                opt.value = `${p.lat},${p.lng}`;
                opt.setAttribute('data-name', p.name);
                opt.textContent = `${p.name} (${p.lat.toFixed(2)}, ${p.lng.toFixed(2)})`;
                if (idx === 0) opt.selected = true;
                portSelect.appendChild(opt);
            });
        }
        portSelect.disabled = false;

        if (triggerCalcAfter) {
            setTimeout(() => runRouteSimulation(), 250);
        }
    } catch (e) {
        console.error(e);
        portSelect.innerHTML = `<option value="0,0">${countryName} Sea Port (Default)</option>`;
        portSelect.disabled = false;
        if (triggerCalcAfter) {
            runRouteSimulation();
        }
    }
}

function setupSimulatorEventListeners() {
    const originCountry = document.getElementById('originCountrySelect');
    const destCountry = document.getElementById('destCountrySelect');
    const originPort = document.getElementById('originPortSelect');
    const destPort = document.getElementById('destPortSelect');
    const speedSelect = document.getElementById('vesselSpeedSelect');
    const simForm = document.getElementById('maritimeSimForm');

    if (originCountry) {
        originCountry.addEventListener('change', (e) => {
            loadPortsForSimulator('origin', e.target.value, true);
        });
    }

    if (destCountry) {
        destCountry.addEventListener('change', (e) => {
            const syncNameEl = document.getElementById('autoSyncCountryName');
            if (syncNameEl) syncNameEl.textContent = e.target.value;
            loadPortsForSimulator('dest', e.target.value, true);
        });
    }

    if (originPort) originPort.addEventListener('change', () => runRouteSimulation());
    if (destPort) destPort.addEventListener('change', () => runRouteSimulation());
    if (speedSelect) speedSelect.addEventListener('change', () => runRouteSimulation());

    if (simForm) {
        simForm.addEventListener('submit', (e) => {
            e.preventDefault();
            runRouteSimulation();
        });
    }
}

async function runRouteSimulation() {
    const originPort = document.getElementById('originPortSelect');
    const destPort = document.getElementById('destPortSelect');
    const speedSelect = document.getElementById('vesselSpeedSelect');
    const originCountryEl = document.getElementById('originCountrySelect');
    const destCountryEl = document.getElementById('destCountrySelect');

    // Guard: both countries and ports must be selected
    if (!originCountryEl?.value || !destCountryEl?.value) return;
    if (!originPort || !destPort || !originPort.value || !destPort.value) return;

    const [originLat, originLng] = originPort.value.split(',').map(Number);
    const [destLat, destLng] = destPort.value.split(',').map(Number);

    const originName = originPort.options[originPort.selectedIndex]?.getAttribute('data-name') || originPort.options[originPort.selectedIndex]?.textContent.split(' (')[0] || 'Origin Port';
    const destName = destPort.options[destPort.selectedIndex]?.getAttribute('data-name') || destPort.options[destPort.selectedIndex]?.textContent.split(' (')[0] || 'Destination Port';

    const originCountry = document.getElementById('originCountrySelect')?.value || 'Indonesia';
    const destCountry = document.getElementById('destCountrySelect')?.value || 'Netherlands';

    const vesselSpeed = speedSelect ? Number(speedSelect.value) : 18.0;

    const payload = {
        origin_country: originCountry,
        dest_country: destCountry,
        origin_name: originName,
        origin_lat: originLat,
        origin_lng: originLng,
        dest_name: destName,
        dest_lat: destLat,
        dest_lng: destLng,
        vessel_speed_knots: vesselSpeed,
        _token: document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
    };

    try {
        const response = await fetch('/api/maritime/simulate', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': payload._token
            },
            body: JSON.stringify(payload)
        });

        if (!response.ok) throw new Error("Simulator API Error");

        const result = await response.json();
        if (result.success && result.data) {
            updateSimulatorUI(result.data);
            drawMaritimeRouteOnMap(result.data);
        }
    } catch (e) {
        console.error("Simulation error:", e);
    }
}

function updateSimulatorUI(data) {
    const distNmEl = document.getElementById('simDistanceNm');
    const distKmEl = document.getElementById('simDistanceKm');
    const baseDurEl = document.getElementById('simBaseDuration');
    const delayDurEl = document.getElementById('simDelayDuration');
    const totalDurEl = document.getElementById('simTotalDuration');
    const riskBadgeEl = document.getElementById('simRiskCategoryBadge');
    const breakdownContainer = document.getElementById('simBreakdownList');

    // Update Auto-Synced Indicator values
    if (data.risk_engine) {
        const syncW = document.getElementById('syncWeatherVal');
        const syncN = document.getElementById('syncNewsVal');
        const syncI = document.getElementById('syncInflationVal');
        const syncC = document.getElementById('syncCurrencyVal');
        if (syncW) syncW.textContent = `${data.risk_engine.weather_risk ?? '-'}%`;
        if (syncN) syncN.textContent = `${data.risk_engine.news_risk ?? '-'}%`;
        if (syncI) syncI.textContent = `${data.risk_engine.inflation_risk ?? '-'}%`;
        if (syncC) syncC.textContent = `${data.risk_engine.currency_risk ?? '-'}%`;
    }

    if (distNmEl) distNmEl.textContent = `${data.distance_nm.toLocaleString()} NM`;
    if (distKmEl) distKmEl.textContent = `(${data.distance_km.toLocaleString()} km)`;
    if (baseDurEl) baseDurEl.textContent = `${data.base_duration_days} Hari`;
    
    if (delayDurEl) {
        delayDurEl.textContent = `+${data.delay_days} Hari`;
        delayDurEl.className = data.delay_days > 5 ? 'fw-bold text-danger fs-4' : (data.delay_days > 0 ? 'fw-bold text-warning fs-4' : 'fw-bold text-success fs-4');
    }

    if (totalDurEl) totalDurEl.textContent = `${data.total_duration_days} Hari`;

    if (riskBadgeEl) {
        const isHigh    = data.risk_engine.status_color === 'danger';
        const isMedium  = data.risk_engine.status_color === 'warning';
        const bg    = isHigh ? '#fef2f2' : isMedium ? '#fffbeb' : '#dcfce7';
        const color = isHigh ? '#b91c1c' : isMedium ? '#92400e' : '#166534';
        const border= isHigh ? '#fecaca' : isMedium ? '#fde68a' : '#bbf7d0';
        riskBadgeEl.style.cssText = `background:${bg};color:${color};border:1px solid ${border};`;
        riskBadgeEl.innerHTML = `<i class="fa-solid fa-shield me-1"></i> ${data.risk_engine.category} (${data.risk_engine.total_score}/100)`;
    }

    if (breakdownContainer && Array.isArray(data.delay_breakdown)) {
        breakdownContainer.innerHTML = data.delay_breakdown.map(item => {
            const hasDelay = item.delay_days > 0;
            const cardBg     = hasDelay ? '#fef2f2' : '#f0fdf4';
            const cardBorder = hasDelay ? '#fecaca' : '#bbf7d0';
            const iconClass  = hasDelay ? 'fa-triangle-exclamation' : 'fa-check-circle';
            const iconColor  = hasDelay ? '#dc2626' : '#16a34a';
            return `
                <div class="p-3 mb-2 rounded d-flex align-items-start justify-content-between gap-3"
                     style="background:${cardBg};border:1px solid ${cardBorder};">
                    <div>
                        <div class="fw-bold mb-1" style="color:#0f172a;">
                            <i class="fa-solid ${iconClass} me-2" style="color:${iconColor};"></i>
                            ${item.type}
                            ${hasDelay
                                ? `<span class="badge ms-2" style="background:#fef2f2;color:#b91c1c;border:1px solid #fecaca;">+${item.delay_days} Days Delay</span>`
                                : `<span class="badge ms-2" style="background:#dcfce7;color:#166534;border:1px solid #bbf7d0;">Normal</span>`
                            }
                        </div>
                        <div class="small mb-1" style="color:#475569;font-size:13px;">${item.description}</div>
                        <div class="small fw-semibold" style="color:#3b82f6;font-size:12px;">
                            <i class="fa-solid fa-lightbulb me-1"></i> Mitigation: ${item.mitigation}
                        </div>
                    </div>
                </div>
            `;
        }).join('');
    }
}

function drawMaritimeRouteOnMap(data) {
    if (!maritimeRouteMap || !routeLayerGroup) return;

    routeLayerGroup.clearLayers();

    const originCoords = [data.origin.lat, data.origin.lng];
    const destCoords = [data.destination.lat, data.destination.lng];

    // Warna garis berdasar kategori risiko
    let lineColor = '#00f2fe'; // Low risk neon cyan
    if (data.risk_engine.status_color === 'warning') lineColor = '#f59e0b'; // Medium gold
    if (data.risk_engine.status_color === 'danger') lineColor = '#ef4444'; // High risk neon red

    // Custom Anchor Icons
    const originIcon = L.divIcon({
        className: 'origin-port-marker',
        html: `<div style="background: #10b981; border: 2px solid #fff; width: 16px; height: 16px; border-radius: 50%; box-shadow: 0 0 12px #10b981;"></div>`,
        iconSize: [16, 16],
        iconAnchor: [8, 8]
    });

    const destIcon = L.divIcon({
        className: 'dest-port-marker',
        html: `<div style="background: #ef4444; border: 2px solid #fff; width: 16px; height: 16px; border-radius: 50%; box-shadow: 0 0 12px #ef4444;"></div>`,
        iconSize: [16, 16],
        iconAnchor: [8, 8]
    });

    const originMarker = L.marker(originCoords, { icon: originIcon }).bindPopup(`
        <div style="background:#ffffff;color:#0f172a;padding:10px;border-radius:8px;border:1px solid #bbf7d0;min-width:160px;">
            <div style="color:#10b981;font-weight:700;font-size:13px;"><i class="fa-solid fa-anchor"></i> Origin Port</div>
            <div style="font-size:12px;margin-top:4px;color:#0f172a;"><strong>${data.origin.name}</strong></div>
        </div>
    `, { className: 'custom-light-popup' });

    const destMarker = L.marker(destCoords, { icon: destIcon }).bindPopup(`
        <div style="background:#ffffff;color:#0f172a;padding:10px;border-radius:8px;border:1px solid #fecaca;min-width:160px;">
            <div style="color:#ef4444;font-weight:700;font-size:13px;"><i class="fa-solid fa-flag-checkered"></i> Destination Port</div>
            <div style="font-size:12px;margin-top:4px;color:#0f172a;"><strong>${data.destination.name}</strong></div>
        </div>
    `, { className: 'custom-light-popup' });

    routeLayerGroup.addLayer(originMarker);
    routeLayerGroup.addLayer(destMarker);

    // Garis patah-patah khusus fitur ini (Dashed Maritime Polyline)
    const polyline = L.polyline([originCoords, destCoords], {
        color: lineColor,
        weight: 4,
        dashArray: '10, 15',
        opacity: 0.95
    });

    routeLayerGroup.addLayer(polyline);

    // Auto fit bounds agar kedua pelabuhan dan garis patah-patah tepat di tengah layar
    maritimeRouteMap.fitBounds(polyline.getBounds(), {
        padding: [70, 70],
        maxZoom: 6,
        animate: true,
        duration: 1.0
    });
}
