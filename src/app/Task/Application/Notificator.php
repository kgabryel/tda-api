<?php

namespace App\Task\Application;

use App\Shared\Domain\Entity\TaskId;
use App\Shared\Domain\Entity\TasksGroupId;
use App\Shared\Domain\Entity\UserId;

interface Notificator
{
    public function tasksChanges(UserId|int $user, TaskId|TasksGroupId ...$ids): void;
}
