<?php

namespace App\Task\Application\Query\FindTaskStatusById;

use App\Task\Domain\Entity\TaskStatus;
use App\Task\Domain\Repository\TasksStatusesWriteRepository;

class FindTaskStatusByIdQueryHandler
{
    protected TasksStatusesWriteRepository $writeRepository;

    public function __construct(TasksStatusesWriteRepository $writeRepository)
    {
        $this->writeRepository = $writeRepository;
    }

    public function handle(FindTaskStatusById $query): TaskStatus
    {
        return $this->writeRepository->findById($query->getId());
    }
}
