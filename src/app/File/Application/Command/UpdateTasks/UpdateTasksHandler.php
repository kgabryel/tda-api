<?php

namespace App\File\Application\Command\UpdateTasks;

use App\File\Application\Command\ModifyFileHandler;
use App\File\Domain\Event\TasksAssigmentChanged;
use App\File\Domain\Event\TasksGroupsAssigmentChanged;
use App\Shared\Application\Query\GetTaskTypes\GetTasksTypes;

class UpdateTasksHandler extends ModifyFileHandler
{
    public function handle(UpdateTasks $command): void
    {
        $tasks = $this->queryBus->handle(new GetTasksTypes(...$command->getTasks()));
        $file = $this->getFile($command->getFileId());
        if (!$tasks->getTasksGroups()->isEmpty()) {
            $changed = $file->updateTasksGroups(...$tasks->getTasksGroups()->toArray());
            if (!$changed->isEmpty()) {
                $this->eventEmitter->emit(new TasksGroupsAssigmentChanged($file->getUserId(), ...$changed->get()));
            }
        }
        if (!$tasks->getTasks()->isEmpty()) {
            $changed = $file->updateTasks(...$tasks->getTasks()->toArray());
            if (!$changed->isEmpty()) {
                $this->eventEmitter->emit(new TasksAssigmentChanged($file->getUserId(), ...$changed->get()));
            }
        }
    }
}
