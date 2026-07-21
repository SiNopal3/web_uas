<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\DecisionSupportService;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class DecisionSupportController extends Controller
{
    protected DecisionSupportService $decisionSupportService;

    public function __construct(DecisionSupportService $decisionSupportService)
    {
        $this->decisionSupportService = $decisionSupportService;
    }

    /**
     * Display the AI Decision Support Center (`/decision-support`) view.
     */
    public function index(): View
    {
        $data = $this->decisionSupportService->getDecisionSupportData();
        return view('decision-support.index', compact('data'));
    }

    /**
     * Return structured JSON data for AJAX 60s auto-refresh and Scenario Simulation.
     */
    public function getData(Request $request): JsonResponse
    {
        $simulatedDeltas = [
            'weather_delta' => (float) $request->input('weather_delta', 0),
            'inflation_delta' => (float) $request->input('inflation_delta', 0),
            'currency_delta' => (float) $request->input('currency_delta', 0),
            'news_delta' => (float) $request->input('news_delta', 0),
            'delay_delta' => (float) $request->input('delay_delta', 0),
            'prediction_delta' => (float) $request->input('prediction_delta', 0),
        ];

        $data = $this->decisionSupportService->getDecisionSupportData($simulatedDeltas);

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }
}
