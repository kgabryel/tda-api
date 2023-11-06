<?php

namespace App\Task\Application;

use App\Shared\Domain\Entity\TaskId;
use App\Shared\Domain\Entity\TasksGroupId;
use App\Shared\Domain\Entity\UserId;
use App\Task\Application\Command\PeriodicTask\Create\TaskDto as PeriodicTaskDto;
use App\Task\Application\Command\SingleTask\Create\TaskDto as SingleTaskDto;
use App\Task\Domain\Entity\PeriodicTask;
use App\Task\Domain\Entity\SingleTask;
use App\Task\Domain\Entity\TaskStatus;

interface TaskManagerInterface
{
    public function deleteSingleTask(TaskId $taskId): void;

    public function deletePeriodicTask(TasksGroupId $taskId): void;

    public function updateSingleTask(SingleTask $task): void;

    public function updatePeriodicTask(PeriodicTask $task): void;

    public function createSingleTask(SingleTaskDto $taskDto, UserId $userId, TaskStatus $taskStatus): SingleTask;

    public function createPeriodicTask(PeriodicTaskDto $taskDto, UserId $userId): PeriodicTask;
}
