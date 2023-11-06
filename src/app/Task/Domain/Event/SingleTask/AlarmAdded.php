<?php

namespace App\Task\Domain\Event\SingleTask;

use App\Alarm\Application\Command\SingleAlarm\Create\AlarmDto;
use App\Alarm\Application\Command\SingleAlarm\Create\Notifications;
use App\Alarm\Domain\Event\Port\SingleAlarmAdded;
use App\Shared\Domain\Entity\TaskId;

/**
 * Podczas tworzenia alarmu pojedynczego utworzono takze alarm pojedynczy
 */
class AlarmAdded implements SingleAlarmAdded
{
    private TaskId $taskId;
    private AlarmDto $alarmDto;
    private Notifications $notifications;

    public function __construct(TaskId $taskId, AlarmDto $alarmDto, Notifications $notifications)
    {
        $this->taskId = $taskId;
        $this->alarmDto = $alarmDto;
        $this->notifications = $notifications;
    }

    public function getAlarmDto(): AlarmDto
    {
        return $this->alarmDto;
    }

    public function getNotifications(): Notifications
    {
        return $this->notifications;
    }

    public function getTaskId(): TaskId
    {
        return $this->taskId;
    }
}
