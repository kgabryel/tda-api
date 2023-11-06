<?php

namespace App\Alarm\Application\Command\PeriodicAlarm\Create;

use App\Core\Cqrs\CommandHandler;
use App\Shared\Application\Command\Command;

/**
 * Tworzy nowy alarm okresowy
 */
#[CommandHandler(CreateHandler::class)]
class Create implements Command
{
    private AlarmDto $alarm;
    private Notifications $notifications;
    private ?TasksGroupsList $taskGroup;

    public function __construct(AlarmDto $alarm, Notifications $notifications, ?TasksGroupsList $taskGroup = null)
    {
        $this->alarm = $alarm;
        $this->notifications = $notifications;
        $this->taskGroup = $taskGroup;
    }

    public function getAlarm(): AlarmDto
    {
        return $this->alarm;
    }

    public function getNotifications(): Notifications
    {
        return $this->notifications;
    }

    public function getTaskGroup(): ?TasksGroupsList
    {
        return $this->taskGroup;
    }
}
