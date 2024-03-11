<?php

namespace App\Task\Application\Query\FindTasksToDisable;

use App\Task\Domain\Repository\TasksWriteRepository;

/**
 * Pobiera liste zadan, ktorych termin na wykonanie uplynal
 */
class FindTasksToDisableQueryHandler
{
    private TasksWriteRepository $repository;

    public function __construct(TasksWriteRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(FindTasksToDisable $query): array
    {
        return $this->repository->getTasksToDisable();
    }
}
