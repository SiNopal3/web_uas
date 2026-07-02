<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RiskEngineController extends Controller
{
    // Fitur AI: Lexicon Based Sentiment Analysis
    public function analyzeSentiment(Request $request)
    {
        // Mengambil teks berita dari parameter URL
        $text = $request->input('text', '');
        
        if (empty($text)) {
            return response()->json(['error' => 'Silakan masukkan teks berita.']);
        }

        // Memecah kalimat menjadi array kata-kata kecil (lowercase)
        $words = explode(' ', strtolower($text));
        
        // Membuat Dictionary / Kamus Data
        $positiveWords = ['growth', 'increase', 'profit', 'stable', 'improve', 'good', 'success', 'boom'];
        $negativeWords = ['war', 'crisis', 'inflation', 'delay', 'disaster', 'decrease', 'bad', 'loss'];

        $positiveScore = 0;
        $negativeScore = 0;

        // Mulai menghitung skor berdasarkan kecocokan kata
        foreach ($words as $word) {
            // Bersihkan tanda baca (koma, titik) yang menempel pada kata
            $cleanWord = preg_replace('/[^a-z]/', '', $word);
            
            if (in_array($cleanWord, $positiveWords)) {
                $positiveScore++;
            }
            if (in_array($cleanWord, $negativeWords)) {
                $negativeScore++;
            }
        }

        // Penentuan Sentimen Akhir
        if ($positiveScore > $negativeScore) {
            $sentiment = "Positive";
        } elseif ($negativeScore > $positiveScore) {
            $sentiment = "Negative";
        } else {
            $sentiment = "Neutral";
        }

        return response()->json([
            'success' => true,
            'analyzed_text' => $text,
            'scores' => [
                'positive_words_found' => $positiveScore,
                'negative_words_found' => $negativeScore,
            ],
            'final_sentiment' => $sentiment
        ]);
    }
    // Fitur AI: Supply Chain Risk Prediction (Weighted Risk Model) [cite: 212-214]
    public function predictRisk(Request $request)
    {
        // Menerima input data indikator dari URL (skala risiko 0-100), default 0 jika kosong
        $weather = $request->input('weather', 0);
        $inflation = $request->input('inflation', 0);
        $news = $request->input('news', 0);
        $currency = $request->input('currency', 0);

        // Pembobotan (Weights) sesuai spesifikasi dokumen [cite: 217-221]:
        // Weather 30%, Inflation 20%, Political News 40%, Currency 10%
        $weatherWeight = 0.30;
        $inflationWeight = 0.20;
        $newsWeight = 0.40;
        $currencyWeight = 0.10;

        // Algoritma Kalkulasi Total Risk Score [cite: 222]
        $totalRisk = ($weather * $weatherWeight) + 
                     ($inflation * $inflationWeight) + 
                     ($news * $newsWeight) + 
                     ($currency * $currencyWeight);

        // Klasifikasi Tingkat Risiko (Low, Medium, High) [cite: 112-113]
        if ($totalRisk <= 33) {
            $status = "Low Risk";
        } elseif ($totalRisk <= 66) {
            $status = "Medium Risk";
        } else {
            $status = "High Risk";
        }

        return response()->json([
            'success' => true,
            'inputs' => [
                'weather_score' => $weather,
                'inflation_score' => $inflation,
                'news_sentiment_score' => $news,
                'currency_score' => $currency,
            ],
            'prediction' => [
                'total_risk_score' => round($totalRisk, 2),
                'risk_status' => $status
            ]
        ]);
    }
}