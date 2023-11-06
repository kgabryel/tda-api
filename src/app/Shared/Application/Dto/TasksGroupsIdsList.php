<?php

namespace App\Shared\Application\Dto;

use App\Shared\Domain\Entity\TasksGroupId;

class TasksGroupsIdsList
{
    private array $ids;

    public function __construct(TasksGroupId ...$ids)
    {
        $this->ids = $ids;
    }

    public function get(): array
    {
        return $this->ids;
    }

    public function getIds(): array
    {
        return array_map(static fn(TasksGroupId $tasksGroupId) => $tasksGroupId->getValue(), $this->ids);
    }
}
