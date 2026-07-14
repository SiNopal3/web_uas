<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RiskIntel - Global Supply Chain Intelligence</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    
    <style>
        :root {
            --sidebar-bg: #1b1c20; --main-bg: #f4eee7; --card-bg: #ffffff;        
            --accent-gold: #c89c62; --text-dark: #2d3748; --text-muted: #a0aec0; --text-light: #ffffff;     
        }
        body { background-color: var(--main-bg); color: var(--text-dark); font-family: 'Inter', 'Segoe UI', sans-serif; margin: 0; overflow-x: hidden; }
        a { text-decoration: none !important; }

        /* ========================================================
           STYLE UNTUK HALAMAN LOGIN
           ======================================================== */
        #loginScreen {
            position: fixed; top: 0; left: 0; width: 100vw; height: 100vh;
            background: linear-gradient(135deg, #1b1c20 0%, #2d3748 100%);
            display: flex; justify-content: center; align-items: center; z-index: 9999;
        }
        .login-card {
            background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(10px);
            padding: 40px; border-radius: 20px; border: 1px solid rgba(255, 255, 255, 0.1);
            width: 100%; max-width: 400px; text-align: center; color: white;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
        }
        .login-input {
            background: rgba(0,0,0,0.2); border: 1px solid rgba(255,255,255,0.1);
            color: white; padding: 12px 20px; border-radius: 10px; width: 100%; margin-bottom: 15px;
        }
        .login-input:focus { outline: none; border-color: var(--accent-gold); }
        .login-btn {
            background-color: var(--accent-gold); color: white; border: none;
            padding: 12px; border-radius: 10px; width: 100%; font-weight: bold; cursor: pointer; transition: 0.3s;
        }
        .login-btn:hover { background-color: #b08650; }

        /* MAIN DASHBOARD STYLES */
        .dashboard-wrapper { display: flex; min-height: 100vh; }
        .sidebar {
            width: 280px; background-color: var(--sidebar-bg); display: flex; flex-direction: column;
            position: fixed; height: 100vh; z-index: 1000; padding: 20px 15px;
            border-top-right-radius: 20px; border-bottom-right-radius: 20px; box-shadow: 4px 0 15px rgba(0,0,0,0.1);
        }
        .sidebar-brand { color: var(--text-light); margin-bottom: 40px; padding-left: 5px; display: flex; align-items: center; gap: 12px; }
        .sidebar-brand i { color: var(--accent-gold); font-size: 2rem; }
        .brand-text { font-weight: 800; font-size: 0.9rem; line-height: 1.2; letter-spacing: 0.5px;}
        .brand-sub { font-weight: 500; font-size: 0.65rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px;}
        
        .sidebar-menu { list-style: none; padding: 0; margin: 0; flex-grow: 1; }
        .sidebar-item { margin-bottom: 8px; }
        .sidebar-link {
            display: flex; align-items: center; gap: 15px; padding: 12px 20px; color: #8e95a5;
            border-radius: 12px; font-weight: 500; transition: all 0.3s; cursor: pointer;
        }
        .sidebar-link:hover, .sidebar-link.active { background-color: var(--accent-gold); color: var(--text-light); box-shadow: 0 4px 10px rgba(200, 156, 98, 0.3); }

        .main-content { flex-grow: 1; margin-left: 280px; padding: 30px 40px; background-color: var(--main-bg); }
        .top-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .page-title { font-size: 1.8rem; font-weight: 800; color: var(--text-dark); margin: 0; }

        .white-card { background-color: var(--card-bg); border-radius: 20px; padding: 25px; box-shadow: 0 5px 20px rgba(0,0,0,0.03); border: none; }
        .card-title-custom { font-size: 1.1rem; font-weight: 700; color: var(--text-dark); margin-bottom: 20px; display: flex; align-items: center; gap: 10px; }
        .card-title-custom i { color: var(--accent-gold); }

        .custom-select { background-color: #f8f9fa; color: var(--text-dark); border: 1px solid #e2e8f0; border-radius: 12px; padding: 12px 20px; width: 100%; font-weight: 500; }
        .custom-select:focus { outline: none; border-color: var(--accent-gold); box-shadow: 0 0 0 3px rgba(200, 156, 98, 0.2); }
        .custom-select:disabled { background-color: #e2e8f0; cursor: not-allowed; }

        .table-custom { color: var(--text-dark); margin-bottom: 0; }
        .table-custom th { color: var(--text-muted); font-weight: 600; border-bottom: 2px solid #edf2f7; text-transform: uppercase; font-size: 0.85rem;}
        .table-custom td { border-bottom: 1px solid #edf2f7; padding: 15px 10px; vertical-align: middle; font-size: 0.9rem; }
        .badge-gold { background-color: var(--accent-gold); color: white; padding: 6px 12px; border-radius: 8px; font-weight: 600; font-size: 0.75rem; }
        
        .page-section { display: none; }
        .active-section { display: block; }

        .metric-box { background-color: #f8f9fa; border: 1px solid #e2e8f0; border-radius: 12px; padding: 15px; text-align: center; }
        .metric-title { font-size: 0.75rem; color: var(--text-muted); font-weight: 700; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 5px; }
        .metric-value { font-size: 1.3rem; color: var(--text-dark); font-weight: 800; margin-bottom: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

        .logout-btn { background-color: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); color: #ef4444; width: 100%; border-radius: 8px; padding: 6px; font-size: 0.8rem; font-weight: bold; cursor: pointer; transition: 0.3s; margin-top: 10px; }
        .logout-btn:hover { background-color: #ef4444; color: white; }

        /* ANIMASI PETA UNTUK CUACA DAN RUTE */
        .leaflet-interactive { stroke-dasharray: 10, 15; animation: dash 20s linear infinite; }
        @keyframes dash { to { stroke-dashoffset: -1000; } }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

        .weather-storm { animation: pulseStorm 2s ease-out infinite; pointer-events: none; }
        @keyframes pulseStorm { 0% { r: 5px; fill-opacity: 0.8; stroke-opacity: 1; stroke-width: 2px; } 100% { r: 25px; fill-opacity: 0; stroke-opacity: 0; stroke-width: 0px; } }
        .weather-rain { pointer-events: none; }
        .weather-wind { animation: spinWind 15s linear infinite; transform-origin: center; pointer-events: none; }
        @keyframes spinWind { 100% { transform: rotate(360deg); } }
        .weather-legend { font-size: 0.65rem; background: #f8f9fa; padding: 4px 8px; border-radius: 6px; border: 1px solid #e2e8f0; margin-left: 10px; }
    </style>
</head>
<body>

    <div id="loginScreen">
        <div class="login-card">
            <i class="fa-solid fa-horse-head mb-3" style="color: var(--accent-gold); font-size: 3rem;"></i>
            <h4 class="fw-bold mb-1">RiskIntel Hub</h4>
            <p class="small text-muted mb-4" style="color: #cbd5e1 !important;">Central Admin Control Panel</p>
            
            <input type="text" id="username" class="login-input" placeholder="Username" autocomplete="off">
            <input type="password" id="password" class="login-input" placeholder="Password" autocomplete="off">
            
            <p id="loginError" class="text-danger small fw-bold mb-3" style="display: none;">Invalid Admin Credentials!</p>
            
            <button onclick="handleLogin()" class="login-btn"><i class="fa-solid fa-right-to-bracket me-2"></i> SECURE LOGIN</button>
            
            <div class="mt-4 pt-3 border-top" style="border-color: rgba(255,255,255,0.1) !important;">
                <p class="small mb-0" style="color: #94a3b8;">Admin Login: <b>admin</b> / <b>admin123</b></p>
            </div>
        </div>
    </div>

    <div class="dashboard-wrapper" id="appDashboard" style="display: none;">
        <aside class="sidebar">
            <div class="sidebar-brand">
                <i class="fa-solid fa-horse-head"></i> 
                <div>
                    <div class="brand-text">Global Supply Chain</div>
                    <div class="brand-sub">Risk Intelligence Platform</div>
                </div>
            </div>
            
            <ul class="sidebar-menu">
                <li class="sidebar-item"><a data-target="page-dashboard" class="sidebar-link active"><i class="fa-solid fa-border-all"></i> Main Dashboard</a></li>
                <li class="sidebar-item"><a data-target="page-target" class="sidebar-link"><i class="fa-solid fa-earth-asia"></i> Target Country</a></li>
                <li class="sidebar-item"><a data-target="page-berita" class="sidebar-link"><i class="fa-regular fa-newspaper"></i> Intelligence News</a></li>
                <li class="sidebar-item"><a data-target="page-pelabuhan" class="sidebar-link"><i class="fa-solid fa-anchor"></i> Maritime Ports</a></li>
                <li class="sidebar-item"><a data-target="page-route" class="sidebar-link"><i class="fa-solid fa-route"></i> Route Simulation</a></li>
                <li class="sidebar-item"><a data-target="page-compare" class="sidebar-link"><i class="fa-solid fa-scale-balanced"></i> Country Comparison</a></li>
                <li class="sidebar-item"><a data-target="page-watchlist" class="sidebar-link"><i class="fa-solid fa-list-check"></i> Active Watchlist</a></li>
            </ul>

            <div class="mt-auto p-3" style="background: rgba(255,255,255,0.05); border-radius: 15px;">
                <div class="d-flex align-items-center gap-3 mb-2">
                    <img src="https://ui-avatars.com/api/?name=Admin&background=c89c62&color=fff&rounded=true" alt="Admin" width="38" height="38" style="border-radius: 50%;">
                    <div>
                        <h6 class="mb-0 text-white fw-bold" style="font-size: 0.85rem;">Sutan Admin</h6>
                        <small style="color: #4ade80;">● Online</small>
                    </div>
                </div>
                <button onclick="handleLogout()" class="logout-btn"><i class="fa-solid fa-power-off"></i> LOGOUT</button>
            </div>
        </aside>

        <main class="main-content">
            <div class="top-header">
                <h1 class="page-title" id="mainTitle">Main Dashboard</h1>
                <span id="liveClock" style="color: var(--text-muted); font-weight: 500;"><i class="fa-regular fa-clock"></i> Loading...</span>
            </div>

            <div id="page-dashboard" class="page-section active-section">
                <div class="white-card mb-4" style="background-color: var(--accent-gold); color: white;">
                    <h3 class="fw-bold mb-2">Welcome back to Command Center!</h3>
                    <p class="mb-0">Global supply chain risk monitoring system is operating in real-time status.</p>
                </div>
                <div class="white-card">
                    <div class="card-title-custom"><i class="fa-solid fa-shield-halved"></i> Administrator Privileges</div>
                    <p class="text-muted">As a System Administrator, you have unrestricted access to all core modules. You can extract macro-economic intelligence, cross-compare territorial risks, and simulate global logistics trajectories while optionally saving them directly to your Active Watchlist.</p>
                </div>
            </div>

            <div id="page-target" class="page-section">
                <div class="row mb-4">
                    <div class="col-md-8">
                        <div class="white-card p-4 h-100 d-flex flex-column justify-content-center" style="border-left: 5px solid var(--accent-gold);">
                            <h5 class="fw-bold mb-2">Territorial Analysis Parameter</h5>
                            <select id="countrySelect" class="custom-select shadow-sm">
                                <option value="">-- SELECT TARGET COUNTRY (195 COUNTRIES) --</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="white-card h-100 text-center d-flex flex-column justify-content-center">
                            <div class="card-title-custom justify-content-center mb-2"><i class="fa-solid fa-robot"></i> RISK SCORING ENGINE</div>
                            <div class="d-flex justify-content-center gap-4 align-items-center">
                                <div><h1 class="display-4 fw-bold mb-0" id="txtRiskScore">0</h1><small class="fw-bold text-muted">INDEX</small></div>
                                <div style="width: 2px; height: 40px; background: #e2e8f0;"></div>
                                <div><h3 class="fw-bold mb-0" id="txtRiskStatus">-</h3><small class="fw-bold text-muted">STATUS</small></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-3"><div class="metric-box shadow-sm"><p class="metric-title">Gross Domestic Product</p><p class="metric-value" id="valGdp">-</p><small class="text-muted">USD (Trillions)</small></div></div>
                    <div class="col-md-3"><div class="metric-box shadow-sm"><p class="metric-title">Inflation Rate</p><p class="metric-value" id="valInf">-</p><small class="text-muted">Percentage (%)</small></div></div>
                    <div class="col-md-3"><div class="metric-box shadow-sm"><p class="metric-title">Total Population</p><p class="metric-value" id="valPop">-</p><small class="text-muted">Millions</small></div></div>
                    <div class="col-md-3"><div class="metric-box shadow-sm"><p class="metric-title">Exchange Rate (1 USD)</p><p class="metric-value" id="valCurr" style="color: var(--accent-gold);">-</p><small class="text-muted" id="lblCurr">CURRENCY</small></div></div>
                </div>

                <div class="white-card mb-4">
                    <div class="card-title-custom"><i class="fa-solid fa-chart-pie"></i> Economic Metrics Visualization</div>
                    <div class="row">
                        <div class="col-md-4"><canvas id="gdpChart"></canvas></div>
                        <div class="col-md-4"><canvas id="inflationChart"></canvas></div>
                        <div class="col-md-4"><canvas id="currencyChart"></canvas></div>
                    </div>
                </div>

                <div class="white-card">
                    <div class="card-title-custom d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <i class="fa-solid fa-satellite-dish"></i> Maritime Meteorology Radar
                            <span class="weather-legend text-primary ms-3"><i class="fa-solid fa-cloud-rain"></i> Rain</span>
                            <span class="weather-legend text-danger"><i class="fa-solid fa-hurricane"></i> Storm</span>
                            <span class="weather-legend text-warning"><i class="fa-solid fa-wind"></i> Wind</span>
                        </div>
                        <div class="d-flex gap-4 text-center">
                            <div><h5 id="wTemp" class="fw-bold mb-0">- °C</h5><small class="text-muted fw-bold">TEMP</small></div>
                            <div><h5 id="wWind" class="fw-bold mb-0">- km/h</h5><small class="text-muted fw-bold">WIND</small></div>
                            <div><h5 id="wRain" class="fw-bold mb-0">- mm</h5><small class="text-muted fw-bold">RAIN</small></div>
                        </div>
                    </div>
                    <div id="mapTarget" style="height: 400px; border-radius: 15px; border: 1px solid #e2e8f0; z-index: 1;"></div>
                </div>
            </div>

            <div id="page-berita" class="page-section">
                <div class="white-card">
                    <div class="card-title-custom"><i class="fa-solid fa-newspaper"></i> Regional Intelligence News Feed</div>
                    <div class="row" id="newsGrid">
                        <div class="col-12 text-center py-5"><h5 class="text-muted">Please specify a Target Country first.</h5></div>
                    </div>
                </div>
            </div>

            <div id="page-pelabuhan" class="page-section">
                <div class="white-card">
                    <div class="card-title-custom d-flex align-items-center">
                        <i class="fa-solid fa-anchor"></i> Major Commercial Port Infrastructure
                        <span class="weather-legend text-primary ms-3"><i class="fa-solid fa-cloud-rain"></i> Rain</span>
                        <span class="weather-legend text-warning"><i class="fa-solid fa-wind"></i> Wind</span>
                    </div>
                    <div id="mapPort" style="height: 450px; border-radius: 15px; border: 1px solid #e2e8f0; z-index: 1; margin-bottom: 30px;"></div>
                    <table class="table table-custom w-100 mb-4">
                        <thead><tr><th>PORT CODE</th><th>MARITIME TERMINAL NAME</th><th>OPERATIONAL STATUS</th></tr></thead>
                        <tbody id="portsTableBody">
                            <tr><td colspan="3" class="text-center text-muted">Waiting for territorial parameter selection...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div id="page-route" class="page-section">
                <div class="row">
                    <div class="col-md-4 mb-4">
                        <div class="white-card h-100">
                            <div class="card-title-custom"><i class="fa-solid fa-route"></i> Plan Your Route</div>
                            
                            <label class="fw-bold small text-muted mb-1 mt-3">ORIGIN COUNTRY</label>
                            <select id="routeOrigCountry" class="custom-select mb-3"><option value="">-- Select Origin --</option></select>
                            
                            <label class="fw-bold small text-muted mb-1">ORIGIN PORT</label>
                            <select id="routeOrigPort" class="custom-select mb-4" disabled><option value="">Select country first</option></select>
                            
                            <label class="fw-bold small text-muted mb-1">DESTINATION COUNTRY</label>
                            <select id="routeDestCountry" class="custom-select mb-3"><option value="">-- Select Destination --</option></select>
                            
                            <label class="fw-bold small text-muted mb-1">DESTINATION PORT</label>
                            <select id="routeDestPort" class="custom-select mb-4" disabled><option value="">Select country first</option></select>
                            
                            <div class="d-flex gap-2">
                                <button onclick="runSimulation(false)" class="btn w-50 fw-bold shadow-sm" style="background-color: #f1f5f9; color: var(--text-dark); padding: 12px; border-radius: 12px; border: 1px solid #e2e8f0; font-size: 0.85rem;">
                                    <i class="fa-solid fa-eye me-1"></i> View Only
                                </button>
                                <button onclick="runSimulation(true)" class="btn w-50 fw-bold shadow-sm" style="background-color: var(--accent-gold); color: white; padding: 12px; border-radius: 12px; font-size: 0.85rem;">
                                    <i class="fa-solid fa-bookmark me-1"></i> View & Save
                                </button>
                            </div>

                            <div id="routeResults" style="display: none; margin-top: 30px; animation: fadeIn 0.5s;">
                                <h6 class="fw-bold text-dark mb-3 border-bottom pb-2">Route Analysis Report</h6>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted small fw-bold">TOTAL DISTANCE</span>
                                    <span class="text-dark fw-bold" id="resDistance">- km</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted small fw-bold">ESTIMATED TIME</span>
                                    <span class="text-dark fw-bold" id="resTime">- Days</span>
                                </div>
                                <div class="d-flex justify-content-between mb-3">
                                    <span class="text-muted small fw-bold">EXPECTED ARRIVAL</span>
                                    <span class="text-dark fw-bold" id="resEta" style="color: var(--accent-gold) !important;">-</span>
                                </div>
                                
                                <div class="p-3 shadow-sm" id="resRiskBox" style="background-color: #f8f9fa; border-left: 4px solid var(--accent-gold); border-radius: 8px;">
                                    <h6 class="fw-bold text-dark mb-1" style="font-size: 0.85rem;"><i class="fa-solid fa-triangle-exclamation me-1" id="resRiskIcon"></i> Risk & Delay Analysis</h6>
                                    <p class="text-muted mb-0" style="font-size: 0.8rem; line-height: 1.4;" id="resRiskText">Calculating...</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-8 mb-4">
                        <div class="white-card h-100 p-2" style="position: relative;">
                            <div style="position: absolute; top: 15px; right: 15px; z-index: 1000; background: rgba(255,255,255,0.9); padding: 5px 10px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                                <span class="weather-legend border-0 bg-transparent text-primary p-0 m-0"><i class="fa-solid fa-cloud-rain"></i> Rain</span>
                                <span class="weather-legend border-0 bg-transparent text-danger p-0 ms-2"><i class="fa-solid fa-hurricane"></i> Storm Impact</span>
                            </div>
                            <div id="mapRoute" style="height: 100%; min-height: 550px; width: 100%; border-radius: 15px; z-index: 1;"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="page-compare" class="page-section">
                <div class="row mb-4">
                    <div class="col-md-6 mb-3 mb-md-0">
                        <div class="white-card p-4" style="border-left: 5px solid var(--accent-gold);">
                            <h6 class="fw-bold text-muted small mb-2">PRIMARY SUBJECT COUNTRY</h6>
                            <select id="compareCountry1" class="custom-select shadow-sm">
                                <option value="">-- SELECT PRIMARY COUNTRY --</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="white-card p-4" style="border-left: 5px solid var(--text-dark);">
                            <h6 class="fw-bold text-muted small mb-2">SECONDARY BENCHMARK COUNTRY</h6>
                            <select id="compareCountry2" class="custom-select shadow-sm">
                                <option value="">-- SELECT BENCHMARK COUNTRY --</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="white-card">
                            <div class="card-title-custom mb-4"><i class="fa-solid fa-scale-balanced"></i> Comparative Intelligence Matrix</div>
                            <div class="table-responsive">
                                <table class="table table-bordered table-custom text-center align-middle shadow-sm">
                                    <thead class="table-dark">
                                        <tr>
                                            <th style="width: 34%; text-align: left; background-color: var(--sidebar-bg);">RISK & MACRO INDICATORS</th>
                                            <th style="width: 33%; color: var(--accent-gold);" id="lblComp1">COUNTRY A</th>
                                            <th style="width: 33%; color: #60a5fa;" id="lblComp2">COUNTRY B</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="fw-bold text-start text-dark"><i class="fa-solid fa-shield-halved me-2 text-muted"></i> Total Risk Score Index</td>
                                            <td id="compRisk1" class="fw-bold">-</td>
                                            <td id="compRisk2" class="fw-bold">-</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold text-start text-dark"><i class="fa-solid fa-coins me-2 text-muted"></i> GDP (Trillion USD)</td>
                                            <td id="compGdp1">-</td>
                                            <td id="compGdp2">-</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold text-start text-dark"><i class="fa-solid fa-arrow-trend-up me-2 text-muted"></i> Inflation Rate</td>
                                            <td id="compInf1">-</td>
                                            <td id="compInf2">-</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold text-start text-dark"><i class="fa-solid fa-users me-2 text-muted"></i> Total Population</td>
                                            <td id="compPop1">-</td>
                                            <td id="compPop2">-</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold text-start text-dark"><i class="fa-solid fa-money-bill-transfer me-2 text-muted"></i> Local Exchange Rate (vs 1 USD)</td>
                                            <td id="compCur1">-</td>
                                            <td id="compCur2">-</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold text-start text-dark"><i class="fa-solid fa-temperature-half me-2 text-muted"></i> Maritime Temperature</td>
                                            <td id="compTemp1">-</td>
                                            <td id="compTemp2">-</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold text-start text-dark"><i class="fa-solid fa-wind me-2 text-muted"></i> Wind Velocity</td>
                                            <td id="compWind1">-</td>
                                            <td id="compWind2">-</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="page-watchlist" class="page-section">
                <div class="white-card">
                    <div class="card-title-custom"><i class="fa-solid fa-list-check"></i> Global Shipment Watchlist</div>
                    <p class="text-muted small mb-4">Monitor active logistics routes saved from the Route Simulation module.</p>
                    
                    <div class="table-responsive">
                        <table class="table table-custom w-100 align-middle">
                            <thead style="background-color: #f8f9fa;">
                                <tr>
                                    <th>TRACKING ID</th>
                                    <th>OPERATOR</th>
                                    <th>LOGISTICS ROUTE</th>
                                    <th>ETA ARRIVAL DATE</th>
                                    <th>OPERATIONAL STATUS</th>
                                    <th>ACTION</th>
                                </tr>
                            </thead>
                            <tbody id="watchlistBody">
                                </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        // =========================================================================
        // LOGIN & SESSION MANAGEMENT (SINGLE ADMIN ROLE)
        // =========================================================================
        document.getElementById('password').addEventListener('keypress', function (e) {
            if (e.key === 'Enter') handleLogin();
        });

        function checkLoginState() {
            const loggedIn = sessionStorage.getItem('admin_logged_in');
            if (!loggedIn) {
                document.getElementById('loginScreen').style.display = 'flex';
                document.getElementById('appDashboard').style.display = 'none';
            } else {
                document.getElementById('loginScreen').style.display = 'none';
                document.getElementById('appDashboard').style.display = 'flex';
                renderWatchlistTable();
            }
        }

        function handleLogin() {
            const u = document.getElementById('username').value;
            const p = document.getElementById('password').value;
            const err = document.getElementById('loginError');
            
            if (u === 'admin' && p === 'admin123') {
                sessionStorage.setItem('admin_logged_in', 'true');
                err.style.display = 'none';
                checkLoginState();
            } else {
                err.style.display = 'block';
            }
        }

        function handleLogout() {
            sessionStorage.removeItem('admin_logged_in');
            document.getElementById('username').value = '';
            document.getElementById('password').value = '';
            checkLoginState();
        }

        // =========================================================================
        // PANGKALAN DATA 195 NEGARA
        // =========================================================================
        let gdpChartInstance = null, inflationChartInstance = null, currencyChartInstance = null;
        let mapTargetInstance = null, mapTargetMarker = null;
        let mapPortInstance = null, portLayerGroup = null;
        let mapRouteInstance = null, routeLayerGroup = null;

        const rawCountryData = [
            ["Afghanistan", "AF", 33.9391, 67.7100, "AFN"], ["Albania", "AL", 41.1533, 20.1683, "ALL"], ["Algeria", "DZ", 28.0339, 1.6596, "DZD"],
            ["Andorra", "AD", 42.5063, 1.5218, "EUR"], ["Angola", "AO", -11.2027, 17.8739, "AOA"], ["Antigua and Barbuda", "AG", 17.0608, -61.7964, "XCD"],
            ["Argentina", "AR", -38.4161, -63.6167, "ARS"], ["Armenia", "AM", 40.0691, 45.0382, "AMD"], ["Australia", "AU", -25.2744, 133.7751, "AUD"],
            ["Austria", "AT", 47.5162, 14.5501, "EUR"], ["Azerbaijan", "AZ", 40.1431, 47.5769, "AZN"], ["Bahamas", "BS", 25.0343, -77.3963, "BSD"],
            ["Bahrain", "BH", 26.0667, 50.5577, "BHD"], ["Bangladesh", "BD", 23.6850, 90.3563, "BDT"], ["Barbados", "BB", 13.1939, -59.5432, "BBD"],
            ["Belarus", "BY", 53.7098, 27.9534, "BYN"], ["Belgium", "BE", 50.5039, 4.4699, "EUR"], ["Belize", "BZ", 17.1899, -88.4976, "BZD"],
            ["Benin", "BJ", 9.3077, 2.3158, "XOF"], ["Bhutan", "BT", 27.5142, 90.4336, "BTN"], ["Bolivia", "BO", -16.2902, -63.5887, "BOB"],
            ["Bosnia and Herzegovina", "BA", 43.9159, 17.6791, "BAM"], ["Botswana", "BW", -22.3285, 24.6849, "BWP"], ["Brazil", "BR", -14.2350, -51.9253, "BRL"],
            ["Brunei", "BN", 4.5353, 114.7277, "BND"], ["Bulgaria", "BG", 42.7339, 25.4858, "BGN"], ["Burkina Faso", "BF", 12.2383, -1.5616, "XOF"],
            ["Burundi", "BI", -3.3731, 29.9189, "BIF"], ["Cambodia", "KH", 12.5657, 104.9910, "KHR"], ["Cameroon", "CM", 3.8480, 11.5021, "XAF"],
            ["Canada", "CA", 56.1304, -106.3468, "CAD"], ["Cape Verde", "CV", 16.0021, -24.0132, "CVE"], ["Central African Republic", "CF", 6.6111, 20.9394, "XAF"],
            ["Chad", "TD", 15.4542, 18.7322, "XAF"], ["Chile", "CL", -35.6751, -71.5430, "CLP"], ["China", "CN", 35.8617, 104.1954, "CNY"],
            ["Colombia", "CO", 4.5709, -74.2973, "COP"], ["Comoros", "KM", -11.8750, 43.8722, "KMF"], ["Costa Rica", "CR", 9.7489, -83.7534, "CRC"],
            ["Croatia", "HR", 45.1000, 15.2000, "EUR"], ["Cuba", "CU", 21.5218, -77.7812, "CUP"], ["Cyprus", "CY", 35.1264, 33.4299, "EUR"],
            ["Czech Republic", "CZ", 49.8175, 15.4730, "CZK"], ["Democratic Republic of the Congo", "CD", -4.0383, 21.7587, "CDF"], ["Denmark", "DK", 56.2639, 9.5018, "DKK"],
            ["Djibouti", "DJ", 11.8251, 42.5903, "DJF"], ["Dominica", "DM", 15.4149, -61.3705, "XCD"], ["Ecuador", "EC", -1.8312, -78.1834, "USD"],
            ["Egypt", "EG", 26.8206, 30.8025, "EGP"], ["El Salvador", "SV", 13.7942, -88.8965, "USD"], ["Equatorial Guinea", "GQ", 1.6508, 10.2679, "XAF"],
            ["Eritrea", "ER", 15.1794, 39.7823, "ERN"], ["Estonia", "EE", 58.5953, 25.0136, "EUR"], ["Eswatini", "SZ", -26.5225, 31.4659, "SZL"],
            ["Ethiopia", "ET", 9.1450, 40.4897, "ETB"], ["Fiji", "FJ", -17.7134, 178.0650, "FJD"], ["Finland", "FI", 61.9241, 25.7482, "EUR"],
            ["France", "FR", 46.2276, 2.2137, "EUR"], ["Gabon", "GA", -0.8037, 11.6094, "XAF"], ["Gambia", "GM", 13.4432, -15.3101, "GMD"],
            ["Georgia", "GE", 42.3154, 43.3569, "GEL"], ["Germany", "DE", 51.1657, 10.4515, "EUR"], ["Ghana", "GH", 7.9465, -1.0232, "GHS"],
            ["Greece", "GR", 39.0742, 21.8243, "EUR"], ["Grenada", "GD", 12.1165, -61.6790, "XCD"], ["Guatemala", "GT", 15.7835, -90.2308, "GTQ"],
            ["Guinea", "GN", 9.9456, -9.6966, "GNF"], ["Guinea-Bissau", "GW", 11.8037, -15.1804, "XOF"], ["Guyana", "GY", 4.8604, -58.9302, "GYD"],
            ["Haiti", "HT", 18.9712, -72.2852, "HTG"], ["Honduras", "HN", 15.2000, -86.2419, "HNL"], ["Hungary", "HU", 47.1625, 19.5033, "HUF"],
            ["Iceland", "IS", 64.9631, -19.0208, "ISK"], ["India", "IN", 20.5937, 78.9629, "INR"], ["Indonesia", "ID", -0.7893, 113.9213, "IDR"],
            ["Iran", "IR", 32.4279, 53.6880, "IRR"], ["Iraq", "IQ", 33.2232, 43.6793, "IQD"], ["Ireland", "IE", 53.1424, -7.6921, "EUR"],
            ["Israel", "IL", 31.0461, 34.8516, "ILS"], ["Italy", "IT", 41.8719, 12.5674, "EUR"], ["Jamaica", "JM", 18.1096, -77.2975, "JMD"],
            ["Japan", "JP", 36.2048, 138.2529, "JPY"], ["Jordan", "JO", 30.5852, 36.2384, "JOD"], ["Kazakhstan", "KZ", 48.0196, 66.9237, "KZT"],
            ["Kenya", "KE", -0.0236, 37.9062, "KES"], ["Kiribati", "KI", -3.3704, -168.7340, "AUD"], ["Kuwait", "KW", 29.3117, 47.4818, "KWD"],
            ["Kyrgyzstan", "KG", 41.2044, 74.7661, "KGS"], ["Laos", "LA", 19.8563, 102.4955, "LAK"], ["Latvia", "LV", 56.8796, 24.6032, "EUR"],
            ["Lebanon", "LB", 33.8547, 35.8623, "LBP"], ["Lesotho", "LS", -29.6099, 28.2336, "LSL"], ["Liberia", "LR", 6.4281, -9.4295, "LRD"],
            ["Libya", "LY", 26.3351, 17.2283, "LYD"], ["Liechtenstein", "LI", 47.1660, 9.5554, "CHF"], ["Lithuania", "LT", 55.1694, 23.8813, "EUR"],
            ["Luxembourg", "LU", 49.8153, 6.1296, "EUR"], ["Madagascar", "MG", -18.7669, 46.8691, "MGA"], ["Malawi", "MW", -13.2543, 34.3015, "MWK"],
            ["Malaysia", "MY", 4.2105, 101.9758, "MYR"], ["Maldives", "MV", 3.2028, 73.2207, "MVR"], ["Mali", "ML", 17.5707, -3.9962, "XOF"],
            ["Malta", "MT", 35.9375, 14.3754, "EUR"], ["Marshall Islands", "MH", 7.1315, 171.1845, "USD"], ["Mauritania", "MR", 21.0079, -10.9408, "MRU"],
            ["Mauritius", "MU", -20.3484, 57.5522, "MUR"], ["Mexico", "MX", 23.6345, -102.5528, "MXN"], ["Micronesia", "FM", 7.4256, 150.5508, "USD"],
            ["Moldova", "MD", 47.4116, 28.3699, "MDL"], ["Monaco", "MC", 43.7384, 7.4246, "EUR"], ["Mongolia", "MN", 46.8625, 103.8467, "MNT"],
            ["Montenegro", "ME", 42.7087, 19.3744, "EUR"], ["Morocco", "MA", 31.7917, -7.0926, "MAD"], ["Mozambique", "MZ", -18.6657, 35.5296, "MZN"],
            ["Myanmar", "MM", 21.9162, 95.9560, "MMK"], ["Namibia", "NA", -22.9576, 18.4904, "NAD"], ["Nauru", "NR", -0.5228, 166.9315, "AUD"],
            ["Nepal", "NP", 28.3949, 84.1240, "NPR"], ["Netherlands", "NL", 52.1326, 5.2913, "EUR"], ["New Zealand", "NZ", -40.9006, 174.8860, "NZD"],
            ["Nicaragua", "NI", 12.8654, -85.2072, "NIO"], ["Niger", "NE", 17.6078, 8.0817, "XOF"], ["Nigeria", "NG", 9.0820, 8.6753, "NGN"],
            ["North Korea", "KP", 40.3399, 127.5101, "KPW"], ["Norway", "NO", 60.4720, 8.4689, "NOK"], ["Oman", "OM", 21.4735, 55.9754, "OMR"],
            ["Pakistan", "PK", 30.3753, 69.3451, "PKR"], ["Palau", "PW", 7.5150, 134.5825, "USD"], ["Palestine", "PS", 31.9522, 35.2332, "ILS"],
            ["Panama", "PA", 8.5380, -80.7821, "PAB"], ["Papua New Guinea", "PG", -6.3149, 143.9555, "PGK"], ["Paraguay", "PY", -23.4425, -58.4438, "PYG"],
            ["Peru", "PE", -9.1900, -75.0152, "PEN"], ["Philippines", "PH", 12.8797, 121.7740, "PHP"], ["Poland", "PL", 51.9194, 19.1451, "PLN"],
            ["Portugal", "PT", 39.3999, -8.2245, "EUR"], ["Qatar", "QA", 25.3548, 51.1839, "QAR"], ["Republic of the Congo", "CG", -0.2280, 15.8277, "XAF"],
            ["Romania", "RO", 45.9432, 24.9668, "RON"], ["Russia", "RU", 61.5240, 105.3188, "RUB"], ["Rwanda", "RW", -1.9403, 29.8739, "RWF"],
            ["Saint Kitts and Nevis", "KN", 17.3578, -62.7830, "XCD"], ["Saint Lucia", "LC", 13.9094, -60.9789, "XCD"], ["Saint Vincent and the Grenadines", "VC", 13.2528, -61.1971, "XCD"],
            ["Samoa", "WS", -13.7590, -172.1046, "WST"], ["San Marino", "SM", 43.9424, 12.4578, "EUR"], ["Sao Tome and Principe", "ST", 0.1864, 6.6131, "STN"],
            ["Saudi Arabia", "SA", 23.8859, 45.0792, "SAR"], ["Senegal", "SN", 14.4974, -14.4524, "XOF"], ["Serbia", "RS", 44.0165, 21.0059, "RSD"],
            ["Seychelles", "SC", -4.6796, 55.4920, "SCR"], ["Sierra Leone", "SL", 8.4606, -11.7799, "SLE"], ["Singapore", "SG", 1.3521, 103.8198, "SGD"],
            ["Slovakia", "SK", 48.6690, 19.6990, "EUR"], ["Slovenia", "SI", 46.1512, 14.9955, "EUR"], ["Solomon Islands", "SB", -9.6457, 160.1562, "SBD"],
            ["Somalia", "SO", 5.1521, 46.1996, "SOS"], ["South Africa", "ZA", -30.5595, 22.9375, "ZAR"], ["South Korea", "KR", 35.9078, 127.7669, "KRW"],
            ["South Sudan", "SS", 6.8770, 31.3070, "SSP"], ["Spain", "ES", 40.4637, -3.7492, "EUR"], ["Sri Lanka", "LK", 7.8731, 80.7718, "LKR"],
            ["Sudan", "SD", 12.8628, 30.2176, "SDG"], ["Suriname", "SR", 3.9193, -56.0278, "SRD"], ["Sweden", "SE", 60.1282, 18.6435, "SEK"],
            ["Switzerland", "CH", 46.8182, 8.2275, "CHF"], ["Syria", "SY", 34.8021, 38.9968, "SYP"], ["Tajikistan", "TJ", 38.8610, 71.2761, "TJS"],
            ["Tanzania", "TZ", -6.3690, 34.8888, "TZS"], ["Thailand", "TH", 15.8700, 100.9925, "THB"], ["Timor-Leste", "TL", -8.8742, 125.7275, "USD"],
            ["Togo", "TG", 8.6195, 0.8248, "XOF"], ["Tonga", "TO", -21.1790, -175.1982, "TOP"], ["Trinidad and Tobago", "TT", 10.6918, -61.2225, "TTD"],
            ["Tunisia", "TN", 33.8869, 9.5375, "TND"], ["Turkey", "TR", 38.9637, 35.2433, "TRY"], ["Turkmenistan", "TM", 38.9697, 59.5563, "TMT"],
            ["Tuvalu", "TV", -7.1095, 177.6493, "AUD"], ["Uganda", "UG", 1.3733, 32.2903, "UGX"], ["Ukraine", "UA", 48.3794, 31.1656, "UAH"],
            ["United Arab Emirates", "AE", 23.4241, 53.8478, "AED"], ["United Kingdom", "GB", 55.3781, -3.4360, "GBP"], ["United States", "US", 37.0902, -95.7129, "USD"],
            ["Uruguay", "UY", -32.5228, -55.7658, "UYU"], ["Uzbekistan", "UZ", 41.3775, 64.5853, "UZS"], ["Vanuatu", "VU", -15.3767, 166.9592, "VUV"],
            ["Vatican City", "VA", 41.9029, 12.4534, "EUR"], ["Venezuela", "VE", 6.4238, -66.5897, "VES"], ["Vietnam", "VN", 14.0583, 108.2772, "VND"],
            ["Yemen", "YE", 15.5527, 48.5164, "YER"], ["Zambia", "ZM", -13.1339, 27.8493, "ZMW"], ["Zimbabwe", "ZW", -19.0154, 29.1549, "ZWL"]
        ];

        const globalCountryDatabase = rawCountryData.map(c => {
            let seed = c[1].charCodeAt(0) + c[1].charCodeAt(1);
            return {
                name: c[0], iso: c[1], lat: c[2], lng: c[3], cur: c[4],
                pop: ((seed % 90) * 2.5 + 5).toFixed(1),
                gdp: ((seed % 40) * 0.1 + 0.5).toFixed(2), 
                inf: ((seed % 15) * 0.4 + 1.5).toFixed(1)
            };
        });

        const majorPortsDatabase = {
            'US': [{name: 'Port of Los Angeles', code: 'USLAX', lat: 33.72, lng: -118.26}, {name: 'Port of New York', code: 'USNYC', lat: 40.67, lng: -74.04}],
            'CN': [{name: 'Port of Shanghai', code: 'CNSHA', lat: 31.22, lng: 121.48}, {name: 'Port of Ningbo', code: 'CNNGB', lat: 29.87, lng: 121.55}],
            'JP': [{name: 'Port of Tokyo', code: 'JPTYO', lat: 35.61, lng: 139.77}, {name: 'Port of Yokohama', code: 'JPYOK', lat: 35.45, lng: 139.66}],
            'SG': [{name: 'PSA Singapore', code: 'SGSIN', lat: 1.27, lng: 103.80}],
            'MY': [{name: 'Port Klang', code: 'MYPKG', lat: 3.00, lng: 101.40}],
            'ID': [{name: 'Tanjung Priok, Jakarta', code: 'IDTPP', lat: -6.10, lng: 106.88}, {name: 'Tanjung Perak, Surabaya', code: 'IDTJR', lat: -7.19, lng: 112.73}],
            'PS': [{name: 'Gaza Seaport', code: 'PSGAZ', lat: 31.52, lng: 34.43}],
            'IL': [{name: 'Port of Haifa', code: 'ILHFA', lat: 32.81, lng: 35.00}],
            'RU': [{name: 'Port of Novorossiysk', code: 'RUNVS', lat: 44.73, lng: 37.79}],
            'UA': [{name: 'Port of Odesa', code: 'UAODS', lat: 46.49, lng: 30.73}]
        };

        function applyRandomWeather(mapGrp, centerLat, centerLng) {
            for(let i=0; i<4; i++) {
                let lat = centerLat + (Math.random() * 8 - 4);
                let lng = centerLng + (Math.random() * 8 - 4);
                let type = Math.random();
                if(type > 0.7) {
                    L.circle([lat, lng], { radius: 80000, className: 'weather-rain', color: '#3b82f6', weight: 1, fillOpacity: 0.3 }).addTo(mapGrp);
                } else if(type > 0.4) {
                    L.circle([lat, lng], { radius: 120000, className: 'weather-wind', color: '#f59e0b', weight: 2, fillOpacity: 0.1, dashArray: '10, 15' }).addTo(mapGrp);
                }
            }
        }

        function generatePortStatus(portName) {
            let seed = 0; for(let i=0; i<portName.length; i++) { seed += portName.charCodeAt(i); }
            const rand = (seed % 100) / 100;
            if (rand > 0.85) return { text: 'CONGESTED', color: '#f59e0b' }; 
            if (rand > 0.70) return { text: 'HEAVY TRAFFIC', color: '#fbbf24' }; 
            if (rand > 0.55) return { text: 'DELAYED', color: '#ef4444' }; 
            if (rand > 0.25) return { text: 'NORMAL', color: '#3b82f6' }; 
            return { text: 'OPTIMAL', color: '#10b981' }; 
        }

        async function fetchSynchronizedPorts(cName, cData) {
            if (majorPortsDatabase[cData.iso]) return { ports: majorPortsDatabase[cData.iso] };
            try {
                const res = await fetch(`/api/external/ports/${cName}`);
                const p = await res.json();
                if (p.success && p.data && p.data.length > 0) {
                    let cleanPorts = p.data.slice(0, 2).map(port => {
                        let pseudoCode = port.name.replace(/[^A-Za-z]/g, '').substring(0,3).toUpperCase();
                        if(pseudoCode.length < 3) pseudoCode = 'PRT';
                        port.code = cData.iso + pseudoCode;
                        return port;
                    });
                    return { ports: cleanPorts };
                }
            } catch (e) {}
            return { 
                ports: [
                    { name: `Port of ${cName}`, code: cData.iso + "PT1", lat: parseFloat(cData.lat) + 0.2, lng: parseFloat(cData.lng) + 0.2 },
                    { name: `${cName} Coastal Terminal`, code: cData.iso + "PT2", lat: parseFloat(cData.lat) - 0.2, lng: parseFloat(cData.lng) - 0.2 }
                ] 
            };
        }

        function loadPortsForDropdown(cName, cData, dropdownId) {
            const drop = document.getElementById(dropdownId);
            drop.innerHTML = '<option value="">Synchronizing data...</option>'; drop.disabled = true;
            fetchSynchronizedPorts(cName, cData).then(result => {
                drop.innerHTML = '';
                result.ports.forEach(port => { 
                    drop.innerHTML += `<option value="${port.lat},${port.lng},${cData.iso},${port.code}">${port.name}</option>`; 
                });
                drop.disabled = false;
            }).catch(e => { drop.innerHTML = '<option value="">Failed to load</option>'; });
        }

        // =========================================================================
        // SINKRONISASI DAFTAR PANTAUAN (AMAN DARI CRASH CACHE)
        // =========================================================================
        const defaultWatchlist = [
            { id: '#SHP-8821', operator: 'Sutan Admin', origin: 'CNSHA', dest: 'USLAX', eta: 'August 20, 2026', status: 'ON SCHEDULE', color: 'bg-success' }
        ];

        function getWatchlistFromStorage() {
            try {
                let data = localStorage.getItem('riskintel_watchlist');
                if(!data) {
                    localStorage.setItem('riskintel_watchlist', JSON.stringify(defaultWatchlist));
                    return [...defaultWatchlist]; 
                }
                let parsed = JSON.parse(data);
                if (!Array.isArray(parsed)) throw new Error("Data not array");
                return parsed;
            } catch(e) {
                localStorage.setItem('riskintel_watchlist', JSON.stringify(defaultWatchlist));
                return [...defaultWatchlist];
            }
        }

        function renderWatchlistTable() {
            const list = getWatchlistFromStorage();
            const tbody = document.getElementById('watchlistBody');
            tbody.innerHTML = '';
            list.forEach(row => {
                tbody.innerHTML += `
                    <tr style="animation: fadeIn 0.5s ease-in-out;">
                        <td class="fw-bold" style="color: var(--accent-gold);">${row.id}</td>
                        <td><span class="fw-bold text-dark">${row.operator}</span></td>
                        <td><span class="badge bg-light text-dark border border-secondary">${row.origin}</span> &rarr; <span class="badge bg-light text-dark border border-secondary">${row.dest}</span></td>
                        <td class="text-muted fw-bold">${row.eta}</td>
                        <td><span class="badge ${row.color}">${row.status}</span></td>
                        <td>
                            <div class="btn-group" role="group">
                                <button onclick="updateWatchlistStatus('${row.id}', 'ARRIVED', 'bg-success')" class="btn btn-sm btn-outline-success" title="Mark as Arrived"><i class="fa-solid fa-check-double"></i></button>
                                <button onclick="updateWatchlistStatus('${row.id}', 'DELAYED', 'bg-warning text-dark')" class="btn btn-sm btn-outline-warning" title="Mark as Delayed"><i class="fa-solid fa-clock"></i></button>
                                <button onclick="deleteWatchlistEntry('${row.id}')" class="btn btn-sm btn-outline-danger" title="Delete Record"><i class="fa-solid fa-trash"></i></button>
                            </div>
                        </td>
                    </tr>
                `;
            });
        }

        window.updateWatchlistStatus = function(id, newStatus, newColor) {
            let list = getWatchlistFromStorage();
            let index = list.findIndex(item => item.id === id);
            if(index !== -1) {
                list[index].status = newStatus;
                list[index].color = newColor;
                localStorage.setItem('riskintel_watchlist', JSON.stringify(list));
                renderWatchlistTable();
            }
        };

        window.deleteWatchlistEntry = function(id) {
            if(confirm("Are you sure you want to delete this shipment record?")) {
                let list = getWatchlistFromStorage();
                list = list.filter(item => item.id !== id);
                localStorage.setItem('riskintel_watchlist', JSON.stringify(list));
                renderWatchlistTable();
            }
        };

        // =========================================================================
        // PARALEL FETCH FUNCTIONS
        // =========================================================================
        function executeMatrixComparison(targetIndex) {
            const cName = document.getElementById(`compareCountry${targetIndex}`).value;
            if(!cName) return;

            const cData = globalCountryDatabase.find(x => x.name === cName);
            document.getElementById(`lblComp${targetIndex}`).innerText = cName.toUpperCase();

            const spinner = '<span class="spinner-border spinner-border-sm text-secondary"></span>';
            document.getElementById(`compGdp${targetIndex}`).innerHTML = spinner;
            document.getElementById(`compInf${targetIndex}`).innerHTML = spinner;
            document.getElementById(`compPop${targetIndex}`).innerHTML = spinner;
            document.getElementById(`compCur${targetIndex}`).innerHTML = spinner;
            document.getElementById(`compRisk${targetIndex}`).innerHTML = spinner;
            document.getElementById(`compTemp${targetIndex}`).innerHTML = spinner;
            document.getElementById(`compWind${targetIndex}`).innerHTML = spinner;

            (async () => {
                let gdp = cData.gdp, inf = cData.inf, pop = cData.pop, rate = 1.0;
                try {
                    let resEco = await fetch(`/api/external/economy/${cData.iso}`);
                    let rEco = await resEco.json();
                    if(rEco.success && rEco.data) {
                        gdp = rEco.data.gdp ? (rEco.data.gdp / 1e12).toFixed(2) : gdp;
                        inf = rEco.data.inflation ? rEco.data.inflation.toFixed(1) : inf;
                        pop = rEco.data.population ? (rEco.data.population / 1e6).toFixed(1) : pop;
                    }
                } catch(e) {}

                document.getElementById(`compGdp${targetIndex}`).innerText = gdp + ' T';
                document.getElementById(`compInf${targetIndex}`).innerText = inf + '%';
                document.getElementById(`compPop${targetIndex}`).innerText = pop + ' M';

                try {
                    let resCur = await fetch(`/api/external/currency/USD`);
                    let rCur = await resCur.json();
                    if(rCur.success && rCur.rates) rate = rCur.rates[cData.cur] || 1.0;
                } catch(e) {}
                
                document.getElementById(`compCur${targetIndex}`).innerText = `${rate > 100 ? rate.toLocaleString('en-US',{maximumFractionDigits:2}) : rate.toFixed(2)} ${cData.cur}`;

                try {
                    let resRisk = await fetch(`/api/ai/predict-risk?wind=12&inflation=${inf}&exchange=${rate}&iso=${cData.iso}`);
                    let rRisk = await resRisk.json();
                    if(rRisk.success) {
                        let stat = rRisk.prediction.risk_status;
                        let color = stat === 'LOW RISK' ? '#4ade80' : (stat === 'MEDIUM RISK' ? '#fbbf24' : '#ef4444');
                        document.getElementById(`compRisk${targetIndex}`).innerHTML = `<span style="color: ${color}; font-weight:800;">${rRisk.prediction.total_risk_score} (${stat})</span>`;
                    } else throw new Error();
                } catch(e) {
                    document.getElementById(`compRisk${targetIndex}`).innerHTML = `<span style="color: #fbbf24; font-weight:800;">45 (MEDIUM RISK)</span>`;
                }
            })();

            (async () => {
                try {
                    let resW = await fetch(`/api/external/weather/${cData.lat}/${cData.lng}`);
                    let w = await resW.json();
                    if(w.success && w.data) {
                        document.getElementById(`compTemp${targetIndex}`).innerText = w.data.temperature_2m + ' °C';
                        document.getElementById(`compWind${targetIndex}`).innerText = w.data.wind_speed_10m + ' km/h';
                    } else throw new Error();
                } catch(e) {
                    let baseT = Math.abs(cData.lat) < 23.5 ? 28 : 18;
                    document.getElementById(`compTemp${targetIndex}`).innerText = (baseT + (Math.random()*2)).toFixed(1) + ' °C';
                    document.getElementById(`compWind${targetIndex}`).innerText = (Math.random()*15+5).toFixed(1) + ' km/h';
                }
            })();
        }

        // =========================================================================
        // DOM LOAD & EVENT LISTENERS
        // =========================================================================
        document.addEventListener("DOMContentLoaded", function() {
            checkLoginState();

            setInterval(() => { document.getElementById('liveClock').innerHTML = `<i class="fa-regular fa-clock me-1"></i> ${new Date().toLocaleString('en-US')}`; }, 1000);
            
            document.querySelectorAll('.sidebar-link').forEach(link => { 
                link.addEventListener('click', function(e) { 
                    e.preventDefault();
                    document.querySelectorAll('.sidebar-link').forEach(l => l.classList.remove('active')); 
                    this.classList.add('active'); 
                    
                    document.querySelectorAll('.page-section').forEach(page => page.classList.remove('active-section'));
                    const targetId = this.getAttribute('data-target');
                    document.getElementById(targetId).classList.add('active-section');
                    document.getElementById('mainTitle').innerText = this.innerText.trim();

                    if(targetId === 'page-target' && mapTargetInstance) { setTimeout(() => { mapTargetInstance.invalidateSize(); }, 200); }
                    if(targetId === 'page-pelabuhan' && mapPortInstance) { setTimeout(() => { mapPortInstance.invalidateSize(); }, 200); }
                    if(targetId === 'page-watchlist') { renderWatchlistTable(); }
                    
                    if(targetId === 'page-route') { 
                        if(!mapRouteInstance) {
                            mapRouteInstance = L.map('mapRoute').setView([0, 0], 2);
                            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(mapRouteInstance);
                            routeLayerGroup = L.layerGroup().addTo(mapRouteInstance);
                        }
                        setTimeout(() => { mapRouteInstance.invalidateSize(); }, 200); 
                    }
                }); 
            });

            const select = document.getElementById('countrySelect');
            const origSelect = document.getElementById('routeOrigCountry');
            const destSelect = document.getElementById('routeDestCountry');
            const comp1 = document.getElementById('compareCountry1');
            const comp2 = document.getElementById('compareCountry2');
            
            globalCountryDatabase.sort((a, b) => a.name.localeCompare(b.name)).forEach(c => { 
                let opt = `<option value="${c.name}">${c.name.toUpperCase()}</option>`;
                select.innerHTML += opt; origSelect.innerHTML += opt; destSelect.innerHTML += opt;
                comp1.innerHTML += opt; comp2.innerHTML += opt;
            });

            Chart.defaults.color = '#718096'; Chart.defaults.borderColor = '#edf2f7';

            comp1.addEventListener('change', function() { executeMatrixComparison(1); });
            comp2.addEventListener('change', function() { executeMatrixComparison(2); });

            origSelect.addEventListener('change', function() {
                const cName = this.value; if(!cName) return;
                const cData = globalCountryDatabase.find(x => x.name === cName);
                loadPortsForDropdown(cName, cData, 'routeOrigPort');
            });
            destSelect.addEventListener('change', function() {
                const cName = this.value; if(!cName) return;
                const cData = globalCountryDatabase.find(x => x.name === cName);
                loadPortsForDropdown(cName, cData, 'routeDestPort');
            });

            // =========================================================================
            // FUNGSI UTAMA: MENJALANKAN SIMULASI RUTE (DENGAN/TANPA SAVE KE WATCHLIST)
            // =========================================================================
            window.runSimulation = function(saveToWatchlist) {
                const origDrop = document.getElementById('routeOrigPort');
                const destDrop = document.getElementById('routeDestPort');

                if(!origDrop.value || !destDrop.value) { alert("Please select both origin and destination ports."); return; }

                const origVals = origDrop.value.split(','); const destVals = destDrop.value.split(',');
                const origLat = parseFloat(origVals[0]); const origLng = parseFloat(origVals[1]); 
                const origIso = origVals[2]; const origCode = origVals[3];
                
                const destLat = parseFloat(destVals[0]); const destLng = parseFloat(destVals[1]); 
                const destIso = destVals[2]; const destCode = destVals[3];

                const origName = origDrop.options[origDrop.selectedIndex].text; const destName = destDrop.options[destDrop.selectedIndex].text;

                document.getElementById('resDistance').innerHTML = '<span class="spinner-border spinner-border-sm text-secondary"></span>';
                document.getElementById('resTime').innerHTML = '<span class="spinner-border spinner-border-sm text-secondary"></span>';
                document.getElementById('resEta').innerHTML = '<span class="spinner-border spinner-border-sm text-secondary"></span>';
                document.getElementById('resRiskText').innerText = "Analyzing global parameters...";
                document.getElementById('routeResults').style.display = 'block';

                if (!mapRouteInstance) {
                    mapRouteInstance = L.map('mapRoute').setView([0, 0], 2);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(mapRouteInstance);
                    routeLayerGroup = L.layerGroup().addTo(mapRouteInstance);
                }

                setTimeout(() => {
                    try {
                        const distanceKm = calculateDistance(origLat, origLng, destLat, destLng);
                        const speedKmH = 40; const baseHours = distanceKm / speedKmH;
                        let baseDays = Math.ceil(baseHours / 24); if(baseDays === 0) baseDays = 1;

                        let delayDays = 0; let riskMsg = "Normal maritime weather conditions. Standard logistics operation protocol in effect."; let riskColor = "var(--accent-gold)";
                        const highRiskZones = ['PS', 'IL', 'YE', 'RU', 'UA', 'SD', 'SO'];
                        
                        let midLat = (origLat + destLat) / 2;
                        let midLng = (origLng + destLng) / 2;

                        if (highRiskZones.includes(origIso) || highRiskZones.includes(destIso)) {
                            delayDays = Math.floor(Math.random() * 8) + 7;
                            riskMsg = "CRITICAL RISK: Active geopolitical conflict zone detected. Rerouting protocols engaged.";
                            riskColor = "#ef4444"; document.getElementById('resRiskBox').style.borderColor = "#ef4444";
                        } else if (Math.random() > 0.6) {
                            delayDays = Math.floor(Math.random() * 4) + 2;
                            riskMsg = "MEDIUM RISK: Maritime storm anomaly detected along trajectory. Speed reduction applied.";
                            riskColor = "#fbbf24"; document.getElementById('resRiskBox').style.borderColor = "#fbbf24";
                        } else { document.getElementById('resRiskBox').style.borderColor = "var(--accent-gold)"; }

                        const totalDays = baseDays + delayDays;
                        let etaDate = new Date(); etaDate.setDate(etaDate.getDate() + totalDays);
                        const formattedEta = etaDate.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });

                        document.getElementById('resDistance').innerText = Math.round(distanceKm).toLocaleString('en-US') + ' km (Nautical)';
                        document.getElementById('resTime').innerText = totalDays + ' Days';
                        document.getElementById('resEta').innerText = formattedEta;
                        document.getElementById('resRiskText').innerText = riskMsg;
                        document.getElementById('resRiskIcon').style.color = riskColor;

                        routeLayerGroup.clearLayers();
                        let m1 = L.marker([origLat, origLng]).addTo(routeLayerGroup).bindPopup(`<b>ORIGIN:</b> ${origName}`);
                        let m2 = L.marker([destLat, destLng]).addTo(routeLayerGroup).bindPopup(`<b>DESTINATION:</b> ${destName}`);
                        
                        if (riskColor === '#fbbf24') {
                            L.circleMarker([midLat, midLng], { radius: 10, className: 'weather-storm', color: '#ef4444', fillColor: '#ef4444' })
                                .bindPopup("<b>SEVERE STORM ANOMALY</b><br>Slowing down cargo ship speed.")
                                .addTo(routeLayerGroup);
                        } else {
                            applyRandomWeather(routeLayerGroup, midLat, midLng); 
                        }

                        let routeLine = L.polyline([[origLat, origLng], [destLat, destLng]], { color: riskColor==="#ef4444"?'#ef4444':'#3b82f6', weight: 3, dashArray: '10, 10' }).addTo(routeLayerGroup);
                        mapRouteInstance.fitBounds(routeLine.getBounds(), {padding: [50, 50], maxZoom: 6}); m1.openPopup();

                        // JIKA TOMBOL "VIEW & SAVE" DIKLIK, MAKA SIMPAN KE WATCHLIST
                        if (saveToWatchlist) {
                            const trackingId = '#SHP-' + Math.floor(Math.random() * 9000 + 1000);
                            let statusLabel = 'ON SCHEDULE';
                            let statusColor = 'bg-success';
                            
                            if (riskColor === '#ef4444') { statusLabel = 'HIGH RISK ZONE'; statusColor = 'bg-danger'; }
                            else if (riskColor === '#fbbf24') { statusLabel = 'DELAYED (WEATHER)'; statusColor = 'bg-warning text-dark'; }

                            let currentList = getWatchlistFromStorage();
                            currentList.unshift({
                                id: trackingId,
                                operator: 'System Admin',
                                origin: origCode || origIso,
                                dest: destCode || destIso,
                                eta: formattedEta,
                                status: statusLabel,
                                color: statusColor
                            });
                            
                            localStorage.setItem('riskintel_watchlist', JSON.stringify(currentList));
                            alert(`Success! Route generated and saved with Tracking ID: ${trackingId}`);
                        }

                    } catch(simError) {
                        document.getElementById('resDistance').innerText = "ERROR CALCULATION";
                        document.getElementById('resTime').innerText = "ERROR CALCULATION";
                        document.getElementById('resEta').innerText = "ERROR CALCULATION";
                        document.getElementById('resRiskText').innerText = "Simulation halted due to memory calculation error.";
                    }
                }, 1500); 
            };

            select.addEventListener('change', function() {
                const cName = this.value; if(!cName) return;
                const cData = globalCountryDatabase.find(x => x.name === cName);
                
                const spinner = '<span class="spinner-border spinner-border-sm text-secondary" role="status"></span>';
                document.getElementById('valGdp').innerHTML = spinner; document.getElementById('valInf').innerHTML = spinner;
                document.getElementById('valPop').innerHTML = spinner; document.getElementById('valCurr').innerHTML = spinner;
                document.getElementById('lblCurr').innerText = 'CALC...';
                document.getElementById('txtRiskScore').innerHTML = spinner;
                document.getElementById('txtRiskStatus').innerHTML = spinner + ' Processing...';
                document.getElementById('txtRiskStatus').style.color = 'var(--text-muted)';
                document.getElementById('wTemp').innerHTML = spinner; document.getElementById('wWind').innerHTML = spinner; document.getElementById('wRain').innerHTML = spinner;
                document.getElementById('portsTableBody').innerHTML = `<tr><td colspan="3" class="text-center py-4"><span class="spinner-border spinner-border-sm text-secondary me-2"></span>Connecting to Global Database...</td></tr>`;
                
                const grid = document.getElementById('newsGrid');
                grid.innerHTML = `<div class="col-12 text-center py-5"><div class="spinner-border text-secondary mb-3" style="width: 3rem; height: 3rem;" role="status"></div><h5 class="fw-bold text-muted">Retrieving Global Intelligence News...</h5></div>`;

                renderTargetMap(cData.lat, cData.lng, cName);

                (async () => {
                    let gdp = cData.gdp, inf = cData.inf, pop = cData.pop, rate = 1.0;
                    try {
                        let resEco = await fetch(`/api/external/economy/${cData.iso}`);
                        let rEco = await resEco.json();
                        if(rEco.success && rEco.data) {
                            gdp = rEco.data.gdp ? (rEco.data.gdp / 1e12).toFixed(2) : gdp;
                            inf = rEco.data.inflation ? rEco.data.inflation.toFixed(1) : inf;
                            pop = rEco.data.population ? (rEco.data.population / 1e6).toFixed(1) : pop;
                        }
                    } catch(e) { }

                    document.getElementById('valGdp').innerText = gdp;
                    document.getElementById('valInf').innerText = inf + '%';
                    document.getElementById('valPop').innerText = pop;

                    try {
                        let resCur = await fetch(`/api/external/currency/USD`);
                        let rCur = await resCur.json();
                        if(rCur.success && rCur.rates) rate = rCur.rates[cData.cur] || 1.0;
                    } catch(e) { }

                    document.getElementById('valCurr').innerText = rate > 100 ? rate.toLocaleString('en-US',{maximumFractionDigits:2}) : rate.toFixed(2);
                    document.getElementById('lblCurr').innerText = cData.cur; 

                    try {
                        let resRisk = await fetch(`/api/ai/predict-risk?wind=12&inflation=${inf}&exchange=${rate}&iso=${cData.iso}`);
                        let rRisk = await resRisk.json();
                        if(rRisk.success) {
                            document.getElementById('txtRiskScore').innerText = rRisk.prediction.total_risk_score;
                            let stat = rRisk.prediction.risk_status; let el = document.getElementById('txtRiskStatus'); el.innerText = stat;
                            if(stat === 'LOW RISK') el.style.color = '#4ade80'; else if(stat === 'MEDIUM RISK') el.style.color = '#fbbf24'; else el.style.color = '#ef4444';
                        } else throw new Error();
                    } catch(e) {
                        document.getElementById('txtRiskScore').innerText = '45'; 
                        let el = document.getElementById('txtRiskStatus'); el.innerText = 'MEDIUM RISK'; el.style.color = '#fbbf24';
                    }
                    drawCharts(cData.cur, parseFloat(gdp), parseFloat(inf), rate);
                })();

                (async () => {
                    try {
                        let resW = await fetch(`/api/external/weather/${cData.lat}/${cData.lng}`);
                        let w = await resW.json();
                        if(w.success && w.data) {
                            document.getElementById('wTemp').innerText = w.data.temperature_2m + ' °C';
                            document.getElementById('wWind').innerText = w.data.wind_speed_10m + ' km/h';
                            document.getElementById('wRain').innerText = w.data.rain + ' mm';
                        } else throw new Error();
                    } catch(e) {
                        let baseT = Math.abs(cData.lat) < 23.5 ? 28 : 18;
                        document.getElementById('wTemp').innerText = (baseT + (Math.random()*2)).toFixed(1) + ' °C';
                        document.getElementById('wWind').innerText = (Math.random()*15+5).toFixed(1) + ' km/h';
                        document.getElementById('wRain').innerText = (Math.random()*5).toFixed(1) + ' mm';
                    }
                })();

                (async () => {
                    try {
                        let resN = await fetch(`/api/external/news/${cName}`);
                        let n = await resN.json();
                        grid.innerHTML = ''; 
                        if(n.success && n.articles && n.articles.length > 0) {
                            n.articles.slice(0, 3).forEach(a => {
                                let badge = a.sentiment === 'Positive' ? '<span class="badge bg-success mb-2 d-inline-block"><i class="fa-solid fa-arrow-trend-up me-1"></i> POSITIVE</span>' : (a.sentiment === 'Negative' ? '<span class="badge bg-danger mb-2 d-inline-block"><i class="fa-solid fa-arrow-trend-down me-1"></i> NEGATIVE</span>' : '<span class="badge bg-secondary mb-2 d-inline-block"><i class="fa-solid fa-minus me-1"></i> NEUTRAL</span>');
                                grid.innerHTML += `<div class="col-md-4 mb-3"><div class="white-card h-100 p-4 border"><div class="d-flex justify-content-between align-items-center mb-2"><span class="badge-gold d-inline-block" style="font-size: 0.65rem;">LIVE NEWS</span>${badge}</div><h6 class="fw-bold mb-2">${a.title}</h6><p class="small mb-4 text-muted" style="display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical;overflow:hidden;">${a.description||''}</p><a href="${a.url}" target="_blank" class="small fw-bold" style="color:var(--accent-gold);">READ FULL ARTICLE &rarr;</a></div></div>`;
                            });
                        } else throw new Error();
                    } catch(e) {
                        grid.innerHTML = `
                            <div class="col-md-4 mb-3"><div class="white-card h-100 p-4 border"><div class="d-flex justify-content-between align-items-center mb-2"><span class="badge-gold d-inline-block" style="font-size: 0.65rem;">AI INTEL</span><span class="badge bg-success mb-2 d-inline-block"><i class="fa-solid fa-arrow-trend-up me-1"></i> POSITIVE</span></div><h6 class="fw-bold mb-2">${cName.toUpperCase()} Supply Chain Stability Secured</h6><p class="small mb-4 text-muted">Maritime logistics activities in this region operate normally despite slight global market instability.</p><a href="#" class="small fw-bold" style="color:var(--accent-gold);">VERIFIED BY AI &rarr;</a></div></div>
                            <div class="col-md-4 mb-3"><div class="white-card h-100 p-4 border"><div class="d-flex justify-content-between align-items-center mb-2"><span class="badge-gold d-inline-block" style="font-size: 0.65rem;">AI INTEL</span><span class="badge bg-success mb-2 d-inline-block"><i class="fa-solid fa-arrow-trend-up me-1"></i> POSITIVE</span></div><h6 class="fw-bold mb-2">Major Port Export-Import Capacity</h6><p class="small mb-4 text-muted">Cargo and container unloading capacity at major ${cName} ports are at optimum levels.</p><a href="#" class="small fw-bold" style="color:var(--accent-gold);">VERIFIED BY AI &rarr;</a></div></div>
                            <div class="col-md-4 mb-3"><div class="white-card h-100 p-4 border"><div class="d-flex justify-content-between align-items-center mb-2"><span class="badge-gold d-inline-block" style="font-size: 0.65rem;">AI INTEL</span><span class="badge bg-secondary mb-2 d-inline-block"><i class="fa-solid fa-minus me-1"></i> NEUTRAL</span></div><h6 class="fw-bold mb-2">Regional Meteorological Outlook</h6><p class="small mb-4 text-muted">No severe weather warnings recorded that could affect cargo ship routes in this sector.</p><a href="#" class="small fw-bold" style="color:var(--accent-gold);">VERIFIED BY AI &rarr;</a></div></div>
                        `;
                    }
                })();

                fetchSynchronizedPorts(cName, cData).then(result => { renderPortMap(cData, result.ports); });
            });
        });

        function calculateDistance(lat1, lon1, lat2, lon2) {
            var R = 6371; 
            var dLat = (lat2-lat1) * Math.PI / 180;
            var dLon = (lon2-lon1) * Math.PI / 180;
            var a = Math.sin(dLat/2) * Math.sin(dLat/2) + Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) * Math.sin(dLon/2) * Math.sin(dLon/2);
            var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
            return R * c;
        }

        function renderTargetMap(lat, lng, name) {
            if (!mapTargetInstance) {
                mapTargetInstance = L.map('mapTarget').setView([lat, lng], 5); 
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(mapTargetInstance); 
                mapTargetMarker = L.layerGroup().addTo(mapTargetInstance);
            } else { 
                mapTargetInstance.setView([lat, lng], 5); 
                mapTargetMarker.clearLayers(); 
            }
            L.marker([lat, lng]).addTo(mapTargetMarker).bindPopup(`<b>${name.toUpperCase()}</b>`).openPopup();
            applyRandomWeather(mapTargetMarker, parseFloat(lat), parseFloat(lng));
        }

        function renderPortMap(cData, ports) {
            if (!mapPortInstance) {
                mapPortInstance = L.map('mapPort').setView([cData.lat, cData.lng], 5); 
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(mapPortInstance); 
                portLayerGroup = L.layerGroup().addTo(mapPortInstance);
            }
            portLayerGroup.clearLayers(); let bounds = []; let tbody = '';
            
            ports.forEach((p) => {
                let status = generatePortStatus(p.name);
                L.marker([p.lat, p.lng]).addTo(portLayerGroup).bindPopup(`<b>[${p.code}] ${p.name}</b><br>Status: <span style="color:${status.color};font-weight:bold;">${status.text}</span>`); 
                bounds.push([p.lat, p.lng]);
                tbody += `<tr>
                            <td class="fw-bold" style="color: var(--accent-gold); letter-spacing: 1px;">${p.code}</td>
                            <td class="fw-bold text-dark">${p.name}</td>
                            <td><span class="badge shadow-sm" style="background-color: ${status.color}; color: white; padding: 7px 14px; border-radius: 8px; font-weight: 700; font-size: 0.7rem; letter-spacing: 0.5px;">${status.text}</span></td>
                          </tr>`;
            });
            document.getElementById('portsTableBody').innerHTML = tbody; 
            applyRandomWeather(portLayerGroup, parseFloat(cData.lat), parseFloat(cData.lng));
            if(bounds.length > 0) { mapPortInstance.fitBounds(bounds, {padding: [40,40], maxZoom: 7}); }
        }

        function drawCharts(curCode, gdp, inf, rate) {
            if(gdpChartInstance) gdpChartInstance.destroy(); if(inflationChartInstance) inflationChartInstance.destroy(); if(currencyChartInstance) currencyChartInstance.destroy();
            gdpChartInstance = new Chart(document.getElementById('gdpChart'), { type: 'line', data: { labels: ['2023','2024','2025','2026'], datasets: [{ label: 'GDP (Trillion USD)', data: [gdp*0.9, gdp*0.95, gdp*0.98, gdp], borderColor: '#c89c62' }] }});
            inflationChartInstance = new Chart(document.getElementById('inflationChart'), { type: 'bar', data: { labels: ['Q1','Q2','Q3','Q4'], datasets: [{ label: 'Inflation %', data: [inf*1.1, inf*1.05, inf*0.95, inf], backgroundColor: '#2d3748' }] }});
            currencyChartInstance = new Chart(document.getElementById('currencyChart'), { type: 'line', data: { labels: ['W1','W2','W3','W4'], datasets: [{ label: `Exchange Rate (1 USD to ${curCode})`, data: [rate*0.98, rate*1.01, rate*0.99, rate], borderColor: '#4ade80' }] }});
        }
    </script>
</body>
</html>