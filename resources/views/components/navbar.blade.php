<header class="navbar navbar-expand border-bottom px-2 px-md-3 py-2" style="background: rgba(24, 27, 33, 0.9); backdrop-filter: blur(10px); border-color: rgba(255,255,255,0.08) !important; position: sticky; top: 0; z-index: 1030;">
    <div class="d-flex align-items-center justify-content-between w-100 gap-2 flex-nowrap">
        <div class="d-flex align-items-center flex-grow-1" style="min-width: 0; max-width: calc(100% - 290px);">
            <!-- Mobile Sidebar Toggle -->
            <button id="sidebarToggleBtn" class="btn btn-outline-secondary d-lg-none me-2 py-1 px-2 text-white border-secondary flex-shrink-0">
                <i class="fa-solid fa-bars"></i>
            </button>
            
            <div class="overflow-hidden text-truncate pe-1">
                <h5 class="fw-bold text-white mb-0 text-truncate" style="font-size: clamp(13px, 1.2vw, 17px);">Global Supply Chain Intelligence Hub</h5>
                <div class="small fw-semibold text-truncate d-none d-md-block" style="color: #cbd5e1; font-size: 11px;">Real-time Maritime & Economic Risk Monitoring Engine</div>
            </div>
        </div>

        <div class="d-flex align-items-center gap-1.5 gap-sm-2 flex-shrink-0 ms-auto">
            <!-- Global Real-Time Clock (Horizontal / Panjang Satu Baris) -->
            <div class="badge bg-dark border border-secondary px-2 px-md-3 py-1.5 text-white shadow-sm d-flex align-items-center" style="border-radius: 8px; white-space: nowrap;">
                <i class="fa-regular fa-clock me-2 text-info fs-6"></i>
                <div id="globalNavbarClock" class="d-flex align-items-center fw-bold" style="font-size: 12px; letter-spacing: 0.3px;">
                    <span class="text-info">{{ now()->setTimezone('Asia/Jakarta')->format('d M Y') }}</span>
                    <span class="text-secondary mx-1.5">•</span>
                    <span class="text-white">{{ now()->setTimezone('Asia/Jakarta')->format('h:i:s A') }}</span>
                </div>
            </div>

            @auth
            <!-- Logout Button Next to Clock showing Logged-in Email -->
            <form action="{{ route('logout') }}" method="POST" class="m-0" onsubmit="Object.keys(localStorage).forEach(k => { if (k.startsWith('antigravity_selected_') || k.startsWith('selected_country_')) localStorage.removeItem(k); }); sessionStorage.clear();">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-danger px-2 py-1 d-flex align-items-center gap-1.5 fw-bold shadow-sm" title="Log out ({{ Auth::user()->email }})" style="border-radius: 8px;">
                    <div class="d-flex flex-column text-start" style="line-height: 1.2;">
                        <span class="small text-light" style="font-size: 9.5px; opacity: 0.85;">Log out:</span>
                        <span class="fw-bold text-white text-truncate" style="font-size: 11.5px; max-width: 115px;">{{ Auth::user()->email }}</span>
                    </div>
                    <i class="fa-solid fa-right-from-bracket text-danger" style="font-size: 13px;"></i>
                </button>
            </form>
            @endauth
        </div>
    </div>
</header>
