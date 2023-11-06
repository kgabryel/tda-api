<?php

namespace App\Alarm\Domain\Entity;

use App\Shared\Domain\Entity\AlarmId;
use DateTimeImmutable;

class NotificationTime
{
    private NotificationId $notificationId;
    private AlarmId $alarmId;
    private DateTimeImmutable $time;

    public function __construct(NotificationId $notificationId, AlarmId $alarmId, DateTimeImmutable $time)
    {
        $this->notificationId = $notificationId;
        $this->alarmId = $alarmId;
        $this->time = $time;
    }

    public static function fromNotification(Notification $notification): self
    {
        return new NotificationTime(
            $notification->getNotificationId(),
            $notification->getAlarmId(),
            $notification->getTime()
        );
    }

    public function getNotificationId(): NotificationId
    {
        return $this->notificationId;
    }

    public function getAlarmId(): AlarmId
    {
        return $this->alarmId;
    }

    public function getTime(): DateTimeImmutable
    {
        return $this->time;
    }
}
