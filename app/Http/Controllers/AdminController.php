<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use App\Services\AdminService;
use App\Services\SystemHealthService;
use App\Services\AuditLogService;
use App\Services\UserManagementService;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    protected AdminService $adminService;
    protected SystemHealthService $systemHealthService;
    protected AuditLogService $auditLogService;
    protected UserManagementService $userManagementService;

    public function __construct(
        AdminService $adminService,
        SystemHealthService $systemHealthService,
        AuditLogService $auditLogService,
        UserManagementService $userManagementService
    ) {
        $this->adminService = $adminService;
        $this->systemHealthService = $systemHealthService;
        $this->auditLogService = $auditLogService;
        $this->userManagementService = $userManagementService;
    }

    /**
     * Tampilkan halaman utama Enterprise Administration & System Monitoring (`/admin`).
     */
    public function index(): View
    {
        $kpi = $this->adminService->getDashboardKpiCards();
        $summary = $this->adminService->generateAutomaticDashboardSummary();
        $settings = $this->adminService->getSystemSettings();

        return view('admin.index', compact('kpi', 'summary', 'settings'));
    }

    /**
     * Tampilkan atau perbarui System Settings (`/admin/settings`).
     */
    public function settings(Request $request): View|JsonResponse
    {
        if ($request->isMethod('POST') || $request->isMethod('PUT')) {
            $updated = $this->adminService->updateSystemSettings($request->all(), Auth::user());
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => true, 'data' => $updated]);
            }
            return redirect()->route('admin.settings')->with('status', 'Konfigurasi sistem berhasil diperbarui.');
        }

        $kpi = $this->adminService->getDashboardKpiCards();
        $summary = $this->adminService->generateAutomaticDashboardSummary();
        $settings = $this->adminService->getSystemSettings();

        return view('admin.index', compact('kpi', 'summary', 'settings'));
    }

    /**
     * Endpoint AJAX JSON untuk telemetri dasbor administrasi.
     */
    public function getDashboardData(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'kpi' => $this->adminService->getDashboardKpiCards(),
            'summary' => $this->adminService->generateAutomaticDashboardSummary(),
            'settings' => $this->adminService->getSystemSettings(),
        ]);
    }
}
