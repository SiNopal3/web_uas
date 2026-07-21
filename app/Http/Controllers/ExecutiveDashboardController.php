<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ExecutiveDashboardService;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class ExecutiveDashboardController extends Controller
{
    protected ExecutiveDashboardService $dashboardService;

    public function __construct(ExecutiveDashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    /**
     * Display the Executive Dashboard index view.
     */
    public function index(): View
    {
        $data = $this->dashboardService->getDashboardData();
        return view('executive.index', compact('data'));
    }

    /**
     * Return structured JSON data for AJAX auto-refresh every 60 seconds.
     */
    public function getData(): JsonResponse
    {
        $data = $this->dashboardService->getDashboardData();
        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }
}
