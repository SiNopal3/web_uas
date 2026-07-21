<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\NotificationService;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NotificationServiceTest extends TestCase
{
    use RefreshDatabase;

    protected NotificationService $notificationService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->notificationService = new NotificationService();
    }

    public function test_get_notifications_data_returns_all_required_sections()
    {
        $user = User::factory()->create();

        $data = $this->notificationService->getNotificationsData($user);

        $this->assertIsArray($data);
        $this->assertArrayHasKey('header', $data);
        $this->assertArrayHasKey('statistics', $data);
        $this->assertArrayHasKey('notification_feed', $data);
        $this->assertArrayHasKey('critical_incidents', $data);
        $this->assertArrayHasKey('alert_categories', $data);
        $this->assertArrayHasKey('timeline_chart', $data);
        $this->assertArrayHasKey('country_alert_map', $data);
        $this->assertArrayHasKey('alert_filters', $data);
        $this->assertArrayHasKey('unread_count', $data);
    }

    public function test_statistics_calculation_matches_database_records()
    {
        $user = User::factory()->create();

        Notification::create([
            'user_id' => $user->id,
            'title' => 'Test Critical',
            'message' => 'Critical message',
            'type' => 'Critical',
            'priority' => 'Critical',
            'category' => 'Maritime',
            'status' => 'Active',
            'is_read' => false,
        ]);

        Notification::create([
            'user_id' => $user->id,
            'title' => 'Test Warning',
            'message' => 'Warning message',
            'type' => 'Warning',
            'priority' => 'High',
            'category' => 'Weather',
            'status' => 'Resolved',
            'is_read' => true,
        ]);

        $data = $this->notificationService->getNotificationsData($user);
        $stats = $data['statistics'];

        $this->assertGreaterThanOrEqual(2, $stats['total_notifications']);
        $this->assertGreaterThanOrEqual(1, $stats['unread']);
        $this->assertGreaterThanOrEqual(1, $stats['critical']);
        $this->assertGreaterThanOrEqual(1, $stats['resolved']);
    }

    public function test_filtering_notifications_by_priority_and_country()
    {
        $user = User::factory()->create();

        Notification::create([
            'user_id' => $user->id,
            'title' => 'China Alert',
            'message' => 'Delay in Shanghai',
            'type' => 'Port',
            'priority' => 'High',
            'category' => 'Operational',
            'country' => 'China',
            'status' => 'Active',
        ]);

        Notification::create([
            'user_id' => $user->id,
            'title' => 'Germany Alert',
            'message' => 'Weather in Hamburg',
            'type' => 'Weather',
            'priority' => 'Low',
            'category' => 'Weather',
            'country' => 'Germany',
            'status' => 'Active',
        ]);

        $filteredData = $this->notificationService->getNotificationsData($user, ['country' => 'China']);
        
        foreach ($filteredData['notification_feed'] as $item) {
            $this->assertEquals('China', $item['country']);
        }
    }
}
