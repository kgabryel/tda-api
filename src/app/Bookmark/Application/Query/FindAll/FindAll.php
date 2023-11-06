<?php

namespace App\Bookmark\Application\Query\FindAll;

use App\Core\Cqrs\QueryHandler;
use App\Shared\Application\Query\Query;

/**
 * Pobiera wszystkie zakladki
 */
#[QueryHandler(FindAllQueryHandler::class)]
class FindAll implements Query
{
}
