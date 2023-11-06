<?php

namespace App\Alarm\Domain\Event;

use App\Shared\Domain\Entity\TasksGroupId;
use App\Shared\Domain\Entity\UserId;
use App\Task\Domain\Event\Port\TasksGroupsModified as TasksGroupsModifiedInterface;

/**
 * Podane zadania okresowe zostaly zmodyfikowane
 */
class TasksGroupsModified implements TasksGroupsModifiedInterface
{
    private UserId $userId;
    private array $ids;

    public function __construct(UserId $userId, TasksGroupId ...$ids)
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
