<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserStatusChanged implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $userId;
    public string $action; // 'disabled', 'deleted', 'updated'
    public string $message;

    public function __construct(int $userId, string $action, string $message = '')
    {
        $this->userId = $userId;
        $this->action = $action;
        $this->message = $message ?: 'Status akun Anda telah diubah oleh Administrator.';
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('App.Models.User.' . $this->userId),
            new PrivateChannel('admin.console'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'UserStatusChanged';
    }
}
