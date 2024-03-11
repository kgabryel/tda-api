<?php

namespace App\Task\Domain\Event;

use App\Alarm\Domain\Event\Port\AlarmDetachedFromTask as AlarmDetachedFromTaskInterface;
use App\Shared\Domain\Entity\AlarmId;

/**
 * Podany alarm zostal odpiety od zadania
 */
class AlarmDetachedFromTask implements AlarmDetachedFromTaskInterface
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
