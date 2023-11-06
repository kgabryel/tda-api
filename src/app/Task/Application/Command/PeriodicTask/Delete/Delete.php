<?php

namespace App\Task\Application\Command\PeriodicTask\Delete;

use App\Core\Cqrs\CommandHandler;
use App\Shared\Domain\Entity\TasksGroupId;
use App\Task\Application\Command\PeriodicTask\ModifyPeriodicTaskCommand;
use InvalidArgumentException;

/**
 * Usuwa zadanie okresowe
 */
#[CommandHandler(DeleteHandler::class)]
class Delete extends ModifyPeriodicTaskCommand
{
    private bool $deleteTasks;
    private bool $deleteAlarm;

    public function __construct(TasksGroupId $id, bool $deleteTasks = false, bool $deleteAlarm = false)
    {
        parent::__construct($id);
        if (!$deleteAlarm && !$deleteTasks) {
            throw new InvalidArgumentException();
        }
        $this->deleteTasks = $deleteTasks;
        $this->deleteAlarm = $deleteAlarm;
    }

    public function deleteTasks(): bool
    {
        return $this->deleteTasks;
    }

    public function deleteAlarm(): bool
    {
        return $this->deleteAlarm;
    }
}
