<?php

namespace App\Alarm\Application\Query\FindNotificationBetweenDates;

use App\Core\Cqrs\QueryHandler;
use App\Shared\Application\Query\Query;
use DateTimeImmutable;

/**
 * pobiera liste powiadomien w podanym zakresie dat
 */
#[QueryHandler(FindNotificationsBetweenDatesQueryHandler::class)]
class FindNotificationsBetweenDates implements Query
{
    private DateTimeImmutable $start;
    private DateTimeImmutable $stop;

    public function __construct(DateTimeImmutable $start, DateTimeImmutable $stop)
    {
        $this->start = $start;
        $this->stop = $stop;
    }

    public function getStart(): DateTimeImmutable
    {
        return $this->start;
    }

    public function getStop(): DateTimeImmutable
    {
        return $this->stop;
    }
}
