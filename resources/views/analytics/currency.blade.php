@extends('layouts.app')

@section('title', 'Currency Impact Dashboard')

@section('content')
<div class="container-fluid py-3" style="color: #f8fafc;">
    <!-- HEADER SECTION (Tanpa tombol & badge Gambar ke 1 yang dihapus sesuai permintaan) -->
    <div class="glass-card mb-4 p-4 border-start border-warning border-4 shadow-lg d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <h1 class="h3 fw-bold mb-0 text-white">Currency Impact Dashboard</h1>
            </div>
            <p class="text-muted mb-0 fw-semibold">Pemantauan Nilai Tukar Valuta Asing &bull; Grafik Perubahan Kurs Real-Time (195 Negara Berdaulat)</p>
        </div>
    </div>

    <!-- TOP SELECTOR (Pencarian & Negara Aktif PERSIS SEPERTI FITUR LAIN) -->
    <div class="row align-items-center mb-4 g-3">
        <!-- Kartu Kiri: Negara & Mata Uang Aktif -->
        <div class="col-12 col-xl-5">
            <div class="glass-card p-3 d-flex align-items-center justify-content-between flex-wrap gap-2 h-100 border border-secondary shadow-sm">
                <div class="overflow-hidden text-truncate pe-2">
                    <span class="small text-uppercase fw-bold d-block text-truncate" style="color: #e2e8f0; letter-spacing: 0.8px;">Negara Analisis Aktif</span>
                    <h3 id="selectedCountryName" class="fw-bold text-white mb-0 mt-1 text-truncate">-</h3>
                </div>
                <div class="d-flex align-items-center gap-2 flex-wrap ms-auto">
                    <button type="button" class="btn btn-sm btn-outline-info fw-bold px-3 py-2 flex-shrink-0" onclick="(window.resetCurrToGlobal && window.resetCurrToGlobal()) || (window.resetToAdminFeed && window.resetToAdminFeed())" title="Reset pilihan negara & langsung buka dropdown untuk memilih negara baru" style="border-radius: 8px;">
                        <i class="fa-solid fa-rotate-left me-1"></i> Reset
                    </button>
                    <div class="text-end ms-1 flex-shrink-0">
                        <span id="selectedCountryRegion" class="badge bg-secondary mb-1 px-3 py-1 fw-bold text-white">-</span>
                        <div id="selectedCountryCurrency" class="text-warning small fw-bold mt-1" style="font-size: 14px;">-</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kartu Kanan: Kotak Pencarian 195 Negara Berdaulat -->
        <div class="col-12 col-xl-7" style="position: relative; z-index: 100;">
            <div class="glass-card p-3 border border-secondary shadow-sm" style="overflow: visible !important;">
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

    <!-- BAGIAN UTAMA: GRAFIK PERUBAHAN KURS (LEBAR PENUH COL-12, KARENA TABEL DAN KARTU KPI DIHAPUS) -->
    <div class="row g-4">
        <div class="col-12">
            <div class="glass-card p-4 h-100 d-flex flex-column justify-content-between border border-secondary shadow-lg">
                <div>
                    <!-- Header Grafik & Filter Periode -->
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3 pb-3 border-bottom border-secondary gap-2">
                        <div>
                            <h4 class="fw-bold text-white mb-1">
                                <i class="fa-solid fa-chart-area text-warning me-2"></i> Grafik Perubahan Kurs (<span id="chartCurrTitle">-</span>)
                            </h4>
                            <small class="text-muted">Tren pergerakan dan fluktuasi historis nilai tukar mata uang terpilih terhadap Dolar AS (USD) menggunakan <span class="badge bg-dark text-warning border border-warning">Chart.js</span></small>
                        </div>
                        <div class="btn-group btn-group-sm" role="group">
                            <button type="button" class="btn btn-outline-warning period-btn active" data-period="30">30 Hari</button>
                            <button type="button" class="btn btn-outline-warning period-btn" data-period="90">90 Hari</button>
                            <button type="button" class="btn btn-outline-warning period-btn" data-period="180">6 Bulan</button>
                            <button type="button" class="btn btn-outline-warning period-btn" data-period="365">1 Tahun</button>
                        </div>
                    </div>

                    <!-- Canvas Chart.js (Tinggi proporsional & cerah) -->
                    <div style="height: 450px; width: 100%; position: relative; background: rgba(15, 23, 42, 0.6); padding: 20px; border-radius: 10px; border: 1px solid rgba(255,255,255,0.08);">
                        <canvas id="currencyTrendChartCanvas"></canvas>
                    </div>
                </div>

                <!-- Statistik Bawah Grafik (Tertinggi, Terendah, Rata-Rata) -->
                <div class="row g-3 mt-3 pt-3 border-top border-secondary text-center small">
                    <div class="col-12 col-md-4">
                        <div class="p-3 rounded bg-dark border border-secondary">
                            <span class="text-muted d-block fw-semibold mb-1" style="font-size: 12px;"><i class="fa-solid fa-arrow-trend-up text-danger me-1"></i> Kurs Tertinggi (High):</span>
                            <strong class="text-danger fs-5" id="statHighRate">-</strong>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="p-3 rounded bg-dark border border-secondary">
                            <span class="text-muted d-block fw-semibold mb-1" style="font-size: 12px;"><i class="fa-solid fa-arrow-trend-down text-success me-1"></i> Kurs Terendah (Low):</span>
                            <strong class="text-success fs-5" id="statLowRate">-</strong>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="p-3 rounded bg-dark border border-secondary">
                            <span class="text-muted d-block fw-semibold mb-1" style="font-size: 12px;"><i class="fa-solid fa-scale-balanced text-info me-1"></i> Rata-Rata Periode (Average):</span>
                            <strong class="text-info fs-5" id="statAvgRate">-</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/currency_dashboard.js') }}"></script>
@endpush
