<?php

namespace App\Alarm\Application\Dto;

use App\Alarm\Application\Command\SingleAlarm\Create\Notification;

class NotificationsList
{
    private array $notifications;

    public function __construct()
    {
        $this->notifications = [];
    }

    public function addNotification(Notification $notification): void
    {
        $this->notifications[] = $notification;
    }

    public function get(): array
    {
        return $this->notifications;
    }
}
