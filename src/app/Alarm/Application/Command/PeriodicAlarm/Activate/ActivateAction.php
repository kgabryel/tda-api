<?php

namespace App\Alarm\Application\Command\PeriodicAlarm\Activate;

/**
 * definiuje co ma sie stac z alarmami pojedynczymi przy aktywowaniu alarmu okresowego
 */
enum ActivateAction: string
{
    /**
     * alarmy pozostaja bez zmian
     */
    case NOT_MODIFY = 'notModify';

    /**
     * aktywuje alarmy, ktore sa nieaktywne, a powinny byc aktywne - ich data jest wieksza niz data aktualna
     */
    case ACTIVATE = 'activate';

    public static function getValues(): array
    {
        return array_map(static fn(self $action) => $action->value, self::cases());
    }
}
