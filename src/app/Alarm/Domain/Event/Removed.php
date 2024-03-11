<?php

namespace App\Alarm\Domain\Event;

use App\Alarm\Domain\Entity\PeriodicAlarm;
use App\Alarm\Domain\Entity\SingleAlarm;
use App\Core\Cqrs\Event;

/**
 * Alarm zostal usuniety, nalezy zmodyfikowac powiazane katalogi
 */
class Removed implements Event
{
    private SingleAlarm|PeriodicAlarm $alarm;

    public function __construct(SingleAlarm|PeriodicAlarm $alarm)
    {
        $this->alarm = $alarm;
    }

    public function getAlarm(): SingleAlarm|PeriodicAlarm
    {
        return $this->alarm;
    }
}
