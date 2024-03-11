<?php

namespace App\Alarm\Application\Command\SingleAlarm\Create;

use App\Alarm\Domain\Entity\NotificationsGroupId;
use App\Alarm\Domain\Entity\NotificationTypesList;
use DateTimeImmutable;

class Notification
{
    private DateTimeImmutable $time;
    private ?NotificationsGroupId $notificationsGroupId;
    private NotificationTypesList $typesList;

    public function __construct(
        DateTimeImmutable $time,
        NotificationTypesList $typesList,
        ?NotificationsGroupId $notificationsGroupId = null
    ) {
        $this->time = $time;
        $this->notificationsGroupId = $notificationsGroupId;
        $this->typesList = $typesList;
    }

    public function getTime(): DateTimeImmutable
    {
        return $this->time;
    }

    public function getNotificationsGroupId(): ?NotificationsGroupId
    {
        return $this->notificationsGroupId;
    }

    public function getTypesList(): NotificationTypesList
    {
        return $this->typesList;
    }
}
