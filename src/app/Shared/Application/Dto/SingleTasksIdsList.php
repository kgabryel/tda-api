<?php

namespace App\Shared\Application\Dto;

use App\Shared\Domain\Entity\TaskId;

class SingleTasksIdsList
{
    private array $ids;

    public function __construct(TaskId ...$ids)
    {
        $this->ids = $ids;
    }

    public function get(): array
    {
        return $this->ids;
    }

    public function add(TaskId $taskId): void
    {
        $this->ids[] = $taskId;
    }

    public function getIds(): array
    {
        return array_map(static fn(TaskId $taskId) => $taskId->getValue(), $this->ids);
    }
}
