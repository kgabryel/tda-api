<?php

namespace App\Task\Application;

use App\Shared\Domain\Entity\TaskId;
use App\Shared\Domain\Entity\TasksGroupId;
use App\Shared\Domain\Entity\UserId;
use App\Task\Application\ViewModel\PeriodicTask;
use App\Task\Application\ViewModel\SingleTask;

interface ReadRepository
{
    public function findSingleTaskById(UserId $userId, TaskId $taskId): SingleTask;

    public function findById(UserId $userId, string $taskId): SingleTask|PeriodicTask;

    public function findPeriodicTaskById(UserId $userId, TasksGroupId $taskId): PeriodicTask;

    public function find(UserId $userId, string ...$tasksIds): array;

    public function findAll(UserId $userId): array;
}
