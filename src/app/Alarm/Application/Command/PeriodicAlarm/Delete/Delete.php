<?php

namespace App\Alarm\Application\Command\PeriodicAlarm\Delete;

use App\Core\Cqrs\CommandHandler;
use App\Shared\Application\Command\Command;
use App\Shared\Domain\Entity\AlarmsGroupId;

/**
 * Usuwa alarm okresowy
 */
#[CommandHandler(DeleteHandler::class)]
class Delete implements Command
{
    private AlarmsGroupId $alarmId;
    private bool $deleteAlarms;

    public function __construct(AlarmsGroupId $alarmId, bool $deleteAlarms)
    {
        $this->deleteAlarms = $deleteAlarms;
        $this->alarmId = $alarmId;
    }

    public function getAlarmId(): AlarmsGroupId
    {
        return $this->alarmId;
    }

    public function deleteAlarms(): bool
    {
        return $this->deleteAlarms;
    }
}
