<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// Rute Autentikasi
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

use App\Http\Controllers\ExecutiveDashboardController;
use App\Http\Controllers\PredictionController;
use App\Http\Controllers\DecisionSupportController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PortController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\SystemController;
use App\Http\Controllers\AuditController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\MaritimeRouteController;

use Illuminate\Support\Facades\Auth;

// Rute Beranda Utama (Tampilkan halaman welcome dengan tombol login/register di ujung kanan jika guest)
Route::get('/', function () {
    if (Auth::check()) {
        return view('dashboard');
    }
    return view('welcome');
})->name('home');

// Rute untuk menampilkan halaman modular - dilindungi middleware auth
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    Route::get('/ports', function () {
        return view('ports');
    });
    Route::get('/news-sentiment', function () {
        return view('news_sentiment');
    });
    Route::get('/risk-simulator', function () {
        return view('risk_simulator');
    });
    Route::get('/watchlist', function () {
        return view('watchlist');
    });

    // Maritime Route & Delay Simulator routes
    Route::get('/maritime-route', [MaritimeRouteController::class, 'index'])->name('maritime-route.index');
    Route::post('/api/maritime/simulate', [MaritimeRouteController::class, 'simulate'])->name('maritime-route.simulate');

    // Executive Dashboard modular routes
    Route::get('/executive', [ExecutiveDashboardController::class, 'index'])->name('executive.index');
    Route::get('/api/executive/data', [ExecutiveDashboardController::class, 'getData'])->name('executive.data');

    // Enterprise Prediction Dashboard modular routes
    Route::get('/prediction', [PredictionController::class, 'index'])->name('prediction.index');
    Route::get('/api/prediction/data', [PredictionController::class, 'getData'])->name('prediction.data');

    // AI Decision Support Center modular routes
    Route::get('/decision-support', [DecisionSupportController::class, 'index'])->name('decision-support.index');
    Route::get('/api/decision-support/data', [DecisionSupportController::class, 'getData'])->name('decision-support.data');

    // Business Intelligence Analytics Center modular routes
    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics.index');
    Route::get('/api/analytics/data', [AnalyticsController::class, 'getData'])->name('analytics.data');

    // Data Visualization Dashboard routes
    Route::get('/data-visualization', [AnalyticsController::class, 'dataVisualization'])->name('data-visualization.index');
    Route::get('/api/data-visualization/charts', [AnalyticsController::class, 'getChartData'])->name('data-visualization.charts');

    // Smart Notification & Alert Center modular routes
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/api/notifications', [NotificationController::class, 'getData'])->name('notifications.data');
    Route::post('/api/notifications/read/{id}', [NotificationController::class, 'markRead'])->name('notifications.read');
    Route::post('/api/notifications/read-all', [NotificationController::class, 'markAllRead'])->name('notifications.read-all');
    Route::delete('/api/notifications/clear', [NotificationController::class, 'clear'])->name('notifications.clear');
    Route::delete('/api/notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');

    // Enterprise Reporting & Export Suite modular routes
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/executive', [ReportController::class, 'executiveReports'])->name('reports.executive');
    Route::get('/reports/analytics', [ReportController::class, 'analyticsReports'])->name('reports.analytics');
    Route::get('/reports/system', [ReportController::class, 'systemReports'])->name('reports.system');
    Route::get('/api/reports', [ReportController::class, 'getApiReports'])->name('api.reports');
    Route::post('/api/reports/scheduled', [ReportController::class, 'storeScheduledReport'])->name('api.reports.scheduled.store');

    // Export Center routes
    Route::post('/export/pdf', [ExportController::class, 'exportPdf'])->name('export.pdf');
    Route::post('/export/excel', [ExportController::class, 'exportExcel'])->name('export.excel');
    Route::post('/export/csv', [ExportController::class, 'exportCsv'])->name('export.csv');
    Route::post('/export/png', [ExportController::class, 'exportPng'])->name('export.png');
    Route::post('/export/print', [ExportController::class, 'exportPrint'])->name('export.print');
});

// Enterprise Administration & System Monitoring modular routes - protected by auth & admin middleware
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
    Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users');
    Route::get('/admin/system', [SystemController::class, 'index'])->name('admin.system');
    Route::get('/admin/logs', [AuditController::class, 'index'])->name('admin.logs');
    Route::match(['get', 'post', 'put'], '/admin/settings', [AdminController::class, 'settings'])->name('admin.settings');

    // AJAX API routes for Enterprise Administration
    Route::get('/api/admin/dashboard', [AdminController::class, 'getDashboardData'])->name('api.admin.dashboard');
    
    // User management routes
    Route::get('/api/admin/users-list', [UserController::class, 'getUsersData'])->name('api.admin.users-list');
    Route::get('/api/admin/users/{id}', [UserController::class, 'show'])->name('api.admin.users.show');
    Route::post('/admin/users', [UserController::class, 'store'])->name('admin.users.store');
    Route::put('/admin/users/{id}', [UserController::class, 'update'])->name('admin.users.update');
    Route::patch('/admin/users/{id}/status', [UserController::class, 'updateStatus'])->name('admin.users.update-status');
    Route::delete('/admin/users/{id}', [UserController::class, 'destroy'])->name('admin.users.destroy');

    // Port dataset management routes
    Route::get('/api/admin/ports-list', [PortController::class, 'index'])->name('api.admin.ports-list');
    Route::get('/api/admin/ports/{id}', [PortController::class, 'show'])->name('api.admin.ports.show');
    Route::post('/admin/ports', [PortController::class, 'store'])->name('admin.ports.store');
    Route::put('/admin/ports/{id}', [PortController::class, 'update'])->name('admin.ports.update');
    Route::delete('/admin/ports/{id}', [PortController::class, 'destroy'])->name('admin.ports.destroy');

    // Analysis articles management routes
    Route::get('/api/admin/articles-list', [ArticleController::class, 'index'])->name('api.admin.articles-list');
    Route::get('/api/admin/articles/{id}', [ArticleController::class, 'show'])->name('api.admin.articles.show');
    Route::post('/admin/articles', [ArticleController::class, 'store'])->name('admin.articles.store');
    Route::put('/admin/articles/{id}', [ArticleController::class, 'update'])->name('admin.articles.update');
    Route::delete('/admin/articles/{id}', [ArticleController::class, 'destroy'])->name('admin.articles.destroy');

    Route::get('/api/admin/system', [SystemController::class, 'getData'])->name('api.admin.system');
    Route::get('/api/admin/audit-list', [AuditController::class, 'getData'])->name('api.admin.audit-list');
});