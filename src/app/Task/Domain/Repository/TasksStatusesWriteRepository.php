<?php

namespace App\Task\Domain\Repository;

use App\Task\Domain\Entity\StatusId;
use App\Task\Domain\Entity\TaskStatus;
use App\Task\Domain\TaskStatus as TaskStatusName;

interface TasksStatusesWriteRepository
{
    public function findById(StatusId $statusId): TaskStatus;

    public function findByName(TaskStatusName $name): TaskStatus;

    public function exists(TaskStatusName $name): bool;
}
