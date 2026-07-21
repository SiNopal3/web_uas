<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;

class PdfReportService
{
    /**
     * Generate structured PDF package and print-ready HTML for the given report data.
     */
    public function generatePdfPackage(string $title, string $reportType, array $data, array $filters = []): array
    {
        $userName = Auth::check() ? Auth::user()->name : 'Enterprise Administrator';
        $timestamp = now()->setTimezone('Asia/Jakarta')->format('d M Y, h:i:s A');
        $htmlContent = $this->generateHtmlDocument($title, $reportType, $data, $filters, $userName, $timestamp);

        return [
            'success' => true,
            'report_type' => $reportType,
            'title' => $title,
            'generated_at' => $timestamp,
            'generated_by' => $userName,
            'filters' => $filters,
            'html_content' => $htmlContent,
            'file_name' => strtolower(preg_replace('/[^a-zA-Z0-9]+/', '_', $title)) . '_' . now()->format('Ymd_His') . '.pdf',
            'meta' => [
                'security_classification' => 'CONFIDENTIAL / ENTERPRISE RESTRICTED',
                'compliance' => 'ISO 27001 & Supply Chain Security Framework',
            ]
        ];
    }

    /**
     * Generate standalone corporate print-ready HTML document suitable for browser PDF rendering or DomPDF.
     */
    public function generateHtmlDocument(string $title, string $reportType, array $data, array $filters, string $userName, string $timestamp): string
    {
        $rowsHtml = '';
        if (!empty($data['items']) && is_array($data['items'])) {
            foreach ($data['items'] as $item) {
                $rowsHtml .= '<tr>';
                foreach ($item as $val) {
                    $rowsHtml .= '<td>' . htmlspecialchars(is_array($val) ? json_encode($val) : (string) $val) . '</td>';
                }
                $rowsHtml .= '</tr>';
            }
        } elseif (!empty($data['metrics']) && is_array($data['metrics'])) {
            foreach ($data['metrics'] as $key => $val) {
                $rowsHtml .= '<tr><td><strong>' . htmlspecialchars(ucwords(str_replace('_', ' ', $key))) . '</strong></td><td>' . htmlspecialchars(is_array($val) ? json_encode($val) : (string)$val) . '</td></tr>';
            }
        } else {
            $rowsHtml .= '<tr><td colspan="4" style="text-align: center; color: #64748b;">No tabular records available for this report criteria.</td></tr>';
        }

        $headerCols = '';
        if (!empty($data['headers']) && is_array($data['headers'])) {
            foreach ($data['headers'] as $h) {
                $headerCols .= '<th>' . htmlspecialchars($h) . '</th>';
            }
        } else {
            $headerCols = '<th>Metric / Parameter</th><th>Value / Status</th>';
        }

        $summaryHtml = !empty($data['summary']) ? '<div class="summary-box"><h4>Executive Narrative Summary</h4><p>' . nl2br(htmlspecialchars($data['summary'])) . '</p></div>' : '';

        return <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{$title} - RiskIntel Hub PDF Report</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; color: #1e293b; background: #ffffff; margin: 30px; line-height: 1.5; }
        .header-container { display: flex; justify-content: space-between; align-items: center; border-bottom: 3px solid #0f172a; padding-bottom: 15px; margin-bottom: 25px; }
        .logo-area { font-size: 24px; font-weight: bold; color: #0f172a; letter-spacing: 1px; }
        .logo-accent { color: #d97706; }
        .doc-meta { text-align: right; font-size: 12px; color: #64748b; }
        .badge-confidential { background: #fee2e2; color: #b91c1c; padding: 4px 10px; font-weight: bold; border-radius: 4px; font-size: 11px; text-transform: uppercase; display: inline-block; margin-bottom: 5px; }
        h1 { font-size: 22px; color: #0f172a; margin: 0 0 5px 0; }
        .subtitle { font-size: 14px; color: #475569; margin-bottom: 20px; }
        .summary-box { background: #f8fafc; border-left: 4px solid #d97706; padding: 15px; margin-bottom: 25px; border-radius: 4px; font-size: 13px; }
        .summary-box h4 { margin: 0 0 8px 0; color: #0f172a; font-size: 14px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 25px; font-size: 12px; }
        th { background: #0f172a; color: #ffffff; text-align: left; padding: 10px 12px; font-weight: 600; border: 1px solid #1e293b; }
        td { padding: 9px 12px; border: 1px solid #e2e8f0; }
        tr:nth-child(even) { background: #f8fafc; }
        .footer { margin-top: 40px; padding-top: 15px; border-top: 1px solid #e2e8f0; font-size: 11px; color: #94a3b8; display: flex; justify-content: space-between; }
    </style>
</head>
<body>
    <div class="header-container">
        <div>
            <div class="logo-area">RiskIntel <span class="logo-accent">Hub</span></div>
            <div style="font-size: 11px; color: #64748b;">Global Supply Chain Risk Intelligence Platform</div>
        </div>
        <div class="doc-meta">
            <div class="badge-confidential">Confidential & Proprietary</div>
            <div><strong>Report Type:</strong> {$reportType}</div>
            <div><strong>Generated At:</strong> {$timestamp}</div>
            <div><strong>Author:</strong> {$userName}</div>
        </div>
    </div>

    <h1>{$title}</h1>
    <div class="subtitle">Enterprise Evaluation & Audit Export Package</div>

    {$summaryHtml}

    <table>
        <thead>
            <tr>{$headerCols}</tr>
        </thead>
        <tbody>
            {$rowsHtml}
        </tbody>
    </table>

    <div class="footer">
        <div>RiskIntel Hub Enterprise Reporting Suite — Certified ISO 27001 Compliant</div>
        <div>Page 1 of 1 &bull; Printed on Demand</div>
    </div>
</body>
</html>
HTML;
    }
}
