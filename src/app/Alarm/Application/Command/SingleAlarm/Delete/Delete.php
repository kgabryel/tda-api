<?php

namespace App\Alarm\Application\Command\SingleAlarm\Delete;

use App\Core\Cqrs\CommandHandler;
use App\Shared\Application\Command\Command;
use App\Shared\Domain\Entity\AlarmId;

/**
 * Usuwa alarm pojedynczy
 */
#[CommandHandler(DeleteHandler::class)]
class Delete implements Command
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
