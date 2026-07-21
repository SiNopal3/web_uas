<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class NotificationDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login_for_notifications_routes()
    {
        $responseIndex = $this->get('/notifications');
        $responseIndex->assertStatus(302);
        $responseIndex->assertRedirect('/login');

        $responseData = $this->get('/api/notifications');
        $responseData->assertStatus(302);
        $responseData->assertRedirect('/login');
    }

    public function test_authenticated_user_can_access_notifications_dashboard_view()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/notifications');

        $response->assertStatus(200);
        $response->assertViewIs('notifications.index');
        $response->assertViewHas('data');
    }

    public function test_authenticated_user_can_fetch_notifications_ajax_json()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->getJson('/api/notifications');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'data' => [
                'header',
                'statistics',
                'notification_feed',
                'critical_incidents',
                'alert_categories',
                'timeline_chart',
                'country_alert_map',
                'alert_filters',
                'unread_count'
            ]
        ]);
    }
}
