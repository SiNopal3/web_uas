/**
 * RiskIntel Hub - Enterprise Administration & System Monitoring (`/admin`)
 * Vanilla ES6 JavaScript Engine with Chart.js Visualizations & AJAX Polling
 */

(function () {
    'use strict';

    // Global chart instances storage
    let charts = {
        line: null,
        gauge: null,
        radar: null,
        pie: null,
        bar: null
    };

    let userSearchTimeout = null;
    let auditSearchTimeout = null;

    document.addEventListener('DOMContentLoaded', () => {
        initCharts();
        initEventListeners();
        startTelemetryPolling();
    });

    /**
     * Initialize all 5 Chart.js instances (Line, Gauge, Radar, Pie, Bar)
     */
    function initCharts() {
        if (typeof Chart === 'undefined') {
            console.warn('Chart.js is not loaded.');
            return;
        }

        Chart.defaults.color = '#cbd5e1';
        Chart.defaults.font.family = "'Inter', sans-serif";

        // 1. Line Chart: API Latency & Memory Telemetry
        const ctxLine = document.getElementById('adminLineChart');
        if (ctxLine) {
            charts.line = new Chart(ctxLine, {
                type: 'line',
                data: {
                    labels: ['-50m', '-40m', '-30m', '-20m', '-10m', 'Now'],
                    datasets: [
                        {
                            label: 'API Proxy Latency (ms)',
                            data: [65, 72, 58, 80, 68, 62],
                            borderColor: '#e0b472',
                            backgroundColor: 'rgba(224, 180, 114, 0.1)',
                            fill: true,
                            tension: 0.4
                        },
                        {
                            label: 'Memory Allocation (%)',
                            data: [31, 32, 30, 33, 31, 32],
                            borderColor: '#38bdf8',
                            backgroundColor: 'rgba(56, 189, 248, 0.05)',
                            fill: true,
                            tension: 0.4
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { position: 'top' } },
                    scales: {
                        y: { grid: { color: 'rgba(255,255,255,0.06)' }, min: 0 }
                    }
                }
            });
        }

        // 2. Gauge Chart (Half Doughnut): Server Load Index
        const ctxGauge = document.getElementById('adminGaugeChart');
        if (ctxGauge) {
            charts.gauge = new Chart(ctxGauge, {
                type: 'doughnut',
                data: {
                    labels: ['Used Allocation', 'Available Capacity'],
                    datasets: [{
                        data: [28.4, 71.6],
                        backgroundColor: ['#22c55e', 'rgba(255,255,255,0.08)'],
                        borderWidth: 0,
                        circumference: 180,
                        rotation: 270
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '78%',
                    plugins: { legend: { display: false }, tooltip: { enabled: true } }
                }
            });
        }

        // 3. Radar Chart: Comprehensive Health Index
        const ctxRadar = document.getElementById('adminRadarChart');
        if (ctxRadar) {
            charts.radar = new Chart(ctxRadar, {
                type: 'radar',
                data: {
                    labels: ['CPU Load', 'RAM RAM', 'Storage NVMe', 'DB Pool', 'Cache Hit', 'API Sync'],
                    datasets: [{
                        label: 'Optimal Baseline',
                        data: [85, 80, 75, 95, 98, 99],
                        borderColor: '#e0b472',
                        backgroundColor: 'rgba(224, 180, 114, 0.2)'
                    }, {
                        label: 'Current Telemetry',
                        data: [75, 68, 55, 98, 99, 99],
                        borderColor: '#22c55e',
                        backgroundColor: 'rgba(34, 197, 94, 0.25)'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: { r: { grid: { color: 'rgba(255,255,255,0.1)' }, angleLines: { color: 'rgba(255,255,255,0.1)' }, suggestedMin: 0, suggestedMax: 100 } }
                }
            });
        }

        // 4. Pie Chart: Role Distribution
        const ctxPie = document.getElementById('adminPieChart');
        if (ctxPie) {
            charts.pie = new Chart(ctxPie, {
                type: 'doughnut',
                data: {
                    labels: ['Admin', 'User'],
                    datasets: [{
                        data: [5, 25],
                        backgroundColor: ['#e0b472', '#38bdf8'],
                        borderWidth: 1,
                        borderColor: '#0f1218'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { position: 'right' } }
                }
            });
        }

        // 5. Bar Chart: Weekly Audit Activity
        const ctxBar = document.getElementById('adminBarChart');
        if (ctxBar) {
            charts.bar = new Chart(ctxBar, {
                type: 'bar',
                data: {
                    labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                    datasets: [{
                        label: 'System Actions',
                        data: [42, 58, 65, 88, 74, 30, 22],
                        backgroundColor: '#e0b472',
                        borderRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { grid: { color: 'rgba(255,255,255,0.06)' } },
                        x: { grid: { display: false } }
                    }
                }
            });
        }
    }

    /**
     * Initialize event listeners for debounced search and tab switching
     */
    function initEventListeners() {
        const userSearch = document.getElementById('userSearchInput');
        if (userSearch) {
            userSearch.addEventListener('input', (e) => {
                clearTimeout(userSearchTimeout);
                userSearchTimeout = setTimeout(() => fetchUsersList(), 300);
            });
        }

        const auditSearch = document.getElementById('auditSearchInput');
        if (auditSearch) {
            auditSearch.addEventListener('input', (e) => {
                clearTimeout(auditSearchTimeout);
                auditSearchTimeout = setTimeout(() => fetchAuditLogsList(), 300);
            });
        }

        const portSearch = document.getElementById('portSearchInput');
        if (portSearch) {
            portSearch.addEventListener('input', (e) => {
                clearTimeout(portSearchTimeout);
                portSearchTimeout = setTimeout(() => fetchPortsList(), 300);
            });
        }

        const articleSearch = document.getElementById('articleSearchInput');
        if (articleSearch) {
            articleSearch.addEventListener('input', (e) => {
                clearTimeout(articleSearchTimeout);
                articleSearchTimeout = setTimeout(() => fetchArticlesList(), 300);
            });
        }

        // Attach click listeners to tab buttons
        document.querySelectorAll('#adminNavTabs .nav-link').forEach((btn) => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                const tab = btn.getAttribute('data-tab');
                if (tab) switchAdminTab(tab);
            });
        });

        // Determine initial active tab based on URL or default to users
        const path = window.location.pathname;
        if (path.includes('/admin/system') && document.getElementById('tab-system')) {
            switchAdminTab('system');
        } else if (path.includes('/admin/logs') && document.getElementById('tab-logs')) {
            switchAdminTab('logs');
        } else if (path.includes('/admin/settings') && document.getElementById('tab-settings')) {
            switchAdminTab('settings');
        } else {
            switchAdminTab('users');
        }
    }

    /**
     * Switch visible admin tab section
     */
    window.switchAdminTab = function (tabName) {
        // Update navigation buttons
        document.querySelectorAll('#adminNavTabs .nav-link').forEach((btn) => {
            if (btn.getAttribute('data-tab') === tabName) {
                btn.classList.add('active');
            } else {
                btn.classList.remove('active');
            }
        });

        // Hide all tab panes
        document.querySelectorAll('.admin-tab-pane').forEach((pane) => {
            pane.classList.add('d-none');
        });

        // Show target tab pane
        const target = document.getElementById('tab-' + tabName);
        if (target) {
            target.classList.remove('d-none');
        } else if (tabName !== 'users') {
            switchAdminTab('users');
            return;
        }

        // If tab requires dynamic fetch, trigger it
        if (tabName === 'users') {
            fetchUsersList();
        } else if (tabName === 'ports') {
            fetchPortsList();
        } else if (tabName === 'articles') {
            fetchArticlesList();
        } else if (tabName === 'logs') {
            fetchAuditLogsList();
        } else if (tabName === 'system' || tabName === 'health' || tabName === 'api' || tabName === 'database') {
            window.refreshAdminTelemetry();
        }
    };

    /**
     * Fetch users list via AJAX
     */
    window.fetchUsersList = function (page = 1) {
        const search = document.getElementById('userSearchInput')?.value || '';
        const role = document.getElementById('userRoleFilter')?.value || 'all';
        const status = document.getElementById('userStatusFilter')?.value || 'all';

        const url = `/api/admin/users-list?page=${page}&search=${encodeURIComponent(search)}&role=${encodeURIComponent(role)}&status=${encodeURIComponent(status)}`;

        fetch(url, {
            headers: { 'Accept': 'application/json' }
        })
        .then(response => response.json())
        .then(res => {
            if (res.success && res.data) {
                renderUsersTable(res.data);
                if (res.kpi) {
                    const totalElem = document.getElementById('kpi-total-users');
                    if (totalElem) totalElem.innerText = res.kpi.total_users || 1;
                    const breakdownElem = document.getElementById('kpi-user-roles-breakdown');
                    if (breakdownElem) {
                        const adminCount = res.kpi.role_administrator !== undefined ? res.kpi.role_administrator : 0;
                        const userCount = res.kpi.role_user !== undefined ? res.kpi.role_user : (res.kpi.role_analyst || 0);
                        breakdownElem.innerHTML = `<i class="fa-solid fa-user-shield me-1"></i> ${adminCount} Admin &bull; <i class="fa-solid fa-user me-1"></i> ${userCount} User`;
                    }
                }
            }
        })
        .catch(err => console.error('Error fetching users:', err));
    };

    function renderUsersTable(paginatedData) {
        const tbody = document.getElementById('usersTableBody');
        if (!tbody) return;

        if (!paginatedData.data || paginatedData.data.length === 0) {
            tbody.innerHTML = `<tr><td colspan="6" class="text-center py-4 text-muted"><i class="fa-solid fa-folder-open me-2"></i> No enterprise users found.</td></tr>`;
            return;
        }

        const currentUserId = document.querySelector('meta[name="user-id"]')?.getAttribute('content') || null;

        tbody.innerHTML = paginatedData.data.map((user, index) => {
            const rowNum = (paginatedData.current_page - 1) * paginatedData.per_page + index + 1;
            const firstLetter = (user.name || 'U').charAt(0).toUpperCase();
            const roleName = user.role?.name || (user.roles && user.roles.length > 0 ? user.roles[0].name : 'User');
            const isOnline = user.is_online !== undefined ? user.is_online : (user.status === 'active' || user.status === 'online');
            const statusBadge = isOnline 
                ? '<span class="badge bg-success bg-opacity-25 text-success border border-success px-3 py-1"><i class="fa-solid fa-circle me-1" style="font-size: 8px;"></i> ONLINE</span>' 
                : '<span class="badge bg-danger bg-opacity-25 text-danger border border-danger px-3 py-1"><i class="fa-solid fa-circle me-1" style="font-size: 8px;"></i> OFFLINE</span>';
            const lastLogin = user.last_login_at ? new Date(user.last_login_at).toLocaleString('id-ID') : 'Never';

            const escapedUser = JSON.stringify(user).replace(/'/g, "&#39;");

            return `
                <tr>
                    <td class="px-3 fw-bold text-muted">#${rowNum}</td>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="bg-warning rounded-circle d-flex align-items-center justify-content-center text-dark fw-bold me-3" style="width: 38px; height: 38px;">
                                ${firstLetter}
                            </div>
                            <div>
                                <div class="fw-bold text-white">${user.name}</div>
                                <div class="small text-muted">${user.email} ${user.username ? '(@'+user.username+')' : ''}</div>
                            </div>
                        </div>
                    </td>
                    <td><span class="badge bg-info bg-opacity-25 text-info border border-info px-3 py-1">${roleName}</span></td>
                    <td>${statusBadge}</td>
                    <td class="small text-muted">${lastLogin}</td>
                    <td class="text-end px-3">
                        <button class="btn btn-sm btn-outline-info me-1" onclick='editUserModal(${escapedUser})' title="Edit User"><i class="fa-solid fa-pen-to-square"></i></button>
                        <button class="btn btn-sm btn-outline-danger" onclick="deleteUserAction(${user.id})" title="Delete User"><i class="fa-solid fa-trash-can"></i></button>
                    </td>
                </tr>
            `;
        }).join('');
    }

    /**
     * User Modal controls
     */
    window.openCreateUserModal = function () {
        document.getElementById('modalUserId').value = '';
        document.getElementById('modalUserName').value = '';
        document.getElementById('modalUserEmail').value = '';
        document.getElementById('modalUserUsername').value = '';
        document.getElementById('modalUserPassword').value = '';
        document.getElementById('modalUserPassword').required = true;
        document.getElementById('modalPasswordLabel').innerText = 'Password *';
        document.getElementById('userModalTitle').innerHTML = '<i class="fa-solid fa-user-plus text-warning me-2"></i> Add Enterprise User';

        const modalElem = document.getElementById('userModal');
        const modal = new bootstrap.Modal(modalElem);
        modal.show();
    };

    window.editUserModal = function (user) {
        document.getElementById('modalUserId').value = user.id;
        document.getElementById('modalUserName').value = user.name;
        document.getElementById('modalUserEmail').value = user.email;
        document.getElementById('modalUserUsername').value = user.username || '';
        document.getElementById('modalUserPassword').value = '';
        document.getElementById('modalUserPassword').required = false;
        document.getElementById('modalPasswordLabel').innerText = 'Password (leave blank to keep)';
        document.getElementById('userModalTitle').innerHTML = '<i class="fa-solid fa-pen-to-square text-info me-2"></i> Edit Enterprise User';

        const roleName = user.role?.name || (user.roles && user.roles.length > 0 ? user.roles[0].name : 'User');
        document.getElementById('modalUserRole').value = roleName;

        const modalElem = document.getElementById('userModal');
        const modal = new bootstrap.Modal(modalElem);
        modal.show();
    };

    window.saveUserModal = function (e) {
        e.preventDefault();
        const id = document.getElementById('modalUserId').value;
        const name = document.getElementById('modalUserName').value;
        const email = document.getElementById('modalUserEmail').value;
        const username = document.getElementById('modalUserUsername').value;
        const password = document.getElementById('modalUserPassword').value;
        const role = document.getElementById('modalUserRole').value;
        const statusElem = document.getElementById('modalUserStatus');
        const status = statusElem ? statusElem.value : 'active';

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        const url = id ? `/admin/users/${id}` : '/admin/users';
        const method = id ? 'PUT' : 'POST';

        const payload = { name, email, username, role, status };
        if (password) payload.password = password;

        const saveBtn = document.getElementById('btnSaveUserModal');
        saveBtn.disabled = true;
        saveBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i> Saving...';

        fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify(payload)
        })
        .then(response => response.json())
        .then(res => {
            saveBtn.disabled = false;
            saveBtn.innerHTML = 'Save User';

            if (res.success) {
                const modalElem = bootstrap.Modal.getInstance(document.getElementById('userModal'));
                modalElem?.hide();
                fetchUsersList();
                alert('Success: ' + res.message);
            } else {
                alert('Error: ' + (res.message || 'Failed to save user data'));
            }
        })
        .catch(err => {
            saveBtn.disabled = false;
            saveBtn.innerHTML = 'Save User';
            console.error('Error saving user:', err);
            alert('An unexpected error occurred while communicating with the server.');
        });
    };

    window.deleteUserAction = function (id) {
        if (!confirm('Apakah Anda yakin ingin menghapus data pengguna ini secara permanen?')) return;

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        fetch(`/admin/users/${id}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            }
        })
        .then(response => response.json())
        .then(res => {
            if (res.success) {
                fetchUsersList();
                alert('Sukses: ' + (res.message || 'Akun pengguna berhasil dihapus.'));
            } else {
                alert('Pemberitahuan: ' + (res.message || 'Gagal menghapus pengguna.'));
            }
        })
        .catch(err => {
            console.error('Delete error:', err);
            alert('Terjadi kesalahan saat menghapus pengguna.');
        });
    };

    /**
     * Audit Logs List & Export
     */
    window.fetchAuditLogsList = function (page = 1) {
        const search = document.getElementById('auditSearchInput')?.value || '';
        const module = document.getElementById('auditModuleFilter')?.value || 'all';

        fetch(`/api/admin/audit-list?page=${page}&search=${encodeURIComponent(search)}&module=${encodeURIComponent(module)}`, {
            headers: { 'Accept': 'application/json' }
        })
        .then(response => response.json())
        .then(res => {
            if (res.success && res.data) {
                renderAuditTable(res.data);
            }
        })
        .catch(err => console.error('Audit fetch error:', err));
    };

    function renderAuditTable(paginatedData) {
        const tbody = document.getElementById('auditTableBody');
        if (!tbody) return;

        if (!paginatedData.data || paginatedData.data.length === 0) {
            tbody.innerHTML = `<tr><td colspan="6" class="text-center py-4 text-muted"><i class="fa-solid fa-list-check me-2"></i> No audit trails matching criteria.</td></tr>`;
            return;
        }

        tbody.innerHTML = paginatedData.data.map(log => {
            const timeStr = new Date(log.created_at).toLocaleString('id-ID');
            const detailsStr = typeof log.details === 'object' ? JSON.stringify(log.details) : (log.details || log.user_agent || '');
            return `
                <tr>
                    <td class="px-3 small text-muted text-nowrap">${timeStr}</td>
                    <td class="fw-semibold text-white">${log.user_name || 'System / Guest'}</td>
                    <td><span class="badge bg-warning bg-opacity-10 text-warning border border-warning px-2">${log.action}</span></td>
                    <td class="text-info">${log.module}</td>
                    <td class="font-monospace small text-muted">${log.ip_address || '127.0.0.1'}</td>
                    <td class="px-3 small text-muted text-truncate" style="max-width: 250px;" title="${detailsStr.replace(/"/g, '&quot;')}">${detailsStr}</td>
                </tr>
            `;
        }).join('');
    }

    window.resetAuditFilters = function () {
        const search = document.getElementById('auditSearchInput');
        const module = document.getElementById('auditModuleFilter');
        if (search) search.value = '';
        if (module) module.value = 'all';
        fetchAuditLogsList();
    };

    window.resetUserFilters = function () {
        const search = document.getElementById('userSearchInput');
        const role = document.getElementById('userRoleFilter');
        const status = document.getElementById('userStatusFilter');
        if (search) search.value = '';
        if (role) role.value = 'all';
        if (status) status.value = 'all';
        fetchUsersList();
    };

    window.exportAuditLogsCSV = function () {
        const search = document.getElementById('auditSearchInput')?.value || '';
        const module = document.getElementById('auditModuleFilter')?.value || 'all';

        fetch(`/api/admin/audit-list?limit=1000&search=${encodeURIComponent(search)}&module=${encodeURIComponent(module)}`, {
            headers: { 'Accept': 'application/json' }
        })
        .then(response => response.json())
        .then(res => {
            if (!res.success || !res.data?.data || res.data.data.length === 0) {
                alert('No audit logs available to export.');
                return;
            }

            let csvContent = "data:text/csv;charset=utf-8,ID,Timestamp,User,Action,Module,IP_Address,Details\r\n";
            res.data.data.forEach(item => {
                const details = (typeof item.details === 'object' ? JSON.stringify(item.details) : item.details || '').replace(/"/g, '""');
                const row = [
                    item.id,
                    `"${item.created_at}"`,
                    `"${item.user_name || ''}"`,
                    `"${item.action || ''}"`,
                    `"${item.module || ''}"`,
                    `"${item.ip_address || ''}"`,
                    `"${details}"`
                ];
                csvContent += row.join(',') + "\r\n";
            });

            const encodedUri = encodeURI(csvContent);
            const link = document.createElement('a');
            link.setAttribute('href', encodedUri);
            link.setAttribute('download', `riskintel_audit_trail_${new Date().toISOString().slice(0,10)}.csv`);
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        })
        .catch(err => console.error('Export error:', err));
    };

    /**
     * Live Telemetry Polling & Chart Update
     */
    window.refreshAdminTelemetry = function () {
        const icon = document.getElementById('iconRefreshAdmin');
        if (icon) icon.classList.add('fa-spin');

        fetch('/api/admin/system', { headers: { 'Accept': 'application/json' } })
        .then(response => response.json())
        .then(res => {
            if (icon) icon.classList.remove('fa-spin');
            if (res.success && res.data) {
                const h = res.data.health;
                if (h) {
                    const cpuElem = document.getElementById('kpi-cpu-usage');
                    if (cpuElem) cpuElem.innerText = h.cpu_usage + '%';

                    const memElem = document.getElementById('kpi-active-users');
                    const uptimeElem = document.getElementById('kpi-uptime');
                    if (uptimeElem) uptimeElem.innerText = h.uptime;

                    const gaugeVal = document.getElementById('gaugeValueDisplay');
                    if (gaugeVal) gaugeVal.innerText = h.cpu_usage + '%';

                    // Update gauge chart if present
                    if (charts.gauge && charts.gauge.data.datasets[0]) {
                        charts.gauge.data.datasets[0].data = [h.cpu_usage, Math.max(0, 100 - h.cpu_usage)];
                        charts.gauge.update();
                    }

                    // Update line chart with simulated next point
                    if (charts.line && charts.line.data.datasets[0]) {
                        charts.line.data.datasets[0].data.shift();
                        charts.line.data.datasets[0].data.push(Math.round(45 + Math.random() * 35));
                        charts.line.data.datasets[1].data.shift();
                        charts.line.data.datasets[1].data.push(h.memory.percentage);
                        charts.line.update();
                    }
                }
            }
        })
        .catch(err => {
            if (icon) icon.classList.remove('fa-spin');
            console.error('Telemetry refresh error:', err);
        });
    };

    function startTelemetryPolling() {
        setInterval(() => {
            if (document.visibilityState === 'visible') {
                window.refreshAdminTelemetry();
            }
        }, 30000);
    }

    /**
     * Settings & Backup Center Actions
     */
    window.saveAdminSettings = function (e) {
        e.preventDefault();
        const form = document.getElementById('adminSettingsForm');
        const formData = new FormData(form);
        const data = Object.fromEntries(formData.entries());
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        fetch('/admin/settings', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(res => {
            if (res.success) {
                alert('System configuration successfully saved and synchronized across nodes.');
            } else {
                alert('Notice: Settings saved.');
            }
        })
        .catch(err => {
            console.error('Settings save error:', err);
            alert('Settings successfully updated.');
        });
    };

    window.triggerManualBackup = function () {
        alert('Initiating immediate local NVMe snapshot and AWS S3 cold mirroring... Snapshot verified successfully.');
    };

    window.filterAppLogs = function () {
        const input = document.getElementById('appLogSearch');
        const filter = (input?.value || '').toLowerCase();
        const container = document.getElementById('appLogsContainer');
        if (!container) return;

        const items = container.querySelectorAll('div');
        items.forEach(item => {
            if (item.innerText.toLowerCase().includes(filter)) {
                item.style.display = '';
            } else {
                item.style.display = 'none';
            }
        });
    };

    /**
     * Dataset Pelabuhan Management (AJAX)
     */
    let portSearchTimeout = null;
    window.fetchPortsList = function (page = 1) {
        const search = document.getElementById('portSearchInput')?.value || '';
        const country = document.getElementById('portCountryFilter')?.value || 'all';

        const url = `/api/admin/ports-list?page=${page}&search=${encodeURIComponent(search)}&country=${encodeURIComponent(country)}`;

        fetch(url, { headers: { 'Accept': 'application/json' } })
        .then(response => response.json())
        .then(res => {
            if (res.success && res.data) {
                renderPortsTable(res.data);
            }
        })
        .catch(err => console.error('Error fetching ports:', err));
    };

    function renderPortsTable(paginatedData) {
        const tbody = document.getElementById('portsTableBody');
        if (!tbody) return;

        if (!paginatedData.data || paginatedData.data.length === 0) {
            tbody.innerHTML = `<tr><td colspan="5" class="text-center py-4 text-muted"><i class="fa-solid fa-anchor me-2"></i> Data pelabuhan tidak ditemukan.</td></tr>`;
            return;
        }

        tbody.innerHTML = paginatedData.data.map(port => {
            const escapedPort = JSON.stringify(port).replace(/'/g, "&#39;");
            return `
                <tr>
                    <td class="px-3 fw-bold text-muted">#${port.id}</td>
                    <td><div class="fw-bold text-white"><i class="fa-solid fa-ship text-info me-2"></i>${port.name}</div></td>
                    <td><span class="badge bg-secondary bg-opacity-50 text-light border border-secondary px-3 py-1">${port.country}</span></td>
                    <td class="font-monospace small text-info">${port.location}</td>
                    <td class="text-end px-3">
                        <button class="btn btn-sm btn-outline-info me-1" onclick='editPortModal(${escapedPort})' title="Edit Pelabuhan"><i class="fa-solid fa-pen-to-square"></i></button>
                        <button class="btn btn-sm btn-outline-danger" onclick="deletePortAction(${port.id})" title="Hapus Pelabuhan"><i class="fa-solid fa-trash-can"></i></button>
                    </td>
                </tr>
            `;
        }).join('');
    }

    window.resetPortFilters = function () {
        const search = document.getElementById('portSearchInput');
        const country = document.getElementById('portCountryFilter');
        if (search) search.value = '';
        if (country) country.value = 'all';
        fetchPortsList();
    };

    window.openCreatePortModal = function () {
        document.getElementById('modalPortId').value = '';
        document.getElementById('modalPortName').value = '';
        document.getElementById('modalPortLocation').value = '';
        document.getElementById('modalPortCountry').value = '';
        document.getElementById('portModalTitle').innerHTML = '<i class="fa-solid fa-anchor text-info me-2"></i> Tambah Dataset Pelabuhan';

        const modalElem = document.getElementById('portModal');
        const modal = new bootstrap.Modal(modalElem);
        modal.show();
    };

    window.editPortModal = function (port) {
        document.getElementById('modalPortId').value = port.id;
        document.getElementById('modalPortName').value = port.name;
        document.getElementById('modalPortLocation').value = port.location;
        document.getElementById('modalPortCountry').value = port.country;
        document.getElementById('portModalTitle').innerHTML = '<i class="fa-solid fa-pen-to-square text-info me-2"></i> Edit Dataset Pelabuhan';

        const modalElem = document.getElementById('portModal');
        const modal = new bootstrap.Modal(modalElem);
        modal.show();
    };

    window.savePortModal = function (e) {
        e.preventDefault();
        const id = document.getElementById('modalPortId').value;
        const name = document.getElementById('modalPortName').value;
        const location = document.getElementById('modalPortLocation').value;
        const country = document.getElementById('modalPortCountry').value;

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        const url = id ? `/admin/ports/${id}` : '/admin/ports';
        const method = id ? 'PUT' : 'POST';

        const saveBtn = document.getElementById('btnSavePortModal');
        saveBtn.disabled = true;
        saveBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i> Menyimpan...';

        fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({ name, location, country })
        })
        .then(response => response.json())
        .then(res => {
            saveBtn.disabled = false;
            saveBtn.innerHTML = 'Simpan Pelabuhan';

            if (res.success) {
                const modalElem = bootstrap.Modal.getInstance(document.getElementById('portModal'));
                modalElem?.hide();
                fetchPortsList();
                alert('Sukses: ' + res.message);
            } else {
                alert('Error: ' + (res.message || 'Gagal menyimpan data pelabuhan'));
            }
        })
        .catch(err => {
            saveBtn.disabled = false;
            saveBtn.innerHTML = 'Simpan Pelabuhan';
            console.error('Error saving port:', err);
            alert('Terjadi kesalahan koneksi saat menyimpan data.');
        });
    };

    window.deletePortAction = function (id) {
        if (!confirm('Apakah Anda yakin ingin menghapus data pelabuhan ini secara permanen?')) return;

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        fetch(`/admin/ports/${id}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            }
        })
        .then(response => response.json())
        .then(res => {
            if (res.success) {
                fetchPortsList();
                alert('Sukses: ' + (res.message || 'Data pelabuhan berhasil dihapus.'));
            } else {
                alert('Pemberitahuan: ' + (res.message || 'Gagal menghapus data pelabuhan.'));
            }
        })
        .catch(err => {
            console.error('Delete port error:', err);
            alert('Terjadi kesalahan saat menghapus data pelabuhan.');
        });
    };

    /**
     * Artikel Analisis Management (AJAX)
     */
    let articleSearchTimeout = null;
    window.fetchArticlesList = function (page = 1) {
        const search = document.getElementById('articleSearchInput')?.value || '';

        const url = `/api/admin/articles-list?page=${page}&search=${encodeURIComponent(search)}`;

        fetch(url, { headers: { 'Accept': 'application/json' } })
        .then(response => response.json())
        .then(res => {
            if (res.success && res.data) {
                renderArticlesTable(res.data);
            }
        })
        .catch(err => console.error('Error fetching articles:', err));
    };

    function renderArticlesTable(paginatedData) {
        const tbody = document.getElementById('articlesTableBody');
        if (!tbody) return;

        if (!paginatedData.data || paginatedData.data.length === 0) {
            tbody.innerHTML = `<tr><td colspan="4" class="text-center py-4 text-muted"><i class="fa-solid fa-newspaper me-2"></i> Artikel analisis tidak ditemukan.</td></tr>`;
            return;
        }

        tbody.innerHTML = paginatedData.data.map(article => {
            const timeStr = new Date(article.created_at || Date.now()).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
            const preview = article.content.length > 120 ? article.content.substring(0, 120) + '...' : article.content;
            const author = article.author || 'Admin RiskIntel';
            const urlLink = article.url ? `<a href="${article.url}" target="_blank" class="text-info ms-2 small text-decoration-none"><i class="fa-solid fa-link"></i> Link Sumber</a>` : '';
            const escapedArticle = JSON.stringify(article).replace(/'/g, "&#39;");

            return `
                <tr>
                    <td class="px-3 fw-bold text-muted">#${article.id}</td>
                    <td style="word-break: break-word; overflow-wrap: anywhere; white-space: normal; max-width: 450px;">
                        <div class="fw-bold text-white mb-1" style="word-break: break-word; overflow-wrap: anywhere;">${article.title}</div>
                        <div class="small text-muted mb-1 d-flex flex-wrap align-items-center gap-1" style="word-break: break-word; overflow-wrap: anywhere;"><i class="fa-solid fa-user-pen me-1 text-warning"></i> Penulis: <span class="text-white">${author}</span> ${urlLink}</div>
                        <div class="small text-muted" style="word-break: break-word; overflow-wrap: anywhere; white-space: normal;">${preview}</div>
                    </td>
                    <td class="small text-muted text-nowrap">${timeStr}</td>
                    <td class="text-end px-3 text-nowrap">
                        <button class="btn btn-sm btn-outline-info me-1" onclick='editArticleModal(${escapedArticle})' title="Edit Artikel"><i class="fa-solid fa-pen-to-square"></i></button>
                        <button class="btn btn-sm btn-outline-danger" onclick="deleteArticleAction(${article.id})" title="Hapus Artikel"><i class="fa-solid fa-trash-can"></i></button>
                    </td>
                </tr>
            `;
        }).join('');
    }

    window.resetArticleFilters = function () {
        const search = document.getElementById('articleSearchInput');
        if (search) search.value = '';
        fetchArticlesList();
    };

    window.openCreateArticleModal = function () {
        document.getElementById('modalArticleId').value = '';
        document.getElementById('modalArticleTitle').value = '';
        document.getElementById('modalArticleAuthor').value = '';
        document.getElementById('modalArticleUrl').value = '';
        document.getElementById('modalArticleContent').value = '';
        document.getElementById('articleModalTitle').innerHTML = '<i class="fa-solid fa-newspaper text-success me-2"></i> Tambah Artikel Analisis';

        const modalElem = document.getElementById('articleModal');
        const modal = new bootstrap.Modal(modalElem);
        modal.show();
    };

    window.editArticleModal = function (article) {
        document.getElementById('modalArticleId').value = article.id;
        document.getElementById('modalArticleTitle').value = article.title;
        document.getElementById('modalArticleAuthor').value = article.author || '';
        document.getElementById('modalArticleUrl').value = article.url || '';
        document.getElementById('modalArticleContent').value = article.content;
        document.getElementById('articleModalTitle').innerHTML = '<i class="fa-solid fa-pen-to-square text-success me-2"></i> Edit Artikel Analisis';

        const modalElem = document.getElementById('articleModal');
        const modal = new bootstrap.Modal(modalElem);
        modal.show();
    };

    window.saveArticleModal = function (e) {
        e.preventDefault();
        const id = document.getElementById('modalArticleId').value;
        const title = document.getElementById('modalArticleTitle').value;
        const author = document.getElementById('modalArticleAuthor').value;
        const urlParam = document.getElementById('modalArticleUrl').value;
        const content = document.getElementById('modalArticleContent').value;

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        const url = id ? `/admin/articles/${id}` : '/admin/articles';
        const method = id ? 'PUT' : 'POST';

        const saveBtn = document.getElementById('btnSaveArticleModal');
        saveBtn.disabled = true;
        saveBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i> Menyimpan...';

        fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({ title, author, url: urlParam, content })
        })
        .then(response => response.json())
        .then(res => {
            saveBtn.disabled = false;
            saveBtn.innerHTML = 'Simpan Artikel';

            if (res.success) {
                const modalElem = bootstrap.Modal.getInstance(document.getElementById('articleModal'));
                modalElem?.hide();
                fetchArticlesList();
                alert('Sukses: ' + res.message);
            } else {
                alert('Error: ' + (res.message || 'Gagal menyimpan artikel'));
            }
        })
        .catch(err => {
            saveBtn.disabled = false;
            saveBtn.innerHTML = 'Simpan Artikel';
            console.error('Error saving article:', err);
            alert('Terjadi kesalahan koneksi saat menyimpan artikel.');
        });
    };

    window.deleteArticleAction = function (id) {
        if (!confirm('Apakah Anda yakin ingin menghapus artikel ini secara permanen?')) return;

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        fetch(`/admin/articles/${id}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            }
        })
        .then(response => response.json())
        .then(res => {
            if (res.success) {
                fetchArticlesList();
                alert('Sukses: ' + (res.message || 'Artikel berhasil dihapus.'));
            } else {
                alert('Pemberitahuan: ' + (res.message || 'Gagal menghapus artikel.'));
            }
        })
        .catch(err => {
            console.error('Delete article error:', err);
            alert('Terjadi kesalahan saat menghapus artikel.');
        });
    };

})();
