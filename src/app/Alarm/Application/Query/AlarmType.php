<?php

namespace App\Alarm\Application\Query;

use App\Shared\Domain\Entity\AlarmId;
use App\Shared\Domain\Entity\AlarmsGroupId;

enum AlarmType: string
{
    case SINGLE_ALARM = 'single';
    case PERIODIC_ALARM = 'periodic';

    public static function fromId(AlarmId|AlarmsGroupId $id): self
    {
        return $id instanceof AlarmId ? self::SINGLE_ALARM : self::PERIODIC_ALARM;
    }
}
