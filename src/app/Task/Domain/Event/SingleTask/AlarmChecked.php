<?php

namespace App\Task\Domain\Event\SingleTask;

use App\Alarm\Domain\Event\Port\TriggeredAlarmCheckedIntent;
use App\Shared\Domain\Entity\AlarmId;

class AlarmChecked implements TriggeredAlarmCheckedIntent
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
