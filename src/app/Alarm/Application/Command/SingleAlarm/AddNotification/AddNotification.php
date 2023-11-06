<?php

namespace App\Alarm\Application\Command\SingleAlarm\AddNotification;

use App\Core\Cqrs\CommandHandler;
use App\Shared\Application\Command\Command;

/**
 * Tworzy nowe powiadomienie dla alarmu pojedynczego
 */
#[CommandHandler(AddNotificationHandler::class)]
class AddNotification implements Command
{
    private NotificationDto $notification;
    private bool $fromGroup;

    public function __construct(NotificationDto $notification, bool $fromGroup = false)
    {
        $this->notification = $notification;
        $this->fromGroup = $fromGroup;
    }

    public function getNotification(): NotificationDto
    {
        return $this->notification;
    }

    public function isFromGroup(): bool
    {
        return $this->fromGroup;
    }
}
