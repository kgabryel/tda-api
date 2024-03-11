<?php

namespace App\Task\Application\Query\FindTaskStatuses;

use App\Core\Cqrs\QueryHandler;
use App\Shared\Application\Query\Query;

/**
 * Pobiera wszystkie statusy zadan
 */
#[QueryHandler(FindTasksStatusesQueryHandler::class)]
class FindTasksStatuses implements Query
{
}
