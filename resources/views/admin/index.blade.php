@extends('layouts.app')

@section('title', 'Admin Dashboard - Enterprise Administration Center')

@section('content')
<div class="container-fluid py-4">
    <!-- Header Admin Dashboard -->
    <div class="row align-items-center justify-content-between mb-4 pb-3 border-bottom border-secondary border-opacity-50">
        <div class="col-12">
            <div>
                <h2 class="fw-bold text-white mb-0 mt-1" style="letter-spacing: -0.5px;">Admin Dashboard</h2>
                <p class="small text-muted mb-0">Pusat Kendali Eksekutif & Manajemen Data Terintegrasi (Kelola User, Dataset Pelabuhan, dan Artikel Analisis).</p>
            </div>
        </div>
    </div>

    <!-- Summary KPI Cards (3 Kotak Utama) -->
    <div class="row g-3 mb-4">
        <!-- Kotak 1: User yang login / aktif di web ini -->
        <div class="col-12 col-md-4">
            <div class="glass-card p-3 d-flex align-items-center justify-content-between h-100 border-start border-warning border-4">
                <div>
                    <span class="small text-uppercase fw-bold text-muted d-block" style="font-size: 11px;">Pengguna</span>
                    <h3 class="fw-bold text-white mb-0 mt-1" id="kpi-total-users">{{ $kpi['total_users'] ?? 1 }}</h3>
                    <span class="small text-warning mt-1 d-block" id="kpi-user-roles-breakdown"><i class="fa-solid fa-user-shield me-1"></i> {{ $kpi['role_administrator'] ?? 0 }} Admin &bull; <i class="fa-solid fa-user me-1"></i> {{ $kpi['role_user'] ?? ($kpi['role_analyst'] ?? 0) }} User</span>
                </div>
                <div class="bg-warning bg-opacity-10 rounded-circle p-3 text-warning">
                    <i class="fa-solid fa-users fs-4"></i>
                </div>
            </div>
        </div>
        <!-- Kotak 2: Jumlah negara yang ada di web ini -->
        <div class="col-12 col-md-4">
            <div class="glass-card p-3 d-flex align-items-center justify-content-between h-100 border-start border-info border-4">
                <div>
                    <span class="small text-uppercase fw-bold text-muted d-block" style="font-size: 11px;">Jumlah Negara</span>
                    <h3 class="fw-bold text-white mb-0 mt-1" id="kpi-total-countries">{{ $kpi['total_countries'] ?? 195 }}</h3>
                    <span class="small text-info mt-1 d-block"><i class="fa-solid fa-flag me-1"></i> Negara Berdaulat Global (PBB)</span>
                </div>
                <div class="bg-info bg-opacity-10 rounded-circle p-3 text-info">
                    <i class="fa-solid fa-earth-asia fs-4"></i>
                </div>
            </div>
        </div>
        <!-- Kotak 3: Berapa pelabuhan yang ada di web ini -->
        <div class="col-12 col-md-4">
            <div class="glass-card p-3 d-flex align-items-center justify-content-between h-100 border-start border-success border-4">
                <div>
                    <span class="small text-uppercase fw-bold text-muted d-block" style="font-size: 11px;">Dataset Pelabuhan</span>
                    <h3 class="fw-bold text-white mb-0 mt-1" id="kpi-total-ports">{{ number_format($kpi['total_ports'] ?? 3739, 0, ',', '.') }}</h3>
                    <span class="small text-success mt-1 d-block"><i class="fa-solid fa-anchor me-1"></i> Global NGA World Port Index</span>
                </div>
                <div class="bg-success bg-opacity-10 rounded-circle p-3 text-success">
                    <i class="fa-solid fa-ship fs-4"></i>
                </div>
            </div>
        </div>
    </div>



    <!-- Navigation Tabs for Admin CRUD -->
    <div id="adminNavTabs" class="d-flex flex-wrap gap-2 mb-4 pb-2 border-bottom border-secondary border-opacity-50">
        <button class="nav-link active btn btn-outline-warning fw-bold px-4 py-2 d-flex align-items-center" data-tab="users" onclick="switchAdminTab('users')">
            <i class="fa-solid fa-users me-2"></i> Kelola User
        </button>
        <button class="nav-link btn btn-outline-info fw-bold px-4 py-2 d-flex align-items-center" data-tab="ports" onclick="switchAdminTab('ports')">
            <i class="fa-solid fa-anchor me-2"></i> Dataset Pelabuhan
        </button>
        <button class="nav-link btn btn-outline-success fw-bold px-4 py-2 d-flex align-items-center" data-tab="articles" onclick="switchAdminTab('articles')">
            <i class="fa-solid fa-newspaper me-2"></i> Artikel Analisis
        </button>
    </div>

    <!-- TAB 1: KELOLA USER -->
    <div id="tab-users" class="admin-tab-pane">
        <div class="glass-card p-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
                <div>
                    <h4 class="fw-bold text-white mb-1"><i class="fa-solid fa-users-gear text-warning me-2"></i> Manajemen Akses Pengguna (Kelola User)</h4>
                    <p class="small text-muted mb-0">Kelola kredensial, hak akses peran (Role), serta status aktif pengguna di dalam ekosistem Enterprise.</p>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-warning fw-bold d-flex align-items-center px-4" onclick="openCreateUserModal()">
                        <i class="fa-solid fa-user-plus me-2"></i> Tambah
                    </button>
                </div>
            </div>


            <!-- Table User -->
            <div class="table-responsive">
                <table class="table table-dark table-hover align-middle mb-0 border-secondary">
                    <thead class="table-active">
                        <tr class="text-muted small text-uppercase">
                            <th class="px-3 py-3" style="width: 70px;">No</th>
                            <th class="py-3">Pengguna</th>
                            <th class="py-3">Peran (Role)</th>
                            <th class="py-3">Status</th>
                            <th class="py-3">Terakhir Masuk</th>
                            <th class="text-end px-3 py-3" style="width: 140px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="usersTableBody" class="border-top-0">
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-spinner fa-spin me-2 fs-5"></i> Memuat data pengguna dari server...
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- TAB 2: DATASET PELABUHAN -->
    <div id="tab-ports" class="admin-tab-pane d-none">
        <div class="glass-card p-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
                <div>
                    <h4 class="fw-bold text-white mb-1"><i class="fa-solid fa-anchor text-info me-2"></i> Manajemen Dataset Pelabuhan (Global NGA World Port Index)</h4>
                    <p class="small text-muted mb-0">Kelola master data pelabuhan maritim, kode negara, serta koordinat geospasial untuk pemantauan rantai pasok global.</p>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-info fw-bold d-flex align-items-center px-4 text-dark" onclick="openCreatePortModal()">
                        <i class="fa-solid fa-plus me-2"></i> Tambah Pelabuhan
                    </button>
                </div>
            </div>

            <!-- Search & Filter Port -->
            <div class="row g-2 mb-3">
                <div class="col-12 col-md-8">
                    <div class="input-group">
                        <span class="input-group-text bg-dark border-secondary text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                        <input type="text" id="portSearchInput" class="form-control bg-dark text-white border-secondary" placeholder="Cari nama pelabuhan, negara, atau koordinat...">
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <select id="portCountryFilter" class="form-select bg-dark text-white border-secondary" onchange="fetchPortsList()">
                        <option value="all">Semua Negara</option>
                        <option value="Indonesia">Indonesia</option>
                        <option value="Singapore">Singapore</option>
                        <option value="United States">United States</option>
                        <option value="China">China</option>
                        <option value="Japan">Japan</option>
                        <option value="Germany">Germany</option>
                        <option value="Netherlands">Netherlands</option>
                        <option value="Australia">Australia</option>
                        <option value="United Kingdom">United Kingdom</option>
                        <option value="United Arab Emirates">United Arab Emirates</option>
                    </select>
                </div>
            </div>

            <!-- Table Port -->
            <div class="table-responsive">
                <table class="table table-dark table-hover align-middle mb-0 border-secondary">
                    <thead class="table-active">
                        <tr class="text-muted small text-uppercase">
                            <th class="px-3 py-3" style="width: 70px;">ID</th>
                            <th class="py-3">Nama Pelabuhan</th>
                            <th class="py-3">Negara</th>
                            <th class="py-3">Koordinat / Lokasi</th>
                            <th class="text-end px-3 py-3" style="width: 140px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="portsTableBody" class="border-top-0">
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-spinner fa-spin me-2 fs-5"></i> Memuat dataset pelabuhan dari server...
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- TAB 3: ARTIKEL ANALISIS -->
    <div id="tab-articles" class="admin-tab-pane d-none">
        <div class="glass-card p-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
                <div>
                    <h4 class="fw-bold text-white mb-1"><i class="fa-solid fa-newspaper text-success me-2"></i> Manajemen Artikel Analisis & Berita Risiko</h4>
                    <p class="small text-muted mb-0">Kelola artikel kecerdasan intelijen, analisis geopolitik, serta berita risiko pasokan strategis global.</p>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-success fw-bold d-flex align-items-center px-4" onclick="openCreateArticleModal()">
                        <i class="fa-solid fa-plus me-2"></i> Tambah Artikel
                    </button>
                </div>
            </div>

            <!-- Search Article -->
            <div class="row g-2 mb-3">
                <div class="col-12">
                    <div class="input-group">
                        <span class="input-group-text bg-dark border-secondary text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                        <input type="text" id="articleSearchInput" class="form-control bg-dark text-white border-secondary" placeholder="Cari judul artikel atau isi kutipan analisis...">
                    </div>
                </div>
            </div>

            <!-- Table Articles -->
            <div class="table-responsive">
                <table class="table table-dark table-hover align-middle mb-0 border-secondary">
                    <thead class="table-active">
                        <tr class="text-muted small text-uppercase">
                            <th class="px-3 py-3" style="width: 70px;">ID</th>
                            <th class="py-3">Judul & Kutipan Konten</th>
                            <th class="py-3" style="width: 150px;">Tanggal Dibuat</th>
                            <th class="text-end px-3 py-3" style="width: 140px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="articlesTableBody" class="border-top-0">
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-spinner fa-spin me-2 fs-5"></i> Memuat artikel analisis dari server...
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- MODAL USER -->
<div class="modal fade" id="userModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark border border-secondary text-white shadow-lg">
            <div class="modal-header border-secondary border-opacity-50">
                <h5 class="modal-title fw-bold" id="userModalTitle"><i class="fa-solid fa-user-plus text-warning me-2"></i> Tambah User</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form onsubmit="saveUserModal(event)">
                <div class="modal-body p-4">
                    <input type="hidden" id="modalUserId">
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Nama Lengkap *</label>
                        <input type="text" id="modalUserName" class="form-control bg-dark text-white border-secondary" required placeholder="Contoh: Budi Santoso">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Alamat Email *</label>
                        <input type="email" id="modalUserEmail" class="form-control bg-dark text-white border-secondary" required placeholder="budi@riskintel.com">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Username (Opsional)</label>
                        <input type="text" id="modalUserUsername" class="form-control bg-dark text-white border-secondary" placeholder="budi_s">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted" id="modalPasswordLabel">Password *</label>
                        <input type="password" id="modalUserPassword" class="form-control bg-dark text-white border-secondary" required minlength="6" placeholder="Minimal 6 karakter">
                    </div>
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label small fw-bold text-muted">Peran (Role)</label>
                            <select id="modalUserRole" class="form-select bg-dark text-white border-secondary">
                                <option value="User" selected>User</option>
                                <option value="Admin">Admin</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-secondary border-opacity-50">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning fw-bold px-4" id="btnSaveUserModal">Simpan User</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL PORT -->
<div class="modal fade" id="portModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark border border-secondary text-white shadow-lg">
            <div class="modal-header border-secondary border-opacity-50">
                <h5 class="modal-title fw-bold" id="portModalTitle"><i class="fa-solid fa-anchor text-info me-2"></i> Tambah Dataset Pelabuhan</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form onsubmit="savePortModal(event)">
                <div class="modal-body p-4">
                    <input type="hidden" id="modalPortId">
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Nama Pelabuhan *</label>
                        <input type="text" id="modalPortName" class="form-control bg-dark text-white border-secondary" required placeholder="Contoh: Port of Tanjung Priok">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Negara *</label>
                        <input type="text" id="modalPortCountry" class="form-control bg-dark text-white border-secondary" required placeholder="Contoh: Indonesia">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Koordinat / Lokasi *</label>
                        <input type="text" id="modalPortLocation" class="form-control bg-dark text-white border-secondary" required placeholder="Contoh: 6.1018° S, 106.8823° E">
                    </div>
                </div>
                <div class="modal-footer border-secondary border-opacity-50">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-info fw-bold px-4 text-dark" id="btnSavePortModal">Simpan Pelabuhan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL ARTICLE -->
<div class="modal fade" id="articleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content bg-dark border border-secondary text-white shadow-lg">
            <div class="modal-header border-secondary border-opacity-50">
                <h5 class="modal-title fw-bold" id="articleModalTitle"><i class="fa-solid fa-newspaper text-success me-2"></i> Tambah Artikel Analisis</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form onsubmit="saveArticleModal(event)">
                <div class="modal-body p-4">
                    <input type="hidden" id="modalArticleId">
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">1. Judul Artikel Analisis *</label>
                        <input type="text" id="modalArticleTitle" class="form-control bg-dark text-white border-secondary" required placeholder="Contoh: Analisis Disrupsi Rantai Pasok Selat Malaka Q3">
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-12 col-md-6">
                            <label class="form-label small fw-bold text-muted">2. Penulis Artikel</label>
                            <input type="text" id="modalArticleAuthor" class="form-control bg-dark text-white border-secondary" placeholder="Contoh: Tim Analisis RiskIntel / Admin">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label small fw-bold text-muted">3. Link Sumber Berita (URL)</label>
                            <input type="url" id="modalArticleUrl" class="form-control bg-dark text-white border-secondary" placeholder="https://... (Copy link dari hasil Google)">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">4. Konten Analisis & Berita *</label>
                        <textarea id="modalArticleContent" class="form-control bg-dark text-white border-secondary" rows="6" required placeholder="Tuliskan analisis intelijen, faktor risiko, dan rekomendasi mitigasi..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-secondary border-opacity-50">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success fw-bold px-4" id="btnSaveArticleModal">Simpan Artikel</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/admin.js') }}"></script>
@endpush
