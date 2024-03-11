<?php

namespace App\File\Domain\Event;

use App\Shared\Domain\Entity\TasksGroupId;
use App\Shared\Domain\Entity\UserId;
use App\Task\Domain\Event\Port\TasksGroupsModified;

/**
 * Aktualizuje liste zadan okresowych przypisanych do pliku
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
