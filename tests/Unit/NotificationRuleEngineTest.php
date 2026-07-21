<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\NotificationService;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NotificationRuleEngineTest extends TestCase
{
    use RefreshDatabase;

    protected NotificationService $notificationService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->notificationService = new NotificationService();
    }

    public function test_alert_rule_engine_evaluates_and_generates_notifications()
    {
        $user = User::factory()->create();

        $this->notificationService->evaluateAlertRuleEngine($user->id);

        $notificationsCount = Notification::where('user_id', $user->id)->count();
        $this->assertGreaterThanOrEqual(5, $notificationsCount);
    }

    public function test_rule_engine_catalog_contains_at_least_50_rules()
    {
        $catalog = $this->notificationService->getRuleEngineCatalog();

        $this->assertIsArray($catalog);
        $this->assertGreaterThanOrEqual(50, count($catalog));
        
        $firstRule = $catalog[0];
        $this->assertArrayHasKey('rule_id', $firstRule);
        $this->assertArrayHasKey('condition', $firstRule);
        $this->assertArrayHasKey('action', $firstRule);
        $this->assertArrayHasKey('category', $firstRule);
    }

    public function test_critical_alerts_are_triggered_for_high_risk_corridors()
    {
        $user = User::factory()->create();

        $this->notificationService->evaluateAlertRuleEngine($user->id);

        $criticalAlerts = Notification::where('user_id', $user->id)
            ->where(function ($q) {
                $q->where('priority', 'Critical')->orWhere('type', 'Critical');
            })
            ->get();

        $this->assertNotEmpty($criticalAlerts);
        $hasChinaOrHighRisk = $criticalAlerts->contains(function ($item) {
            return $item->country === 'China' || $item->priority === 'Critical';
        });
        $this->assertTrue($hasChinaOrHighRisk);
    }
}
