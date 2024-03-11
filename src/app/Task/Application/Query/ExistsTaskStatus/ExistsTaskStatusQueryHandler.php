<?php

namespace App\Task\Application\Query\ExistsTaskStatus;

use App\Task\Domain\Repository\TasksStatusesWriteRepository;

class ExistsTaskStatusQueryHandler
{
    protected TasksStatusesWriteRepository $writeRepository;

    public function __construct(TasksStatusesWriteRepository $writeRepository)
    {
        $this->writeRepository = $writeRepository;
    }

    public function handle(ExistsTaskStatus $query): bool
    {
        return $this->writeRepository->exists($query->getName());
    }
}
