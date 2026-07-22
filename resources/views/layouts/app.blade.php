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
    <link rel="icon" href="{{ asset('favicon.ico') }}?v={{ time() }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}?v={{ time() }}" type="image/x-icon">
    <link rel="apple-touch-icon" href="{{ asset('favicon.ico') }}">
    
    <!-- Bootstrap 5.3.2 & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <!-- Google Fonts: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Leaflet & Choices.js CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />

    <style>
        :root {
            /* ── Light Theme: White + Navy Sidebar ── */
            --bg-main:        #f8fafc;
            --sidebar-bg:     #1e293b;
            --sidebar-hover:  rgba(59, 130, 246, 0.12);
            --card-bg:        #ffffff;
            --border-color:   #e2e8f0;
            --accent-gold:    #d97706;
            --accent-blue:    #3b82f6;
            --text-primary:   #0f172a;
            --text-secondary: #475569;
            --text-muted-lt:  #64748b;
            --shadow-card:    0 1px 3px rgba(0,0,0,0.08), 0 4px 16px rgba(0,0,0,0.06);
            --shadow-hover:   0 4px 12px rgba(0,0,0,0.12), 0 8px 24px rgba(0,0,0,0.08);
        }

        /* ── Base ── */
        body {
            background-color: var(--bg-main);
            font-family: 'Inter', sans-serif;
            color: var(--text-primary);
            margin: 0;
            overflow-x: hidden;
            font-size: 14px !important;
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
        }

        /* ── Typography overrides ── */
        .text-muted, .text-secondary, small, .small {
            color: var(--text-muted-lt) !important;
            font-weight: 500 !important;
            font-size: 13px !important;
        }
        label, .form-label {
            color: var(--text-secondary) !important;
            font-weight: 600 !important;
        }
        span.text-muted, div.text-muted, p.text-muted {
            color: var(--text-muted-lt) !important;
            font-weight: 500 !important;
        }
        h1, h2, h3, h4, h5, h6 {
            color: var(--text-primary) !important;
            text-shadow: none !important;
            font-weight: 700 !important;
            letter-spacing: -0.01em;
        }
        .table td, .table th, .form-control, .dropdown-item, .btn {
            font-size: 14px !important;
        }

        /* ── Sidebar (stays dark navy for contrast) ── */
        .sidebar {
            width: 260px;
            min-width: 260px;
            background-color: var(--sidebar-bg);
            border-right: none;
            box-shadow: 2px 0 8px rgba(0,0,0,0.12);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: all 0.3s ease;
            z-index: 1080 !important;
            overflow: hidden;
        }

        .sidebar-link {
            color: #94a3b8;
            padding: 11px 20px;
            display: flex;
            align-items: center;
            text-decoration: none;
            border-radius: 8px;
            margin: 2px 10px;
            font-weight: 500;
            font-size: 13.5px !important;
            transition: all 0.18s ease;
            border-left: 3px solid transparent;
        }
        .sidebar-link:hover {
            color: #ffffff;
            background: rgba(255,255,255,0.08);
            border-left: 3px solid var(--accent-gold);
        }
        .sidebar-link.active {
            color: #ffffff;
            background: rgba(59,130,246,0.18);
            border-left: 3px solid var(--accent-blue);
            font-weight: 600;
        }
        .sidebar-link i {
            width: 22px;
            text-align: center;
            margin-right: 10px;
        }

        /* ── Card ── */
        .glass-card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 20px;
            box-shadow: var(--shadow-card);
            transition: box-shadow 0.2s ease, border-color 0.2s ease;
            backdrop-filter: none;
        }
        .glass-card:hover {
            box-shadow: var(--shadow-hover);
            border-color: #cbd5e1;
        }

        /* ── Form controls ── */
        .form-control, .form-select {
            background-color: #f8fafc !important;
            border: 1px solid #e2e8f0 !important;
            color: var(--text-primary) !important;
            border-radius: 8px !important;
            box-shadow: none !important;
            transition: border-color 0.15s, box-shadow 0.15s;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--accent-blue) !important;
            box-shadow: 0 0 0 3px rgba(59,130,246,0.12) !important;
            background-color: #ffffff !important;
        }
        .form-control::placeholder {
            color: #94a3b8 !important;
        }

        /* ── Dropdown menus ── */
        .dropdown-menu {
            background: #ffffff !important;
            border: 1px solid #e2e8f0 !important;
            box-shadow: 0 8px 24px rgba(0,0,0,0.12) !important;
            border-radius: 10px !important;
        }
        .dropdown-item {
            color: var(--text-primary) !important;
        }
        .dropdown-item:hover {
            background: #f1f5f9 !important;
            color: var(--accent-blue) !important;
        }

        /* Country search dropdown */
        #countryDropdownList .dropdown-item,
        #countryDropdownList > div {
            color: var(--text-primary) !important;
            background: #ffffff !important;
        }
        #countryDropdownList .dropdown-item:hover {
            background: #f1f5f9 !important;
        }
        #countryDropdownList {
            background: #ffffff !important;
            border: 1px solid #e2e8f0 !important;
        }

        /* ── Tables ── */
        .table {
            color: var(--text-primary) !important;
        }
        .table thead th {
            background: #f1f5f9 !important;
            color: var(--text-secondary) !important;
            border-color: #e2e8f0 !important;
            font-weight: 600;
            font-size: 12.5px !important;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .table tbody td {
            border-color: #f1f5f9 !important;
        }
        .table-dark, .bg-dark {
            background-color: #1e293b !important;
        }

        /* ── Badges ── */
        .badge {
            font-weight: 600;
            letter-spacing: 0.02em;
        }
        .badge.bg-secondary {
            background-color: #e2e8f0 !important;
            color: #475569 !important;
        }

        /* ── Buttons ── */
        .btn-outline-secondary {
            border-color: #e2e8f0 !important;
            color: var(--text-secondary) !important;
        }
        .btn-outline-secondary:hover {
            background: #f1f5f9 !important;
            border-color: #cbd5e1 !important;
        }

        /* ── Choices.js Light Mode ── */
        .choices__inner {
            background-color: #f8fafc !important;
            border: 1px solid #e2e8f0 !important;
            border-radius: 8px !important;
            color: var(--text-primary) !important;
            min-height: 44px !important;
        }
        .choices__list--dropdown {
            background-color: #ffffff !important;
            border: 1px solid #e2e8f0 !important;
            color: var(--text-primary) !important;
            box-shadow: 0 8px 24px rgba(0,0,0,0.1) !important;
        }
        .choices__item--selectable.is-highlighted {
            background-color: #eff6ff !important;
            color: var(--accent-blue) !important;
        }
        .choices__item {
            color: var(--text-primary) !important;
        }

        /* ── Leaflet popups (light) ── */
        .leaflet-popup-content-wrapper {
            background: #ffffff !important;
            box-shadow: 0 4px 16px rgba(0,0,0,0.12) !important;
            border-radius: 10px !important;
            padding: 0 !important;
            border: 1px solid #e2e8f0 !important;
        }
        .leaflet-popup-tip {
            background: #ffffff !important;
        }

        /* ── Modals ── */
        .modal-content {
            background: #ffffff !important;
            border: 1px solid #e2e8f0 !important;
            border-radius: 14px !important;
            box-shadow: 0 16px 48px rgba(0,0,0,0.16) !important;
            color: var(--text-primary) !important;
        }
        .modal-header, .modal-footer {
            border-color: #f1f5f9 !important;
        }

        /* ── Word wrap ── */
        .news-item, .glass-card, table, td, th, p, h1, h2, h3, h4, h5, h6, .modal-body, .modal-content {
            word-break: break-word;
            overflow-wrap: anywhere;
        }
        .d-flex { max-width: 100%; }

        /* ── Custom Scrollbar ── */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--accent-blue); }

        /* ── Input clear button ── */
        input[type="text"]::-webkit-search-cancel-button,
        input[type="search"]::-webkit-search-cancel-button { -webkit-appearance: none; display: none; }
        input[type="text"]::-ms-clear,
        input[type="search"]::-ms-clear { display: none; }
        .clear-search-trigger { transition: all 0.2s ease; }
        .clear-search-trigger:hover {
            transform: translateY(-50%) rotate(45deg) scale(1.15) !important;
            color: var(--accent-blue) !important;
        }

        /* ── Global override: strip dark classes from form elements ── */
        .form-control.bg-dark,
        .form-select.bg-dark,
        input.bg-dark,
        textarea.bg-dark {
            background-color: #f8fafc !important;
            color: var(--text-primary) !important;
            border-color: #e2e8f0 !important;
        }
        .form-control.bg-dark:focus,
        .form-select.bg-dark:focus {
            background-color: #ffffff !important;
            border-color: var(--accent-blue) !important;
            box-shadow: 0 0 0 3px rgba(59,130,246,0.12) !important;
        }
        .input-group-text.bg-dark {
            background-color: #f1f5f9 !important;
            border-color: #e2e8f0 !important;
            color: var(--text-secondary) !important;
        }
        /* Override text-white inside light cards */
        .glass-card .text-white,
        .glass-card h1, .glass-card h2, .glass-card h3,
        .glass-card h4, .glass-card h5, .glass-card h6 {
            color: var(--text-primary) !important;
        }
        /* Override border-secondary on light bg */
        .glass-card .border-secondary,
        .form-control.border-secondary,
        .form-select.border-secondary {
            border-color: #e2e8f0 !important;
        }
        /* Modals stay light */
        .modal-body .bg-dark,
        .modal-content .bg-dark {
            background-color: #f8fafc !important;
            color: var(--text-primary) !important;
        }
        /* dropdown country search */
        .dropdown-menu.bg-dark,
        #countryDropdownList.bg-dark {
            background: #ffffff !important;
            border-color: #e2e8f0 !important;
        }
        /* Main content wrapper */
        .main-content {
            background-color: var(--bg-main);
        }
        .hover-gold:hover {
            border-color: var(--accent-gold) !important;
            box-shadow: 0 6px 20px rgba(217,119,6,0.15) !important;
        }

        /* ── Responsive ── */
        @media (max-width: 991.98px) {
            .sidebar {
                position: fixed;
                left: -260px;
                top: 0;
                bottom: 0;
                height: 100vh;
                min-height: unset;
                overflow-y: auto;
            }
            .sidebar.show { left: 0; }
        }

        /* ── Sidebar Backdrop ── */
        .sidebar-backdrop {
            position: fixed; top: 0; left: 0;
            width: 100vw; height: 100vh;
            background: rgba(15,23,42,0.5);
            backdrop-filter: blur(3px);
            z-index: 1070 !important;
            display: none; opacity: 0;
            transition: opacity 0.3s ease;
        }
        .sidebar-backdrop.show { display: block; opacity: 1; }
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
    </script>
</body>
</html>
