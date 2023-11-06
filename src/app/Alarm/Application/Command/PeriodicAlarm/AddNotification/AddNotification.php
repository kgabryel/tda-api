<?php

namespace App\Alarm\Application\Command\PeriodicAlarm\AddNotification;

use App\Core\Cqrs\CommandHandler;
use App\Shared\Application\Command\Command;
use App\Shared\Domain\Entity\AlarmsGroupId;

/**
 * Tworzy nowe powiadomienie dla alarmu okresowego
 */
#[CommandHandler(AddNotificationHandler::class)]
class AddNotification implements Command
{
    private AlarmsGroupId $alarmId;
    private NotificationDto $notification;

    public function __construct(AlarmsGroupId $alarmId, NotificationDto $notification)
    {
        $this->alarmId = $alarmId;
        $this->notification = $notification;
    }

    public function getNotification(): NotificationDto
    {
        return $this->notification;
    }

    public function getAlarmId(): AlarmsGroupId
    {
        return $this->alarmId;
    }
}
