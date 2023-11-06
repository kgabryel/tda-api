<?php

namespace App\Alarm\Application\Query\FindAll;

use App\Core\Cqrs\QueryHandler;
use App\Shared\Application\Query\Query;

/**
 * Pobiera wszystkie alarmy
 */
#[QueryHandler(FindAllQueryHandler::class)]
class FindAll implements Query
{
}
