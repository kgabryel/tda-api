<?php

namespace App\Alarm\Application\Query\FindNotificationTypes;

use App\Alarm\Application\NotificationsTypesRepository;
use App\Shared\Application\Query\QueryResult;

class FindNotificationsTypesQueryHandler
{
    private NotificationsTypesRepository $repository;

    public function __construct(NotificationsTypesRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(FindNotificationsTypes $query): array
    {
        if ($query->getQueryResult() === QueryResult::VIEW_MODEL) {
            return $this->repository->findAllAsViewModels();
        }

        return $this->repository->findAllAsDomainModels();
    }
}
