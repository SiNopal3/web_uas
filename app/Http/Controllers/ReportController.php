<?php

namespace App\Http\Controllers;

use App\Services\ReportingService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class ReportController extends Controller
{
    protected ReportingService $reportingService;

    public function __construct(ReportingService $reportingService)
    {
        $this->reportingService = $reportingService;
    }

    /**
     * Display the Enterprise Reporting & Export Suite dashboard (`/reports`).
     */
    public function index(): View
    {
        $summary = $this->reportingService->getDashboardSummary();
        return view('reports.index', compact('summary'));
    }

    /**
     * Display or return Executive Reports data (`/reports/executive`).
     */
    public function executiveReports(Request $request): JsonResponse|View
    {
        $filters = $request->all();
        $data = $this->reportingService->getReportData('Executive Report', $filters);
        $narrative = $this->reportingService->generateRuleBasedExecutiveNarrative($filters);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'data' => $data,
                'narrative' => $narrative
            ]);
        }

        return view('reports.index', ['section' => 'executive', 'data' => $data, 'narrative' => $narrative]);
    }

    /**
     * Display or return Analytics Reports data (`/reports/analytics`).
     */
    public function analyticsReports(Request $request): JsonResponse|View
    {
        $filters = $request->all();
        $data = $this->reportingService->getReportData('Analytics Report', $filters);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        }

        return view('reports.index', ['section' => 'analytics', 'data' => $data]);
    }

    /**
     * Display or return System Health Reports data (`/reports/system`).
     */
    public function systemReports(Request $request): JsonResponse|View
    {
        $filters = $request->all();
        $data = $this->reportingService->getReportData('System Health Report', $filters);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        }

        return view('reports.index', ['section' => 'system', 'data' => $data]);
    }

    /**
     * AJAX endpoint for comprehensive telemetry, filtering, builder, and history (`/api/reports`).
     */
    public function getApiReports(Request $request): JsonResponse
    {
        $action = $request->get('action', 'dashboard');
        $filters = $request->all();

        if ($action === 'report_data') {
            $type = $request->get('report_type', 'Executive Report');
            return response()->json([
                'success' => true,
                'report_type' => $type,
                'data' => $this->reportingService->getReportData($type, $filters)
            ]);
        }

        if ($action === 'build_custom') {
            return response()->json([
                'success' => true,
                'custom_report' => $this->reportingService->buildCustomReport($filters)
            ]);
        }

        if ($action === 'narrative') {
            return response()->json([
                'success' => true,
                'narrative' => $this->reportingService->generateRuleBasedExecutiveNarrative($filters)
            ]);
        }

        if ($action === 'charts_gallery') {
            return response()->json([
                'success' => true,
                'charts' => $this->reportingService->getChartsGalleryData()
            ]);
        }

        if ($action === 'scheduled_list') {
            return response()->json([
                'success' => true,
                'scheduled_reports' => $this->reportingService->getScheduledReportsList()
            ]);
        }

        if ($action === 'history_list') {
            return response()->json([
                'success' => true,
                'history' => $this->reportingService->getHistoryList($filters)
            ]);
        }

        // Default: return dashboard summary + charts gallery + history
        return response()->json([
            'success' => true,
            'summary' => $this->reportingService->getDashboardSummary(),
            'charts_gallery' => $this->reportingService->getChartsGalleryData(),
            'scheduled_reports' => $this->reportingService->getScheduledReportsList(),
            'history' => $this->reportingService->getHistoryList($filters),
            'executive_narrative' => $this->reportingService->generateRuleBasedExecutiveNarrative($filters),
        ]);
    }

    /**
     * Store new scheduled report via AJAX (`POST /api/reports/scheduled`).
     */
    public function storeScheduledReport(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'report_type' => 'required|string',
            'frequency' => 'required|string',
            'recipients' => 'nullable|string',
        ]);

        $item = $this->reportingService->storeScheduledReport($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Scheduled report successfully created.',
            'data' => $item
        ]);
    }
}
