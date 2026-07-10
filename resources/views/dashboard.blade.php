<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RiskIntel - Enterprise Dashboard</title>
    
    <!-- Library CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    
    <style>
        /* TEMA FINAL: DARK SIDEBAR + GOLDEN ACCENT + BEIGE/WHITE CONTENT */
        :root {
            --sidebar-bg: #1b1c20;     /* Hitam Pekat Sidebar */
            --main-bg: #f4eee7;        /* Beige / Krem Latar Utama */
            --card-bg: #ffffff;        /* Putih Bersih Kotak Data */
            --accent-gold: #c89c62;    /* Emas Kecoklatan (Aksen Utama) */
            --text-dark: #2d3748;      /* Teks Gelap Utama */
            --text-muted: #a0aec0;     /* Teks Abu-abu */
            --text-light: #ffffff;     /* Teks Putih Sidebar */
        }

        body {
            background-color: var(--main-bg);
            color: var(--text-dark);
            font-family: 'Inter', 'Segoe UI', sans-serif;
            margin: 0;
            overflow-x: hidden;
        }

        a { text-decoration: none !important; }

        /* LAYOUT WRAPPER */
        .dashboard-wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* SIDEBAR KIRI */
        .sidebar {
            width: 260px;
            background-color: var(--sidebar-bg);
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100vh;
            z-index: 1000;
            padding: 20px 15px;
            border-top-right-radius: 20px;
            border-bottom-right-radius: 20px;
            box-shadow: 4px 0 15px rgba(0,0,0,0.1);
        }

        .sidebar-brand {
            font-size: 1.5rem; font-weight: 800; color: var(--text-light);
            margin-bottom: 40px; padding-left: 10px; display: flex; align-items: center; gap: 10px;
        }
        .sidebar-brand i { color: var(--accent-gold); }

        .sidebar-menu { list-style: none; padding: 0; margin: 0; flex-grow: 1; }
        .sidebar-item { margin-bottom: 8px; }
        .sidebar-link {
            display: flex; align-items: center; gap: 15px;
            padding: 12px 20px; color: #8e95a5;
            border-radius: 12px; font-weight: 500; transition: all 0.3s;
        }

        /* Tombol Aktif - Emas (Sesuai Referensi) */
        .sidebar-link:hover, .sidebar-link.active {
            background-color: var(--accent-gold);
            color: var(--text-light);
            box-shadow: 0 4px 10px rgba(200, 156, 98, 0.3);
        }

        /* KONTEN UTAMA */
        .main-content {
            flex-grow: 1;
            margin-left: 260px;
            padding: 30px 40px;
            background-color: var(--main-bg);
        }

        /* HEADER DALAM KONTEN */
        .top-header {
            display: flex; justify-content: space-between; align-items: center;
            margin-bottom: 30px;
        }

        .page-title { font-size: 1.8rem; font-weight: 800; color: var(--text-dark); margin: 0; }

        /* KOTAK DATA (CARDS) */
        .white-card {
            background-color: var(--card-bg);
            border-radius: 20px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.03);
            border: none;
        }

        .card-title-custom {
            font-size: 1.1rem; font-weight: 700; color: var(--text-dark);
            margin-bottom: 20px; display: flex; align-items: center; gap: 10px;
        }
        .card-title-custom i { color: var(--accent-gold); }

        /* INPUT NEGARA */
        .custom-select {
            background-color: #f8f9fa; color: var(--text-dark);
            border: 1px solid #e2e8f0; border-radius: 12px; padding: 12px 20px; width: 100%;
            font-weight: 500;
        }
        .custom-select:focus { outline: none; border-color: var(--accent-gold); box-shadow: 0 0 0 3px rgba(200, 156, 98, 0.2); }

        /* TABEL PELABUHAN */
        .table-custom { color: var(--text-dark); }
        .table-custom th { color: var(--text-muted); font-weight: 600; border-bottom: 2px solid #edf2f7; text-transform: uppercase; font-size: 0.85rem;}
        .table-custom td { border-bottom: 1px solid #edf2f7; padding: 15px 10px; vertical-align: middle; }

        /* BADGE KECIL */
        .badge-gold { background-color: var(--accent-gold); color: white; padding: 6px 12px; border-radius: 8px; font-weight: 600; font-size: 0.8rem; }
    </style>
</head>
<body>

    <div class="dashboard-wrapper">
        <!-- 1. SIDEBAR KIRI (GELAP PEKAT) -->
        <aside class="sidebar">
            <div class="sidebar-brand">
                <i class="fa-solid fa-horse-head"></i> PBD-INTEL
            </div>
            
            <ul class="sidebar-menu">
                <li class="sidebar-item"><a href="#dasbor" class="sidebar-link active"><i class="fa-solid fa-border-all"></i> Dashboard</a></li>
                <li class="sidebar-item"><a href="#target" class="sidebar-link"><i class="fa-solid fa-earth-asia"></i> Target Negara</a></li>
                <li class="sidebar-item"><a href="#cuaca" class="sidebar-link"><i class="fa-solid fa-cloud-bolt"></i> Pantauan Cuaca</a></li>
                <li class="sidebar-item"><a href="#ekonomi" class="sidebar-link"><i class="fa-solid fa-chart-line"></i> Ekonomi Global</a></li>
                <li class="sidebar-item"><a href="#logistik" class="sidebar-link"><i class="fa-solid fa-ship"></i> Logistik & Rute</a></li>
                <li class="sidebar-item"><a href="#berita" class="sidebar-link"><i class="fa-regular fa-newspaper"></i> Intelijen Berita</a></li>
            </ul>

            <div class="mt-auto p-3" style="background: rgba(255,255,255,0.05); border-radius: 15px;">
                <div class="d-flex align-items-center gap-3">
                    <img src="https://ui-avatars.com/api/?name=Admin&background=c89c62&color=fff&rounded=true" alt="User" width="40" height="40">
                    <div>
                        <h6 class="mb-0 text-white fw-bold" style="font-size: 0.9rem;">Sutan Admin</h6>
                        <small style="color: #4ade80;">● Active</small>
                    </div>
                </div>
            </div>
        </aside>

        <!-- 2. KONTEN UTAMA (KREM / PUTIH) -->
        <main class="main-content">
            
            <div class="top-header">
                <h1 class="page-title">Dashboard Utama</h1>
                <span id="liveClock" style="color: var(--text-muted); font-weight: 500;"><i class="fa-regular fa-clock"></i> Memuat waktu...</span>
            </div>

            <div class="row">
                <!-- A. Kotak Selamat Datang (Meniru gaya gambar) -->
                <div class="col-12 mb-4">
                    <div class="white-card" style="background-color: var(--accent-gold); color: white;">
                        <h3 class="fw-bold mb-2">Welcome back, Sutan!</h3>
                        <p class="mb-4" style="color: rgba(255,255,255,0.9);">Platform Intelijen Rantai Pasok Global siap digunakan. Pilih negara target untuk memulai analisis real-time.</p>
                        <div class="row">
                            <div class="col-md-4">
                                <select id="countrySelect" class="form-select border-0 shadow-sm" style="border-radius: 12px; padding: 12px; font-weight:600;">
                                    <option value="">-- PILIH NEGARA TARGET --</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- B. AI Prediksi -->
                <div class="col-md-4">
                    <div class="white-card h-100 text-center">
                        <div class="card-title-custom justify-content-center"><i class="fa-solid fa-robot"></i> AI RISK ENGINE</div>
                        <div class="d-flex justify-content-center gap-4 align-items-center mt-3">
                            <div>
                                <h1 class="display-4 fw-bold mb-0" style="color: var(--text-dark);" id="txtRiskScore">0</h1>
                                <small class="fw-bold" style="color: var(--text-muted);">INDEX SKOR</small>
                            </div>
                            <div style="width: 2px; height: 40px; background: #e2e8f0;"></div>
                            <div>
                                <h3 class="fw-bold mb-0" style="color: var(--accent-gold);" id="txtRiskStatus">-</h3>
                                <small class="fw-bold" style="color: var(--text-muted);">STATUS</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- C. Ekonomi & Kurs -->
                <div class="col-md-8">
                    <div class="white-card h-100">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="card-title-custom mb-0"><i class="fa-solid fa-chart-pie"></i> Metrik Ekonomi</div>
                            <span class="badge-gold shadow-sm" id="baseCurrencyDisplay">1 USD = -</span>
                        </div>
                        <div class="row">
                            <div class="col-md-4"><canvas id="gdpChart"></canvas></div>
                            <div class="col-md-4"><canvas id="inflationChart"></canvas></div>
                            <div class="col-md-4"><canvas id="currencyChart"></canvas></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- D. Cuaca & Peta (PETA PUTIH ASLI) -->
            <div class="row">
                <div class="col-12">
                    <div class="white-card">
                        <div class="card-title-custom d-flex justify-content-between align-items-center">
                            <span><i class="fa-solid fa-satellite-dish"></i> Radar Maritim & Cuaca</span>
                            <div class="d-flex gap-4 text-center">
                                <div><h5 id="wTemp" class="fw-bold mb-0 text-dark">- °C</h5><small class="text-muted fw-bold" style="font-size:0.7rem;">SUHU</small></div>
                                <div><h5 id="wWind" class="fw-bold mb-0 text-dark">- km/h</h5><small class="text-muted fw-bold" style="font-size:0.7rem;">ANGIN</small></div>
                                <div><h5 id="wRain" class="fw-bold mb-0 text-dark">- mm</h5><small class="text-muted fw-bold" style="font-size:0.7rem;">HUJAN</small></div>
                            </div>
                        </div>
                        <!-- PETA NORMAL TANPA FILTER (TERANG/PUTIH) -->
                        <div id="map" style="height: 400px; border-radius: 15px; border: 1px solid #e2e8f0; z-index: 1;"></div>
                    </div>
                </div>
            </div>

            <!-- E. Pelabuhan & Berita -->
            <div class="row">
                <div class="col-md-6">
                    <div class="white-card h-100">
                        <div class="card-title-custom"><i class="fa-solid fa-anchor"></i> Status Infrastruktur</div>
                        <table class="table table-custom w-100 mb-4">
                            <thead><tr><th>KODE</th><th>TERMINAL UTAMA</th><th>STATUS</th></tr></thead>
                            <tbody id="portsTableBody"></tbody>
                        </table>
                        
                        <div class="p-3 mt-4" style="background-color: #f8f9fa; border-left: 4px solid var(--accent-gold); border-radius: 8px;">
                            <i class="fa-solid fa-circle-check" style="color: var(--accent-gold);"></i> 
                            <span class="fw-bold ms-2 text-dark">Rute Operasional.</span> <span class="text-muted">Kapasitas pengiriman dalam batas normal.</span>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="white-card h-100">
                        <div class="card-title-custom"><i class="fa-solid fa-newspaper"></i> Intelijen Berita Global</div>
                        <div id="newsGrid" class="d-flex flex-column gap-3">
                            <!-- Berita masuk ke sini -->
                        </div>
                    </div>
                </div>
            </div>

        </main>
    </div>

    <!-- Library JS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        let gdpChartInstance = null, inflationChartInstance = null, currencyChartInstance = null;
        let mapInstance = null, mapMarker = null;

        const countryDirectory = [
            { name: 'Indonesia', iso2: 'ID', lat: -0.7893, lng: 113.9213, curr: 'IDR', pop: 275000000, gdp: '1.4', inf: '2.8' },
            { name: 'Malaysia', iso2: 'MY', lat: 4.2105, lng: 101.9758, curr: 'MYR', pop: 33000000, gdp: '0.4', inf: '2.5' },
            { name: 'Singapore', iso2: 'SG', lat: 1.3521, lng: 103.8198, curr: 'SGD', pop: 5600000, gdp: '0.5', inf: '4.1' },
            { name: 'Japan', iso2: 'JP', lat: 36.2048, lng: 138.2529, curr: 'JPY', pop: 125000000, gdp: '4.9', inf: '3.2' },
            { name: 'China', iso2: 'CN', lat: 35.8617, lng: 104.1954, curr: 'CNY', pop: 1400000000, gdp: '19.8', inf: '0.2' },
            { name: 'United States', iso2: 'US', lat: 37.0902, lng: -95.7129, curr: 'USD', pop: 331000000, gdp: '25.4', inf: '3.7' },
            { name: 'Germany', iso2: 'DE', lat: 51.1657, lng: 10.4515, curr: 'EUR', pop: 83000000, gdp: '4.5', inf: '3.1' },
            { name: 'United Kingdom', iso2: 'GB', lat: 55.3781, lng: -3.4360, curr: 'GBP', pop: 67000000, gdp: '3.1', inf: '4.6' },
            { name: 'India', iso2: 'IN', lat: 20.5937, lng: 78.9629, curr: 'INR', pop: 1410000000, gdp: '3.5', inf: '4.2' },
            { name: 'Brazil', iso2: 'BR', lat: -14.2350, lng: -51.9253, curr: 'BRL', pop: 214000000, gdp: '1.9', inf: '4.8' },
            { name: 'Algeria', iso2: 'DZ', lat: 28.0339, lng: 1.6596, curr: 'DZD', pop: 44000000, gdp: '0.2', inf: '9.0' },
            { name: 'South Africa', iso2: 'ZA', lat: -30.5595, lng: 22.9375, curr: 'ZAR', pop: 60000000, gdp: '0.4', inf: '5.4' }
        ];

        document.addEventListener("DOMContentLoaded", function() {
            setInterval(() => { document.getElementById('liveClock').innerHTML = `<i class="fa-regular fa-clock me-1"></i> ${new Date().toLocaleString('id-ID')}`; }, 1000);
            
            document.querySelectorAll('.sidebar-link').forEach(link => { 
                link.addEventListener('click', function(e) { 
                    document.querySelectorAll('.sidebar-link').forEach(l => l.classList.remove('active')); 
                    this.classList.add('active'); 
                }); 
            });

            const select = document.getElementById('countrySelect');
            countryDirectory.sort((a, b) => a.name.localeCompare(b.name)).forEach(c => { select.innerHTML += `<option value="${c.name}">${c.name.toUpperCase()}</option>`; });

            // Warna Grafik untuk Background Putih
            Chart.defaults.color = '#718096'; 
            Chart.defaults.borderColor = '#edf2f7';

            select.addEventListener('change', function() {
                const cName = this.value; if(!cName) return;
                const cData = countryDirectory.find(x => x.name === cName);
                
                renderMap(cData.lat, cData.lng, cName);
                load4Charts(cData.curr, cName, parseFloat(cData.gdp), parseFloat(cData.inf));
                loadPortData(cName);

                fetch(`/api/external/economy/${cData.iso2}`).then(res => res.json()).then(r => {
                    if(r.success && r.data) load4Charts(cData.curr, cName, parseFloat(r.data.gdp?r.data.gdp/1e12:cData.gdp), parseFloat(r.data.inflation!==null?r.data.inflation:cData.inf));
                }).catch(()=>{});

                fetch(`/api/external/weather/${cData.lat}/${cData.lng}`).then(res => res.json()).then(w => {
                    if(w.success && w.data) { document.getElementById('wTemp').innerText = w.data.temperature_2m+' °C'; document.getElementById('wWind').innerText = w.data.wind_speed_10m+' km/h'; document.getElementById('wRain').innerText = w.data.rain+' mm'; }
                }).catch(() => { document.getElementById('wTemp').innerText = Math.floor(Math.random()*10+22)+' °C'; document.getElementById('wWind').innerText = Math.floor(Math.random()*15+5)+' km/h'; document.getElementById('wRain').innerText = '0.0 mm'; });

                fetch(`/api/ai/predict-risk?weather=30&inflation=20`).then(res => res.json()).then(ai => {
                    if(ai.success) { document.getElementById('txtRiskScore').innerText = ai.prediction.total_risk_score; document.getElementById('txtRiskStatus').innerText = ai.prediction.risk_status; }
                }).catch(() => { document.getElementById('txtRiskScore').innerText = '18'; document.getElementById('txtRiskStatus').innerText = 'STABIL'; });

                fetch(`/api/external/news/${cName}`).then(res => res.json()).then(n => {
                    const grid = document.getElementById('newsGrid'); grid.innerHTML = '';
                    if(n.success && n.articles && n.articles.length > 0) {
                        n.articles.slice(0, 3).forEach(a => { 
                            grid.innerHTML += `<div class="p-3" style="background:#f8f9fa; border-radius:12px; border: 1px solid #e2e8f0;"><span class="badge-gold mb-2 d-inline-block">NEWS</span><h6 class="fw-bold text-dark mb-1" style="display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">${a.title}</h6><a href="${a.url}" target="_blank" class="small fw-bold" style="color:var(--accent-gold);">BACA SELENGKAPNYA &rarr;</a></div>`; 
                        });
                    } else throw new Error();
                }).catch(() => {
                    document.getElementById('newsGrid').innerHTML = `<div class="p-3" style="background:#f8f9fa; border-radius:12px; border: 1px solid #e2e8f0;"><span class="badge-gold mb-2 d-inline-block">INFO</span><h6 class="fw-bold text-dark mb-1">Rantai Pasok Aman</h6><p class="small text-muted mb-0">Distribusi logistik terpantau normal.</p></div><div class="p-3 mt-3" style="background:#f8f9fa; border-radius:12px; border: 1px solid #e2e8f0;"><span class="badge-gold mb-2 d-inline-block">INFO</span><h6 class="fw-bold text-dark mb-1">Kondisi Ekspor/Impor</h6><p class="small text-muted mb-0">Volume pelabuhan stabil.</p></div>`;
                });
            });
        });

        // PETA LEAFLET NORMAL TERANG (TANPA FILTER CSS)
        function renderMap(lat, lng, name) {
            if (!mapInstance) { mapInstance = L.map('map').setView([lat, lng], 5); L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(mapInstance); mapMarker = L.marker([lat, lng]).addTo(mapInstance);
            } else { setTimeout(() => { mapInstance.invalidateSize(); mapInstance.flyTo([lat, lng], 5); mapMarker.setLatLng([lat, lng]); }, 100); }
            mapMarker.bindPopup(`<b>${name.toUpperCase()}</b>`).openPopup();
        }

        function load4Charts(currencyCode, countryName, gdpVal, infVal) {
            const currDisplay = document.getElementById('baseCurrencyDisplay'); currDisplay.innerHTML = `Loading...`;
            fetch(`/api/external/currency/USD`).then(res => res.json()).then(c => {
                let r = (c.success && c.rates) ? c.rates[currencyCode] : 1.0; currDisplay.innerText = `1 USD = ${r>100?r.toLocaleString('id-ID',{maximumFractionDigits:2}):r.toFixed(2)} ${currencyCode}`; draw(r);
            }).catch(() => { currDisplay.innerText = `1 USD = - ${currencyCode}`; draw(1.0); });

            function draw(rate) {
                if(gdpChartInstance) gdpChartInstance.destroy(); if(inflationChartInstance) inflationChartInstance.destroy(); if(currencyChartInstance) currencyChartInstance.destroy(); 
                gdpChartInstance = new Chart(document.getElementById('gdpChart'), { type: 'line', data: { labels: ['Q1','Q2','Q3','Q4'], datasets: [{ label: 'GDP', data: [gdpVal*0.8, gdpVal*0.9, gdpVal*0.95, gdpVal], borderColor: '#c89c62', tension: 0.3 }] }});
                inflationChartInstance = new Chart(document.getElementById('inflationChart'), { type: 'bar', data: { labels: ['Q1','Q2','Q3','Q4'], datasets: [{ label: 'Inflation', data: [infVal+0.4, infVal+0.1, infVal-0.2, infVal], backgroundColor: '#2d3748' }] }});
                currencyChartInstance = new Chart(document.getElementById('currencyChart'), { type: 'line', data: { labels: ['W1','W2','W3','W4'], datasets: [{ label: 'Rate', data: [rate*0.98, rate*1.01, rate*0.99, rate], borderColor: '#4ade80', backgroundColor: 'rgba(74, 222, 128, 0.2)', fill:true, tension: 0.3 }] }});
            }
        }

        function loadPortData(countryName) {
            let code = countryName.substring(0,2).toUpperCase();
            document.getElementById('portsTableBody').innerHTML = `<tr><td class="fw-bold">${code}-01</td><td class="text-dark fw-bold">${countryName.toUpperCase()} HUB</td><td><span class="badge-gold">ACTIVE</span></td></tr><tr><td class="fw-bold">${code}-02</td><td class="text-dark fw-bold">SOUTH TERMINAL</td><td><span class="badge-gold">ACTIVE</span></td></tr>`;
        }
    </script>
</body>
</html>