<?php

namespace App\Alarm\Application\Query\FindAlarmsToCreate;

use App\Alarm\Domain\WriteRepository;

class FindAlarmsToCreateQueryHandler
{
    private WriteRepository $repository;

    public function __construct(WriteRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(FindAlarmsToCreate $query): array
    {
        return $this->repository->findAlarmsToCreate($query->getDate());
    }
}
