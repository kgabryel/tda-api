<?php

namespace App\Color\Application\Query\FindAll;

use App\Core\Cqrs\QueryHandler;
use App\Shared\Application\Query\Query;

/**
 * Pobiera wszystkie kolory
 */
#[QueryHandler(FindAllQueryHandler::class)]
class FindAll implements Query
{
}
