<?php

namespace App\Task\Application\Command\PeriodicTask\Deactivate;

/**
 * definiuje co ma sie stac z zadaniami pojedynczymi, ktorych data jest wieksza niz data aktualna,
 * przy dezaktywowaniu zadania okresowego
 */
enum DeactivateAction: string
{
    /**
     * zadania pozostaja bez zmian,
     * dezaktywuje powiazany alarm okresowy jezeli istnieje, a pojedyncze alarmy pozostaja bez zmian
     */
    case NOT_MODIFY = 'notModify';
    /**
     * usuwa zadania i alarmy, dezaktywuje powiazany alarm okresowy
     */
    case DELETE = 'delete';

    /**
     * ustawia stasus zadan pojedynczych jako odrzucone (oprocz ukonczonych i nieukonczonych),
     * dezaktywuje alarm okresoy i powiazane alarmy pojedyncze
     */
    case REJECT = 'reject';

    public static function getValues(): array
    {
        return array_map(static fn(self $action) => $action->value, self::cases());
    }
}
