<?php

namespace App\Catalog\Domain\Event;

use App\Alarm\Domain\Event\Port\AlarmsModified;
use App\Shared\Domain\Entity\AlarmId;
use App\Shared\Domain\Entity\UserId;

/**
 * Podane alarmy zostaly zmodyfikowane, zostaly odpiete lub przypiete do katalogu
 */
class AlarmsAssigmentChanged implements AlarmsModified
{
    private array $ids;
    private UserId $userId;

    public function __construct(UserId $userId, AlarmId ...$ids)
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
