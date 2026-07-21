<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\LexiconSentimentService;

class LexiconSentimentUnitTest extends TestCase
{
    /**
     * Uji deteksi kata positif yang menghasilkan sentimen POSITIVE.
     */
    public function test_analyze_positive_text(): void
    {
        $service = new LexiconSentimentService();
        $text = "The logistics company achieved strong profit growth and stable supply chain recovery.";
        
        $result = $service->analyzeText($text);

        $this->assertEquals('POSITIVE', $result['label']);
        $this->assertGreaterThan(0, $result['score']);
        $this->assertContains('profit', $result['keywords']);
        $this->assertContains('growth', $result['keywords']);
    }

    /**
     * Uji deteksi kata negatif yang menghasilkan sentimen NEGATIVE.
     */
    public function test_analyze_negative_text(): void
    {
        $service = new LexiconSentimentService();
        $text = "Port congestion and geopolitical conflict caused major shipping crisis and disruption risk.";
        
        $result = $service->analyzeText($text);

        $this->assertEquals('NEGATIVE', $result['label']);
        $this->assertLessThan(0, $result['score']);
        $this->assertContains('crisis', $result['keywords']);
        $this->assertContains('conflict', $result['keywords']);
    }

    /**
     * Uji teks netral yang tidak mengandung kata kamus berlebihan.
     */
    public function test_analyze_neutral_text(): void
    {
        $service = new LexiconSentimentService();
        $text = "The container ship departed from the terminal at 08:00 AM local time.";
        
        $result = $service->analyzeText($text);

        $this->assertEquals('NEUTRAL', $result['label']);
        $this->assertEquals(0, $result['score']);
    }
}
