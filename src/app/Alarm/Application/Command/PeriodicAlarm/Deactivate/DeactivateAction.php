<?php

namespace App\Alarm\Application\Command\PeriodicAlarm\Deactivate;

/**
 * definiuje co ma sie stac z alarmami pojedynczymi, ktorych data jest wieksza niz data aktualna,
 * przy dezaktywowaniu alarmu okresowego
 */
enum DeactivateAction: string
{
    /**
     * alarmy pozostaja bez zmian
     */
    case NOT_MODIFY = 'notModify';

    /**
     * usuwa alarmy
     */
    case DELETE = 'delete';

    /**
     * dezaktywuje alarmy
     */
    case DEACTIVATE = 'deactivate';

    public static function getValues(): array
    {
        return array_map(static fn(self $action) => $action->value, self::cases());
    }
}
