<?php

namespace App\Task\Application\Query\FindAll;

use App\Core\Cqrs\QueryHandler;
use App\Shared\Application\Query\Query;

/**
 * Pobiera wszystkie zadania
 */
#[QueryHandler(FindAllQueryHandler::class)]
class FindAll implements Query
{
}
