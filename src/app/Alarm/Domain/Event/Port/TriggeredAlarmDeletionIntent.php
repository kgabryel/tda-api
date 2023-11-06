<?php

namespace App\Alarm\Domain\Event\Port;

use App\Core\Cqrs\Event;
use App\Shared\Domain\Entity\AlarmId;

/**
 * wywolano intencje usuniecia alarmu pojedynczego - nalezy wykonac odpowiednia opercje
 */
interface TriggeredAlarmDeletionIntent extends Event
{
    public function getAlarmId(): AlarmId;
}
