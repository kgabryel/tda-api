<?php

namespace App\Alarm\Application\Query\ExistsNotificationType;

use App\Alarm\Domain\Entity\NotificationTypeValue;
use App\Core\Cqrs\QueryHandler;
use App\Shared\Application\Query\Query;

/**
 * Sprawdza czy istnieje typ powiadomienia o podanej nazwie
 */
#[QueryHandler(ExistsNotificationTypeQueryHandler::class)]
class ExistsNotificationType implements Query
{
    private NotificationTypeValue $name;

    public function __construct(NotificationTypeValue $name)
    {
        $this->name = $name;
    }

    public function getName(): NotificationTypeValue
    {
        return $this->name;
    }
}
