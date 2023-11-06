<?php

namespace App\Shared\Application\Query\GetTaskTypes;

use App\Shared\Application\TasksTypesCollection;
use App\Shared\Application\TasksTypesRepository;

class GetTasksTypesQueryHandler
{
    private TasksTypesRepository $statusesRepository;

    public function __construct(TasksTypesRepository $statusesRepository)
    {
        $this->statusesRepository = $statusesRepository;
    }

    public function handle(GetTasksTypes $query): TasksTypesCollection
    {
        return $this->statusesRepository->getTasksTypes(...$query->getIds());
    }
}
