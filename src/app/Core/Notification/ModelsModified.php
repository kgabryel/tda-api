<?php

namespace App\Core\Notification;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;

class ModelsModified implements ShouldBroadcastNow
{
    use Dispatchable;
    use InteractsWithSockets;

    private int $userId;
    private string $type;
    private array $modifiedData;

    public function __construct(int $userId, string $type, array $modifiedData)
    {
        $this->userId = $userId;
        $this->type = $type;
        $this->modifiedData = array_values($modifiedData);
    }

    public function broadcastWith(): array
    {
        return ['modifiedData' => $this->modifiedData];
    }

    public function broadcastAs(): string
    {
        return sprintf('%s.modified', $this->type);
    }

    public function broadcastOn(): PrivateChannel
    {
        return new PrivateChannel(sprintf('events.%s', $this->userId));
    }
}
