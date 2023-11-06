<?php

namespace App\Alarm\Application\EventHandler;

use App\Alarm\Application\AlarmManagerInterface;

abstract class EventHandler
{
    protected AlarmManagerInterface $alarmManager;

    public function __construct(AlarmManagerInterface $alarmManager)
    {
        $this->alarmManager = $alarmManager;
    }
}
