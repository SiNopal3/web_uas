<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Notification;

class NotificationCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_mark_notification_as_read()
    {
        $user = User::factory()->create();
        $notification = Notification::create([
            'user_id' => $user->id,
            'title' => 'Test Notification',
            'message' => 'Message body',
            'type' => 'Warning',
            'priority' => 'High',
            'category' => 'Operational',
            'is_read' => false,
        ]);

        $response = $this->actingAs($user)->postJson("/api/notifications/read/{$notification->id}");

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);

        $this->assertDatabaseHas('notifications', [
            'id' => $notification->id,
            'is_read' => true,
        ]);
    }

    public function test_authenticated_user_can_mark_all_notifications_as_read()
    {
        $user = User::factory()->create();
        Notification::create([
            'user_id' => $user->id,
            'title' => 'Notif 1',
            'message' => 'Body 1',
            'is_read' => false,
        ]);
        Notification::create([
            'user_id' => $user->id,
            'title' => 'Notif 2',
            'message' => 'Body 2',
            'is_read' => false,
        ]);

        $response = $this->actingAs($user)->postJson('/api/notifications/read-all');

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);

        $this->assertEquals(0, Notification::where('user_id', $user->id)->where('is_read', false)->count());
    }

    public function test_authenticated_user_can_delete_notification()
    {
        $user = User::factory()->create();
        $notification = Notification::create([
            'user_id' => $user->id,
            'title' => 'To be deleted',
            'message' => 'Delete me',
        ]);

        $response = $this->actingAs($user)->deleteJson("/api/notifications/{$notification->id}");

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);

        $this->assertDatabaseMissing('notifications', [
            'id' => $notification->id,
        ]);
    }

    public function test_authenticated_user_can_clear_all_notifications()
    {
        $user = User::factory()->create();
        Notification::create([
            'user_id' => $user->id,
            'title' => 'Clear 1',
            'message' => 'Body 1',
        ]);
        Notification::create([
            'user_id' => $user->id,
            'title' => 'Clear 2',
            'message' => 'Body 2',
        ]);

        $response = $this->actingAs($user)->deleteJson('/api/notifications/clear');

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);

        $this->assertEquals(0, Notification::where('user_id', $user->id)->count());
    }
}
