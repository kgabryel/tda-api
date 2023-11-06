<?php

namespace App\Task\Application\Command\PeriodicTask\UpdateName;

use App\Task\Application\Command\ModifyTaskHandler;
use App\Task\Domain\Event\PeriodicTask\Updated;
use App\Task\Domain\Event\SingleTask\TasksModified;

class UpdateNameHandler extends ModifyTaskHandler
{
    public function handle(UpdateName $command): void
    {
        $task = $this->getPeriodicTask($command->getTaskId());
        if ($task->updateName($command->getName())) {
            $this->eventEmitter->emit(new Updated($task));
            $this->eventEmitter->emit(new TasksModified($task->getUserId(), ...$task->getTasksIds()));
        }
    }
}
