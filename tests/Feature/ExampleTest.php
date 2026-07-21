<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Uji halaman login mengembalikan status 200.
     */
    public function test_login_page_returns_successful_response(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }

    /**
     * Uji rute utama (welcome) untuk guest mengembalikan status 200.
     */
    public function test_guest_can_access_welcome_page(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertViewIs('welcome');
    }

    /**
     * Uji rute utama (dashboard) untuk pengguna terautentikasi mengembalikan status 200.
     */
    public function test_authenticated_user_can_access_dashboard(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/');
        $response->assertStatus(200);
        $response->assertViewIs('dashboard');

        $responseDashboard = $this->actingAs($user)->get('/dashboard');
        $responseDashboard->assertStatus(200);
        $responseDashboard->assertViewIs('dashboard');
    }

    /**
     * Uji bahwa logout mengarahkan pengguna kembali ke halaman utama (welcome).
     */
    public function test_user_is_redirected_to_welcome_page_on_logout(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->post('/logout');
        $response->assertStatus(302);
        $response->assertRedirect('/');
    }
}
