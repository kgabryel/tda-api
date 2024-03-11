<?php

namespace App\Task\Application\Query\FindTasksToCreate;

use App\Task\Domain\Repository\TasksWriteRepository;

class FindTasksToCreateQueryHandler
{
    private TasksWriteRepository $repository;

    public function __construct(TasksWriteRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(FindTasksToCreate $query): array
    {
        return $this->repository->findTasksToCreate($query->getDate());
    }
}
