<?php

namespace App\Alarm\Domain\Event\Port;

use App\Core\Cqrs\Event;
use App\Shared\Domain\Entity\AlarmId;

/**
 * wywolano intencje dezaktywowania alarmu pojedynczego - nalezy wykonac odpowiednia opercje
 */
interface TriggeredAlarmCheckedIntent extends Event
{
    public function getAlarmId(): AlarmId;
}
