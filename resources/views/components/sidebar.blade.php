<aside id="sidebarMain" class="sidebar shadow-lg">
    <div class="p-4 d-flex justify-content-between align-items-center border-bottom" style="border-color: rgba(255,255,255,0.08) !important;">
        <div class="d-flex align-items-center">
            <i class="fa-solid fa-shield-halved fa-2x me-2" style="color: var(--accent-gold);"></i>
            <div>
                <h5 class="fw-bold mb-0 text-white" style="letter-spacing: 0.5px;">RiskIntel</h5>
                <span class="badge bg-dark border border-secondary text-muted" style="font-size: 10px;">PROD v3.0</span>
            </div>
        </div>
        <button id="sidebarCloseBtn" class="btn btn-sm btn-link text-white d-lg-none p-0">
            <i class="fa-solid fa-xmark fa-lg"></i>
        </button>
    </div>

    <div class="py-3 flex-grow-1 overflow-auto">
        <a href="{{ url('/') }}" class="sidebar-link {{ request()->is('/') ? 'active' : '' }}">
            Global Country Dashboard
        </a>
        <a href="{{ url('/ports') }}" class="sidebar-link {{ request()->is('ports*') ? 'active' : '' }}">
            Maritime Weather & Port Hub
        </a>
        <a href="{{ url('/maritime-route') }}" class="sidebar-link {{ request()->is('maritime-route') ? 'active' : '' }}">
            Simulasi Rute & Keterlambatan
        </a>
        <a href="{{ url('/analytics') }}" class="sidebar-link {{ request()->is('analytics') && !request()->has('view') ? 'active' : '' }}">
            Currency Impact Dashboard
        </a>
        <a href="{{ url('/news-sentiment') }}" class="sidebar-link {{ request()->is('news-sentiment') ? 'active' : '' }}">
            News Intelligence
        </a>
        <a href="{{ url('/analytics?view=charts') }}" class="sidebar-link {{ request()->is('analytics') && request()->get('view') == 'charts' ? 'active' : '' }}">
            Data Visualization Dashboard
        </a>
        <a href="{{ url('/decision-support') }}" class="sidebar-link {{ request()->is('decision-support') ? 'active' : '' }}">
            Country Comparison Engine
        </a>
        <a href="{{ url('/watchlist') }}" class="sidebar-link {{ request()->is('watchlist') ? 'active' : '' }}">
            Favorite Monitoring List
        </a>
        @if(auth()->check() && auth()->user()->isAdmin())
        <a href="{{ url('/admin') }}" class="sidebar-link {{ request()->is('admin*') ? 'active' : '' }}" style="color: var(--accent-gold);">
            <i class="fa-solid fa-user-shield me-2"></i> Admin Dashboard
        </a>
        @endif


    </div>

    <div class="p-3 border-top mt-auto" style="border-color: rgba(255,255,255,0.08) !important;">
        @auth
            <div class="d-flex align-items-center p-2 rounded" style="background: rgba(0,0,0,0.3);">
                <div class="bg-warning rounded-circle d-flex align-items-center justify-content-center text-dark fw-bold me-2" style="width: 32px; height: 32px; flex-shrink: 0;">
                    {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                </div>
                <div class="overflow-hidden">
                    <div class="text-white small fw-semibold text-truncate">{{ Auth::user()->name }}</div>
                    <div class="text-muted text-truncate" style="font-size: 11px;">{{ Auth::user()->email }}</div>
                </div>
            </div>
        @else
            <a href="{{ route('login') }}" class="btn btn-outline-warning w-100 small">
                <i class="fa-solid fa-right-to-bracket me-1"></i> Masuk Sistem
            </a>
        @endauth
    </div>
</aside>
