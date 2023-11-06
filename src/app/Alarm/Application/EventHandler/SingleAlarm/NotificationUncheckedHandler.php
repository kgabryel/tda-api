<?php

namespace App\Alarm\Application\EventHandler\SingleAlarm;

use App\Alarm\Application\NotificationManagerInterface;
use App\Alarm\Domain\Entity\NotificationTime;
use App\Alarm\Domain\Event\SingleAlarm\NotificationUnchecked;
use App\Core\Cqrs\ListenEvent;

#[ListenEvent(NotificationUnchecked::class)]
class NotificationUncheckedHandler
{
    private NotificationManagerInterface $notificationManager;

    public function __construct(NotificationManagerInterface $notificationManager)
    {
        $this->notificationManager = $notificationManager;
    }

    public function handle(NotificationUnchecked $event): void
    {
        $this->notificationManager->uncheck($event->getNotification()->getNotificationId());
        $this->notificationManager->addToBuff(NotificationTime::fromNotification($event->getNotification()));
    }
}
