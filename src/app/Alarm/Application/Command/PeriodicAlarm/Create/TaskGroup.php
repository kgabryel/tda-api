<?php

namespace App\Alarm\Application\Command\PeriodicAlarm\Create;

use App\Shared\Domain\Entity\AlarmId;
use App\Shared\Domain\Entity\TaskId;

class TaskGroup
{
    private TaskId $taskId;
    private AlarmId $alarmId;
    private int $time;

    public function __construct(TaskId $taskId, AlarmId $alarmId, int $time)
    {
        $this->taskId = $taskId;
        $this->alarmId = $alarmId;
        $this->time = $time;
    }

    public function getTaskId(): TaskId
    {
        return $this->taskId;
    }

    public function getAlarmId(): AlarmId
    {
        return $this->alarmId;
    }

    public function getTime(): int
    {
        return $this->time;
    }
}
