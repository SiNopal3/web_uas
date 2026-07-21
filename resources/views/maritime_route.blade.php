@extends('layouts.app')

@section('title', 'Simulasi Rute & Keterlambatan Logistik Maritim - RiskIntel Hub')

@section('content')
<div class="container-fluid p-0">
    <!-- Header Title Banner -->
    <div class="row mb-4 g-3 align-items-center">
        <div class="col-12">
            <div class="glass-card p-4 d-flex align-items-center justify-content-between flex-wrap gap-3 border border-warning" style="background: linear-gradient(135deg, rgba(200, 156, 98, 0.15) 0%, rgba(15, 23, 42, 0.85) 100%);">
                <div>
                    <span class="badge bg-warning text-dark fw-bold mb-2 px-3 py-1">MARITIME FREIGHT SIMULATION</span>
                    <h2 class="fw-bold text-white mb-1">
                        Simulasi Rute & Pengaruh Risiko Terhadap Waktu Kirim
                    </h2>
                    <p class="small text-muted mb-0">
                        Hitung jarak pelayaran laut (*sea freight*), estimasi waktu tempuh normal, dan dampak keterlambatan (*delay penalty*) dinamis berdasarkan kondisi cuaca, geopolitik, dan kemacetan pelabuhan.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Simulator Control Section & Map -->
    <div class="row g-4 mb-4">
        <!-- Control Form & Parameters -->
        <div class="col-12 col-xl-5">
            <div class="glass-card p-4 h-100 d-flex flex-column justify-content-between">
                <div>
                    <div class="border-bottom pb-3 mb-3" style="border-color: rgba(255,255,255,0.12) !important;">
                        <h5 class="fw-bold text-white mb-1">
                            <i class="fa-solid fa-route text-warning me-2"></i> Konfigurasi Rute & Parameter Pelayaran
                        </h5>
                        <div class="small text-muted">Pilih terminal pelabuhan asal & tujuan serta sensitivitas risiko.</div>
                    </div>

                    <form id="maritimeSimForm">
                        <!-- Origin & Destination Port Selector -->
                        <div class="row g-3 mb-3">
                            <div class="col-12 col-md-6">
                                <label class="form-label small fw-bold text-info mb-1">
                                    <i class="fa-solid fa-flag-checkered me-1"></i> Negara Asal:
                                </label>
                                <select id="originCountrySelect" class="form-select bg-dark text-white border-secondary small"></select>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label small fw-bold text-info mb-1">
                                    <i class="fa-solid fa-anchor me-1"></i> Pelabuhan Asal:
                                </label>
                                <select id="originPortSelect" class="form-select bg-dark text-white border-secondary small"></select>
                            </div>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-12 col-md-6">
                                <label class="form-label small fw-bold text-danger mb-1">
                                    <i class="fa-solid fa-flag me-1"></i> Negara Tujuan:
                                </label>
                                <select id="destCountrySelect" class="form-select bg-dark text-white border-secondary small"></select>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label small fw-bold text-danger mb-1">
                                    <i class="fa-solid fa-anchor me-1"></i> Pelabuhan Tujuan:
                                </label>
                                <select id="destPortSelect" class="form-select bg-dark text-white border-secondary small"></select>
                            </div>
                        </div>

                        <!-- Vessel Speed Selection -->
                        <div class="mb-4 p-3 rounded bg-dark border border-secondary">
                            <label class="form-label small fw-bold text-warning mb-2 d-block">
                                <i class="fa-solid fa-gauge-high me-1"></i> Kecepatan Kapal Kargo (*Vessel Speed*):
                            </label>
                            <select id="vesselSpeedSelect" class="form-select bg-dark text-white border-secondary small">
                                <option value="14">14 Knots (~622 km/hari) - Eco Slow Steaming / Bulk Carrier</option>
                                <option value="18" selected>18 Knots (~800 km/hari) - Standard Container Ship (Recommended)</option>
                                <option value="24">24 Knots (~1,066 km/hari) - Fast Express Feeder / Refrigerated</option>
                            </select>
                        </div>

                        <!-- Automated Risk Engine Sync Info -->
                        <div class="mb-4 p-3 rounded bg-dark border border-info shadow-sm" style="background: rgba(0, 242, 254, 0.06) !important; border-color: rgba(0, 242, 254, 0.4) !important;">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <div class="d-flex align-items-center">
                                    <i class="fa-solid fa-satellite-dish text-info fs-5 me-2"></i>
                                    <span class="fw-bold text-white small">Risk Engine Sync: <span id="autoSyncCountryName" class="text-warning">Netherlands</span></span>
                                </div>
                                <span class="badge bg-info text-dark fw-bold"><i class="fa-solid fa-link me-1"></i> Auto-Connected</span>
                            </div>
                            <p class="small text-muted mb-2" style="font-size: 12.5px;">
                                Indeks risiko eksternal (cuaca maritim, geopolitik/blokade, kongesti pelabuhan, dan volatilitas valas) kini <strong class="text-white">otomatis menyesuaikan</strong> dengan skor risiko negara dari Country Dashboard.
                            </p>
                            <div id="syncedIndicatorsBadge" class="d-flex flex-wrap gap-2 pt-1 border-top" style="border-color: rgba(255,255,255,0.1) !important; font-size: 11.5px;">
                                <span class="badge bg-dark border border-secondary text-light"><i class="fa-solid fa-cloud-showers-heavy text-info me-1"></i> Cuaca: <strong id="syncWeatherVal">-</strong></span>
                                <span class="badge bg-dark border border-secondary text-light"><i class="fa-solid fa-globe text-danger me-1"></i> Geopolitik: <strong id="syncNewsVal">-</strong></span>
                                <span class="badge bg-dark border border-secondary text-light"><i class="fa-solid fa-boxes-stacked text-warning me-1"></i> Kongesti: <strong id="syncInflationVal">-</strong></span>
                                <span class="badge bg-dark border border-secondary text-light"><i class="fa-solid fa-money-bill-transfer text-success me-1"></i> Valas: <strong id="syncCurrencyVal">-</strong></span>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="border-top pt-3 mt-4 small text-muted" style="border-color: rgba(255,255,255,0.12) !important;">
                    <i class="fa-solid fa-circle-info text-warning me-1"></i> Garis rute maritim (*dashed polyline*) dihitung berdasarkan koordinat geodesic pelabuhan.
                </div>
            </div>
        </div>

        <!-- Exclusive Dashed Polyline Map Section -->
        <div class="col-12 col-xl-7">
            <div class="glass-card p-4 h-100 d-flex flex-column">
                <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
                    <div>
                        <h5 class="fw-bold text-white mb-0">
                            <i class="fa-solid fa-map-location-dot text-info me-2"></i> Peta Rute Pelayaran Maritim (*Exclusive Simulator Map*)
                        </h5>
                        <span class="small text-muted">Menampilkan garis patah-patah rute kapal kargo dari titik awal ke titik akhir.</span>
                    </div>
                    <div id="simRiskCategoryBadge" class="badge bg-success px-3 py-2 fs-6 fw-bold">
                        <i class="fa-solid fa-shield me-1"></i> LOW RISK (25/100)
                    </div>
                </div>

                <div id="maritimeRouteMapContainer" class="flex-grow-1 rounded overflow-hidden border border-secondary shadow-lg" style="min-height: 480px; position: relative;">
                    <div id="maritimeRouteMap" style="width: 100%; height: 100%; min-height: 480px; background: #0f172a;"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Output KPI Cards & Delay Breakdown Summary -->
    <div class="row g-4 mb-4">
        <div class="col-12 col-md-3">
            <div class="glass-card p-3 text-center h-100 border border-info">
                <span class="small text-muted d-block text-uppercase fw-bold"><i class="fa-solid fa-ruler-horizontal me-1"></i> Jarak Maritim (Geodesic)</span>
                <h3 id="simDistanceNm" class="fw-bold text-info my-2">- NM</h3>
                <span id="simDistanceKm" class="small text-muted">- km</span>
            </div>
        </div>
        <div class="col-12 col-md-3">
            <div class="glass-card p-3 text-center h-100 border border-secondary">
                <span class="small text-muted d-block text-uppercase fw-bold"><i class="fa-solid fa-stopwatch me-1"></i> Waktu Tempuh Normal</span>
                <h3 id="simBaseDuration" class="fw-bold text-white my-2">- Hari</h3>
                <span class="small text-muted">Tanpa gangguan risiko eksternal</span>
            </div>
        </div>
        <div class="col-12 col-md-3">
            <div class="glass-card p-3 text-center h-100 border border-warning" style="background: rgba(245, 158, 11, 0.08);">
                <span class="small text-warning d-block text-uppercase fw-bold"><i class="fa-solid fa-triangle-exclamation me-1"></i> Penalti Keterlambatan (Delay)</span>
                <h3 id="simDelayDuration" class="fw-bold text-warning my-2">+0 Hari</h3>
                <span class="small text-muted">Berdasarkan skor Risk Engine</span>
            </div>
        </div>
        <div class="col-12 col-md-3">
            <div class="glass-card p-3 text-center h-100 border border-success" style="background: rgba(16, 185, 129, 0.08);">
                <span class="small text-success d-block text-uppercase fw-bold"><i class="fa-solid fa-calendar-check me-1"></i> Estimasi Kedatangan (ETA)</span>
                <h3 id="simTotalDuration" class="fw-bold text-success my-2">- Hari</h3>
                <span class="small text-muted">Total waktu pelayaran laut</span>
            </div>
        </div>
    </div>

    <!-- Delay Reasons & Mitigation Recommendations -->
    <div class="row g-4">
        <div class="col-12">
            <div class="glass-card p-4 border border-secondary">
                <h5 class="fw-bold text-white mb-3">
                    <i class="fa-solid fa-clipboard-list text-warning me-2"></i> Analisis Penyebab Keterlambatan & Rekomendasi Mitigasi Logistik
                </h5>
                <div id="simBreakdownList">
                    <div class="p-3 rounded border border-secondary bg-dark text-muted small">
                        Memuat kalkulasi dampak risiko rantai pasok maritim...
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/maritime-route.js') }}"></script>
@endpush
