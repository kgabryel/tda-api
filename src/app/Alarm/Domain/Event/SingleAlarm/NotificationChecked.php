<?php

namespace App\Alarm\Domain\Event\SingleAlarm;

use App\Alarm\Domain\Entity\NotificationId;
use App\Core\Cqrs\Event;

/**
 * powiadomienie zostalo dezaktywowane, nalezy zmodyfikowac dane w bazie
 */
class NotificationChecked implements Event
{
    private NotificationId $notificationId;

    public function __construct(NotificationId $notificationId)
    {
        $this->notificationId = $notificationId;
    }

    public function getNotificationId(): NotificationId
    {
        return $this->notificationId;
    }
}
