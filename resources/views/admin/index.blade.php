@extends('layouts.app')

@section('title', 'Admin Console - Enterprise Administration Center')

@section('content')
<div class="container-fluid p-0">
    <!-- Header Admin Console -->
    <div class="d-flex align-items-center justify-content-between mb-4 pb-3 border-bottom">
        <div>
            <h3 class="fw-bold text-dark mb-1">Admin Console</h3>
            <p class="small text-muted mb-0">Integrated Executive Control & Data Management Center (Users, Ports, Analysis Articles).</p>
        </div>
    </div>

    <!-- Summary KPI Cards (3 Executive Metrics) -->
    <div class="row g-3 mb-4">
        <!-- Card 1: Users -->
        <div class="col-12 col-md-4">
            <div class="glass-card p-3 d-flex align-items-center justify-content-between h-100" style="border-left: 4px solid var(--primary);">
                <div>
                    <span class="small text-uppercase fw-semibold text-muted d-block" style="font-size: 11px !important;">Total Users</span>
                    <h2 class="fw-bold text-dark mb-0 mt-0.5" id="kpi-total-users">{{ $kpi['total_users'] ?? 1 }}</h2>
                    <span class="small text-muted mt-1 d-block" id="kpi-user-roles-breakdown"><i class="fa-solid fa-user-shield me-1 text-primary"></i> {{ $kpi['role_administrator'] ?? 0 }} Admin &bull; <i class="fa-solid fa-user me-1 text-slate-500"></i> {{ $kpi['role_user'] ?? ($kpi['role_analyst'] ?? 0) }} User</span>
                </div>
                <div class="badge-soft-info p-3 rounded-circle d-flex align-items-center justify-content-center" style="width:48px;height:48px;">
                    <i class="fa-solid fa-users fs-5 text-primary"></i>
                </div>
            </div>
        </div>
        <!-- Card 2: Sovereign Countries -->
        <div class="col-12 col-md-4">
            <div class="glass-card p-3 d-flex align-items-center justify-content-between h-100" style="border-left: 4px solid var(--info);">
                <div>
                    <span class="small text-uppercase fw-semibold text-muted d-block" style="font-size: 11px !important;">Sovereign Countries</span>
                    <h2 class="fw-bold text-dark mb-0 mt-0.5" id="kpi-total-countries">{{ $kpi['total_countries'] ?? 195 }}</h2>
                    <span class="small text-muted mt-1 d-block"><i class="fa-solid fa-globe me-1 text-info"></i> UN Recognized Global Dataset</span>
                </div>
                <div class="badge-soft-info p-3 rounded-circle d-flex align-items-center justify-content-center" style="width:48px;height:48px;">
                    <i class="fa-solid fa-earth-asia fs-5 text-info"></i>
                </div>
            </div>
        </div>
        <!-- Card 3: Ports Dataset -->
        <div class="col-12 col-md-4">
            <div class="glass-card p-3 d-flex align-items-center justify-content-between h-100" style="border-left: 4px solid var(--success);">
                <div>
                    <span class="small text-uppercase fw-semibold text-muted d-block" style="font-size: 11px !important;">Ports Dataset</span>
                    <h2 class="fw-bold text-dark mb-0 mt-0.5" id="kpi-total-ports">{{ number_format($kpi['total_ports'] ?? 3739, 0, ',', '.') }}</h2>
                    <span class="small text-muted mt-1 d-block"><i class="fa-solid fa-anchor me-1 text-success"></i> NGA World Port Index</span>
                </div>
                <div class="badge-soft-success p-3 rounded-circle d-flex align-items-center justify-content-center" style="width:48px;height:48px;">
                    <i class="fa-solid fa-ship fs-5 text-success"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation Tabs for Admin Management -->
    <div id="adminNavTabs" class="d-flex flex-wrap gap-2 mb-4 pb-2 border-bottom">
        <button class="nav-link active btn btn-primary fw-semibold px-3 py-2 d-flex align-items-center" data-tab="users" onclick="switchAdminTab('users')">
            <i class="fa-solid fa-users me-1.5"></i> Manage Users
        </button>
        <button class="nav-link btn btn-secondary fw-semibold px-3 py-2 d-flex align-items-center" data-tab="ports" onclick="switchAdminTab('ports')">
            <i class="fa-solid fa-anchor me-1.5"></i> Ports Dataset
        </button>
        <button class="nav-link btn btn-secondary fw-semibold px-3 py-2 d-flex align-items-center" data-tab="articles" onclick="switchAdminTab('articles')">
            <i class="fa-solid fa-newspaper me-1.5"></i> Analysis Articles
        </button>
    </div>

    <!-- TAB 1: MANAGE USERS -->
    <div id="tab-users" class="admin-tab-pane">
        <div class="glass-card p-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
                <div>
                    <h4 class="fw-bold text-dark mb-1"><i class="fa-solid fa-users-gear text-primary me-2"></i> User Access Management</h4>
                    <p class="small text-muted mb-0">Manage credentials, role permissions, and user status in the enterprise platform.</p>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-primary fw-semibold px-3" onclick="openCreateUserModal()">
                        <i class="fa-solid fa-user-plus me-1.5"></i> Add User
                    </button>
                </div>
            </div>

            <!-- User Table -->
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="px-3" style="width: 70px;">#</th>
                            <th>User Name & Email</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Last Login</th>
                            <th class="text-end px-3" style="width: 140px;">Action</th>
                        </tr>
                    </thead>
                    <tbody id="usersTableBody">
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-spinner fa-spin me-2 fs-5 text-primary"></i> Loading user accounts...
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- TAB 2: DATASET PORTS -->
    <div id="tab-ports" class="admin-tab-pane d-none">
        <div class="glass-card p-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
                <div>
                    <h4 class="fw-bold text-dark mb-1"><i class="fa-solid fa-anchor text-primary me-2"></i> Ports Dataset Management (NGA World Port Index)</h4>
                    <p class="small text-muted mb-0">Manage maritime port records, country codes, and geospatial coordinates.</p>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-primary fw-semibold px-3" onclick="openCreatePortModal()">
                        <i class="fa-solid fa-plus me-1.5"></i> Add Port
                    </button>
                </div>
            </div>

            <!-- Search & Filter Port -->
            <div class="row g-2 mb-3">
                <div class="col-12 col-md-8">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                        <input type="text" id="portSearchInput" class="form-control border-start-0" placeholder="Search port name, country, or location coordinates...">
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <select id="portCountryFilter" class="form-select" onchange="fetchPortsList()">
                        <option value="all">All Sovereign Countries</option>
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
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="px-3" style="width: 70px;">ID</th>
                            <th>Port Name</th>
                            <th>Country</th>
                            <th>Coordinates / Location</th>
                            <th class="text-end px-3" style="width: 140px;">Action</th>
                        </tr>
                    </thead>
                    <tbody id="portsTableBody">
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-spinner fa-spin me-2 fs-5 text-primary"></i> Loading ports dataset...
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- TAB 3: ANALYSIS ARTICLES -->
    <div id="tab-articles" class="admin-tab-pane d-none">
        <div class="glass-card p-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
                <div>
                    <h4 class="fw-bold text-dark mb-1"><i class="fa-solid fa-newspaper text-primary me-2"></i> Analysis Articles & Intelligence Reports</h4>
                    <p class="small text-muted mb-0">Publish geopolitical risk reports, maritime intelligence, and supply chain analysis.</p>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-primary fw-semibold px-3" onclick="openCreateArticleModal()">
                        <i class="fa-solid fa-plus me-1.5"></i> Add Article
                    </button>
                </div>
            </div>

            <!-- Search Article -->
            <div class="row g-2 mb-3">
                <div class="col-12">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                        <input type="text" id="articleSearchInput" class="form-control border-start-0" placeholder="Search article title or content preview...">
                    </div>
                </div>
            </div>

            <!-- Table Articles -->
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="px-3" style="width: 70px;">ID</th>
                            <th>Article Title & Content Preview</th>
                            <th style="width: 150px;">Created Date</th>
                            <th class="text-end px-3" style="width: 140px;">Action</th>
                        </tr>
                    </thead>
                    <tbody id="articlesTableBody">
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-spinner fa-spin me-2 fs-5 text-primary"></i> Loading analysis articles...
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
        <div class="modal-content shadow-lg">
            <div class="modal-header">
                <h5 class="modal-title fw-bold text-dark" id="userModalTitle"><i class="fa-solid fa-user-plus text-primary me-2"></i> Add User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form onsubmit="saveUserModal(event)">
                <div class="modal-body p-4">
                    <input type="hidden" id="modalUserId">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-muted">Full Name *</label>
                        <input type="text" id="modalUserName" class="form-control" required placeholder="e.g. Budi Santoso">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-muted">Email Address *</label>
                        <input type="email" id="modalUserEmail" class="form-control" required placeholder="user@riskintel.com">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-muted">Username (Optional)</label>
                        <input type="text" id="modalUserUsername" class="form-control" placeholder="user_handle">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-muted" id="modalPasswordLabel">Password *</label>
                        <input type="password" id="modalUserPassword" class="form-control" required minlength="6" placeholder="Minimum 6 characters">
                    </div>
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label small fw-semibold text-muted">Role Permission</label>
                            <select id="modalUserRole" class="form-select">
                                <option value="User" selected>User</option>
                                <option value="Admin">Admin</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4" id="btnSaveUserModal">Save User</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL PORT -->
<div class="modal fade" id="portModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg">
            <div class="modal-header">
                <h5 class="modal-title fw-bold text-dark" id="portModalTitle"><i class="fa-solid fa-anchor text-primary me-2"></i> Add Port Record</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form onsubmit="savePortModal(event)">
                <div class="modal-body p-4">
                    <input type="hidden" id="modalPortId">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-muted">Port Name *</label>
                        <input type="text" id="modalPortName" class="form-control" required placeholder="e.g. Port of Tanjung Priok">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-muted">Country *</label>
                        <input type="text" id="modalPortCountry" class="form-control" required placeholder="e.g. Indonesia">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-muted">Coordinates / Location *</label>
                        <input type="text" id="modalPortLocation" class="form-control" required placeholder="e.g. 6.1018° S, 106.8823° E">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4" id="btnSavePortModal">Save Port</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL ARTICLE -->
<div class="modal fade" id="articleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content shadow-lg">
            <div class="modal-header">
                <h5 class="modal-title fw-bold text-dark" id="articleModalTitle"><i class="fa-solid fa-newspaper text-primary me-2"></i> Add Analysis Article</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form onsubmit="saveArticleModal(event)">
                <div class="modal-body p-4">
                    <input type="hidden" id="modalArticleId">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-muted">1. Article Title *</label>
                        <input type="text" id="modalArticleTitle" class="form-control" required placeholder="e.g. Malacca Strait Supply Chain Disruptions Q3">
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-12 col-md-6">
                            <label class="form-label small fw-semibold text-muted">2. Author Name</label>
                            <input type="text" id="modalArticleAuthor" class="form-control" placeholder="e.g. RiskIntel Intelligence Team">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label small fw-semibold text-muted">3. Source Link (URL)</label>
                            <input type="url" id="modalArticleUrl" class="form-control" placeholder="https://...">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-muted">4. Content & Intelligence Analysis *</label>
                        <textarea id="modalArticleContent" class="form-control" rows="6" required placeholder="Write risk factors, geopolitics, and mitigation advice..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4" id="btnSaveArticleModal">Save Article</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/admin.js') }}"></script>
@endpush
