<?php

namespace App\Alarm\Application\Query\FindByNotificationsGroupId;

use App\Alarm\Domain\Entity\NotificationsGroupId;
use App\Core\Cqrs\QueryHandler;
use App\Shared\Application\Query\Query;

/**
 * pobiera alarm okresowy na podstawie id powiadomienia
 */
#[QueryHandler(FindByNotificationsGroupIdQueryHandler::class)]
class FindByNotificationsGroupId implements Query
{
    private NotificationsGroupId $notificationId;

    public function __construct(NotificationsGroupId $notificationId)
    {
        $this->notificationId = $notificationId;
    }

    public function getNotificationId(): NotificationsGroupId
    {
        return $this->notificationId;
    }
}
