<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
@if(config('app.env') === 'production' || (request()->getHost() !== '127.0.0.1' && request()->getHost() !== 'localhost'))
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
@endif
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Global Supply Chain Risk Intelligence Platform') - RiskIntel Hub</title>
    <link rel="icon" href="{{ asset('favicon.svg') }}?v={{ time() }}" type="image/svg+xml">
    <link rel="icon" href="{{ asset('favicon.ico') }}?v={{ time() }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}?v={{ time() }}" type="image/x-icon">
    <link rel="apple-touch-icon" href="{{ asset('favicon.png') }}?v={{ time() }}">
    
    <!-- Bootstrap 5.3.2 & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <!-- Google Fonts: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Leaflet & Choices.js CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        :root {
            /* ── Enterprise Design System Color Tokens ── */
            --primary:          #2563eb;
            --primary-hover:    #1d4ed8;
            --primary-light:    #eff6ff;
            --primary-border:   #bfdbfe;

            --secondary:        #1e293b;
            --secondary-hover:  #0f172a;

            --bg-main:          #f8fafc;
            --surface:          #ffffff;
            --surface-hover:    #f8fafc;

            --border:           #e2e8f0;
            --border-strong:    #cbd5e1;

            --text-primary:     #0f172a;
            --text-secondary:   #475569;
            --text-muted:       #64748b;
            --text-disabled:    #94a3b8;

            --success:          #22c55e;
            --success-bg:       #f0fdf4;
            --success-border:   #bbf7d0;
            --success-text:     #15803d;

            --warning:          #f59e0b;
            --warning-bg:       #fffbeb;
            --warning-border:   #fde68a;
            --warning-text:     #b45309;

            --danger:           #ef4444;
            --danger-bg:        #fef2f2;
            --danger-border:    #fecaca;
            --danger-text:      #b91c1c;

            --info:             #0ea5e9;
            --info-bg:          #f0f9ff;
            --info-border:      #bae6fd;
            --info-text:        #0369a1;

            --shadow-sm:        0 1px 2px 0 rgba(15, 23, 42, 0.05);
            --shadow-md:        0 4px 6px -1px rgba(15, 23, 42, 0.08), 0 2px 4px -2px rgba(15, 23, 42, 0.04);
            --shadow-lg:        0 10px 15px -3px rgba(15, 23, 42, 0.1), 0 4px 6px -4px rgba(15, 23, 42, 0.05);

            --radius-sm:        6px;
            --radius-md:        8px;
            --radius-lg:        12px;
            --radius-pill:      9999px;

            --transition:       all 0.18s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* ── Base Reset & Typography ── */
        body {
            background-color: var(--bg-main);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            color: var(--text-primary);
            margin: 0;
            overflow-x: hidden;
            font-size: 13.5px !important;
            line-height: 1.5;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        h1, h2, h3, h4, h5, h6 {
            color: var(--text-primary) !important;
            font-weight: 700 !important;
            letter-spacing: -0.02em;
            margin-bottom: 0.5rem;
        }
        h1 { font-size: 24px !important; }
        h2 { font-size: 20px !important; }
        h3 { font-size: 18px !important; }
        h4 { font-size: 16px !important; }
        h5 { font-size: 14px !important; }
        h6 { font-size: 13px !important; }

        p, span, div {
            color: inherit;
        }

        .text-muted, small, .small {
            color: var(--text-muted) !important;
            font-size: 12px !important;
            font-weight: 500;
        }

        /* ── Sidebar (Enterprise Slate Theme) ── */
        .sidebar {
            width: 260px;
            min-width: 260px;
            flex-shrink: 0;
            background-color: var(--secondary);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: left 0.25s ease-in-out;
            border-right: 1px solid rgba(255,255,255,0.06);
        }
        .sidebar-section-title {
            color: #64748b;
            font-size: 10px !important;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            padding: 16px 20px 6px;
        }
        .sidebar-link {
            color: #94a3b8;
            padding: 9px 18px;
            display: flex;
            align-items: center;
            text-decoration: none;
            border-radius: var(--radius-sm);
            margin: 2px 12px;
            font-weight: 500;
            font-size: 13px !important;
            transition: var(--transition);
            border-left: 3px solid transparent;
        }
        .sidebar-link:hover {
            color: #ffffff;
            background-color: rgba(255,255,255,0.06);
            border-left-color: rgba(255,255,255,0.2);
        }
        .sidebar-link.active {
            color: #ffffff;
            background-color: rgba(37,99,235,0.18);
            border-left-color: var(--primary);
            font-weight: 600;
        }
        .sidebar-link i {
            width: 20px;
            text-align: center;
            margin-right: 10px;
            font-size: 14px;
        }

        /* ── Enterprise Navbar ── */
        .navbar-enterprise {
            background-color: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(8px);
            border-bottom: 1px solid var(--border);
            height: 56px;
            position: sticky;
            top: 0;
            z-index: 1030;
            box-shadow: var(--shadow-sm);
        }

        /* ── Card Component ── */
        .glass-card, .card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            padding: 1.25rem;
            box-shadow: var(--shadow-sm);
            transition: var(--transition);
        }
        .glass-card:hover, .card:hover {
            box-shadow: var(--shadow-md);
            border-color: var(--border-strong);
        }

        /* ── Buttons ── */
        .btn {
            font-size: 13px !important;
            font-weight: 500;
            border-radius: var(--radius-sm) !important;
            padding: 6px 14px;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }
        .btn-primary {
            background-color: var(--primary) !important;
            border-color: var(--primary) !important;
            color: #ffffff !important;
            box-shadow: 0 1px 2px rgba(37,99,235,0.2);
        }
        .btn-primary:hover {
            background-color: var(--primary-hover) !important;
            border-color: var(--primary-hover) !important;
        }
        .btn-secondary {
            background-color: #f1f5f9 !important;
            border-color: #e2e8f0 !important;
            color: var(--text-primary) !important;
        }
        .btn-secondary:hover {
            background-color: #e2e8f0 !important;
        }
        .btn-outline-secondary {
            border-color: var(--border-strong) !important;
            color: var(--text-secondary) !important;
            background-color: transparent !important;
        }
        .btn-outline-secondary:hover {
            background-color: #f1f5f9 !important;
            color: var(--text-primary) !important;
        }

        /* ── Form Inputs ── */
        .form-control, .form-select {
            background-color: #ffffff !important;
            border: 1px solid var(--border-strong) !important;
            color: var(--text-primary) !important;
            border-radius: var(--radius-sm) !important;
            font-size: 13.5px !important;
            padding: 8px 12px;
            box-shadow: var(--shadow-sm);
            transition: var(--transition);
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--primary) !important;
            box-shadow: 0 0 0 3px rgba(37,99,235,0.12) !important;
            background-color: #ffffff !important;
        }

        /* ── Tables ── */
        .table {
            color: var(--text-primary) !important;
            margin-bottom: 0;
            width: 100%;
        }
        .table thead th {
            background-color: #f8fafc !important;
            color: var(--text-secondary) !important;
            border-bottom: 1px solid var(--border) !important;
            font-weight: 600;
            font-size: 11.5px !important;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 10px 14px;
        }
        .table tbody td {
            border-bottom: 1px solid #f1f5f9 !important;
            padding: 12px 14px;
            font-size: 13px !important;
            vertical-align: middle;
        }
        .table-hover tbody tr:hover {
            background-color: #f8fafc !important;
        }

        /* ── Enterprise Soft Badges ── */
        .badge {
            font-weight: 600;
            font-size: 11px !important;
            padding: 3px 8px;
            border-radius: 4px;
            letter-spacing: 0.01em;
        }
        .badge-success, .badge-soft-success {
            background-color: var(--success-bg) !important;
            color: var(--success-text) !important;
            border: 1px solid var(--success-border) !important;
        }
        .badge-warning, .badge-soft-warning {
            background-color: var(--warning-bg) !important;
            color: var(--warning-text) !important;
            border: 1px solid var(--warning-border) !important;
        }
        .badge-danger, .badge-soft-danger {
            background-color: var(--danger-bg) !important;
            color: var(--danger-text) !important;
            border: 1px solid var(--danger-border) !important;
        }
        .badge-info, .badge-soft-info {
            background-color: var(--info-bg) !important;
            color: var(--info-text) !important;
            border: 1px solid var(--info-border) !important;
        }
        .badge-secondary, .badge-soft-secondary {
            background-color: #f1f5f9 !important;
            color: #475569 !important;
            border: 1px solid #e2e8f0 !important;
        }

        /* ── Modals ── */
        .modal-content {
            background: #ffffff !important;
            border: 1px solid var(--border) !important;
            border-radius: var(--radius-lg) !important;
            box-shadow: var(--shadow-lg) !important;
            color: var(--text-primary) !important;
        }
        .modal-header {
            border-bottom: 1px solid var(--border) !important;
            padding: 16px 20px;
        }
        .modal-footer {
            border-top: 1px solid var(--border) !important;
            padding: 14px 20px;
        }

        /* ── Custom Scrollbar ── */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--primary); }

        /* ── Master Enterprise Responsive Engine (320px - 3440px) ── */
        html, body {
            max-width: 100vw !important;
            overflow-x: hidden !important;
            box-sizing: border-box !important;
        }

        *, *:before, *:after {
            box-sizing: inherit;
        }

        .main-wrapper {
            display: flex;
            width: 100%;
            min-height: 100vh;
            overflow-x: hidden;
        }

        .content-wrapper {
            flex-grow: 1;
            min-width: 0;
            width: 100%;
            overflow-x: hidden;
        }

        /* Fluid Typography System */
        h1, .h1 { font-size: clamp(1.2rem, 1.8vw + 0.5rem, 1.75rem) !important; line-height: 1.25 !important; }
        h2, .h2 { font-size: clamp(1.05rem, 1.4vw + 0.4rem, 1.5rem) !important; line-height: 1.3 !important; }
        h3, .h3 { font-size: clamp(0.95rem, 1.1vw + 0.35rem, 1.25rem) !important; line-height: 1.35 !important; }
        h4, .h4 { font-size: clamp(0.9rem, 0.9vw + 0.3rem, 1.1rem) !important; }
        h5, .h5 { font-size: clamp(0.85rem, 0.75vw + 0.25rem, 1rem) !important; }
        h6, .h6 { font-size: clamp(0.8rem, 0.6vw + 0.2rem, 0.875rem) !important; }

        /* Responsive Layout Containers & Tables */
        .container-fluid {
            width: 100% !important;
            max-width: 100% !important;
            padding-left: clamp(0.75rem, 1.5vw, 2rem) !important;
            padding-right: clamp(0.75rem, 1.5vw, 2rem) !important;
        }

        .table-responsive {
            width: 100% !important;
            overflow-x: auto !important;
            -webkit-overflow-scrolling: touch;
            margin-bottom: 1rem;
            border-radius: var(--radius-md);
        }

        /* Responsive Leaflet Map & Chart Containers */
        #maritimeMap, #map, .leaflet-container {
            width: 100% !important;
            max-width: 100% !important;
            min-height: 320px !important;
            height: clamp(320px, 45vh, 600px) !important;
            border-radius: 8px !important;
            z-index: 1 !important;
        }

        .chart-container, canvas {
            max-width: 100% !important;
        }

        /* ── Responsive Layout & Sidebar Engine ── */
        @media (min-width: 992px) {
            .sidebar {
                position: relative !important;
                left: 0 !important;
                width: 260px !important;
                min-width: 260px !important;
                flex-shrink: 0 !important;
                z-index: 100 !important;
            }
            .main-content {
                width: calc(100% - 260px) !important;
                flex-grow: 1 !important;
                min-width: 0 !important;
            }
            .sidebar-backdrop {
                display: none !important;
            }
        }

        @media (max-width: 991.98px) {
            .sidebar {
                position: fixed !important;
                left: -260px !important;
                top: 0 !important;
                bottom: 0 !important;
                height: 100vh !important;
                z-index: 1080 !important;
                box-shadow: 0 0 20px rgba(15, 23, 42, 0.3) !important;
            }
            .sidebar.show { left: 0 !important; }
            .main-content {
                width: 100% !important;
                max-width: 100vw !important;
            }
            .btn {
                padding: 6px 12px !important;
                font-size: 12.5px !important;
            }
            .modal-dialog {
                margin: 0.5rem !important;
                max-width: calc(100vw - 1rem) !important;
            }
        }

        @media (max-width: 575.98px) {
            .w-xs-100 {
                width: 100% !important;
            }
            .country-dropdown-menu {
                max-height: 250px !important;
            }
        }

        @media (min-width: 1921px) {
            .container-fluid {
                max-width: 2400px !important;
                margin-left: auto !important;
                margin-right: auto !important;
            }
        }

        .sidebar-backdrop {
            position: fixed; top: 0; left: 0;
            width: 100vw; height: 100vh;
            background: rgba(15,23,42,0.4);
            backdrop-filter: blur(2px);
            z-index: 1070 !important;
            display: none; opacity: 0;
            transition: opacity 0.2s ease;
        }
        /* ── Enterprise Country Selector System (Fix Bug 1 & Bug 2) ── */
        .country-selector-row {
            position: relative !important;
            z-index: 10500 !important;
        }

        .country-selector-card {
            position: relative !important;
            z-index: 10500 !important;
            overflow: visible !important;
        }

        .country-dropdown-menu {
            position: absolute !important;
            top: 100% !important;
            left: 0 !important;
            width: 100% !important;
            background-color: #ffffff !important;
            border: 1px solid #cbd5e1 !important;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.18) !important;
            border-radius: 10px !important;
            padding: 6px 0 !important;
            z-index: 99999 !important;
            max-height: 340px !important;
            overflow-y: auto !important;
            margin-top: 6px !important;
        }

        .country-dropdown-item {
            background-color: #ffffff !important;
            color: #0f172a !important;
            padding: 10px 16px !important;
            border-bottom: 1px solid #f1f5f9 !important;
            cursor: pointer !important;
            transition: background-color 0.15s ease-in-out, color 0.15s ease-in-out !important;
            text-decoration: none !important;
            display: flex !important;
            align-items: center !important;
            justify-content: space-between !important;
            outline: none !important;
            user-select: none !important;
        }

        .country-dropdown-item:last-child {
            border-bottom: none !important;
        }

        .country-dropdown-item .country-name {
            color: #0f172a !important;
            font-weight: 600 !important;
            font-size: 13.5px !important;
            line-height: 1.25 !important;
            transition: color 0.15s ease-in-out !important;
        }

        .country-dropdown-item .country-meta {
            color: #64748b !important;
            font-size: 11px !important;
            margin-top: 1.5px !important;
            transition: color 0.15s ease-in-out !important;
        }

        /* HOVER & FOCUS STATES (Fix Bug 1 Contrast) */
        .country-dropdown-item:hover,
        .country-dropdown-item:focus {
            background-color: #eff6ff !important;
            color: #2563eb !important;
        }

        .country-dropdown-item:hover .country-name,
        .country-dropdown-item:focus .country-name {
            color: #2563eb !important;
        }

        .country-dropdown-item:hover .country-meta,
        .country-dropdown-item:focus .country-meta {
            color: #3b82f6 !important;
        }

        /* SELECTED & ACTIVE STATES */
        .country-dropdown-item:active,
        .country-dropdown-item.selected,
        .country-dropdown-item.active {
            background-color: #dbeafe !important;
            color: #1d4ed8 !important;
        }

        .country-dropdown-item:active .country-name,
        .country-dropdown-item.selected .country-name,
        .country-dropdown-item.active .country-name {
            color: #1d4ed8 !important;
        }

        .country-dropdown-item:active .country-meta,
        .country-dropdown-item.selected .country-meta,
        .country-dropdown-item.active .country-meta {
            color: #1e40af !important;
        }
    </style>
</head>
<body>
    <div id="sidebarBackdrop" class="sidebar-backdrop"></div>
    <div class="d-flex app-wrapper" style="width: 100%; overflow-x: hidden; min-height: 100vh; align-items: stretch;">
        <!-- Sidebar Navigation Component -->
        @include('components.sidebar')

        <!-- Main Content Area -->
        <div class="main-content flex-grow-1 d-flex flex-column" style="min-width: 0; max-width: 100%;">
            @include('components.navbar')

            <main class="flex-grow-1 p-3 p-md-4">
                @yield('content')
            </main>

            <footer class="py-3 px-4 border-top text-center text-md-start d-flex flex-column flex-md-row justify-content-between align-items-center small" style="border-color: #e2e8f0 !important; background:#f8fafc; color:#64748b;">
                <div style="color:#64748b;">&copy; {{ date('Y') }} RiskIntel Hub. All Rights Reserved. Enterprise Grade Supply Chain Platform.</div>
                <div class="mt-2 mt-md-0">
                    <span class="badge me-2" style="background:#dcfce7;color:#166534;border:1px solid #bbf7d0;"><i class="fa-solid fa-check-circle me-1"></i> SSL 256-Bit</span>
                    <span class="badge" style="background:#f1f5f9;color:#475569;border:1px solid #e2e8f0;">NGA Satellite Sync</span>
                </div>
            </footer>
        </div>
    </div>

    <!-- Bootstrap & External Libraries JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>

    <!-- Modular App JS -->
    <script src="{{ asset('js/escape.js') }}"></script>
    <script src="{{ asset('js/maps.js') }}"></script>
    <script src="{{ asset('js/dashboard.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    @stack('scripts')

    <script>
        // Offcanvas Mobile Sidebar Toggle & Backdrop
        const sidebarToggleBtn = document.getElementById('sidebarToggleBtn');
        const sidebarElem = document.getElementById('sidebarMain');
        const sidebarCloseBtn = document.getElementById('sidebarCloseBtn');
        const sidebarBackdrop = document.getElementById('sidebarBackdrop');

        function openSidebar() {
            if (sidebarElem) sidebarElem.classList.add('show');
            if (sidebarBackdrop) sidebarBackdrop.classList.add('show');
        }
        function closeSidebar() {
            if (sidebarElem) sidebarElem.classList.remove('show');
            if (sidebarBackdrop) sidebarBackdrop.classList.remove('show');
        }

        if (sidebarToggleBtn) sidebarToggleBtn.addEventListener('click', openSidebar);
        if (sidebarCloseBtn) sidebarCloseBtn.addEventListener('click', closeSidebar);
        if (sidebarBackdrop) sidebarBackdrop.addEventListener('click', closeSidebar);

        // Global Real-Time Clock Ticker (Navbar)
        const elGlobalClock = document.getElementById('globalNavbarClock');
        if (elGlobalClock) {
            function updateGlobalClock() {
                const now = new Date();
                const day = String(now.getDate()).padStart(2, '0');
                const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                const month = months[now.getMonth()];
                const year = now.getFullYear();
                let hours = now.getHours();
                const minutes = String(now.getMinutes()).padStart(2, '0');
                const seconds = String(now.getSeconds()).padStart(2, '0');
                const ampm = hours >= 12 ? 'PM' : 'AM';
                hours = hours % 12;
                hours = hours ? hours : 12;
                const hoursStr = String(hours).padStart(2, '0');
                elGlobalClock.innerHTML = `<span style="color:#3b82f6;">${day} ${month} ${year}</span><span style="color:#94a3b8;margin:0 5px;">•</span><span style="color:#0f172a;">${hoursStr}:${minutes}:${seconds} ${ampm}</span>`;
            }
            updateGlobalClock();
            setInterval(updateGlobalClock, 1000);
        }

        // Global Delegated Event untuk tombol putar/clear (Kosongkan Teks Pencarian TANPA mereset negara terpilih)
        document.addEventListener('click', (e) => {
            const clearTrigger = e.target.closest('.clear-search-trigger');
            if (clearTrigger) {
                e.stopPropagation();
                e.preventDefault();
                const container = clearTrigger.closest('div');
                const input = container ? container.querySelector('input[type="text"], input[type="search"]') : null;
                if (input) {
                    input.value = '';
                    input.focus();
                    input.dispatchEvent(new Event('input', { bubbles: true }));
                }
            }
        });

        // Enterprise Confirmation Dialog Reusable Helper System
        window.showConfirmDialog = function(options = {}) {
            const title = options.title || 'Konfirmasi Penghapusan';
            const text = options.text || 'Apakah Anda yakin ingin menghapus data ini?\nData yang telah dihapus tidak dapat dikembalikan.';
            const confirmButtonText = options.confirmButtonText || 'Ya, Hapus';
            const cancelButtonText = options.cancelButtonText || 'Batalkan';
            const icon = options.icon || 'warning';
            const onConfirm = options.onConfirm || (async () => {});

            if (typeof Swal === 'undefined') {
                if (window.confirm(text)) {
                    onConfirm();
                }
                return;
            }

            Swal.fire({
                title: title,
                html: text.replace(/\n/g, '<br>'),
                icon: icon,
                iconColor: icon === 'warning' ? '#F59E0B' : (icon === 'danger' || icon === 'error' ? '#EF4444' : '#2563EB'),
                showCancelButton: true,
                confirmButtonText: confirmButtonText,
                cancelButtonText: cancelButtonText,
                reverseButtons: true,
                focusCancel: true,
                customClass: {
                    popup: 'enterprise-swal-popup',
                    title: 'enterprise-swal-title',
                    htmlContainer: 'enterprise-swal-text',
                    confirmButton: 'btn-swal-confirm',
                    cancelButton: 'btn-swal-cancel'
                },
                buttonsStyling: false,
                showClass: {
                    popup: 'swal2-show'
                },
                hideClass: {
                    popup: 'swal2-hide'
                }
            }).then(async (result) => {
                if (result.isConfirmed) {
                    // Tampilkan Loading State saat request berjalan
                    Swal.fire({
                        title: 'Memproses...',
                        text: 'Mohon tunggu sebentar',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    try {
                        const res = await onConfirm();
                        if (res && res.success !== false) {
                            Swal.fire({
                                icon: 'success',
                                iconColor: '#22C55E',
                                title: res.title || 'Berhasil',
                                text: res.message || 'Data berhasil dihapus.',
                                confirmButtonText: 'OK',
                                customClass: {
                                    confirmButton: 'btn-swal-confirm-success'
                                },
                                buttonsStyling: false
                            });
                        } else if (res && res.success === false) {
                            Swal.fire({
                                icon: 'error',
                                iconColor: '#EF4444',
                                title: res.title || 'Gagal',
                                text: res.message || 'Data gagal dihapus.',
                                confirmButtonText: 'Tutup',
                                customClass: {
                                    confirmButton: 'btn-swal-cancel'
                                },
                                buttonsStyling: false
                            });
                        }
                    } catch (err) {
                        console.error('Action error:', err);
                        Swal.fire({
                            icon: 'error',
                            iconColor: '#EF4444',
                            title: 'Gagal',
                            text: err.message || 'Terjadi kesalahan pada server.',
                            confirmButtonText: 'Tutup',
                            customClass: {
                                confirmButton: 'btn-swal-cancel'
                            },
                            buttonsStyling: false
                        });
                    }
                }
            });
        };

        window.confirmDelete = function(onConfirmCallback, customText = null, customTitle = null) {
            window.showConfirmDialog({
                title: customTitle || 'Konfirmasi Penghapusan',
                text: customText || 'Apakah Anda yakin ingin menghapus data ini?\nData yang telah dihapus tidak dapat dikembalikan.',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batalkan',
                icon: 'warning',
                onConfirm: onConfirmCallback
            });
        };

        window.confirmLogout = function(e, formEl) {
            e.preventDefault();
            window.showConfirmDialog({
                title: 'Konfirmasi Keluar (Logout)',
                text: 'Apakah Anda yakin ingin keluar dari sesi aplikasi RiskIntel Hub?',
                confirmButtonText: 'Ya, Log Out',
                cancelButtonText: 'Batalkan',
                icon: 'warning',
                onConfirm: () => {
                    Object.keys(localStorage).forEach(k => { if (k.startsWith('selected_country_')) localStorage.removeItem(k); });
                    sessionStorage.clear();
                    formEl.submit();
                }
            });
        };
    </script>
</body>
</html>
