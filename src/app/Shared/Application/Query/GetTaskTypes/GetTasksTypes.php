<?php

namespace App\Shared\Application\Query\GetTaskTypes;

use App\Core\Cqrs\QueryHandler;
use App\Shared\Application\Query\Query;

/**
 * Kazdemu zadania przyporzadkowuje typ - zadanie pojedyncze lub zadanie okresowe
 */
#[QueryHandler(GetTasksTypesQueryHandler::class)]
class GetTasksTypes implements Query
{
    private array $ids;

    public function __construct(string ...$ids)
    {
        $this->ids = $ids;
    }

    public function getIds(): array
    {
        return $this->ids;
    }
}
