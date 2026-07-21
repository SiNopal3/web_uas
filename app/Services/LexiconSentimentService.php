<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class LexiconSentimentService
{
    /**
     * Daftar kamus fallback positif.
     */
    protected array $fallbackPositiveWords = [
        'surge', 'profit', 'growth', 'gain', 'positive', 'agreement', 'stable', 'boost',
        'success', 'recover', 'expansion', 'favorable', 'optimism', 'breakthrough', 'safe',
        'efficient', 'smooth', 'stabilize', 'upgrade', 'promising', 'untouched', 'improved'
    ];

    /**
     * Daftar kamus fallback negatif.
     */
    protected array $fallbackNegativeWords = [
        'crisis', 'war', 'conflict', 'delay', 'shortage', 'strike', 'inflation', 'sanction',
        'risk', 'loss', 'decline', 'disrupt', 'threat', 'negative', 'concern', 'storm',
        'congested', 'blockade', 'crash', 'damage', 'warning', 'hazard', 'collapse', 'halt'
    ];

    /**
     * Ambil kamus kata positif dari database (dengan fallback ke array lokal & cache 24 jam).
     */
    public function getPositiveWords(): array
    {
        return Cache::remember('lexicon_positive_words', now()->addHours(24), function () {
            if (Schema::hasTable('positive_words')) {
                $dbWords = DB::table('positive_words')->pluck('word')->toArray();
                if (!empty($dbWords)) {
                    return array_map('strtolower', $dbWords);
                }
            }
            return $this->fallbackPositiveWords;
        });
    }

    /**
     * Ambil kamus kata negatif dari database (dengan fallback ke array lokal & cache 24 jam).
     */
    public function getNegativeWords(): array
    {
        return Cache::remember('lexicon_negative_words', now()->addHours(24), function () {
            if (Schema::hasTable('negative_words')) {
                $dbWords = DB::table('negative_words')->pluck('word')->toArray();
                if (!empty($dbWords)) {
                    return array_map('strtolower', $dbWords);
                }
            }
            return $this->fallbackNegativeWords;
        });
    }

    /**
     * Analisis teks mentah dan tentukan skor sentimen, label klasifikasi, serta kata kunci yang ditemukan.
     */
    public function analyzeText(string $text): array
    {
        $textLower = strtolower($text);
        $posWords = $this->getPositiveWords();
        $negWords = $this->getNegativeWords();

        $posCount = 0;
        $negCount = 0;
        $foundPosWords = [];
        $foundNegWords = [];

        foreach ($posWords as $w) {
            if ($w && preg_match('/\b' . preg_quote($w, '/') . '\b/i', $textLower)) {
                $posCount++;
                $foundPosWords[] = $w;
            }
        }

        foreach ($negWords as $w) {
            if ($w && preg_match('/\b' . preg_quote($w, '/') . '\b/i', $textLower)) {
                $negCount++;
                $foundNegWords[] = $w;
            }
        }

        $netScore = $posCount - $negCount;

        if ($netScore > 0) {
            $label = 'POSITIVE';
            $badgeColor = 'success';
        } elseif ($netScore < 0) {
            $label = 'NEGATIVE';
            $badgeColor = 'danger';
        } else {
            $label = 'NEUTRAL';
            $badgeColor = 'secondary';
        }

        return [
            'label' => $label,
            'score' => $netScore,
            'pos_count' => $posCount,
            'neg_count' => $negCount,
            'badge_color' => $badgeColor,
            'keywords' => array_merge($foundPosWords, $foundNegWords)
        ];
    }
}
