<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use App\Services\AuditLogService;
use App\Services\AdminService;

class AuditController extends Controller
{
    protected AuditLogService $auditLogService;
    protected AdminService $adminService;

    public function __construct(AuditLogService $auditLogService, AdminService $adminService)
    {
        $this->auditLogService = $auditLogService;
        $this->adminService = $adminService;
    }

    /**
     * Tampilkan halaman jejak aktivitas audit (`/admin/logs`).
     */
    public function index(Request $request): View
    {
        $logsData = $this->auditLogService->getLogs($request->all());
        $kpi = $this->adminService->getDashboardKpiCards();
        $summary = $this->adminService->generateAutomaticDashboardSummary();
        $settings = $this->adminService->getSystemSettings();

        return view('admin.index', compact('logsData', 'kpi', 'summary', 'settings'));
    }

    /**
     * Endpoint AJAX JSON untuk daftar log dengan filter dan pencarian debounced.
     */
    public function getData(Request $request): JsonResponse
    {
        $logsData = $this->auditLogService->getLogs($request->all());
        return response()->json([
            'success' => true,
            'data' => $logsData,
        ]);
    }
}
