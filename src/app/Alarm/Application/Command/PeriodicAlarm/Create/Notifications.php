<?php

namespace App\Alarm\Application\Command\PeriodicAlarm\Create;

use App\Alarm\Application\Command\PeriodicAlarm\AddNotification\NotificationDto;
use App\Shared\Domain\Entity\AlarmsGroupId;

class Notifications
{
    private AlarmsGroupId $alarmId;
    private array $notifications;

    public function __construct(AlarmsGroupId $alarmId, NotificationDto ...$notifications)
    {
        $this->alarmId = $alarmId;
        $this->notifications = $notifications;
    }

    public function getAlarmId(): AlarmsGroupId
    {
        return $this->alarmId;
    }

    public function getNotifications(): array
    {
        return $this->notifications;
    }
}
