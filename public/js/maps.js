/**
 * Modul Peta Maritim Leaflet & NGA Satellite Port Plotter
 */
let maritimeMap = null;
let portLayerGroup = null;

function initMaritimeMap() {
    const mapElement = document.getElementById('maritimeMap');
    if (!mapElement) return;

    // Inisialisasi peta Leaflet berpusat di koordinat maritim global
    maritimeMap = L.map('maritimeMap', {
        center: [15.0, 115.0],
        zoom: 3,
        minZoom: 2,
        maxZoom: 14,
        zoomControl: false
    });

    // Kontrol zoom di posisi kanan atas
    L.control.zoom({ position: 'topright' }).addTo(maritimeMap);

    // Light tile layer
    L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
        subdomains: 'abcd',
        maxZoom: 20
    }).addTo(maritimeMap);

    portLayerGroup = L.layerGroup().addTo(maritimeMap);

    // Mencegah area gelap/hitam di sebelah kiri peta karena rendering kontainer yang tertunda
    setTimeout(() => { if (maritimeMap) maritimeMap.invalidateSize(); }, 200);
    setTimeout(() => { if (maritimeMap) maritimeMap.invalidateSize(); }, 800);
    window.addEventListener('resize', () => { if (maritimeMap) maritimeMap.invalidateSize(); });
}

/**
 * Memuat titik pelabuhan NGA dan lokal untuk negara yang dipilih, langsung memindahkan kamera peta ke koordinat negara
 */
async function loadPortsForCountry(countryName, abortSignal = null, countryLat = null, countryLng = null) {
    if (!maritimeMap || !portLayerGroup) return;
    
    portLayerGroup.clearLayers();

    const isUnselected = (!countryName || countryName === 'Global / Semua Negara' || countryName === 'Global' || countryName === 'Belum Dipilih' || countryName === '-');
    if (isUnselected) return;

    // Langsung gerakkan peta (zoom & pan) dan pasang banner tanda negara aktif tepat di titik pusat koordinat!
    if (countryLat !== null && countryLng !== null && countryLat !== undefined && countryLng !== undefined) {
        maritimeMap.setView([countryLat, countryLng], 5, { animate: true });
        addActiveCountryBanner(countryName, countryLat, countryLng);
    }

    try {
        const fetchOptions = abortSignal ? { signal: abortSignal } : {};
        const response = await fetch(`/api/external/ports/${encodeURIComponent(countryName)}?lat=${encodeURIComponent(countryLat !== null && countryLat !== undefined ? countryLat : '')}&lng=${encodeURIComponent(countryLng !== null && countryLng !== undefined ? countryLng : '')}`, fetchOptions);
        if (!response.ok) return;

        const result = await response.json();
        if (!result.success || !Array.isArray(result.data)) return;

        const ports = result.data;
        const bounds = [];

        // Pastikan koordinat pusat negara masuk ke bounds agar banner ZONA AKTIF tidak tergeser keluar frame
        if (countryLat !== null && countryLng !== null && countryLat !== undefined && countryLng !== undefined) {
            bounds.push([countryLat, countryLng]);
        }

        // Ikon kustom pelabuhan maritim (emas/cyan)
        const portIcon = L.divIcon({
            className: 'custom-port-marker',
            html: `<div style="
                background: #c89c62;
                border: 2px solid #ffffff;
                width: 14px;
                height: 14px;
                border-radius: 50%;
                box-shadow: 0 0 10px rgba(200, 156, 98, 0.8);
                cursor: pointer;
            "></div>`,
            iconSize: [14, 14],
            iconAnchor: [7, 7],
            popupAnchor: [0, -10]
        });

        ports.forEach(port => {
            if (port.lat !== 0 || port.lng !== 0) {
                const marker = L.marker([port.lat, port.lng], { icon: portIcon });
                const safeName = escapeHtml(port.name);
                
                marker.bindPopup(`
                    <div style="background: #1b1c20; color: #fff; padding: 10px; border-radius: 8px; border: 1px solid #c89c62;">
                        <div style="color: #c89c62; font-weight: bold; font-size: 13px; margin-bottom: 4px;">
                            <i class="fa-solid fa-anchor me-1"></i> ${safeName}
                        </div>
                        <div style="font-size: 11px; color: #a0aec0;">
                            <strong>Lat:</strong> ${port.lat.toFixed(4)}<br>
                            <strong>Lng:</strong> ${port.lng.toFixed(4)}<br>
                            <strong>Status:</strong> <span style="color: #48bb78;">Active Terminal</span>
                        </div>
                    </div>
                `, { className: 'custom-dark-popup' });

                portLayerGroup.addLayer(marker);
                bounds.push([port.lat, port.lng]);
            }
        });

        if (bounds.length > 0) {
            maritimeMap.fitBounds(bounds, { padding: [60, 60], maxZoom: 6, animate: true, duration: 1.0 });
        }

        const portCountBadge = document.getElementById('portCountBadge');
        if (portCountBadge) portCountBadge.textContent = ports.length;

        const portContainer = document.getElementById('portListContainer');
        if (portContainer) {
            if (ports.length > 0) {
                portContainer.innerHTML = ports.map(port => {
                    const safeName = escapeHtml(port.name || 'Terminal Pelabuhan');
                    const latStr = typeof port.lat === 'number' ? port.lat.toFixed(4) : port.lat;
                    const lngStr = typeof port.lng === 'number' ? port.lng.toFixed(4) : port.lng;
                    return `
                        <div class="glass-card p-3 rounded-2 w-100 shadow-sm flex-shrink-0" style="border-left: 3.5px solid var(--primary);">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="badge badge-soft-success"><i class="fa-solid fa-circle-check me-1"></i> Active Terminal</span>
                                <span class="small fw-semibold text-muted" style="font-size: 11px;">NGA Satellite ID</span>
                            </div>
                            <h5 class="fw-semibold text-dark mb-2" style="font-size: 14px; line-height: 1.3;">
                                <i class="fa-solid fa-anchor me-1.5 text-primary"></i> ${safeName}
                            </h5>
                            <div class="small mb-3 text-muted" style="font-size: 12px; line-height: 1.6;">
                                <div class="mb-1"><strong><i class="fa-solid fa-location-dot text-primary me-1"></i> Coordinates:</strong> Lat ${latStr}, Lng ${lngStr}</div>
                                <div class="mb-1"><strong><i class="fa-solid fa-ship text-success me-1"></i> Cargo Capacity:</strong> Deep-Sea Cargo Hub</div>
                                <div><strong><i class="fa-solid fa-shield-halved text-info me-1"></i> Status:</strong> Operational / Clear</div>
                            </div>
                            <div class="pt-2 border-top">
                                <button class="btn btn-sm btn-outline-secondary w-100 fw-semibold py-1" onclick="focusOnPort(${port.lat}, ${port.lng}, '${safeName.replace(/'/g, "\\'")}')">
                                    Focus Map <i class="fa-solid fa-location-crosshairs ms-1 text-primary"></i>
                                </button>
                            </div>
                        </div>
                    `;
                }).join('');
            } else {
                portContainer.innerHTML = `<div class="w-100 small p-4 text-center text-muted">No maritime port terminals detected for this country.</div>`;
            }
        }
    } catch (e) {
        if (e.name !== 'AbortError') {
            console.error("Gagal memuat koordinat pelabuhan:", e);
        }
    }
}

/**
 * Mengarahkan kamera satelit langsung ke titik pelabuhan terpilih dari daftar keterangan
 */
function focusOnPort(lat, lng, portName) {
    if (!maritimeMap || lat === undefined || lng === undefined) return;
    
    maritimeMap.flyTo([lat, lng], 11, { animate: true, duration: 1.2 });
    
    if (portLayerGroup) {
        portLayerGroup.eachLayer(layer => {
            if (layer instanceof L.Marker) {
                const pos = layer.getLatLng();
                if (Math.abs(pos.lat - lat) < 0.0001 && Math.abs(pos.lng - lng) < 0.0001) {
                    layer.openPopup();
                }
            }
        });
    }
}
window.focusOnPort = focusOnPort;

/**
 * Menaruh tanda/label banner eksklusif di pusat koordinat negara agar pengguna langsung tahu posisi peta saat ini
 */
function addActiveCountryBanner(countryName, lat, lng) {
    if (!portLayerGroup || lat === null || lng === null || lat === undefined || lng === undefined) return;

    const safeName = escapeHtml(countryName);
    const bannerIcon = L.divIcon({
        className: 'active-country-banner-icon',
        html: `<div style="
            display: inline-flex;
            align-items: center;
            background: rgba(15, 23, 42, 0.95);
            border: 2px solid #38bdf8;
            border-radius: 30px;
            padding: 6px 16px;
            box-shadow: 0 0 20px rgba(56, 189, 248, 0.7);
            color: #ffffff;
            font-size: 13px;
            font-weight: 800;
            white-space: nowrap;
            transform: translate(-50%, -100%);
            cursor: pointer;
            letter-spacing: 0.5px;
        ">
            <span style="
                display: inline-block;
                width: 10px;
                height: 10px;
                background: #38bdf8;
                border-radius: 50%;
                margin-right: 8px;
                box-shadow: 0 0 10px #38bdf8;
            "></span>
            <i class="fa-solid fa-location-dot text-info me-2"></i>
            <span>ZONA AKTIF: <span style="color: #38bdf8;">${safeName.toUpperCase()}</span></span>
        </div>`,
        iconSize: [0, 0],
        iconAnchor: [0, 0]
    });

    const marker = L.marker([lat, lng], { icon: bannerIcon, zIndexOffset: 1000 });
    marker.bindPopup(`
        <div style="background: #1b1c20; color: #fff; padding: 12px; border-radius: 8px; border: 1px solid #38bdf8; min-width: 200px;">
            <div style="color: #38bdf8; font-weight: bold; font-size: 14px; margin-bottom: 6px; border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 6px;">
                <i class="fa-solid fa-earth-asia me-1"></i> Negara Tujuan: ${safeName}
            </div>
            <div style="font-size: 12px; color: #e2e8f0; line-height: 1.6;">
                <strong>Koordinat Pusat:</strong> ${Number(lat).toFixed(4)}°, ${Number(lng).toFixed(4)}°<br>
                <strong>Status Pemantauan:</strong> <span class="badge bg-info text-dark fw-bold">Zonasi Maritim & Cuaca Aktif</span>
            </div>
        </div>
    `, { className: 'custom-dark-popup' });

    portLayerGroup.addLayer(marker);
}
