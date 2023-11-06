<?php

namespace App\Task\Application\EventHandler;

use App\Task\Application\TaskManagerInterface;

abstract class EventHandler
{
    protected TaskManagerInterface $taskManager;

    public function __construct(TaskManagerInterface $taskManager)
    {
        $this->taskManager = $taskManager;
    }
}
