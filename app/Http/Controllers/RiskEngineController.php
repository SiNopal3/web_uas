<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\LexiconSentimentService;
use App\Services\RiskScoringService;
use App\Models\Country;

class RiskEngineController extends Controller
{
    protected LexiconSentimentService $sentimentService;
    protected RiskScoringService $scoringService;

    public function __construct(LexiconSentimentService $sentimentService, RiskScoringService $scoringService)
    {
        $this->sentimentService = $sentimentService;
        $this->scoringService = $scoringService;
    }

    /**
     * Fitur AI: Lexicon Based Sentiment Analysis (memakai kamus database).
     */
    public function analyzeSentiment(Request $request)
    {
        $text = $request->input('text', '');
        
        if (empty($text)) {
            return response()->json(['error' => 'Silakan masukkan teks berita.'], 400);
        }

        $result = $this->sentimentService->analyzeText($text);

        return response()->json([
            'success' => true,
            'analyzed_text' => $text,
            'scores' => [
                'positive_words_found' => $result['pos_count'],
                'negative_words_found' => $result['neg_count'],
                'net_score' => $result['score'],
                'keywords' => $result['keywords'],
            ],
            'final_sentiment' => $result['label']
        ]);
    }

    /**
     * Fitur AI: Supply Chain Risk Prediction (Weighted Risk Model dengan penyimpanan riwayat).
     */
    public function predictRisk(Request $request)
    {
        $weather = (float) $request->input('weather', 0);
        $inflation = (float) $request->input('inflation', 0);
        $news = (float) $request->input('news', 0);
        $currency = (float) $request->input('currency', 0);
        $countryName = $request->input('country', null);

        $countryId = null;
        if ($countryName) {
            $country = Country::where('name', $countryName)->first();
            if ($country) {
                $countryId = $country->id;
            }
        }

        $result = $this->scoringService->calculateScore($weather, $inflation, $news, $currency, $countryId);

        return response()->json([
            'success' => true,
            'inputs' => [
                'weather_score' => $weather,
                'inflation_score' => $inflation,
                'news_sentiment_score' => $news,
                'currency_score' => $currency,
            ],
            'prediction' => [
                'total_risk_score' => $result['total_risk'],
                'risk_status' => $result['category'],
                'details' => $result['breakdown']
            ]
        ]);
    }
}