<?php

namespace App\Task\Application\Query\FindTasksToDisable;

use App\Core\Cqrs\QueryHandler;
use App\Shared\Application\Query\Query;

#[QueryHandler(FindTasksToDisableQueryHandler::class)]
class FindTasksToDisable implements Query
{
}
