<?php

namespace App\Task\Domain\Event\PeriodicTask;

use App\Alarm\Application\Command\PeriodicAlarm\Create\AlarmDto;
use App\Alarm\Application\Command\PeriodicAlarm\Create\Notifications;
use App\Alarm\Application\Command\PeriodicAlarm\Create\TasksGroupsList;
use App\Alarm\Domain\Event\Port\PeriodicAlarmAdded as PeriodicAlarmAddedInterface;

/**
 * Podczas tworzenia alarmu okresowego utworzono takze alarm okresowy
 */
class PeriodicAlarmAdded implements PeriodicAlarmAddedInterface
{
    private AlarmDto $alarmDto;
    private Notifications $notifications;
    private TasksGroupsList $taskGroup;

    public function __construct(AlarmDto $alarmDto, Notifications $notifications, TasksGroupsList $taskGroup)
    {
        $this->alarmDto = $alarmDto;
        $this->notifications = $notifications;
        $this->taskGroup = $taskGroup;
    }

    public function getAlarm(): AlarmDto
    {
        return $this->alarmDto;
    }

    public function getNotifications(): Notifications
    {
        return $this->notifications;
    }

    public function getTaskGroup(): TasksGroupsList
    {
        return $this->taskGroup;
    }
}
