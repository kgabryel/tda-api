<?php

namespace App\Alarm\Application\Command\SingleAlarm\AddNotification;

use App\Alarm\Application\Command\SingleAlarm\Create\Notification;
use App\Shared\Domain\Entity\AlarmId;

class NotificationDto
{
    private AlarmId $alarmId;
    private Notification $time;

    public function __construct(AlarmId $alarmId, Notification $time)
    {
        $this->alarmId = $alarmId;
        $this->time = $time;
    }

    public function getAlarmId(): AlarmId
    {
        return $this->alarmId;
    }

    public function getTime(): Notification
    {
        return $this->time;
    }
}
