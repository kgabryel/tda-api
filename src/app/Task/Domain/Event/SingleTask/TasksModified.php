<?php

namespace App\Task\Domain\Event\SingleTask;

use App\Shared\Domain\Entity\TaskId;
use App\Shared\Domain\Entity\UserId;
use App\Task\Domain\Event\Port\TasksModified as TasksModifiedInterface;

/**
 * Podane zadania pojedyncze zostaly zmodyfikowane przez przypiecie lub odpiecie czegos do zadania
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
