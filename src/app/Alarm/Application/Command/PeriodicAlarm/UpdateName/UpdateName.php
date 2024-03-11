<?php

namespace App\Alarm\Application\Command\PeriodicAlarm\UpdateName;

use App\Alarm\Application\Command\PeriodicAlarm\ModifyPeriodicAlarmCommand;
use App\Core\Cqrs\CommandHandler;
use App\Shared\Domain\Entity\AlarmsGroupId;

/**
 * Aktualizuje nazwe alarmu okresowego
 */
#[CommandHandler(UpdateNameHandler::class)]
class UpdateName extends ModifyPeriodicAlarmCommand
{
    private string $name;

    public function __construct(AlarmsGroupId $id, string $name)
    {
        parent::__construct($id);
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
