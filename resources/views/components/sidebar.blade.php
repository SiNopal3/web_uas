<aside id="sidebarMain" class="sidebar shadow-lg">
    <div class="p-4 d-flex justify-content-between align-items-center border-bottom" style="border-color: rgba(255,255,255,0.08) !important;">
        <div class="d-flex align-items-center">
            <div class="d-flex align-items-center justify-content-center me-2" style="width:36px;height:36px;background:rgba(217,119,6,0.15);border-radius:10px;">
                <i class="fa-solid fa-shield-halved" style="color: #d97706; font-size:18px;"></i>
            </div>
            <div>
                <h5 class="fw-bold mb-0" style="color:#ffffff !important; letter-spacing: 0.5px; font-size:16px;">RiskIntel</h5>
                <span class="badge" style="font-size: 9px; background:rgba(59,130,246,0.2); color:#93c5fd; border:1px solid rgba(59,130,246,0.3);">PROD v3.0</span>
            </div>
        </div>
        <button id="sidebarCloseBtn" class="btn btn-sm btn-link d-lg-none p-0" style="color:#94a3b8;">
            <i class="fa-solid fa-xmark fa-lg"></i>
        </button>
    </div>

    <div class="py-3 flex-grow-1" style="overflow-y: auto; overflow-x: hidden;">
        <a href="{{ url('/') }}" class="sidebar-link {{ request()->is('/') ? 'active' : '' }}">
            Global Country Dashboard
        </a>
        <a href="{{ url('/ports') }}" class="sidebar-link {{ request()->is('ports*') ? 'active' : '' }}">
            Maritime Weather & Port Hub
        </a>
        <a href="{{ url('/maritime-route') }}" class="sidebar-link {{ request()->is('maritime-route') ? 'active' : '' }}">
            Route & Delay Simulation
        </a>
        <a href="{{ url('/analytics') }}" class="sidebar-link {{ request()->is('analytics') && !request()->has('view') ? 'active' : '' }}">
            Currency Impact Dashboard
        </a>
        <a href="{{ url('/news-sentiment') }}" class="sidebar-link {{ request()->is('news-sentiment') ? 'active' : '' }}">
            News Intelligence
        </a>
        <a href="{{ url('/data-visualization') }}" class="sidebar-link {{ request()->is('data-visualization') ? 'active' : '' }}">
            Data Visualization Dashboard
        </a>
        <a href="{{ url('/decision-support') }}" class="sidebar-link {{ request()->is('decision-support') ? 'active' : '' }}">
            Country Comparison Engine
        </a>
        <a href="{{ url('/watchlist') }}" class="sidebar-link {{ request()->is('watchlist') ? 'active' : '' }}">
            Favorite Monitoring List
        </a>
        @if(auth()->check() && auth()->user()->isAdmin())
        <a href="{{ url('/admin') }}" class="sidebar-link {{ request()->is('admin*') ? 'active' : '' }}" style="color: #fbbf24;">
            <i class="fa-solid fa-user-shield me-2"></i> Admin Dashboard
        </a>
        @endif


    </div>

    <div class="p-3 border-top mt-auto" style="border-color: rgba(255,255,255,0.08) !important;">
        @auth
            <div class="d-flex align-items-center p-2 rounded" style="background: rgba(255,255,255,0.06);">
                <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold me-2 flex-shrink-0" style="width:32px;height:32px;background:#d97706;color:#ffffff;font-size:13px;">
                    {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                </div>
                <div class="overflow-hidden">
                    <div class="fw-semibold text-truncate" style="color:#f1f5f9;font-size:13px;">{{ Auth::user()->name }}</div>
                    <div class="text-truncate" style="font-size:11px;color:#94a3b8;">{{ Auth::user()->email }}</div>
                </div>
            </div>
        @else
            <a href="{{ route('login') }}" class="btn w-100 small fw-semibold" style="background:rgba(59,130,246,0.15);color:#93c5fd;border:1px solid rgba(59,130,246,0.3);border-radius:8px;">
                <i class="fa-solid fa-right-to-bracket me-1"></i> Sign In
            </a>
        @endauth
    </div>
</aside>
