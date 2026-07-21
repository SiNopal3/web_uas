<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use App\Services\SystemHealthService;
use App\Services\AdminService;

class SystemController extends Controller
{
    protected SystemHealthService $systemHealthService;
    protected AdminService $adminService;

    public function __construct(SystemHealthService $systemHealthService, AdminService $adminService)
    {
        $this->systemHealthService = $systemHealthService;
        $this->adminService = $adminService;
    }

    /**
     * Tampilkan halaman System Health & Monitoring (`/admin/system`).
     */
    public function index(): View
    {
        $kpi = $this->adminService->getDashboardKpiCards();
        $summary = $this->adminService->generateAutomaticDashboardSummary();
        $settings = $this->adminService->getSystemSettings();

        return view('admin.index', compact('kpi', 'summary', 'settings'));
    }

    /**
     * Endpoint AJAX JSON (`/api/admin/system`) untuk seluruh telemetri sistem real-time.
     */
    public function getData(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => [
                'health' => $this->systemHealthService->getSystemHealth(),
                'api_monitoring' => $this->systemHealthService->getApiMonitoring(),
                'database_monitoring' => $this->systemHealthService->getDatabaseMonitoring(),
                'application_logs' => $this->systemHealthService->getApplicationLogs($request->input('limit', 40), $request->input('search', '')),
                'backup_center' => $this->systemHealthService->getBackupCenter(),
                'security_center' => $this->systemHealthService->getSecurityCenter(),
                'timestamp' => now()->format('Y-m-d H:i:s'),
            ]
        ]);
    }
}
