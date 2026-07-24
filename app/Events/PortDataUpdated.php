<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PortDataUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public array $port;

    public function __construct(array $port)
    {
        $this->port = $port;
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('dashboard.global'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'PortDataUpdated';
    }
}
