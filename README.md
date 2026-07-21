# RiskIntel Hub — Global Supply Chain Risk Intelligence Platform

> Enterprise-grade supply chain risk monitoring platform built with Laravel 11, featuring real-time maritime data, economic analytics, AI-powered risk scoring, and interactive geospatial visualization.

---

## Tech Stack

| Layer | Technology |
|---|---|
| Backend | Laravel 11 (PHP 8.2+) |
| Frontend | Bootstrap 5.3, Chart.js 4.4, Leaflet.js 1.9 |
| Database | MySQL |
| Map Tiles | CartoDB Light (Leaflet) |
| External APIs | Open-Meteo, World Bank APIv2, ExchangeRate-API, GNews, NGA World Port Index |
| AI Engine | PHP Rule-Based Lexicon Sentiment & Weighted Risk Scoring |
| Auth | Laravel Session Auth |

---

## Features

### 1. Global Country Dashboard
Real-time monitoring dashboard for all 195 sovereign nations.
- Country search & selector (195 countries with ISO, currency, region)
- Live maritime weather card (Open-Meteo API)
- Economic metrics card (World Bank API — GDP, Inflation, Population)
- Currency exchange rate card (ExchangeRate-API)
- AI Supply Chain Risk Score (deterministic weighted engine)
- Quick navigation cards to all features

### 2. Maritime Weather & Port Hub
Interactive map for port locations and weather monitoring.
- Leaflet.js map with light tile layer
- NGA World Port Index integration
- Local MySQL port database fallback
- Real-time maritime weather overlay per country

### 3. Route & Delay Simulation
Sea freight route simulation with risk-adjusted delay calculation.
- Origin & destination country/port selector (65+ maritime nations)
- Geodesic dashed polyline route on Leaflet map
- Risk Engine Sync — auto-connects weather, geopolitics, congestion, FX scores
- Delay penalty calculation with breakdown analysis
- Mitigation recommendations per delay category
- ETA estimation (base transit + delay days)

### 4. Currency Impact Dashboard
Real-time FX monitoring for 195 currencies.
- Multi-period chart (30 days, 90 days, 6 months, 1 year)
- Live exchange rate from ExchangeRate-API
- Currency strength index visualization
- FX volatility trend analysis per country

### 5. News Intelligence
Supply chain news feed with AI sentiment analysis.
- GNews API integration (live logistics & shipping news)
- Admin article management (internal analysis posts)
- Native PHP Lexicon Sentiment Engine (positive/negative word dictionary)
- Per-article sentiment classification (Positive / Neutral / Negative)
- Manual sentiment tester — paste any text for instant AI analysis

### 6. Data Visualization Dashboard
Quarterly macro-economic trend charts per country.
- GDP Trend (quarterly growth vs national target)
- Inflation Trend (actual CPI vs central bank target 2.5%)
- Currency Trend (FX stability index vs market volatility)
- Risk Trend (composite risk score, port logistics risk, financial & weather risk)
- All charts update dynamically on country selection

### 7. Country Comparison Engine
Side-by-side comparison of 2 countries across 5 fundamental parameters.
- GDP & Growth (economic scale, growth rate)
- Inflation & Price Stability (CPI rate, shock index score)
- Supply Chain Risk (composite score, maritime & financial sub-scores)
- Weather & Port Conditions (condition, wind speed, weather score)
- Currency & FX Volatility (currency code, volatility risk, trend)
- Delta spread analysis with winner determination per category
- Supports all 195 sovereign nations

### 8. Favorite Monitoring List (Watchlist)
Persistent watchlist for tracked countries.
- Add/remove countries to personal watchlist
- Per-country mini dashboard (weather, economy, currency, risk score)
- Data stored securely in MySQL database per user account

### 9. Admin Dashboard
Full content management for administrators.
- Article/news management (create, edit, delete)
- User management (create, assign roles)
- Port database management
- Risk score history viewer
- System settings

---

## Installation

### Requirements
- PHP 8.2+
- Composer
- MySQL 8.0+
- Node.js (optional, for asset compilation)

### Steps

```bash
# 1. Clone repository
git clone https://github.com/yourusername/web_uas.git
cd web_uas

# 2. Install PHP dependencies
composer install

# 3. Copy environment file
cp .env.example .env

# 4. Generate application key
php artisan key:generate

# 5. Configure database in .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_supply_chain
DB_USERNAME=root
DB_PASSWORD=

# 6. Run migrations
php artisan migrate --seed

# 7. Create storage symlink
php artisan storage:link

# 8. Start development server
php artisan serve
```

### Third Party API Keys (.env)

```env
EXCHANGERATE_API_KEY=your_key_here
GNEWS_API_KEY=your_key_here
```

Get free keys at:
- ExchangeRate: https://www.exchangerate-api.com
- GNews: https://gnews.io

---

## Deployment (Railway)

1. Push code to GitHub (ensure `.env` is in `.gitignore`)
2. Create new project on [Railway](https://railway.app)
3. Connect GitHub repository
4. Add MySQL service in Railway
5. Set environment variables in Railway dashboard
6. Run `php artisan migrate` via Railway console
7. App auto-deploys on every `git push`

**Important before deploying:**
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.railway.app
```

---

## Project Structure

```
app/
├── Http/Controllers/     # Feature controllers
├── Models/               # Eloquent models
└── Services/             # Business logic layer
    ├── AnalyticsService.php
    ├── PredictionService.php
    ├── RiskScoringService.php
    ├── LexiconSentimentService.php
    └── ...
public/js/                # Frontend JS modules
resources/views/          # Blade templates
```

---

## License

This project is developed as a Final Exam (UAS) submission.  
© 2026 RiskIntel Hub. All Rights Reserved.
