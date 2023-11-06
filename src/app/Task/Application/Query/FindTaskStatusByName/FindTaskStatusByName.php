<?php

namespace App\Task\Application\Query\FindTaskStatusByName;

use App\Core\Cqrs\QueryHandler;
use App\Shared\Application\Query\Query;
use App\Task\Domain\TaskStatus;

/**
 * Pobiera status zadania na podstawie nazwy
 */
#[QueryHandler(FindTaskStatusByNameQueryHandler::class)]
class FindTaskStatusByName implements Query
{
    private TaskStatus $name;

    public function __construct(TaskStatus $name)
    {
        $this->name = $name;
    }

    public function getName(): TaskStatus
    {
        return $this->name;
    }
}
