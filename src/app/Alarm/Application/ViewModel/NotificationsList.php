<?php

namespace App\Alarm\Application\ViewModel;

class NotificationsList
{
    private array $notifications;

    public function __construct(Notification ...$notifications)
    {
        $this->notifications = $notifications;
    }

    public function getNotifications(): array
    {
        return $this->notifications;
    }
}
