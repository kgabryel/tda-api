<?php

namespace App\Alarm\Infrastructure\Command\SendNotifications;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;

class NewNotification implements ShouldBroadcastNow
{
    use Dispatchable;
    use InteractsWithSockets;

    private int $userId;
    private array $notificationData;

    public function __construct(int $userId, array $notificationData)
    {
        $this->userId = $userId;
        $this->notificationData = $notificationData;
    }

    public function broadcastWith(): array
    {
        return ['notificationData' => $this->notificationData];
    }

    public function broadcastAs(): string
    {
        return 'new-notification';
    }

    public function broadcastOn(): PrivateChannel
    {
        return new PrivateChannel(sprintf('notifications.%s', $this->userId));
    }
}
