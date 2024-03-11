<?php

namespace App\Task\Domain\Event;

use App\Alarm\Domain\Event\Port\AlarmsGroupsModified as AlarmsGroupsModifiedInterface;
use App\Shared\Domain\Entity\AlarmsGroupId;
use App\Shared\Domain\Entity\UserId;

/**
 * Podane alarmy okresowe zostaly zmodyfikowane, zostaly odpiete lub przypiete do zadania
 */
class AlarmsGroupsModified implements AlarmsGroupsModifiedInterface
{
    private UserId $userId;
    private array $ids;

    public function __construct(UserId $userId, AlarmsGroupId ...$ids)
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
