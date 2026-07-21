<?php

namespace App\Http\Controllers;

use App\Services\ExportService;
use App\Services\ReportingService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class ExportController extends Controller
{
    protected ExportService $exportService;
    protected ReportingService $reportingService;

    public function __construct(ExportService $exportService, ReportingService $reportingService)
    {
        $this->exportService = $exportService;
        $this->reportingService = $reportingService;
    }

    /**
     * Export report to corporate PDF structure (`POST /export/pdf`).
     */
    public function exportPdf(Request $request): JsonResponse|Response
    {
        return $this->handleExport($request, 'PDF');
    }

    /**
     * Export report to multi-sheet Excel format (`POST /export/excel`).
     */
    public function exportExcel(Request $request): JsonResponse|Response
    {
        return $this->handleExport($request, 'EXCEL');
    }

    /**
     * Export report to RFC 4180 CSV (`POST /export/csv`).
     */
    public function exportCsv(Request $request): JsonResponse|Response
    {
        return $this->handleExport($request, 'CSV');
    }

    /**
     * Export report layout or chart to PNG (`POST /export/png`).
     */
    public function exportPng(Request $request): JsonResponse
    {
        $reportType = $request->input('report_type', 'Executive Report');
        $title = $request->input('title', $reportType . ' Export');
        $data = $request->input('data', []);
        $filters = $request->all();

        $package = $this->exportService->processExport('PNG', $reportType, $title, $data, $filters, $request);

        return response()->json($package);
    }

    /**
     * Export report for clean corporate print view (`POST /export/print`).
     */
    public function exportPrint(Request $request): JsonResponse|Response
    {
        return $this->handleExport($request, 'PRINT');
    }

    /**
     * Shared handler delegating to ExportService and formatting output response.
     */
    protected function handleExport(Request $request, string $format): JsonResponse|Response
    {
        $reportType = $request->input('report_type', 'Executive Report');
        $title = $request->input('title', $reportType . ' Export');
        $filters = $request->all();
        
        // If specific data payload provided via POST, use it; otherwise fetch from ReportingService
        $data = $request->input('data');
        if (empty($data) || !is_array($data)) {
            $data = $this->reportingService->getReportData($reportType, $filters);
        }

        $package = $this->exportService->processExport($format, $reportType, $title, $data, $filters, $request);

        // If AJAX request or JSON explicitly asked, return JSON package with content payload
        if ($request->wantsJson() || $request->ajax() || $request->input('return_json', true)) {
            return response()->json($package);
        }

        // Otherwise return raw downloadable file response
        if ($format === 'CSV' && isset($package['csv_content'])) {
            return response($package['csv_content'], 200, [
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => 'attachment; filename="' . ($package['file_name'] ?? 'report.csv') . '"',
            ]);
        }

        if (($format === 'EXCEL') && isset($package['excel_html'])) {
            return response($package['excel_html'], 200, [
                'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
                'Content-Disposition' => 'attachment; filename="' . ($package['excel_file_name'] ?? 'report.xls') . '"',
            ]);
        }

        if (($format === 'PDF' || $format === 'PRINT') && isset($package['html_content'])) {
            return response($package['html_content'], 200, [
                'Content-Type' => 'text/html; charset=UTF-8',
            ]);
        }

        return response()->json($package);
    }
}
