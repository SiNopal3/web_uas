<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class WeatherUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $country;
    public array $weatherData;

    public function __construct(string $country, array $weatherData)
    {
        $this->country = $country;
        $this->weatherData = $weatherData;
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('dashboard.global'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'WeatherUpdated';
    }
}
