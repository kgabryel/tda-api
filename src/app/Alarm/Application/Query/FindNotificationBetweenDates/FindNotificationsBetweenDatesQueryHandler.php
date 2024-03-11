<?php

namespace App\Alarm\Application\Query\FindNotificationBetweenDates;

use App\Alarm\Domain\WriteRepository;

class FindNotificationsBetweenDatesQueryHandler
{
    private WriteRepository $repository;

    public function __construct(WriteRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(FindNotificationsBetweenDates $query): array
    {
        return $this->repository->getNotificationsBetweenDates($query->getStart(), $query->getStop());
    }
}
