<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class WatchlistUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $userId;
    public string $countryName;
    public bool $isFavorited;

    public function __construct(int $userId, string $countryName, bool $isFavorited)
    {
        $this->userId = $userId;
        $this->countryName = $countryName;
        $this->isFavorited = $isFavorited;
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('App.Models.User.' . $this->userId),
            new Channel('dashboard.global'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'WatchlistUpdated';
    }
}
