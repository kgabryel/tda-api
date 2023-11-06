<?php

namespace App\Task\Domain\Event;

use App\Alarm\Domain\Event\Port\TriggeredAlarmDeletionIntent;
use App\Shared\Domain\Entity\AlarmId;

/**
 * Podany alarm zostal usuniety
 */
class AlarmDeleted implements TriggeredAlarmDeletionIntent
{
    private AlarmId $alarmId;

    public function __construct(AlarmId $alarmId)
    {
        $this->alarmId = $alarmId;
    }

    public function getAlarmId(): AlarmId
    {
        return $this->alarmId;
    }
}
