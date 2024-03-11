<?php

namespace App\Bookmark\Domain\Event;

use App\Shared\Domain\Entity\TasksGroupId;
use App\Shared\Domain\Entity\UserId;
use App\Task\Domain\Event\Port\TasksGroupsModified;

/**
 * Podane zadania okresowe zostaly zmodyfikowane, zostaly odpiete lub przypiete do zakladki
 */
class TasksGroupsAssigmentChanged implements TasksGroupsModified
{
    private array $ids;
    private UserId $userId;

    public function __construct(UserId $userId, TasksGroupId ...$ids)
    {
        $this->ids = $ids;
        $this->userId = $userId;
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
