<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PredictionService;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class PredictionController extends Controller
{
    protected PredictionService $predictionService;

    public function __construct(PredictionService $predictionService)
    {
        $this->predictionService = $predictionService;
    }

    /**
     * Display the Enterprise Prediction Dashboard index view.
     */
    public function index(): View
    {
        $data = $this->predictionService->getPredictionData();
        return view('prediction.index', compact('data'));
    }

    /**
     * Return structured JSON data for AJAX 60-second refresh and Scenario Simulation.
     */
    public function getData(Request $request): JsonResponse
    {
        $simulatedDeltas = [
            'weather_delta' => (float) $request->input('weather_delta', 0),
            'inflation_delta' => (float) $request->input('inflation_delta', 0),
            'currency_delta' => (float) $request->input('currency_delta', 0),
            'news_delta' => (float) $request->input('news_delta', 0),
        ];

        $data = $this->predictionService->getPredictionData($simulatedDeltas);

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }
}
