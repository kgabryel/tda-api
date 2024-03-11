<?php

namespace App\Task\Application\Query;

use App\Shared\Domain\Entity\TaskId;
use App\Shared\Domain\Entity\TasksGroupId;

enum TaskType: string
{
    case SINGLE_TASK = 'single';
    case PERIODIC_TASK = 'periodic';

    public static function fromId(TaskId|TasksGroupId $id): self
    {
        return $id instanceof TaskId ? self::SINGLE_TASK : self::PERIODIC_TASK;
    }
}
