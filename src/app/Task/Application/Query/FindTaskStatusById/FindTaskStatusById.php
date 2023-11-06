<?php

namespace App\Task\Application\Query\FindTaskStatusById;

use App\Core\Cqrs\QueryHandler;
use App\Shared\Application\Query\Query;
use App\Task\Domain\Entity\StatusId;

/**
 * Pobiera status zadania na podstawie id
 */
#[QueryHandler(FindTaskStatusByIdQueryHandler::class)]
class FindTaskStatusById implements Query
{
    private StatusId $id;

    public function __construct(StatusId $id)
    {
        $this->id = $id;
    }

    public function getId(): StatusId
    {
        return $this->id;
    }
}
