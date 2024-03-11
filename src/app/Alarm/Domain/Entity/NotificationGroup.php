<?php

namespace App\Alarm\Domain\Entity;

class NotificationGroup
{
    private NotificationsGroupId $notificationId;
    //ile sekund przed data alarmu ma byc powiadomienie
    private int $time;
    private NotificationTypesList $typesList;

    public function __construct(NotificationsGroupId $notificationId, int $time, NotificationTypesList $typesList)
    {
        $this->notificationId = $notificationId;
        $this->time = $time;
        $this->typesList = $typesList;
    }

    public function getNotificationId(): NotificationsGroupId
    {
        return $this->notificationId;
    }

    public function getTime(): int
    {
        return $this->time;
    }

    public function getNotificationTypesList(): NotificationTypesList
    {
        return $this->typesList;
    }
}
