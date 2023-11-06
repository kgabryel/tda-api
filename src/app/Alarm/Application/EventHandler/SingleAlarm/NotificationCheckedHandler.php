<?php

namespace App\Alarm\Application\EventHandler\SingleAlarm;

use App\Alarm\Application\NotificationManagerInterface;
use App\Alarm\Domain\Event\SingleAlarm\NotificationChecked;
use App\Core\Cqrs\ListenEvent;

#[ListenEvent(NotificationChecked::class)]
class NotificationCheckedHandler
{
    private NotificationManagerInterface $notificationManager;

    public function __construct(NotificationManagerInterface $notificationManager)
    {
        $this->notificationManager = $notificationManager;
    }

    public function handle(NotificationChecked $event): void
    {
        $this->notificationManager->check($event->getNotificationId());
    }
}
