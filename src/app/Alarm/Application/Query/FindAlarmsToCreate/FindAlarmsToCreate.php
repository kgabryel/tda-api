<?php

namespace App\Alarm\Application\Query\FindAlarmsToCreate;

use App\Core\Cqrs\QueryHandler;
use App\Shared\Application\Query\Query;
use DateTimeImmutable;

/**
 * Pobiera liste alarmow okresowych, dla ktorych trzeba utworzyc pojedyncze alarmy. Alarmy musza byc aktywne oraz alarm
 * musi posiadac date koncowa wieksza lub rowna niz podana w parametrze data lub data koncowa musi nie byc ustawiona.
 * Nie pobiera alarmow okresowych, ktore maja przypisane zadanie.
 */
#[QueryHandler(FindAlarmsToCreateQueryHandler::class)]
class FindAlarmsToCreate implements Query
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
