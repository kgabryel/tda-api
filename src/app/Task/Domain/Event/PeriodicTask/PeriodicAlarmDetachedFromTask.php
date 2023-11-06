<?php

namespace App\Task\Domain\Event\PeriodicTask;

use App\Alarm\Domain\Event\Port\PeriodicAlarmDetachedFromTask as PeriodicAlarmDetachedFromTaskInterface;
use App\Shared\Domain\Entity\AlarmsGroupId;

/**
 * Podany alarm okresowy zostal odpiety od zadania okresowego
 */
class PeriodicAlarmDetachedFromTask implements PeriodicAlarmDetachedFromTaskInterface
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
