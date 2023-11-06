<?php

namespace App\Task\Infrastructure\Dto;

use App\Shared\Domain\Entity\TaskId;
use App\Task\Domain\Entity\TasksInFuture as TasksInFutureInterface;
use App\Task\Domain\Entity\TasksList as TasksListInterface;
use DateTime;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TasksList implements TasksListInterface
{
    protected HasMany $tasks;

    public function __construct(HasMany $tasks)
    {
        $this->tasks = $tasks;
    }

    public function updateName(string $name): void
    {
        $this->tasks->update(['name' => $name]);
    }

    public function updateContent(?string $content): void
    {
        $this->tasks->update(['content' => $content]);
    }

    public function getIds(): array
    {
        return $this->tasks->pluck('id')->map(static fn(string $id) => new TaskId($id))->toArray();
    }

    public function delete(): void
    {
        $this->tasks->delete();
    }

    public function disconnect(): void
    {
        $this->tasks->update(['group_id' => null]);
    }

    public function getTasksInFuture(): TasksInFutureInterface
    {
        return new TasksInFuture($this->tasks->clone()->where('tasks.date', '>=', new DateTime()));
    }
}
