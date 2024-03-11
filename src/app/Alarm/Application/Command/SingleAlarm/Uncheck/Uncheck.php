<?php

namespace App\Alarm\Application\Command\SingleAlarm\Uncheck;

use App\Core\Cqrs\CommandHandler;
use App\Shared\Application\Command\Command;
use App\Shared\Domain\Entity\AlarmId;

/**
 * Aktywuje pojedynczy alarm
 */
#[CommandHandler(UncheckHandler::class)]
class Uncheck implements Command
{
    private AlarmId $alarmId;

    public function __construct(AlarmId $alarmId)
    {
        $this->alarmId = $alarmId;
    }

    public function getAlarmId(): AlarmId
    {
        return $this->alarmId;
    }
}
