<?php

namespace App\Alarm\Application\Command\SingleAlarm\UpdateTask;

use App\Alarm\Application\Command\SingleAlarm\ModifySingleAlarmCommand;
use App\Core\Cqrs\CommandHandler;
use App\Shared\Domain\Entity\AlarmId;
use App\Shared\Domain\Entity\TaskId;

/**
 * Aktualizuje zadanie przypisane do alarmu pojedynczego. Przypina nowy lub odpina aktualny
 */
#[CommandHandler(UpdateTaskHandler::class)]
class UpdateTask extends ModifySingleAlarmCommand
{
    private ?TaskId $taskId;

    public function __construct(AlarmId $id, ?TaskId $taskId)
    {
        parent::__construct($id);
        $this->taskId = $taskId;
    }

    public function getTaskId(): ?TaskId
    {
        return $this->taskId;
    }
}
