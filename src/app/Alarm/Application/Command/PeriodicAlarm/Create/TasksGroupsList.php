<?php

namespace App\Alarm\Application\Command\PeriodicAlarm\Create;

use App\Shared\Domain\Entity\AlarmId;
use App\Shared\Domain\Entity\TaskId;
use App\Shared\Domain\Entity\TasksGroupId;
use OutOfRangeException;

class TasksGroupsList
{
    private TasksGroupId $tasksGroupId;
    /** @var TaskGroup[] */
    private array $alarmsTasks;

    public function __construct(TasksGroupId $tasksGroupId)
    {
        $this->tasksGroupId = $tasksGroupId;
        $this->alarmsTasks = [];
    }

    public function addGroup(TaskId $taskId, AlarmId $alarmId, int $time): self
    {
        $this->alarmsTasks[] = new TaskGroup($taskId, $alarmId, $time);

        return $this;
    }

    public function getTasksGroupId(): TasksGroupId
    {
        return $this->tasksGroupId;
    }

    public function getByTime(int $time): TaskGroup
    {
        foreach ($this->alarmsTasks as $alarmsTask) {
            if ($alarmsTask->getTime() === $time) {
                return $alarmsTask;
            }
        }
        throw new OutOfRangeException();
    }
}
