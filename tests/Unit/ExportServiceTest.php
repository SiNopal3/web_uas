<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\ExportService;
use App\Models\ExportLog;
use App\Models\ReportHistory;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExportServiceTest extends TestCase
{
    use RefreshDatabase;

    protected ExportService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(ExportService::class);
    }

    public function test_process_export_pdf_returns_html_and_logs_audit_event()
    {
        $data = [
            'headers' => ['Indicator', 'Value'],
            'items' => [['Risk Score', '65/100']],
            'summary' => 'Test summary'
        ];

        $result = $this->service->processExport('PDF', 'Executive Report', 'Test Profile', $data);

        $this->assertTrue($result['success']);
        $this->assertEquals('PDF', $result['export_format']);
        $this->assertArrayHasKey('html_content', $result);
        $this->assertDatabaseHas('export_logs', [
            'report_type' => 'Executive Report',
            'format' => 'PDF',
            'status' => 'SUCCESS'
        ]);
        $this->assertDatabaseHas('report_history', [
            'report_type' => 'Executive Report',
            'title' => 'Test Profile',
            'file_format' => 'PDF'
        ]);
    }

    public function test_process_export_excel_and_csv_generate_spreadsheet_package()
    {
        $data = [
            'headers' => ['Metric', 'Status'],
            'items' => [['Uptime', '99.9%']]
        ];

        $excelResult = $this->service->processExport('EXCEL', 'System Health Report', 'Uptime Profile', $data);
        $this->assertTrue($excelResult['success']);
        $this->assertArrayHasKey('excel_html', $excelResult);

        $csvResult = $this->service->processExport('CSV', 'System Health Report', 'Uptime Profile', $data);
        $this->assertTrue($csvResult['success']);
        $this->assertArrayHasKey('csv_content', $csvResult);
    }

    public function test_process_export_invalid_format_throws_or_returns_failed_status()
    {
        $result = $this->service->processExport('UNKNOWN_FORMAT', 'Test Report', 'Test', []);
        
        $this->assertFalse($result['success']);
        $this->assertArrayHasKey('error', $result);
    }
}
