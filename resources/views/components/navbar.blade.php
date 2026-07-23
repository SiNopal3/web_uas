<header class="navbar navbar-expand border-bottom px-3 py-2 navbar-enterprise">
    <div class="d-flex align-items-center justify-content-between w-100 gap-2 flex-nowrap">
        <div class="d-flex align-items-center flex-grow-1" style="min-width: 0;">
            <!-- Mobile Sidebar Toggle -->
            <button id="sidebarToggleBtn" class="btn btn-sm btn-outline-secondary d-lg-none me-2 py-1 px-2 flex-shrink-0">
                <i class="fa-solid fa-bars"></i>
            </button>

            <div class="overflow-hidden text-truncate pe-1">
                <h5 class="fw-bold mb-0 text-truncate" style="font-size: 14.5px; color: #0f172a !important;">Global Supply Chain Intelligence Hub</h5>
                <div class="small text-truncate d-none d-md-block" style="color: #64748b; font-size: 11px;">Real-time Maritime & Economic Risk Monitoring Engine</div>
            </div>
        </div>

        <div class="d-flex align-items-center gap-2 flex-shrink-0 ms-auto">
            <!-- Real-Time Clock Badge -->
            <div class="d-flex align-items-center px-2.5 py-1" style="background: #f1f5f9; border-radius: 6px; border: 1px solid #e2e8f0; white-space: nowrap;">
                <i class="fa-regular fa-clock me-1.5 fs-6" style="color: #2563eb;"></i>
                <div id="globalNavbarClock" class="d-flex align-items-center fw-semibold" style="font-size: 12px; letter-spacing: 0.2px; color: #0f172a;">
                    <span style="color: #2563eb;">{{ now()->setTimezone('Asia/Jakarta')->format('d M Y') }}</span>
                    <span style="color: #94a3b8; margin: 0 4px;">•</span>
                    <span style="color: #0f172a;">{{ now()->setTimezone('Asia/Jakarta')->format('h:i:s A') }}</span>
                </div>
            </div>

            @auth
            <form action="{{ route('logout') }}" method="POST" class="m-0" onsubmit="Object.keys(localStorage).forEach(k => { if (k.startsWith('selected_country_')) localStorage.removeItem(k); }); sessionStorage.clear();">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-secondary px-2.5 py-1 d-flex align-items-center gap-1.5" title="Log out">
                    <span style="font-size: 11.5px;" class="d-none d-sm-inline fw-medium text-slate-700">{{ Auth::user()->name }}</span>
                    <i class="fa-solid fa-right-from-bracket" style="font-size: 12px; color: #ef4444;"></i>
                </button>
            </form>
            @endauth
        </div>
    </div>
</header>
