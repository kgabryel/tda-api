<?php

namespace App\Task\Domain\Event;

use App\Alarm\Domain\Event\Port\AlarmsModified as AlarmsModifiedInterface;
use App\Shared\Domain\Entity\AlarmId;
use App\Shared\Domain\Entity\UserId;

/**
 * Podane alarmy pojedyczne zostaly zmodyfikowane, zostaly odpiete lub przypiete do zadania
 */
class AlarmsModified implements AlarmsModifiedInterface
{
    private UserId $userId;
    private array $ids;

    public function __construct(UserId $userId, AlarmId ...$ids)
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
