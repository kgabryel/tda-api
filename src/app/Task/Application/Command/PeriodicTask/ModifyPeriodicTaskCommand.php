<?php

namespace App\Task\Application\Command\PeriodicTask;

use App\Shared\Application\Command\Command;
use App\Shared\Domain\Entity\TasksGroupId;

abstract class ModifyPeriodicTaskCommand implements Command
{
    protected TasksGroupId $taskId;

    public function __construct(TasksGroupId $taskId)
    {
        $this->taskId = $taskId;
    }

    public function getTaskId(): TasksGroupId
    {
        return $this->taskId;
    }
}
