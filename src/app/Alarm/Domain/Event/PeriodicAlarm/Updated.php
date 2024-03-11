<?php

namespace App\Alarm\Domain\Event\PeriodicAlarm;

use App\Alarm\Domain\Entity\PeriodicAlarm;
use App\Core\Cqrs\Event;

/**
 * Alarm okresowy zostal zmodyfikowany, trzeba zaktualizowac dane w bazie danych
 */
class Updated implements Event
{
    private PeriodicAlarm $alarm;

    public function __construct(PeriodicAlarm $alarm)
    {
        $this->alarm = $alarm;
    }

    public function getAlarm(): PeriodicAlarm
    {
        return $this->alarm;
    }
}
