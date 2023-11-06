<?php

namespace App\Task\Application\Command\SingleTask\Create;

use App\Core\Cqrs\CommandHandler;
use App\Shared\Application\Command\Command;

/**
 * Tworzy nowe zadanie pojedyncze
 */
#[CommandHandler(CreateHandler::class)]
class Create implements Command
{
    private TaskDto $task;
    private ?AlarmDto $alarm;

    public function __construct(TaskDto $task, ?AlarmDto $alarm = null)
    {
        $this->task = $task;
        $this->alarm = $alarm;
    }

    public function getTask(): TaskDto
    {
        return $this->task;
    }

    public function getAlarm(): ?AlarmDto
    {
        return $this->alarm;
    }
}
