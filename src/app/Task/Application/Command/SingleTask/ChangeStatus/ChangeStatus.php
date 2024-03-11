<?php

namespace App\Task\Application\Command\SingleTask\ChangeStatus;

use App\Core\Cqrs\CommandHandler;
use App\Shared\Application\Command\Command;
use App\Shared\Domain\Entity\TaskId;
use App\Task\Domain\Entity\StatusId;

/**
 * Zmienia status zadania pojedynczego
 */
#[CommandHandler(ChangeStatusHandler::class)]
class ChangeStatus implements Command
{
    private TaskId $taskId;
    private StatusId $statusId;

    public function __construct(TaskId $taskId, StatusId $statusId)
    {
        $this->taskId = $taskId;
        $this->statusId = $statusId;
    }

    public function getTaskId(): TaskId
    {
        return $this->taskId;
    }

    public function getStatusId(): StatusId
    {
        return $this->statusId;
    }
}
