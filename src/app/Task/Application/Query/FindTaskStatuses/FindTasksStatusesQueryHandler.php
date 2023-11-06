<?php

namespace App\Task\Application\Query\FindTaskStatuses;

use App\Task\Application\TasksStatusesRepository;

class FindTasksStatusesQueryHandler
{
    private TasksStatusesRepository $repository;

    public function __construct(TasksStatusesRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(FindTasksStatuses $query): array
    {
        return $this->repository->findAll();
    }
}
