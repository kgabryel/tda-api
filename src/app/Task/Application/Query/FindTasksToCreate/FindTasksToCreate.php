<?php

namespace App\Task\Application\Query\FindTasksToCreate;

use App\Core\Cqrs\QueryHandler;
use App\Shared\Application\Query\Query;
use DateTimeImmutable;

/**
 * Pobiera liste zadan okresowych, dla ktorych trzeba utworzyc pojedyncze zadania.
 * Zadania musza byc aktywne oraz zadanie musi posiadac date koncowa wieksza lub rowna niz podana w parametrze data
 * lub data koncowa musi nie byc ustawiona.
 */
#[QueryHandler(FindTasksToCreateQueryHandler::class)]
class FindTasksToCreate implements Query
{
    private DateTimeImmutable $date;

    public function __construct(DateTimeImmutable $date)
    {
        $this->date = $date;
    }

    public function getDate(): DateTimeImmutable
    {
        return $this->date;
    }
}
