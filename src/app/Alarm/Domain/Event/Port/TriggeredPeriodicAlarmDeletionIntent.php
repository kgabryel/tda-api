<?php

namespace App\Alarm\Domain\Event\Port;

use App\Core\Cqrs\Event;
use App\Shared\Domain\Entity\AlarmsGroupId;

/**
 * wywolano intencje usuniecia alarmu okresowego - nalezy wykonac odpowiednia opercje
 */
interface TriggeredPeriodicAlarmDeletionIntent extends Event
{
    public function getAlarmId(): AlarmsGroupId;
}
