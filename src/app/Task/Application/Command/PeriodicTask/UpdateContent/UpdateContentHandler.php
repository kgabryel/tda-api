<?php

namespace App\Task\Application\Command\PeriodicTask\UpdateContent;

use App\Task\Application\Command\ModifyTaskHandler;
use App\Task\Domain\Event\PeriodicTask\Updated;
use App\Task\Domain\Event\SingleTask\TasksModified;

class UpdateContentHandler extends ModifyTaskHandler
{
    public function handle(UpdateContent $command): void
    {
        $task = $this->getPeriodicTask($command->getTaskId());
        if ($task->updateContent($command->getContent())) {
            $this->eventEmitter->emit(new Updated($task));
            $this->eventEmitter->emit(new TasksModified($task->getUserId(), ...$task->getTasksIds()));
        }
    }
}
