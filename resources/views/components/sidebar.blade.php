<aside id="sidebarMain" class="sidebar shadow-sm">
    <!-- Sidebar Header / Brand -->
    <div class="px-4 py-3 d-flex justify-content-between align-items-center border-bottom" style="border-color: rgba(255,255,255,0.08) !important;">
        <div class="d-flex align-items-center">
            <div class="d-flex align-items-center justify-content-center me-2.5 rounded-2" style="width:34px;height:34px;background:#2563eb;">
                <i class="fa-solid fa-shield-halved text-white" style="font-size:16px;"></i>
            </div>
            <div>
                <h5 class="fw-bold mb-0 text-white" style="letter-spacing: -0.3px; font-size:15px;">RiskIntel <span style="font-weight:400; opacity:0.7;">Hub</span></h5>
                <span class="badge px-1.5 py-0.5" style="font-size: 9px; background:rgba(37,99,235,0.25); color:#93c5fd; border:1px solid rgba(59,130,246,0.3);">ENTERPRISE v3.0</span>
            </div>
        </div>
        <button id="sidebarCloseBtn" class="btn btn-sm btn-link d-lg-none p-0 text-muted">
            <i class="fa-solid fa-xmark fa-lg"></i>
        </button>
    </div>

    <!-- Sidebar Navigation Links -->
    <div class="py-2 flex-grow-1" style="overflow-y: auto; overflow-x: hidden;">
        <div class="sidebar-section-title">Monitoring Core</div>
        <a href="{{ url('/') }}" class="sidebar-link {{ request()->is('/') ? 'active' : '' }}">
            <i class="fa-solid fa-globe"></i> Global Dashboard
        </a>
        <a href="{{ url('/ports') }}" class="sidebar-link {{ request()->is('ports*') ? 'active' : '' }}">
            <i class="fa-solid fa-ship"></i> Maritime & Ports
        </a>
        <a href="{{ url('/maritime-route') }}" class="sidebar-link {{ request()->is('maritime-route') ? 'active' : '' }}">
            <i class="fa-solid fa-route"></i> Route Simulator
        </a>

        <div class="sidebar-section-title">Analytics & Intelligence</div>
        <a href="{{ url('/analytics') }}" class="sidebar-link {{ request()->is('analytics') && !request()->has('view') ? 'active' : '' }}">
            <i class="fa-solid fa-chart-line"></i> Currency Impact
        </a>
        <a href="{{ url('/news-sentiment') }}" class="sidebar-link {{ request()->is('news-sentiment') ? 'active' : '' }}">
            <i class="fa-solid fa-newspaper"></i> News Intelligence
        </a>
        <a href="{{ url('/data-visualization') }}" class="sidebar-link {{ request()->is('data-visualization') ? 'active' : '' }}">
            <i class="fa-solid fa-chart-column"></i> Data Visualization
        </a>
        <a href="{{ url('/decision-support') }}" class="sidebar-link {{ request()->is('decision-support') ? 'active' : '' }}">
            <i class="fa-solid fa-scale-balanced"></i> Country Comparison
        </a>
        <a href="{{ url('/watchlist') }}" class="sidebar-link {{ request()->is('watchlist') ? 'active' : '' }}">
            <i class="fa-solid fa-bookmark"></i> Favorite Watchlist
        </a>

        @if(auth()->check() && auth()->user()->isAdmin())
        <div class="sidebar-section-title">Administration</div>
        <a href="{{ url('/admin') }}" class="sidebar-link {{ request()->is('admin*') ? 'active' : '' }}" style="color: #60a5fa;">
            <i class="fa-solid fa-user-shield me-2" style="color:#60a5fa;"></i> Admin Console
        </a>
        @endif
    </div>

    <!-- Sidebar User Footer -->
    <div class="p-3 border-top mt-auto" style="border-color: rgba(255,255,255,0.08) !important;">
        @auth
            <div class="d-flex align-items-center p-2 rounded-2" style="background: rgba(255,255,255,0.04);">
                <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold me-2 flex-shrink-0" style="width:32px;height:32px;background:#2563eb;color:#ffffff;font-size:12px;">
                    {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                </div>
                <div class="overflow-hidden me-auto">
                    <div class="fw-semibold text-truncate text-white" style="font-size:12.5px;">{{ Auth::user()->name }}</div>
                    <div class="text-truncate" style="font-size:11px;color:#94a3b8;">{{ Auth::user()->email }}</div>
                </div>
            </div>
        @else
            <a href="{{ route('login') }}" class="btn btn-primary w-100 btn-sm">
                <i class="fa-solid fa-right-to-bracket me-1"></i> Sign In
            </a>
        @endauth
    </div>
</aside>
