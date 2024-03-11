<?php

namespace App\Alarm\Application\Command\PeriodicAlarm\Activate;

use App\Alarm\Application\Command\PeriodicAlarm\ModifyPeriodicAlarmCommand;
use App\Core\Cqrs\CommandHandler;
use App\Shared\Domain\Entity\AlarmsGroupId;

/**
 * Aktywuje alarm okresowy
 */
#[CommandHandler(ActivateHandler::class)]
class Activate extends ModifyPeriodicAlarmCommand
{
    private ActivateAction $action;

    public function __construct(AlarmsGroupId $id, ActivateAction $action)
    {
        parent::__construct($id);
        $this->action = $action;
    }

    public function getAction(): ActivateAction
    {
        return $this->action;
    }
}
