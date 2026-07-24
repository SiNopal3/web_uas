<?php

namespace App\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AuditLogCreated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public array $auditLog;

    public function __construct(array $auditLog)
    {
        $this->auditLog = $auditLog;
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('admin.console'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'AuditLogCreated';
    }
}
