# 🌍 Global Supply Chain Risk Intelligence Platform — RiskIntel Hub
Laravel PHP MySQL Bootstrap Leaflet

Sistem monitoring risiko rantai pasok global berbasis multi-API dan analitik data. Memantau secara otomatis estimasi pengiriman barang, perubahan kurs, kondisi cuaca, dan risiko logistik di 195 negara di seluruh dunia.

---

## 📋 Daftar Isi
- [Fitur Utama](#-fitur-utama)
- [Teknologi](#️-teknologi)
- [API yang Digunakan](#-api-yang-digunakan)
- [Instalasi & Deployment](#-instalasi--deployment)
- [Struktur Database](#-struktur-database)
- [REST API Endpoints](#-rest-api-endpoints)
- [Kontributor](#-kontributor)

---

## 🚀 Fitur Utama

### 1. Global Country Dashboard
- Pilih negara dari 195 negara berdaulat di seluruh dunia.
- Tampilkan data ekonomi real-time (GDP, inflasi, populasi), cuaca maritim, dan kurs mata uang secara langsung.
- Data ekonomi terintegrasi otomatis dari **World Bank API**.
- Data cuaca real-time terintegrasi dari **Open-Meteo API**.

### 2. Risk Scoring Engine
- Perhitungan risk score terotomatisasi berbasis **Weighted Risk Model** (Skala 0 - 100).
- Komponen & bobot indikator risiko:
  - **Weather Risk (30%)** — Kecepatan angin, gelombang laut, dan temperatur maritim.
  - **Political News Risk (30%)** — AI Lexicon Sentiment dari berita logistik & geopolitik.
  - **Congestion & Port Risk (20%)** — Kepadatan lalu lintas pelabuhan & infrastruktur.
  - **Currency Volatility Risk (10%)** — Volatilitas dan fluktuasi nilai tukar kurs.
  - **Macro-Economic Risk (10%)** — Laju inflasi & kestabilan ekonomi.
- Output: Risk Score (0-100) & Risk Level (**Low / Medium / High / Critical**).
- Visualisasi Risk Breakdown Chart dengan **Chart.js**.

### 3. Global Weather & Maritime Monitoring
- Peta dunia interaktif berbasis **Leaflet.js** dengan tile CartoDB Light.
- Menampilkan kondisi cuaca maritim (suhu, kecepatan angin, kelembapan) per lokasi pelabuhan dan negara.
- Marker pelabuhan dan cuaca dengan statistik kondisi real-time.
- Tabel pemantauan maritim global lengkap dengan filter dan pencarian instan.

### 4. Currency Impact Dashboard
- Nilai tukar mata uang real-time dari **ExchangeRate-API** untuk 195 mata uang dunia.
- Grafik tren fluktuasi multi-periode (30 hari, 90 hari, 6 bulan, 1 tahun) menggunakan **Chart.js**.
- Analisis dampak volatilitas kurs terhadap biaya operasional rantai pasok dan perdagangan luar negeri.

### 5. News Intelligence
- Stream berita logistik, perdagangan, dan pelayaran internasional dari **GNews API**.
- Pengelolaan artikel internal dan analisis riset oleh Administrator.
- Pengelompokan berita berdasarkan topik logistik, shipping, trade, dan ekonomi global.

### 6. Sentiment Analysis Engine (AI / Data Science)
- Engine Analisis Sentimen berbasis **Native PHP Lexicon Algorithm**.
- Kamus kata kunci positif (`positive_words`) dan negatif (`negative_words`) terkelola di database.
- Output klasifikasi otomatis per artikel: **Positive / Neutral / Negative** disertai nilai skor sentimen.
- Fitur **Interactive Sentiment Tester** untuk menguji analisis sentimen teks bebas secara langsung.

### 7. Port Location Dashboard
- Integrasi dataset pelabuhan dunia maritim dari **NGA World Port Index**.
- Peta interaktif Leaflet.js dengan marker koordinat presisi pelabuhan laut utama dunia.
- Pencarian pelabuhan cepat, filter negara, dan informasi detail (nama pelabuhan, kode ISO, koordinat, dan region).

### 8. Route & Delay Simulation
- Simulasikan rute pelayaran laut antar 65+ negara dan pelabuhan utama di dunia.
- Tampilan garis rute geodesik (*geodesic polyline*) di atas peta interaktif.
- Perkiraan jarak pengiriman dalam mil laut (*nautical miles* / km) dan estimasi transit waktu (*base transit days*).
- Integrasi **Risk Engine Sync** untuk kalkulasi penalti penundaan (*delay penalty calculation*).
- Rekomendasi mitigasi risiko logistik dan estimasi tanggal kedatangan (**ETA**).

### 9. Data Visualization Dashboard
- Grafik tren makro-ekonomi kuartalan per negara menggunakan **Chart.js**:
  - **GDP Trend Chart** (pertumbuhan kuartalan vs target nasional).
  - **Inflation Trend Chart** (laju CPI aktual vs target bank sentral 2.5%).
  - **Currency Volatility Trend Chart** (indeks stabilitas vs volatilitas pasar).
  - **Risk Score Trend Chart** (komposisi risiko logistik, maritim, dan keuangan).

### 10. Country Comparison Engine
- Bandingkan 2 negara secara *side-by-side* berdasarkan 5 parameter utama:
  1. **GDP & Growth** (skala ekonomi & pertumbuhan GDP).
  2. **Inflation & Price Stability** (laju inflasi CPI & skor risiko harga).
  3. **Supply Chain Risk** (skor komposit risiko & sub-skor maritim/keuangan).
  4. **Weather & Port Conditions** (suhu, angin, dan kondisi pelabuhan).
  5. **Currency & FX Volatility** (kode kurs & indeks risiko fluktuasi).
- Analisis *Delta Spread* otomatis dengan penentuan keunggulan komparatif per kategori.

### 11. Favorite Monitoring List (Watchlist)
- Pengguna dapat menyimpan negara-negara strategis ke dalam daftar favorit per akun (`Auth::id()`).
- Sinkronisasi real-time berbasis database MySQL dan session pengguna.
- Kartu ringkasan mini per negara favorit (cuaca, indikator ekonomi, kurs, dan AI risk score).

### 12. Admin Console & Management
- **User Management**: Manajemen akun pengguna, penetapan role (*Admin / User*), dan hak akses.
- **Ports Dataset Management**: Pengelolaan data pelabuhan maritim.
- **Analysis Articles Management**: Pembuatan dan penyuntingan artikel analisis riset internal.
- **Real-time KPI Summary**: Kartu statistik pengguna dan dataset yang tersinkronisasi otomatis secara real-time.

---

## 🛠️ Teknologi

### Backend
- **Framework**: Laravel 11.x
- **PHP**: 8.2+
- **Database**: MySQL 8.0+
- **Authentication**: Laravel Session Web Authentication (dengan RBAC & Role Management)

### Frontend
- **CSS Framework**: Bootstrap 5.3 & Custom Dark/Light Design System
- **JavaScript**: Vanilla ES6+
- **Mapping Engine**: Leaflet.js 1.9 (CartoDB Light Tiles)
- **Charts**: Chart.js 4.4
- **Icons**: Font Awesome 6 Pro / Free

### API & External Services
- **Open-Meteo API** — Data cuaca maritim real-time
- **World Bank APIv2** — Indikator makro-ekonomi (GDP, Inflasi, Populasi)
- **ExchangeRate-API** — Kurs nilai tukar mata uang dunia
- **GNews API** — Berita logistik dan berita geopolitik internasional
- **NGA World Port Index** — Koordinat dan dataset pelabuhan laut maritim

---

## 🔌 API yang Digunakan

| API | Fungsi Utama | Status / Akses |
|---|---|---|
| **Open-Meteo** | Cuaca & maritim real-time | ✅ Gratis (Tanpa API Key) |
| **World Bank APIv2** | GDP, Inflasi, Populasi | ✅ Gratis (Tanpa API Key) |
| **ExchangeRate-API** | Kurs mata uang real-time | ⚠️ Free Tier / API Key |
| **GNews API** | Berita logistik & ekonomi | ⚠️ Free Tier / API Key |
| **NGA World Port Index** | Dataset pelabuhan dunia | ✅ Gratis / MySQL Seeder |
| **CartoDB / OpenStreetMap** | Tile Peta Leaflet | ✅ Gratis (Tanpa API Key) |

---

## 📥 Instalasi & Deployment

### Prasyarat System
- PHP 8.2+
- Composer 2.x
- MySQL 8.0+
- Git

### Langkah Instalasi Lokal

```bash
# 1. Clone repository
git clone https://github.com/SiNopal3/web_uas.git
cd web_uas

# 2. Install dependencies PHP
composer install

# 3. Salin file environment
cp .env.example .env

# 4. Generate application key
php artisan key:generate

# 5. Konfigurasi database MySQL pada file .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_supply_chain
DB_USERNAME=root
DB_PASSWORD=

# 6. Konfigurasi API Keys eksternal pada .env
EXCHANGERATE_API_KEY=your_exchangerate_key_here
GNEWS_API_KEY=your_gnews_key_here

# 7. Jalankan database migration & seeder
php artisan migrate --seed

# 8. Buat symlink storage
php artisan storage:link

# 9. Jalankan server lokal
php artisan serve
```

### Deployment (Railway Cloud)
1. Push kode terbaru ke branch `main` GitHub.
2. Hubungkan repositori GitHub ke proyek di **Railway.app**.
3. Tambahkan layanan **MySQL Database** di Railway.
4. Set variabel lingkungan di Railway Dashboard (`APP_ENV=production`, `APP_KEY`, `DB_*`, `EXCHANGERATE_API_KEY`, `GNEWS_API_KEY`).
5. Jalankan `php artisan migrate --force` pada konsol Railway.
6. Aplikasi akan otomatis ter-deploy setiap ada pembaruan `git push`.

---

## 🗄️ Struktur Database

| No | Tabel | Fungsi | Keterangan |
|---|---|---|---|
| 1 | `users` | Data akun pengguna sistem | Role: Admin / User |
| 2 | `roles` | Tabel peran pengguna | Admin, Analyst, Viewer, User |
| 3 | `permissions` | Tabel hak akses sistem | PermissionRBAC |
| 4 | `role_has_permissions` | Pivot role & permission | Access Mapping |
| 5 | `model_has_roles` | Pivot user & role | User Role Assignment |
| 6 | `countries` | Data 195 negara berdaulat | ISO, Nama, Mata Uang, Region |
| 7 | `ports` | Data pelabuhan maritim dunia | Dari NGA World Port Index |
| 8 | `risk_scores` | Histori skor risiko AI | Weather, News, Congestion, Currency, Macro |
| 9 | `news_cache` | Cache berita logistik | Terintegrasi GNews API |
| 10 | `articles` | Artikel analisa internal admin | Dengan skor sentimen AI |
| 11 | `watchlists` | Daftar negara favorit user | Relasi `user_id` + `country_name` |
| 12 | `positive_words` | Kamus kata positif AI Lexicon | growth, increase, profit, stable, improve |
| 13 | `negative_words` | Kamus kata negatif AI Lexicon | war, crisis, inflation, delay, disaster |
| 14 | `notifications` | Alert & notifikasi sistem | Warning & Info log |
| 15 | `audit_logs` | Catatan aktivitas sistem | Security & Admin Activity Log |
| 16 | `risk_reports` | Laporan analisis risiko | Export PDF / Document Risk |
| 17 | `cache` | Cache Laravel internal | Performance Caching |
| 18 | `cache_locks` | Lock cache Laravel | Concurrency Locking |
| 19 | `jobs` | Queue antrian job | Background Tasks |
| 20 | `job_batches` | Queue batching | Batch Processing |
| 21 | `failed_jobs` | Catatan job gagal | Queue Error Logging |
| 22 | `migrations` | Log skema database | Laravel Migrations |
| 23 | `sessions` | Session autentikasi user | Active User Sessions |

---

## 📡 REST API Endpoints

Semua endpoint API memerlukan autentikasi session web (`auth` middleware):

| Method | Endpoint | Deskripsi |
|---|---|---|
| `GET` | `/api/user` | Mengambil profil user yang sedang login |
| `GET` | `/api/countries` | Mengambil daftar 195 negara berdaulat |
| `GET` | `/api/risk` | Mengambil data histori risk score |
| `GET` | `/api/ports` | Mengambil data pelabuhan maritim |
| `GET` | `/api/news` | Mengambil cache berita logistik |
| `GET` | `/api/currency` | Mengambil data kurs mata uang |
| `GET` | `/api/external/country/{name}` | Proxy data profil negara real-time |
| `GET` | `/api/external/weather/{lat}/{lng}` | Proxy data cuaca maritim Open-Meteo |
| `GET` | `/api/external/currency/{base}` | Proxy kurs mata uang ExchangeRate-API |
| `GET` | `/api/external/economy/{code}` | Proxy data makro-ekonomi World Bank API |
| `GET` | `/api/external/news/{topic}` | Proxy berita real-time GNews API |
| `GET` | `/api/external/ports/{country}` | Proxy data pelabuhan per negara |
| `GET` | `/api/ai/sentiment` | Engine analisis sentimen Lexicon AI |
| `GET` | `/api/ai/predict-risk` | AI Risk Prediction & Scoring Engine |
| `GET` | `/api/watchlist` | Mengambil daftar favorit watchlist user |
| `POST` | `/api/watchlist` | Menambahkan negara ke watchlist favorit |
| `DELETE` | `/api/watchlist/{id}` | Menghapus negara dari watchlist favorit |

---

## 👨‍💻 Kontributor

- **Project Developer**: Naufal & Team — [GitHub Repository](https://github.com/SiNopal3/web_uas)
- **Credit Template Reference**: Zaskia Azzura — [github.com/zaskiaazzura](https://github.com/zaskiaazzura)

---
© 2026 **RiskIntel Hub**. Developed as a Final Project Submission. All Rights Reserved.
