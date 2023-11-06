<?php

namespace App\Alarm\Application\Command\SingleAlarm\UpdateName;

use App\Alarm\Application\Command\SingleAlarm\ModifySingleAlarmCommand;
use App\Core\Cqrs\CommandHandler;
use App\Shared\Domain\Entity\AlarmId;

/**
 * Aktualizuje nazwe alarmu pojedynczego
 */
#[CommandHandler(UpdateNameHandler::class)]
class UpdateName extends ModifySingleAlarmCommand
{
    private string $name;

    public function __construct(AlarmId $id, string $name)
    {
        parent::__construct($id);
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
