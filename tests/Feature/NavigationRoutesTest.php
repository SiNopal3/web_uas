<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class NavigationRoutesTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_user_is_redirected_to_login_for_modular_routes()
    {
        $routes = ['/dashboard', '/ports', '/news-sentiment', '/risk-simulator', '/watchlist'];

        foreach ($routes as $route) {
            $response = $this->get($route);
            $response->assertStatus(302);
            $response->assertRedirect('/login');
        }
    }

    public function test_authenticated_user_can_access_all_modular_routes()
    {
        $user = User::factory()->create();

        $routes = [
            '/' => 'dashboard',
            '/dashboard' => 'dashboard',
            '/ports' => 'ports',
            '/news-sentiment' => 'news_sentiment',
            '/risk-simulator' => 'risk_simulator',
            '/watchlist' => 'watchlist',
        ];

        foreach ($routes as $route => $viewName) {
            $response = $this->actingAs($user)->get($route);
            $response->assertStatus(200);
            $response->assertViewIs($viewName);
        }
    }
}
