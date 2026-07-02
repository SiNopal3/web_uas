<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RiskIntel - Supply Chain Intelligence Platform</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome untuk Icon -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { background-color: #f4f7f6; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .sidebar { height: 100vh; background: #0f172a; color: #94a3b8; width: 260px; position: fixed; transition: all 0.3s; z-index: 1000; }
        .sidebar .nav-link { color: #94a3b8; padding: 12px 20px; display: flex; align-items: center; gap: 12px; border-radius: 8px; margin: 4px 15px; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { background: #1e293b; color: #f8fafc; }
        .main-content { margin-left: 260px; padding: 30px; transition: all 0.3s; }
        .card { border: none; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); background: #ffffff; }
        .stat-card { border-left: 4px solid #10b981; }
        .badge-risk { font-size: 0.9rem; padding: 6px 12px; border-radius: 30px; font-weight: bold; }
    </style>
</head>
<body>

<!-- Sidebar Navigasi -->
<div class="sidebar py-3">
    <div class="px-4 mb-4 d-flex align-items-center gap-2 text-white">
        <i class="fa-solid fa-cubes-stacked text-primary fs-4"></i>
        <span class="fs-5 fw-bold">RiskIntel</span>
    </div>
    <ul class="nav flex-column">
        <li class="nav-item"><a class="nav-link active" href="#"><i class="fa-solid fa-chart-pie"></i> Dasbor</a></li>
        <li class="nav-item"><a class="nav-link" href="#"><i class="fa-solid fa-earth-americas"></i> Dasbor Negara</a></li>
        <li class="nav-item"><a class="nav-link" href="#"><i class="fa-solid fa-cloud-sun"></i> Peta Cuaca</a></li>
        <li class="nav-item"><a class="nav-link" href="#"><i class="fa-solid fa-newspaper"></i> Intelijen Berita</a></li>
        <li class="nav-item"><a class="nav-link" href="#"><i class="fa-solid fa-anchor"></i> Lokasi Pelabuhan</a></li>
    </ul>
</div>

<!-- Konten Utama -->
<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark m-0">Dasbor Analitik</h2>
            <p class="text-muted m-0">Sistem Intelijen Risiko Rantai Pasok Global</p>
        </div>
        <div class="text-muted fw-semibold" id="liveClock"><i class="fa-regular fa-clock me-1"></i> Memuat Waktu...</div>
    </div>

    <!-- Baris Atas: Ringkasan Umum (Summary Cards) -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card p-3 stat-card" style="border-left-color: #3b82f6;">
                <span class="text-muted text-uppercase fw-bold small">Jumlah Negara</span>
                <h3 class="fw-bold my-1 text-dark" id="totalCountries">-</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 stat-card" style="border-left-color: #10b981;">
                <span class="text-muted text-uppercase fw-bold small">Skor Risiko Rata-Rata</span>
                <h3 class="fw-bold my-1 text-dark" id="avgRiskScore">38.5</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 stat-card" style="border-left-color: #f59e0b;">
                <span class="text-muted text-uppercase fw-bold small">Jumlah Pelabuhan</span>
                <h3 class="fw-bold my-1 text-dark">15</h3>
            </div>
        </div>
    </div>

    <!-- Baris Tengah: Dropdown Pemilihan Negara & Detail Ringkas -->
    <div class="card p-4 mb-4">
        <div class="row align-items-center">
            <div class="col-md-4">
                <label class="form-label fw-bold text-muted small text-uppercase">Pilih Negara Pemantauan</label>
                <select id="countrySelect" class="form-select form-select-lg">
                    <option value="">-- Memuat Data Negara... --</option>
                </select>
            </div>
            <div class="col-md-8" id="quickOverview" style="display: none;">
                <div class="row text-center border-start">
                    <div class="col-md-3">
                        <span class="text-muted d-block small">SKOR RISIKO</span>
                        <span class="fw-bold text-danger fs-5" id="txtRiskScore">-</span>
                        <span class="d-block text-uppercase small fw-bold text-muted" id="txtRiskStatus">-</span>
                    </div>
                    <div class="col-md-3">
                        <span class="text-muted d-block small">MATA UANG</span>
                        <span class="fw-bold text-dark fs-5" id="txtCurrency">-</span>
                    </div>
                    <div class="col-md-3">
                        <span class="text-muted d-block small">WILAYAH</span>
                        <span class="fw-bold text-dark fs-5" id="txtRegion">-</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Baris Bawah: Panel Informasi Cuaca, Kurs, dan Grafik Visualisasi -->
    <div class="row" id="detailGrid" style="display: none;">
        <!-- Kolom Kiri: Cuaca & Grafik GDP -->
        <div class="col-md-6">
            <div class="card p-4 mb-4">
                <h5 class="fw-bold mb-3"><i class="fa-solid fa-cloud-sun text-info me-2"></i>Cuaca Saat Ini</h5>
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-3">
                        <i class="fa-solid fa-cloud text-secondary fs-1"></i>
                        <div>
                            <h2 class="fw-bold m-0" id="wTemp">- °C</h2>
                            <span class="text-muted small">Informasi Cuaca Lokal</span>
                        </div>
                    </div>
                    <div class="text-end small">
                        <div class="text-muted">Kecepatan Angin: <span class="fw-bold text-dark" id="wWind">- km/jam</span></div>
                        <div class="text-muted">Curah Hujan: <span class="fw-bold text-dark" id="wRain">- mm</span></div>
                    </div>
                </div>
            </div>
            
            <div class="card p-4">
                <h5 class="fw-bold mb-3"><i class="fa-solid fa-chart-line text-primary me-2"></i>Tren Risiko & Ekonomi</h5>
                <canvas id="gdpChart"></canvas>
            </div>
        </div>

        <!-- Kolom Kanan: Kurs & Grafik Bar -->
        <div class="col-md-6">
            <div class="card p-4 mb-4">
                <h5 class="fw-bold mb-3"><i class="fa-solid fa-coins text-warning me-2"></i>Kondisi Kurs Valas</h5>
                <div class="p-3 bg-light rounded d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted small d-block">Mata Uang Acuan</span>
                        <h4 class="fw-bold m-0" id="baseCurrencyDisplay">1 USD = -</h4>
                    </div>
                    <i class="fa-solid fa-right-left text-muted fs-4"></i>
                </div>
            </div>

            <div class="card p-4">
                <h5 class="fw-bold mb-3"><i class="fa-solid fa-chart-bar text-warning me-2"></i>Currency Impact Dashboard</h5>
                <canvas id="currencyChart"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Baris Paling Bawah: Panel Intelijen Berita Global -->
    <div class="row mt-4" id="newsContainer" style="display: none;">
        <div class="col-md-12">
            <div class="card p-4 mb-4 border-top border-primary border-4">
                <h5 class="fw-bold mb-3"><i class="fa-regular fa-newspaper text-primary me-2"></i>Intelijen Berita Terkini</h5>
                <div class="row" id="newsGrid">
                    <!-- Kartu Berita akan dirender di sini via JavaScript -->
                    <div class="col-12 text-center text-muted py-3">
                        <div class="spinner-border text-primary spinner-border-sm me-2" role="status"></div>
                        Mengambil berita dari satelit GNews...
                    </div>
                </div>
            </div>
        </div>
    </div>

</div> <!-- Akhir Main Content -->

<script>
    let gdpChartInstance = null;
    let currencyChartInstance = null;

    // Mapping Koordinat Sederhana untuk API Cuaca berdasarkan pilihan Negara
    const countryCoordinates = {
        'Indonesia': { lat: -6.20, lng: 106.81 },
        'Germany': { lat: 52.52, lng: 13.41 },
        'China': { lat: 35.86, lng: 104.19 },
        'Australia': { lat: -25.27, lng: 133.77 }
    };

    document.addEventListener("DOMContentLoaded", function() {
        // Tampilkan Jam Real-time di Dasbor
        setInterval(() => {
            const now = new Date();
            document.getElementById('liveClock').innerHTML = `<i class="fa-regular fa-clock me-1"></i> ${now.toLocaleString('id-ID')}`;
        }, 1000);

        // 1. Fetch Dropdown Negara dari internal API
        fetch('/api/countries')
            .then(res => res.json())
            .then(data => {
                // UPDATE ANGKA JUMLAH NEGARA SECARA OTOMATIS
                document.getElementById('totalCountries').innerText = data.data.length;

                const select = document.getElementById('countrySelect');
                select.innerHTML = '<option value="">-- Pilih Negara --</option>';
                data.data.forEach(c => {
                    let opt = document.createElement('option');
                    opt.value = c.name;
                    opt.textContent = c.name;
                    select.appendChild(opt);
                });
            });

        // 2. Event Listener saat Dropdown Negara dipilih
        document.getElementById('countrySelect').addEventListener('change', function() {
            const countryName = this.value;
            if(!countryName) {
                document.getElementById('quickOverview').style.display = "none";
                document.getElementById('detailGrid').style.display = "none";
                document.getElementById('newsContainer').style.display = "none";
                return;
            }

            // Tampilkan kembali panel-panelnya
            document.getElementById('quickOverview').style.display = "flex";
            document.getElementById('detailGrid').style.display = "flex";
            document.getElementById('newsContainer').style.display = "flex";

            // Kosongkan loading berita setiap ganti negara
            document.getElementById('newsGrid').innerHTML = '<div class="col-12 text-center text-muted py-3"><div class="spinner-border text-primary spinner-border-sm me-2" role="status"></div>Mengambil berita terbaru...</div>';

            // A. Ambil Data Negara dari External REST Countries API
            fetch(`/api/external/country/${countryName}`)
                .then(res => res.json())
                .then(data => {
                    if(data.success) {
                        const curKey = Object.keys(data.currencies)[0];
                        document.getElementById('txtCurrency').innerText = curKey;
                        document.getElementById('txtRegion').innerText = data.region;
                        
                        // Jalankan API Kurs & Gambar Grafik
                        loadCurrencyAndCharts(curKey);
                    }
                });

            // B. Ambil Data Cuaca
            const coord = countryCoordinates[countryName] || { lat: 0, lng: 0 };
            fetch(`/api/external/weather/${coord.lat}/${coord.lng}`)
                .then(res => res.json())
                .then(data => {
                    if(data.success && data.data) {
                        document.getElementById('wTemp').innerText = `${data.data.temperature_2m} °C`;
                        document.getElementById('wWind').innerText = `${data.data.wind_speed_10m} km/jam`;
                        document.getElementById('wRain').innerText = `${data.data.rain} mm`;
                    }
                });

            // C. Ambil Prediksi Skor Risiko dari AI Engine
            fetch('/api/ai/predict-risk?weather=65&inflation=40&news=75&currency=30')
                .then(res => res.json())
                .then(data => {
                    if(data.success) {
                        document.getElementById('txtRiskScore').innerText = data.prediction.total_risk_score;
                        document.getElementById('txtRiskStatus').innerText = data.prediction.risk_status;
                    }
                });
                
            // D. Ambil Data Berita Global (GNews API)
            fetch('/api/external/news/logistics')
                .then(res => res.json())
                .then(data => {
                    const newsGrid = document.getElementById('newsGrid');
                    newsGrid.innerHTML = ''; 

                    if(data.success && data.articles && data.articles.length > 0) {
                        const topNews = data.articles.slice(0, 3);
                        topNews.forEach(article => {
                            const newsCard = `
                                <div class="col-md-4 mb-3">
                                    <div class="card h-100 shadow-sm border-0 bg-light">
                                        <img src="${article.image}" class="card-img-top rounded-top" alt="Cover Berita" style="height: 160px; object-fit: cover; background:#ddd;">
                                        <div class="card-body d-flex flex-column">
                                            <h6 class="fw-bold text-dark" style="font-size: 0.95rem; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                                ${article.title}
                                            </h6>
                                            <p class="text-muted small mb-3" style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
                                                ${article.description}
                                            </p>
                                            <a href="${article.url}" target="_blank" class="btn btn-sm btn-outline-primary mt-auto">Baca Selengkapnya <i class="fa-solid fa-arrow-up-right-from-square ms-1"></i></a>
                                        </div>
                                    </div>
                                </div>
                            `;
                            newsGrid.innerHTML += newsCard;
                        });
                    } else {
                        newsGrid.innerHTML = '<div class="col-12 text-center text-danger p-3"><i class="fa-solid fa-triangle-exclamation me-2"></i>Gagal memuat berita. API Key mungkin mencapai limit harian.</div>';
                    }
                })
                .catch(err => {
                    document.getElementById('newsGrid').innerHTML = '<div class="col-12 text-center text-muted">Koneksi ke server berita terputus.</div>';
                });
        });
    });

    // 3. Fungsi Menggambar Grafik Dinamis dengan Chart.js
    function loadCurrencyAndCharts(currencyCode) {
        fetch(`/api/external/currency/USD`)
            .then(res => res.json())
            .then(resData => {
                let currentRate = 1;
                if(resData.success && resData.rates) {
                    currentRate = resData.rates[currencyCode] || 1;
                    document.getElementById('baseCurrencyDisplay').innerText = `1 USD = ${currentRate.toLocaleString('id-ID')} ${currencyCode}`;
                }

                const ctxGdp = document.getElementById('gdpChart').getContext('2d');
                const ctxCurrency = document.getElementById('currencyChart').getContext('2d');

                if(gdpChartInstance) gdpChartInstance.destroy();
                if(currencyChartInstance) currencyChartInstance.destroy();

                gdpChartInstance = new Chart(ctxGdp, {
                    type: 'line',
                    data: {
                        labels: ['2022', '2023', '2024', '2025', '2026'],
                        datasets: [{
                            label: 'PDB Nominal (Triliun USD)',
                            data: [1.1, 1.3, 1.4, 1.45, 1.5],
                            borderColor: '#3b82f6',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            fill: true,
                            tension: 0.3
                        }]
                    }
                });

                currencyChartInstance = new Chart(ctxCurrency, {
                    type: 'bar',
                    data: {
                        labels: ['Minggu 1', 'Minggu 2', 'Minggu 3', 'Minggu 4'],
                        datasets: [{
                            label: `Nilai Tukar Ke ${currencyCode}`,
                            data: [currentRate * 0.98, currentRate * 0.99, currentRate, currentRate * 1.01],
                            backgroundColor: '#f59e0b',
                            borderRadius: 6
                        }]
                    }
                });
            });
    }
</script>

</body>
</html>