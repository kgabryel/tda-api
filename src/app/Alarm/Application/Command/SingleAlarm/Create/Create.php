<?php

namespace App\Alarm\Application\Command\SingleAlarm\Create;

use App\Core\Cqrs\CommandHandler;
use App\Shared\Application\Command\Command;

/**
 * Tworzy nowy alarm pojedynczy
 */
#[CommandHandler(CreateHandler::class)]
class Create implements Command
{
    private AlarmDto $alarm;
    private Notifications $notifications;
    private bool $fromGroup;

    public function __construct(AlarmDto $alarm, Notifications $notifications, bool $fromGroup = false)
    {
        $this->alarm = $alarm;
        $this->notifications = $notifications;
        $this->fromGroup = $fromGroup;
    }

    public function getNotifications(): Notifications
    {
        return $this->notifications;
    }

    public function getAlarm(): AlarmDto
    {
        return $this->alarm;
    }

    public function isFromGroup(): bool
    {
        return $this->fromGroup;
    }
}
