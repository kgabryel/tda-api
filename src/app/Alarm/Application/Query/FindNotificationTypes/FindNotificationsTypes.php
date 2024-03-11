<?php

namespace App\Alarm\Application\Query\FindNotificationTypes;

use App\Core\Cqrs\QueryHandler;
use App\Shared\Application\Query\Query;
use App\Shared\Application\Query\QueryResult;

/**
 * pobiera wszystkie dostepne typy powiadomien
 */
#[QueryHandler(FindNotificationsTypesQueryHandler::class)]
class FindNotificationsTypes implements Query
{
    private QueryResult $queryResult;

    public function __construct(QueryResult $queryResult)
    {
        $this->queryResult = $queryResult;
    }

    public function getQueryResult(): QueryResult
    {
        return $this->queryResult;
    }
}
