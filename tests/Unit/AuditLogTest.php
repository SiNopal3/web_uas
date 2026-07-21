<?php

namespace Tests\Unit;

use App\Services\AuditLogService;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuditLogTest extends TestCase
{
    use RefreshDatabase;

    protected AuditLogService $auditService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->auditService = app(AuditLogService::class);
    }

    public function test_can_record_audit_log_entry_properly(): void
    {
        $user = User::factory()->create(['name' => 'Alice Inspector', 'email' => 'alice@riskintel.com']);

        $entry = $this->auditService->logAction(
            $user,
            'CREATE_USER',
            'User Management',
            ['target_user' => 'Bob Analyst']
        );

        $this->assertInstanceOf(AuditLog::class, $entry);
        $this->assertEquals('CREATE_USER', $entry->action);
        $this->assertEquals('User Management', $entry->module);
        $this->assertEquals('Alice Inspector', $entry->user_name);
        $this->assertDatabaseHas('audit_logs', [
            'action' => 'CREATE_USER',
            'module' => 'User Management',
            'user_name' => 'Alice Inspector'
        ]);
    }

    public function test_paginated_logs_with_module_and_search_filters(): void
    {
        $user = User::factory()->create(['name' => 'Charlie Ops']);
        
        $initialAll = $this->auditService->getLogs(['module' => 'all'])['total'];
        $initialModule = $this->auditService->getLogs(['module' => 'User Management'])['total'];

        $this->auditService->logAction($user, 'LOGIN', 'Authentication');
        $this->auditService->logAction($user, 'UPDATE_SETTINGS', 'System Configuration');
        $this->auditService->logAction($user, 'DELETE_USER', 'User Management');

        $allLogs = $this->auditService->getLogs(['module' => 'all']);
        $this->assertEquals($initialAll + 3, $allLogs['total']);

        $filteredLogs = $this->auditService->getLogs(['module' => 'User Management']);
        $this->assertEquals($initialModule + 1, $filteredLogs['total']);
        $this->assertEquals('DELETE_USER', $filteredLogs['data'][0]->action);
    }
}
