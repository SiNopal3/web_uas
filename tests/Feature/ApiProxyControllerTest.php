<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;
use App\Models\User;

class ApiProxyControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $user = User::factory()->create();
        Sanctum::actingAs($user);
    }

    /**
     * Uji proxy cuaca Open-Meteo dengan simulasi Http::fake().
     */
    public function test_get_weather_returns_successful_proxy_data(): void
    {
        Http::fake([
            'api.open-meteo.com/*' => Http::response([
                'current_weather' => [
                    'temperature' => 28.5,
                    'windspeed' => 14.2
                ],
                'hourly' => [
                    'rain' => [1.5]
                ]
            ], 200)
        ]);

        $response = $this->getJson('/api/external/weather/-6.1/106.8');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'temperature_2m' => 28.5,
                    'wind_speed_10m' => 14.2,
                    'rain' => 1.5
                ]
            ]);
    }

    /**
     * Uji proxy indikator ekonomi World Bank dengan simulasi Http::fake().
     */
    public function test_get_world_bank_data_returns_economy_metrics(): void
    {
        Http::fake([
            'api.worldbank.org/v2/country/ID/indicator/NY.GDP.MKTP.CD*' => Http::response([
                ['page' => 1],
                [['value' => 1319000000000]]
            ], 200),
            'api.worldbank.org/v2/country/ID/indicator/FP.CPI.TOTL.ZG*' => Http::response([
                ['page' => 1],
                [['value' => 4.2]]
            ], 200),
            'api.worldbank.org/v2/country/ID/indicator/SP.POP.TOTL*' => Http::response([
                ['page' => 1],
                [['value' => 275000000]]
            ], 200),
        ]);

        $response = $this->getJson('/api/external/economy/ID');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'gdp' => 1319000000000,
                    'inflation' => 4.2,
                    'population' => 275000000
                ]
            ]);
    }

    /**
     * Uji proxy berita GNews dengan hasil analisis sentimen Lexicon.
     */
    public function test_get_global_news_with_lexicon_sentiment_enrichment(): void
    {
        Http::fake([
            'gnews.io/*' => Http::response([
                'articles' => [
                    [
                        'title' => 'Logistics company reports massive profit growth and stable expansion',
                        'description' => 'Strong financial recovery observed.',
                        'url' => 'https://example.com/news1',
                        'source' => ['name' => 'Logistics Daily']
                    ]
                ]
            ], 200)
        ]);

        $response = $this->getJson('/api/external/news/supply%20chain');

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('articles.0.sentiment', 'Positive');
    }
}
