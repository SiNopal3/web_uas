<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewsArticleCreated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public array $article;

    public function __construct(array $article)
    {
        $this->article = $article;
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('dashboard.global'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'NewsArticleCreated';
    }
}
