<?php

namespace App\File\Application\Query\FindAll;

use App\Core\Cqrs\QueryHandler;
use App\Shared\Application\Query\Query;

/**
 * Pobiera wszystkie pliki
 */
#[QueryHandler(FindAllQueryHandler::class)]
class FindAll implements Query
{
}
