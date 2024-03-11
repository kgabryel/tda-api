<?php

namespace App\Note\Application\Query\FindAll;

use App\Core\Cqrs\QueryHandler;
use App\Shared\Application\Query\Query;

/**
 * Pobiera wszystkie notatki
 */
#[QueryHandler(FindAllQueryHandler::class)]
class FindAll implements Query
{
}
