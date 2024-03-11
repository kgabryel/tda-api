<?php

namespace App\Task\Application\Command\SingleTask\UpdateMainTask;

use App\Shared\Domain\Entity\TaskId;
use App\Task\Domain\TaskStatus;

class StatusChangeDto
{
    private TaskId $taskId;
    private TaskStatus $status;

    public function __construct(TaskId $taskId, TaskStatus $status)
    {
        $this->taskId = $taskId;
        $this->status = $status;
    }

    public function getTaskId(): TaskId
    {
        return $this->taskId;
    }

    public function getStatus(): TaskStatus
    {
        return $this->status;
    }
}
