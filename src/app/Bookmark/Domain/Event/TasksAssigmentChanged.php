<?php

namespace App\Bookmark\Domain\Event;

use App\Shared\Domain\Entity\TaskId;
use App\Shared\Domain\Entity\UserId;
use App\Task\Domain\Event\Port\TasksModified;

/**
 * Podane zadania zostaly zmodyfikowane, zostaly odpiete lub przypiete do zakladki
 */
class TasksAssigmentChanged implements TasksModified
{
    private array $ids;
    private UserId $userId;

    public function __construct(UserId $userId, TaskId ...$ids)
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
