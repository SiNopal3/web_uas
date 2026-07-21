<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use App\Services\AnalyticsService;

class AnalyticsController extends Controller
{
    protected AnalyticsService $analyticsService;

    public function __construct(AnalyticsService $analyticsService)
    {
        $this->analyticsService = $analyticsService;
    }

    /**
     * Display the Business Intelligence Analytics Center dashboard view (`/analytics`).
     *
     * @return View
     */
    public function index(Request $request): View
    {
        $filters = $request->only(['country', 'period']);
        $filters = array_filter($filters, fn($val) => !is_null($val) && $val !== '' && $val !== 'Semua Negara' && $val !== 'Global / Semua Negara' && $val !== '-');

        $data = $this->analyticsService->getAnalyticsData($filters);

        return view('analytics.currency', compact('data'));
    }

    /**
     * Display the Data Visualization Dashboard (`/data-visualization`).
     */
    public function dataVisualization(Request $request): View
    {
        $country = $request->get('country', '');
        $country = (trim($country) === '' || $country === 'Global / Semua Negara' || $country === '-') ? null : trim($country);
        $chartData = $this->analyticsService->getVisualizationChartData($country);
        return view('data-visualization', compact('chartData', 'country'));
    }

    /**
     * AJAX endpoint for chart data (`/api/data-visualization/charts`).
     */
    public function getChartData(Request $request): JsonResponse
    {
        $country = $request->get('country', '');
        $country = (trim($country) === '' || $country === 'Global / Semua Negara' || $country === '-') ? null : trim($country);
        $chartData = $this->analyticsService->getVisualizationChartData($country);
        return response()->json(['success' => true, 'chartData' => $chartData]);
    }

    /**
     * AJAX JSON endpoint for dynamic filtering, period switching, and auto-refresh (`/api/analytics/data`).
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getData(Request $request): JsonResponse
    {
        $filters = $request->only([
            'country',
            'region',
            'risk_level',
            'period',
            'date_range',
            'weather_filter',
            'currency_filter',
            'inflation_filter',
            'news_filter'
        ]);

        // Remove empty or null filters
        $filters = array_filter($filters, fn($val) => !is_null($val) && $val !== '');

        $data = $this->analyticsService->getAnalyticsData($filters);

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }
}
