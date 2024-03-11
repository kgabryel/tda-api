<?php

namespace App\Alarm\Application\Command\SingleAlarm\Check;

use App\Core\Cqrs\CommandHandler;
use App\Shared\Application\Command\Command;
use App\Shared\Domain\Entity\AlarmId;

/**
 * Dezaktywuje alarm pojedynczy
 */
#[CommandHandler(CheckHandler::class)]
class Check implements Command
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
