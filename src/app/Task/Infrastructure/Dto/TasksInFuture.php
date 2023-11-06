<?php

namespace App\Task\Infrastructure\Dto;

use App\Shared\Domain\Entity\TaskId;
use App\Task\Domain\Entity\TasksInFuture as TasksInFutureInterface;
use App\Task\Domain\Entity\TaskStatus;
use App\Task\Infrastructure\Model\Task;
use Illuminate\Database\Eloquent\Builder;

class TasksInFuture implements TasksInFutureInterface
{
    protected Builder $tasks;

    public function __construct(Builder $tasks)
    {
        $this->tasks = $tasks;
    }

    public function getIds(): array
    {
        return $this->tasks->pluck('id')->map(static fn(string $id) => new TaskId($id))->toArray();
    }

    public function reject(TaskStatus $rejectStatus, TaskStatus $doneStatus, TaskStatus $undoneStatus): void
    {
        $this->tasks->clone()
            ->whereNotIn('status_id', [$doneStatus->getId(), $undoneStatus->getId()])
            ->update(['status_id' => $rejectStatus->getId()]);
    }

    public function delete(): void
    {
        $this->tasks->delete();
    }

    public function get(): array
    {
        return $this->tasks->get()->map(static fn(Task $task) => $task->toDomainModel())->toArray();
    }

    public function activate(TaskStatus $taskStatus): void
    {
        $this->tasks->update(['status_id' => $taskStatus->getId()]);
    }
}
