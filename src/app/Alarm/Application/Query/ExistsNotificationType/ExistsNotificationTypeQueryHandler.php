<?php

namespace App\Alarm\Application\Query\ExistsNotificationType;

use App\Alarm\Infrastructure\Repository\NotificationsTypesRepository;

class ExistsNotificationTypeQueryHandler
{
    protected NotificationsTypesRepository $notificationsTypesRepository;

    public function __construct(NotificationsTypesRepository $notificationsTypesRepository)
    {
        $this->notificationsTypesRepository = $notificationsTypesRepository;
    }

    public function handle(ExistsNotificationType $query): bool
    {
        return $this->notificationsTypesRepository->exists($query->getName());
    }
}
