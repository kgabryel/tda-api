<?php

namespace App\Task\Infrastructure\Dto;

use App\Task\Domain\Entity\SubtasksList as SubtasksListInterface;
use App\Task\Domain\Entity\TaskStatus;
use App\Task\Infrastructure\Model\Task;

class SubtasksList extends TasksList implements SubtasksListInterface
{
    public function disconnect(): void
    {
        $this->tasks->update(['parent_id' => null]);
    }

    public function get(): array
    {
        return $this->tasks->get()->map(static fn(Task $task) => $task->toDomainModel())->toArray();
    }

    public function setStatus(TaskStatus $status): void
    {
        $this->tasks->update(['status_id' => $status->getId()->getValue()]);
    }

    public function isEmpty(): bool
    {
        return $this->tasks->count() === 0;
    }
}
