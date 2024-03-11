<?php

namespace App\Alarm\Domain\Event\SingleAlarm;

use App\Alarm\Domain\Entity\SingleAlarm;
use App\Core\Cqrs\Event;

/**
 * Alarm pojedynczy zostal zmodyfikowany, trzeba zaktualizowac dane w bazie danych
 */
class Updated implements Event
{
    private SingleAlarm $alarm;

    public function __construct(SingleAlarm $alarm)
    {
        $this->alarm = $alarm;
    }

    public function getAlarm(): SingleAlarm
    {
        return $this->alarm;
    }
}
