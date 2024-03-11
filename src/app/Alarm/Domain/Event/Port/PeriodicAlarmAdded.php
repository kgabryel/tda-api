<?php

namespace App\Alarm\Domain\Event\Port;

use App\Alarm\Application\Command\PeriodicAlarm\Create\AlarmDto;
use App\Alarm\Application\Command\PeriodicAlarm\Create\Notifications;
use App\Alarm\Application\Command\PeriodicAlarm\Create\TasksGroupsList;
use App\Core\Cqrs\Event;

/**
 * zostal dodany nowy alarm okresowy, nalezy go utworzyc
 */
interface PeriodicAlarmAdded extends Event
{
    public function getAlarm(): AlarmDto;

    public function getNotifications(): Notifications;

    public function getTaskGroup(): TasksGroupsList;
}
