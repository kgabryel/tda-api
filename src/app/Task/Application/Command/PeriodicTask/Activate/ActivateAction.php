<?php

namespace App\Task\Application\Command\PeriodicTask\Activate;

/**
 * definiuje co ma sie stac z zadaniami pojedynczymi przy aktywowaniu zadania okresowego
 */
enum ActivateAction: string
{
    /**
     * zadania pozostaja bez zmian
     */
    case NOT_MODIFY = 'notModify';
    /**
     * aktywuje zadania, ktore sa nieaktywne, a powinny byc aktywne - ich data jest wieksza niz data aktualna
     */
    case ACTIVATE = 'activate';

    public static function getValues(): array
    {
        return array_map(static fn(self $action) => $action->value, self::cases());
    }
}
