<?php

namespace App\Alarm\Domain\Event\Port;

use App\Core\Cqrs\Event;
use App\Shared\Domain\Entity\AlarmId;

/**
 * z pojedynczego zadania zostal usuniety alarm
 */
interface AlarmDetachedFromTask extends Event
{
    public function getAlarmId(): AlarmId;
}
