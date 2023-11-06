<?php

namespace App\Alarm\Domain\Event\SingleAlarm;

use App\Core\Cqrs\Event;
use App\Shared\Domain\Entity\AlarmId;

/**
 * Alarm pojedynczy zostal usuniety, nalezy usunac go z bazy danych
 */
class Deleted implements Event
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
