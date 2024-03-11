<?php

namespace App\Alarm\Application\Command\SingleAlarm;

use App\Shared\Application\Command\Command;
use App\Shared\Domain\Entity\AlarmId;

abstract class ModifySingleAlarmCommand implements Command
{
    protected AlarmId $alarmId;

    public function __construct(AlarmId $alarmId)
    {
        $this->alarmId = $alarmId;
    }

    public function getAlarmId(): AlarmId
    {
        return $this->alarmId;
    }
}
