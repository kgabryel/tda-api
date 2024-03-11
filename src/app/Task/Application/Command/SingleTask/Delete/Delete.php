<?php

namespace App\Task\Application\Command\SingleTask\Delete;

use App\Core\Cqrs\CommandHandler;
use App\Shared\Application\Command\Command;
use App\Shared\Domain\Entity\TaskId;

/**
 * Usuwa zadanie pojedyncze
 */
#[CommandHandler(DeleteHandler::class)]
class Delete implements Command
{
    private TaskId $taskId;
    private bool $deleteSubtasks;
    private bool $deleteAlarm;

    public function __construct(TaskId $taskId, bool $deleteSubtasks = false, bool $deleteAlarm = false)
    {
        $this->taskId = $taskId;
        $this->deleteSubtasks = $deleteSubtasks;
        $this->deleteAlarm = $deleteAlarm;
    }

    public function getTaskId(): TaskId
    {
        return $this->taskId;
    }

    public function deleteSubtasks(): bool
    {
        return $this->deleteSubtasks;
    }

    public function deleteAlarm(): bool
    {
        return $this->deleteAlarm;
    }
}
