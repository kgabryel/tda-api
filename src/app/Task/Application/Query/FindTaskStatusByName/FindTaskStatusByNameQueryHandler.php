<?php

namespace App\Task\Application\Query\FindTaskStatusByName;

use App\Task\Domain\Entity\TaskStatus;
use App\Task\Domain\Repository\TasksStatusesWriteRepository;

class FindTaskStatusByNameQueryHandler
{
    protected TasksStatusesWriteRepository $writeRepository;

    public function __construct(TasksStatusesWriteRepository $writeRepository)
    {
        $this->writeRepository = $writeRepository;
    }

    public function handle(FindTaskStatusByName $query): TaskStatus
    {
        return $this->writeRepository->findByName($query->getName());
    }
}
