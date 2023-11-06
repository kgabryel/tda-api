<?php

namespace App\Alarm\Application\Command\PeriodicAlarm\Deactivate;

use App\Alarm\Application\Command\PeriodicAlarm\ModifyPeriodicAlarmCommand;
use App\Core\Cqrs\CommandHandler;
use App\Shared\Domain\Entity\AlarmsGroupId;

/**
 * Dezaktywuje alarm okresowy
 */
#[CommandHandler(DeactivateHandler::class)]
class Deactivate extends ModifyPeriodicAlarmCommand
{
    private DeactivateAction $action;

    public function __construct(AlarmsGroupId $id, DeactivateAction $action)
    {
        parent::__construct($id);
        $this->action = $action;
    }

    public function getAction(): DeactivateAction
    {
        return $this->action;
    }
}
