<?php

namespace App\Alarm\Domain\Event;

use App\Shared\Domain\Entity\TaskId;
use App\Shared\Domain\Entity\UserId;
use App\Task\Domain\Event\Port\TasksModified as TasksModifiedInterface;

/**
 * Podane zadania zostaly zmodyfikowane
 */
class TasksModified implements TasksModifiedInterface
{
    private UserId $userId;
    private array $ids;

    public function __construct(UserId $userId, TaskId ...$ids)
    {
        $this->userId = $userId;
        $this->ids = $ids;
    }

    public function getIds(): array
    {
        return $this->ids;
    }

    public function getUserId(): UserId
    {
        return $this->userId;
    }
}
