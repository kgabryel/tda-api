<?php

namespace App\Task\Domain\Repository;

use App\Shared\Domain\Entity\TaskId;
use App\Shared\Domain\Entity\TasksGroupId;
use App\Shared\Domain\Entity\UserId;
use App\Task\Domain\Entity\PeriodicTask;
use App\Task\Domain\Entity\SingleTask;
use DateTimeImmutable;

interface TasksWriteRepository
{
    public function findSingleTaskById(UserId $userId, TaskId $taskId): SingleTask;

    public function findById(UserId $userId, string $taskId): SingleTask|PeriodicTask;

    public function findPeriodicTaskById(UserId $userId, TasksGroupId $taskId): PeriodicTask;

    public function getTasksToDisable(): array;

    public function findTasksToCreate(DateTimeImmutable $date): array;
}
