<?php

namespace App\Task\Domain\Event;

use App\Alarm\Domain\Event\Port\AlarmAttachedToTask as AlarmAttachedToTaskInterface;
use App\Shared\Domain\Entity\AlarmId;
use App\Shared\Domain\Entity\TaskId;

/**
 * Podany alarm zostal przypiety do zadania
 */
class AlarmAttachedToTask implements AlarmAttachedToTaskInterface
{
    private AlarmId $alarmId;
    private TaskId $taskId;

    public function __construct(TaskId $taskId, AlarmId $alarmId)
    {
        $this->taskId = $taskId;
        $this->alarmId = $alarmId;
    }

    public function getAlarmId(): AlarmId
    {
        return $this->alarmId;
    }

    public function getTaskId(): TaskId
    {
        return $this->taskId;
    }
}
