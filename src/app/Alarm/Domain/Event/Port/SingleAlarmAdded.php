<?php

namespace App\Alarm\Domain\Event\Port;

use App\Alarm\Application\Command\SingleAlarm\Create\AlarmDto;
use App\Alarm\Application\Command\SingleAlarm\Create\Notifications;
use App\Core\Cqrs\Event;
use App\Shared\Domain\Entity\TaskId;

/**
 * zostal dodany nowy alarm pojedynczy, nalezy go utworzyc
 */
interface SingleAlarmAdded extends Event
{
    public function getAlarmDto(): AlarmDto;

    public function getNotifications(): Notifications;

    public function getTaskId(): TaskId;
}
