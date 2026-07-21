<?php

namespace App\Services;

use App\Models\ExportLog;
use App\Models\ReportHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExportService
{
    protected PdfReportService $pdfService;
    protected ExcelReportService $excelService;

    public function __construct(PdfReportService $pdfService, ExcelReportService $excelService)
    {
        $this->pdfService = $pdfService;
        $this->excelService = $excelService;
    }

    /**
     * Process an export request, log audit events, and return the formatted package.
     */
    public function processExport(string $format, string $reportType, string $title, array $data, array $filters = [], ?Request $request = null): array
    {
        $startTime = microtime(true);
        $status = 'SUCCESS';
        $result = [];

        try {
            $formatUpper = strtoupper(trim($format));

            switch ($formatUpper) {
                case 'PDF':
                case 'PRINT':
                    $result = $this->pdfService->generatePdfPackage($title, $reportType, $data, $filters);
                    $result['export_format'] = $formatUpper;
                    break;
                case 'EXCEL':
                case 'CSV':
                    $result = $this->excelService->generateExcelPackage($title, $reportType, $data, $filters);
                    $result['export_format'] = $formatUpper;
                    break;
                case 'PNG':
                    $result = [
                        'success' => true,
                        'report_type' => $reportType,
                        'title' => $title,
                        'export_format' => 'PNG',
                        'generated_at' => now()->format('Y-m-d H:i:s'),
                        'file_name' => strtolower(preg_replace('/[^a-zA-Z0-9]+/', '_', $title)) . '_' . now()->format('Ymd_His') . '.png',
                        'image_payload' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+A8AAQUBAScY42YAAAAASUVORK5CYII=', // Placeholder for JS canvas snapshot
                        'instructions' => 'Frontend will capture Chart.js canvas layout as PNG high-res bitmap.',
                    ];
                    break;
                default:
                    throw new \InvalidArgumentException("Unsupported export format: {$format}");
            }

            // Estimate file size in KB
            $sizeKb = 0;
            if (isset($result['html_content'])) {
                $sizeKb = round(strlen($result['html_content']) / 1024, 2);
            } elseif (isset($result['csv_content'])) {
                $sizeKb = round(strlen($result['csv_content']) / 1024, 2);
            } else {
                $sizeKb = 12.5;
            }
            $result['file_size_kb'] = max(1.0, $sizeKb);

        } catch (\Exception $e) {
            $status = 'FAILED';
            $result = [
                'success' => false,
                'error' => $e->getMessage(),
                'export_format' => strtoupper($format),
            ];
        }

        $executionTimeMs = round((microtime(true) - $startTime) * 1000, 2);

        // Record audit log for security & monitoring
        $this->logExportEvent($format, $reportType, $status, $executionTimeMs, $request);

        // Record in Report History if successful
        if ($status === 'SUCCESS') {
            $this->recordHistory($title, $reportType, strtoupper($format), $result['file_size_kb'] ?? 10.0, $filters);
        }

        return $result;
    }

    /**
     * Log every export action to export_logs for security audit.
     */
    protected function logExportEvent(string $format, string $reportType, string $status, float $executionTimeMs, ?Request $request = null): void
    {
        try {
            ExportLog::create([
                'user_id' => Auth::check() ? Auth::id() : null,
                'user_name' => Auth::check() ? Auth::user()->name : 'System / Guest',
                'report_type' => substr($reportType, 0, 100),
                'format' => substr(strtoupper($format), 0, 20),
                'ip_address' => $request ? substr((string)$request->ip(), 0, 45) : '127.0.0.1',
                'user_agent' => $request ? substr((string)$request->userAgent(), 0, 500) : 'CLI / System Job',
                'status' => $status,
                'execution_time_ms' => $executionTimeMs,
            ]);
        } catch (\Exception $e) {
            // Safe fallback if table issue arises
        }
    }

    /**
     * Record report generation into report_history table.
     */
    protected function recordHistory(string $title, string $reportType, string $format, float $fileSizeKb, array $filters = []): void
    {
        try {
            ReportHistory::create([
                'user_id' => Auth::check() ? Auth::id() : null,
                'user_name' => Auth::check() ? Auth::user()->name : 'Enterprise Administrator',
                'report_type' => substr($reportType, 0, 100),
                'title' => substr($title, 0, 255),
                'file_format' => substr($format, 0, 20),
                'file_size_kb' => $fileSizeKb,
                'download_count' => 1,
                'parameters' => $filters,
            ]);
        } catch (\Exception $e) {
            // Safe fallback
        }
    }
}
