<?php

namespace App\Alarm\Application\Command\SingleAlarm\AddTask;

use App\Alarm\Application\Command\SingleAlarm\ModifySingleAlarmCommand;
use App\Core\Cqrs\CommandHandler;
use App\Shared\Domain\Entity\AlarmId;
use App\Shared\Domain\Entity\TaskId;

/**
 * Przypisuje zadanie do alarmu pojedynczego
 */
#[CommandHandler(AddTaskHandler::class)]
class AddTask extends ModifySingleAlarmCommand
{
    private TaskId $taskId;

    public function __construct(AlarmId $id, TaskId $taskId)
    {
        parent::__construct($id);
        $this->taskId = $taskId;
    }

    public function getTaskId(): TaskId
    {
        return $this->taskId;
    }
}
