@extends('layouts.app')

@section('title', 'AI Risk Simulator & Formula - RiskIntel Hub')

@section('content')
<div class="container-fluid p-0">
    <!-- Top Selector and Country Header -->
    <div class="row align-items-center mb-4 g-3">
        <div class="col-12 col-xl-5">
            <div class="glass-card p-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div class="overflow-hidden text-truncate pe-2">
                    <span class="small text-uppercase fw-bold d-block text-truncate" style="color: #e2e8f0; letter-spacing: 0.8px;">Negara Analisis Aktif</span>
                    <h3 id="selectedCountryName" class="fw-bold text-white mb-0 mt-1 text-truncate">-</h3>
                </div>
                <div class="d-flex align-items-center gap-2 flex-wrap ms-auto">
                    <button type="button" class="btn btn-sm btn-outline-info fw-bold px-3 py-2 flex-shrink-0" onclick="window.resetToAdminFeed && window.resetToAdminFeed()" title="Reset pilihan negara & langsung buka dropdown untuk memilih negara baru" style="border-radius: 8px;">
                        <i class="fa-solid fa-rotate-left me-1"></i> Reset
                    </button>
                    <div class="text-end ms-1 flex-shrink-0">
                        <span id="selectedCountryRegion" class="badge bg-secondary mb-1 px-3 py-1 fw-bold text-white">-</span>
                        <div id="selectedCountryCurrency" class="text-warning small fw-bold mt-1" style="font-size: 14px;">-</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-xl-7" style="position: relative; z-index: 100;">
            <div class="glass-card p-3" style="overflow: visible !important;">
                <label for="countrySearchInput" class="form-label small mb-2 fw-bold d-block" style="color: #f8fafc; font-size: 13px;">
                    <i class="fa-solid fa-magnifying-glass me-1 text-warning"></i> Cari Negara:
                </label>
                <div style="position: relative;">
                    <input type="text" id="countrySearchInput" class="form-control bg-dark text-white border-secondary shadow-none" placeholder="Ketik nama atau awalan negara (contoh: Afghanistan, Indonesia, Germany, Japan...)" autocomplete="off" style="border-radius: 6px;">
                    <div id="countryDropdownList" class="dropdown-menu w-100 bg-dark border border-warning shadow-lg p-0" style="position: absolute; top: 100%; left: 0; z-index: 9999; max-height: 280px; overflow-y: auto; display: none; margin-top: 4px; border-radius: 6px;"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Interactive AI Risk Simulator -->
        <div class="col-12 col-xl-6">
            <div id="riskSimulatorSection" class="glass-card h-100 d-flex flex-column justify-content-between">
                <div>
                    <div class="border-bottom pb-3 mb-3" style="border-color: rgba(255,255,255,0.12) !important;">
                        <h5 class="fw-bold text-white mb-1">
                            <i class="fa-solid fa-sliders text-warning me-2"></i> AI Supply Chain Risk Simulator
                        </h5>
                        <div class="small" style="color: #cbd5e1;">Uji sensitivitas rantai pasok terhadap anomali cuaca maritim dan guncangan inflasi.</div>
                    </div>

                    <form id="riskSimulatorForm" class="mt-3">
                        <div class="row g-3 mb-3">
                            <div class="col-12 col-sm-6">
                                <label class="fw-bold mb-2 d-block" style="font-size: 13px; color: #e2e8f0;">
                                    <i class="fa-solid fa-wind text-info me-1"></i> Kecepatan Angin Laut (m/s):
                                </label>
                                <input type="number" id="simWind" class="form-control bg-dark text-white border-secondary p-2" value="15" step="1" required>
                                <span class="small" style="color: #cbd5e1; font-size: 11px;">Ambang badai: > 20 m/s (Cuaca ekstrem)</span>
                            </div>
                            <div class="col-12 col-sm-6">
                                <label class="fw-bold mb-2 d-block" style="font-size: 13px; color: #e2e8f0;">
                                    <i class="fa-solid fa-chart-line text-warning me-1"></i> Tingkat Inflasi Makro (%):
                                </label>
                                <input type="number" id="simInf" class="form-control bg-dark text-white border-secondary p-2" value="5.2" step="0.1" required>
                                <span class="small" style="color: #cbd5e1; font-size: 11px;">Ambang kritis inflasi: > 8%</span>
                            </div>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-12 col-sm-6">
                                <label class="fw-bold mb-2 d-block" style="font-size: 13px; color: #e2e8f0;">
                                    <i class="fa-solid fa-newspaper text-success me-1"></i> Sentimen Berita Logistik (0-100):
                                </label>
                                <input type="number" id="simNews" class="form-control bg-dark text-white border-secondary p-2" value="50" step="1" required>
                                <span class="small" style="color: #cbd5e1; font-size: 11px;">0 = Sangat Positif, 100 = Krisis Negatif</span>
                            </div>
                            <div class="col-12 col-sm-6">
                                <label class="fw-bold mb-2 d-block" style="font-size: 13px; color: #e2e8f0;">
                                    Volatilitas Valas (%):
                                </label>
                                <input type="number" id="simCurr" class="form-control bg-dark text-white border-secondary p-2" value="10" step="1" required>
                                <span class="small" style="color: #cbd5e1; font-size: 11px;">Fluktuasi terhadap USD</span>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-outline-warning fw-bold py-2 w-100 fs-6">
                            <i class="fa-solid fa-bolt me-2"></i> Jalankan Simulasi Kalkulasi Risiko
                        </button>
                    </form>
                    <div id="simResultBox"></div>
                </div>

                <div class="border-top pt-3 mt-4 small" style="color: #94a3b8; border-color: rgba(255,255,255,0.12) !important;">
                    <i class="fa-solid fa-check-double text-warning me-1"></i> Kalkulasi bobot diproses secara deterministik di server-side (`RiskScoringService`).
                </div>
            </div>
        </div>

        <!-- Weighted Risk Model Breakdown (PDF Specification) -->
        <div class="col-12 col-xl-6">
            <div class="glass-card h-100 d-flex flex-column justify-content-between">
                <div>
                    <div class="border-bottom pb-3 mb-3" style="border-color: rgba(255,255,255,0.12) !important;">
                        <h5 class="fw-bold text-white mb-1">
                            <i class="fa-solid fa-calculator text-warning me-2"></i> Spesifikasi Rumus Weighted Risk Model
                        </h5>
                        <div class="small" style="color: #cbd5e1;">Akurasi matematis sesuai dokumen Project Final (Studi Kasus Rantai Pasok).</div>
                    </div>

                    <div class="p-3 mb-4 rounded" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.14);">
                        <code class="d-block text-warning fs-6 mb-2">Total Risk Score = Weather (30%) + Inflation (20%) + News Sentiment (40%) + Currency (10%)</code>
                        <p class="small mb-0" style="color: #e2e8f0; line-height: 1.6;">
                            Rumus pembobotan ini menjamin bahwa gangguan geopolitik/berita dan anomali cuaca memiliki kontribusi terbesar terhadap keterlambatan armada kapal logistik global.
                        </p>
                    </div>

                    <h6 class="fw-bold text-white mb-3">Tabel Ambang Batas Klasifikasi Risiko:</h6>
                    <div class="table-responsive">
                        <table class="table table-dark table-bordered small mb-0" style="border-color: rgba(255,255,255,0.15) !important;">
                            <thead>
                                <tr style="background: rgba(255,255,255,0.08);">
                                    <th class="text-white py-2">Rentang Skor</th>
                                    <th class="text-white py-2">Klasifikasi Output</th>
                                    <th class="text-white py-2">Rekomendasi Tindakan Bisnis</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="fw-bold text-success">0 - 35 Poin</td>
                                    <td><span class="badge bg-success px-2 py-1">LOW RISK</span></td>
                                    <td style="color: #cbd5e1;">Rute logistik stabil. Lanjutkan pengiriman normal.</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold text-warning">36 - 65 Poin</td>
                                    <td><span class="badge bg-warning text-dark px-2 py-1">MEDIUM RISK</span></td>
                                    <td style="color: #cbd5e1;">Siapkan rute alternatif & lindung nilai (hedging) valas.</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold text-danger">66 - 100 Poin</td>
                                    <td><span class="badge bg-danger px-2 py-1">HIGH RISK ALERT</span></td>
                                    <td style="color: #cbd5e1;">Tunda keberangkatan kapal atau alihkan ke pelabuhan sekunder.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="border-top pt-3 mt-4 small" style="color: #94a3b8; border-color: rgba(255,255,255,0.12) !important;">
                    <i class="fa-solid fa-scale-balanced text-warning me-1"></i> Teruji dengan 100% akurasi di unit test (`RiskScoringUnitTest.php`).
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
