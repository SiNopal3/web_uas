<header class="navbar navbar-expand border-bottom px-2 px-md-3 py-2" style="background: #ffffff; border-color: #e2e8f0 !important; position: sticky; top: 0; z-index: 1030; box-shadow: 0 1px 3px rgba(0,0,0,0.06);">
    <div class="d-flex align-items-center justify-content-between w-100 gap-2 flex-nowrap">
        <div class="d-flex align-items-center flex-grow-1" style="min-width: 0; max-width: calc(100% - 290px);">
            <!-- Mobile Sidebar Toggle -->
            <button id="sidebarToggleBtn" class="btn btn-outline-secondary d-lg-none me-2 py-1 px-2 flex-shrink-0" style="border-color: #e2e8f0; color: #475569;">
                <i class="fa-solid fa-bars"></i>
            </button>

            <div class="overflow-hidden text-truncate pe-1">
                <h5 class="fw-bold mb-0 text-truncate" style="font-size: clamp(13px, 1.2vw, 16px); color: #0f172a !important;">Global Supply Chain Intelligence Hub</h5>
                <div class="small fw-semibold text-truncate d-none d-md-block" style="color: #64748b; font-size: 11px;">Real-time Maritime & Economic Risk Monitoring Engine</div>
            </div>
        </div>

        <div class="d-flex align-items-center gap-1.5 gap-sm-2 flex-shrink-0 ms-auto">
            <!-- Clock -->
            <div class="d-flex align-items-center px-3 py-1" style="background: #f1f5f9; border-radius: 8px; border: 1px solid #e2e8f0; white-space: nowrap;">
                <i class="fa-regular fa-clock me-2 fs-6" style="color: #3b82f6;"></i>
                <div id="globalNavbarClock" class="d-flex align-items-center fw-semibold" style="font-size: 12.5px; letter-spacing: 0.3px; color: #0f172a;">
                    <span style="color: #3b82f6;">{{ now()->setTimezone('Asia/Jakarta')->format('d M Y') }}</span>
                    <span style="color: #94a3b8; margin: 0 5px;">•</span>
                    <span style="color: #0f172a;">{{ now()->setTimezone('Asia/Jakarta')->format('h:i:s A') }}</span>
                </div>
            </div>

            @auth
            <form action="{{ route('logout') }}" method="POST" class="m-0" onsubmit="Object.keys(localStorage).forEach(k => { if (k.startsWith('selected_country_')) localStorage.removeItem(k); }); sessionStorage.clear();">
                @csrf
                <button type="submit" class="btn btn-sm px-3 py-1 d-flex align-items-center gap-2 fw-semibold" title="Log out" style="border-radius: 8px; background: #fef2f2; border: 1px solid #fecaca; color: #dc2626;">
                    <div class="d-flex flex-column text-start" style="line-height: 1.2;">
                        <span style="font-size: 9.5px; opacity: 0.75; color: #dc2626;">Log out:</span>
                        <span class="fw-bold text-truncate" style="font-size: 11.5px; max-width: 115px; color: #0f172a;">{{ Auth::user()->email }}</span>
                    </div>
                    <i class="fa-solid fa-right-from-bracket" style="font-size: 13px; color: #dc2626;"></i>
                </button>
            </form>
            @endauth
        </div>
    </div>
</header>
