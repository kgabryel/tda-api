<?php

namespace App\Alarm\Domain\Event\SingleAlarm;

use App\Alarm\Domain\Entity\Notification;
use App\Core\Cqrs\Event;
use App\Shared\Domain\Entity\UserId;

/**
 * powiadomienie zostalo aktywowane, nalezy zmodyfikowac dane w bazie
 */
class NotificationUnchecked implements Event
{
    private Notification $notification;
    private UserId $userId;

    public function __construct(Notification $notification, UserId $userId)
    {
        $this->notification = $notification;
        $this->userId = $userId;
    }

    public function getNotification(): Notification
    {
        return $this->notification;
    }

    public function getUserId(): UserId
    {
        return $this->userId;
    }
}
