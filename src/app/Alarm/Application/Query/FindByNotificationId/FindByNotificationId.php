<?php

namespace App\Alarm\Application\Query\FindByNotificationId;

use App\Alarm\Domain\Entity\NotificationId;
use App\Core\Cqrs\QueryHandler;
use App\Shared\Application\Query\Query;

/**
 * pobiera alarm pojedynczy na podstawie id powiadomienia
 */
#[QueryHandler(FindByNotificationIdQueryHandler::class)]
class FindByNotificationId implements Query
{
    private NotificationId $notificationId;

    public function __construct(NotificationId $notificationId)
    {
        $this->notificationId = $notificationId;
    }

    public function getNotificationId(): NotificationId
    {
        return $this->notificationId;
    }
}
