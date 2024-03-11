<?php

namespace App\Task\Application\Command\SingleTask;

use App\Shared\Application\Command\Command;
use App\Shared\Domain\Entity\TaskId;

abstract class ModifySingleTaskCommand implements Command
{
    protected TaskId $taskId;

    public function __construct(TaskId $taskId)
    {
        $this->taskId = $taskId;
    }

    public function getTaskId(): TaskId
    {
        return $this->taskId;
    }
}
