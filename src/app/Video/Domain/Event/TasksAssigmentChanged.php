<?php

namespace App\Video\Domain\Event;

use App\Shared\Domain\Entity\TaskId;
use App\Shared\Domain\Entity\UserId;
use App\Task\Domain\Event\Port\TasksModified;

/**
 * Aktualizuje liste zadan przypisanych do filmu
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
