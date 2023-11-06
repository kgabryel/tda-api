<?php

namespace App\Task\Domain\Event\PeriodicTask;

use App\Alarm\Domain\Event\Port\TriggeredPeriodicAlarmDeletionIntent;
use App\Shared\Domain\Entity\AlarmsGroupId;

/**
 * Podczas usuwania zadania okresowego usunieto takze podany alarm okresowy
 */
class PeriodicAlarmDeleted implements TriggeredPeriodicAlarmDeletionIntent
{
    private AlarmsGroupId $alarmId;

    public function __construct(AlarmsGroupId $alarmId)
    {
        $this->alarmId = $alarmId;
    }

    public function getAlarmId(): AlarmsGroupId
    {
        return $this->alarmId;
    }
}
