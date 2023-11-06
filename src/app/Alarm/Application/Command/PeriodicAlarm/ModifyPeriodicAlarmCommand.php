<?php

namespace App\Alarm\Application\Command\PeriodicAlarm;

use App\Shared\Application\Command\Command;
use App\Shared\Domain\Entity\AlarmsGroupId;

abstract class ModifyPeriodicAlarmCommand implements Command
{
    protected AlarmsGroupId $alarmId;

    public function __construct(AlarmsGroupId $alarmId)
    {
        $this->alarmId = $alarmId;
    }

    public function getAlarmId(): AlarmsGroupId
    {
        return $this->alarmId;
    }
}
