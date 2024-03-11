<?php

namespace App\Shared\Application\Dto;

class TasksIdsList
{
    private array $ids;

    public function __construct(string ...$ids)
    {
        $this->ids = $ids;
    }

    public function getIds(): array
    {
        return $this->ids;
    }
}
