<?php

namespace App\Note\Application\Command\UpdateTasks;

use App\Note\Application\Command\ModifyNoteHandler;
use App\Note\Domain\Event\TasksAssigmentChanged;
use App\Note\Domain\Event\TasksGroupsAssigmentChanged;
use App\Shared\Application\Query\GetTaskTypes\GetTasksTypes;

class UpdateTasksHandler extends ModifyNoteHandler
{
    public function handle(UpdateTasks $command): void
    {
        $tasks = $this->queryBus->handle(new GetTasksTypes(...$command->getTasks()));
        $note = $this->getNote($command->getNoteId());
        if (!$tasks->getTasksGroups()->isEmpty()) {
            $changed = $note->updateTasksGroups(...$tasks->getTasksGroups()->toArray());
            if (!$changed->isEmpty()) {
                $this->eventEmitter->emit(new TasksGroupsAssigmentChanged($note->getUserId(), ...$changed->get()));
            }
        }
        if (!$tasks->getTasks()->isEmpty()) {
            $changed = $note->updateTasks(...$tasks->getTasks()->toArray());
            if (!$changed->isEmpty()) {
                $this->eventEmitter->emit(new TasksAssigmentChanged($note->getUserId(), ...$changed->get()));
            }
        }
    }
}
