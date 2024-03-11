<?php

namespace App\Alarm\Domain\Event\PeriodicAlarm;

use App\Core\Cqrs\Event;
use App\Shared\Domain\Entity\AlarmsGroupId;

/**
 * Alarm okresowy zostal usuniety, nalezy usunac go z bazy danych
 */
class Deleted implements Event
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
