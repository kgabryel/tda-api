<?php

namespace App\Task\Application\Query\ExistsTaskStatus;

use App\Core\Cqrs\QueryHandler;
use App\Shared\Application\Query\Query;
use App\Task\Domain\TaskStatus;

/**
 * Sprawdza czy istnieje status zadania o podanej nazwie
 */
#[QueryHandler(ExistsTaskStatusQueryHandler::class)]
class ExistsTaskStatus implements Query
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
