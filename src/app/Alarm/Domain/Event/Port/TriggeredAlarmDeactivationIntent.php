<?php

namespace App\Alarm\Domain\Event\Port;

use App\Core\Cqrs\Event;
use App\Shared\Domain\Entity\AlarmsGroupId;

/**
 * wywolano intencje dezaktywowania alarmu okresowego - nalezy wykonac odpowiednia opercje
 */
interface TriggeredAlarmDeactivationIntent extends Event
{
    public function getAlarmId(): AlarmsGroupId;

    public function getAction(): string;
}
