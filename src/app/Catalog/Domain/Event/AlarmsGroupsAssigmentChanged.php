<?php

namespace App\Catalog\Domain\Event;

use App\Alarm\Domain\Event\Port\AlarmsGroupsModified;
use App\Shared\Domain\Entity\AlarmsGroupId;
use App\Shared\Domain\Entity\UserId;

/**
 * Podane alarmy okresowe zostaly zmodyfikowane, zostaly odpiete lub przypiete do katalogu
 */
class AlarmsGroupsAssigmentChanged implements AlarmsGroupsModified
{
    private array $ids;
    private UserId $userId;

    public function __construct(UserId $userId, AlarmsGroupId ...$ids)
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
