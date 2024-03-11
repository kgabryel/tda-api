<?php

namespace App\Alarm\Application\Command\SingleAlarm\Create;

use App\Shared\Domain\Entity\AlarmId;

class Notifications
{
    private AlarmId $alarmId;
    private array $notifications;

    public function __construct(AlarmId $alarmId, Notification ...$notifications)
    {
        $this->alarmId = $alarmId;
        $this->notifications = $notifications;
    }

    public function getAlarmId(): AlarmId
    {
        return $this->alarmId;
    }

    public function getNotifications(): array
    {
        return $this->notifications;
    }
}
