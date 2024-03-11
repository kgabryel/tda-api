<?php

namespace App\Alarm\Infrastructure\Command\SendNotifications\Notification;

use App\Alarm\Domain\Entity\NotificationId;
use App\Alarm\Infrastructure\Command\SendNotifications\NewNotification;
use App\Alarm\Infrastructure\Command\SendNotifications\NotificationToSend;
use App\Shared\Domain\Entity\UserId;

class WebNotification implements NotificationInterface
{
    private string $name;
    private ?string $content;
    private UserId $userId;
    private int $id;

    public function __construct(NotificationToSend $notification)
    {
        $this->name = $notification->getName();
        $this->content = $notification->getContent();
        $this->userId = $notification->getUserId();
        $this->id = $notification->getId();
    }

    public function getUserId(): UserId
    {
        return $this->userId;
    }

    public function isAvailable(): bool
    {
        return true;
    }

    public function send(): void
    {
        event(
            new NewNotification($this->userId->getValue(), [
                'name' => $this->name,
                'content' => $this->content
            ])
        );
    }

    public function getNotificationId(): NotificationId
    {
        return new NotificationId($this->id);
    }
}
