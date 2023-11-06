<?php

namespace App\Task\Application\Command\PeriodicTask\Create;

use App\Core\Cqrs\CommandHandler;
use App\Shared\Application\Command\Command;

/**
 * Tworzy nowe zadanie okresowe
 */
#[CommandHandler(CreateHandler::class)]
class Create implements Command
{
    private TaskDto $taskDto;
    private ?AlarmDto $alarmDto;

    public function __construct(TaskDto $taskDto, ?AlarmDto $alarmDto = null)
    {
        $this->taskDto = $taskDto;
        $this->alarmDto = $alarmDto;
    }

    public function getTask(): TaskDto
    {
        return $this->taskDto;
    }

    public function getAlarm(): ?AlarmDto
    {
        return $this->alarmDto;
    }
}
