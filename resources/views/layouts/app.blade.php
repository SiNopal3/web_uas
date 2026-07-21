<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Global Supply Chain Risk Intelligence Platform') - RiskIntel Hub</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}?v={{ time() }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}?v={{ time() }}" type="image/x-icon">
    <link rel="apple-touch-icon" href="{{ asset('favicon.ico') }}?v={{ time() }}">
    
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
            --bg-dark: #0f1218;
            --sidebar-bg: #151921;
            --card-bg: rgba(28, 34, 46, 0.75);
            --border-color: rgba(255, 255, 255, 0.14);
            --accent-gold: #e0b472;
            --text-light: #f8fafc;
            --text-muted: #cbd5e1;
        }

        body {
            background-color: var(--bg-dark);
            font-family: 'Inter', sans-serif;
            color: var(--text-light);
            margin: 0;
            overflow-x: hidden;
            font-size: 13.5px !important;
        }

        /* Override Bootstrap 5 & Warna Teks agar Super Cerah dan Jernih (WCAG AAA) */
        .text-muted, .text-secondary, small, .small {
            color: #cbd5e1 !important;
            font-weight: 500 !important;
            font-size: 12.5px !important;
        }
        label, .form-label, span.text-muted, div.text-muted, p.text-muted {
            color: #e2e8f0 !important;
            font-weight: 600 !important;
        }
        h1, h2, h3, h4, h5, h6 {
            color: #ffffff !important;
            text-shadow: 0 1px 2px rgba(0,0,0,0.5);
        }

        .table td, .table th, .form-control, .dropdown-item, .btn {
            font-size: 13.5px !important;
        }

        /* Sidebar Styling */
        .sidebar {
            width: 260px;
            background-color: var(--sidebar-bg);
            border-right: 1px solid var(--border-color);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: all 0.3s ease;
            z-index: 1080 !important;
        }

        .sidebar-link {
            color: var(--text-muted);
            padding: 12px 20px;
            display: flex;
            align-items: center;
            text-decoration: none;
            border-radius: 10px;
            margin: 4px 15px;
            font-weight: 500;
            font-size: 13.5px !important;
            transition: all 0.2s;
            border-left: 4px solid transparent;
        }

        .sidebar-link:hover, .sidebar-link.active {
            color: #ffffff;
            background: rgba(200, 156, 98, 0.15);
            border-left: 4px solid var(--accent-gold);
        }

        .sidebar-link i {
            width: 24px;
            text-align: center;
            margin-right: 10px;
        }

        /* Card & Panel Styling */
        .glass-card {
            background: var(--card-bg);
            backdrop-filter: blur(12px);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
            transition: border-color 0.3s, transform 0.2s;
        }

        .glass-card:hover {
            border-color: rgba(200, 156, 98, 0.3);
        }

        /* Choices.js Custom Overrides for Dark Mode */
        .choices__inner {
            background-color: rgba(0,0,0,0.3) !important;
            border: 1px solid var(--border-color) !important;
            border-radius: 10px !important;
            color: #ffffff !important;
            min-height: 44px !important;
        }
        .choices__list--dropdown {
            background-color: #1b1e25 !important;
            border: 1px solid var(--border-color) !important;
            color: #ffffff !important;
        }
        .choices__item--selectable.is-highlighted {
            background-color: var(--accent-gold) !important;
            color: #ffffff !important;
        }

        /* Leaflet Dark Custom Popups */
        .leaflet-popup-content-wrapper {
            background: transparent !important;
            box-shadow: none !important;
            padding: 0 !important;
        }
        .leaflet-popup-tip {
            background: #1b1c20 !important;
        }

        /* Auto word-wrap untuk mencegah teks/URL panjang meluber ke samping */
        .news-item, .glass-card, table, td, th, p, h1, h2, h3, h4, h5, h6, .modal-body, .modal-content {
            word-break: break-word;
            overflow-wrap: anywhere;
        }
        .d-flex {
            max-width: 100%;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: var(--bg-dark);
        }
        ::-webkit-scrollbar-thumb {
            background: #2d3748;
            border-radius: 3px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: var(--accent-gold);
        }

        /* Sembunyikan tombol clear bawaan browser [X] agar tidak bentrok dengan tombol putar/refresh custom kita */
        input[type="text"]::-webkit-search-cancel-button,
        input[type="search"]::-webkit-search-cancel-button {
            -webkit-appearance: none;
            display: none;
        }
        input[type="text"]::-ms-clear,
        input[type="search"]::-ms-clear {
            display: none;
        }
        .clear-search-trigger {
            transition: all 0.2s ease;
        }
        .clear-search-trigger:hover {
            transform: translateY(-50%) rotate(45deg) scale(1.15) !important;
            color: var(--accent-gold) !important;
        }

        /* Responsive Behavior */
        @media (max-width: 991.98px) {
            .sidebar {
                position: fixed;
                left: -260px;
                top: 0;
                bottom: 0;
            }
            .sidebar.show {
                left: 0;
            }
        }

        /* Sidebar Backdrop for Offcanvas (< 992px) */
        .sidebar-backdrop {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(0, 0, 0, 0.65);
            backdrop-filter: blur(3px);
            z-index: 1070 !important;
            display: none;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        .sidebar-backdrop.show {
            display: block;
            opacity: 1;
        }
    </style>
</head>
<body>
    <div id="sidebarBackdrop" class="sidebar-backdrop"></div>
    <div class="d-flex min-vh-100" style="width: 100%; overflow-x: hidden;">
        <!-- Sidebar Navigation Component -->
        @include('components.sidebar')

        <!-- Main Content Area -->
        <div class="main-content flex-grow-1 d-flex flex-column" style="min-width: 0; max-width: 100%;">
            @include('components.navbar')

            <main class="flex-grow-1 p-3 p-md-4">
                @yield('content')
            </main>

            <footer class="py-3 px-4 border-top border-secondary text-center text-md-start d-flex flex-column flex-md-row justify-content-between align-items-center small text-muted" style="border-color: rgba(255,255,255,0.05) !important;">
                <div>&copy; {{ date('Y') }} RiskIntel Hub. All Rights Reserved. Enterprise Grade Supply Chain Platform.</div>
                <div class="mt-2 mt-md-0">
                    <span class="badge bg-success me-2"><i class="fa-solid fa-check-circle me-1"></i> SSL 256-Bit</span>
                    <span class="badge bg-dark border border-secondary">NGA Satellite Sync</span>
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
                elGlobalClock.innerHTML = `<span class="text-info">${day} ${month} ${year}</span><span class="text-secondary mx-1.5">•</span><span class="text-white">${hoursStr}:${minutes}:${seconds} ${ampm}</span>`;
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
