<?php

namespace App\Alarm\Domain\Event\SingleAlarm;

use App\Alarm\Domain\Event\Port\AlarmsModified as AlarmsModifiedInterface;
use App\Shared\Domain\Entity\AlarmId;
use App\Shared\Domain\Entity\UserId;

/**
 * Podane alarmy pojedyncze zostaly zmodyfikowane, zostaly odpiete lub przypiete do zadania lub katalogu
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
