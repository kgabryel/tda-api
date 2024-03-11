<?php

namespace App\Alarm\Domain\Event\Port;

use App\Core\Cqrs\Event;
use App\Shared\Domain\Entity\AlarmsGroupId;

/**
 * z okresowego zadania zostal usuniety alarm
 */
interface PeriodicAlarmDetachedFromTask extends Event
{
    public function getAlarmId(): AlarmsGroupId;
}
