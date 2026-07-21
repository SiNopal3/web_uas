<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;
use App\Models\User;
use App\Models\Country;
use App\Models\Watchlist;

class WatchlistFeatureTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Uji pengguna yang belum login tidak dapat mengakses atau memodifikasi watchlist.
     */
    public function test_unauthenticated_user_cannot_access_watchlist(): void
    {
        $response = $this->getJson('/api/watchlist');
        $response->assertStatus(401);
    }

    /**
     * Uji operasi CRUD Watchlist untuk pengguna yang sudah login.
     */
    public function test_authenticated_user_can_manage_watchlist_crud(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        // 1. Simpan negara ke watchlist (POST)
        $storeResponse = $this->postJson('/api/watchlist', [
            'country_name' => 'Japan',
            'currency' => 'JPY',
            'region' => 'Asia'
        ]);

        $storeResponse->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Negara berhasil ditambahkan ke daftar pantauan.'
            ]);

        $this->assertDatabaseHas('countries', ['name' => 'Japan', 'currency' => 'JPY']);
        $country = Country::where('name', 'Japan')->first();
        $this->assertDatabaseHas('watchlists', ['user_id' => $user->id, 'country_id' => $country->id]);

        // 2. Ambil daftar pantauan (GET)
        $getResponse = $this->getJson('/api/watchlist');
        $getResponse->assertStatus(200)
            ->assertJsonPath('data.0.country_name', 'Japan');

        // 3. Hapus negara dari watchlist (DELETE)
        $deleteResponse = $this->deleteJson('/api/watchlist/' . $country->name);
        $deleteResponse->assertStatus(200)
            ->assertJson([
                'success' => true
            ]);

        $this->assertDatabaseMissing('watchlists', ['user_id' => $user->id, 'country_id' => $country->id]);
    }
}
