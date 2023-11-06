<?php

namespace App\Task\Application\Command\RemoveNote;

use App\Task\Application\Command\ModifyTaskHandler;
use App\Task\Domain\Event\NotesAssigmentChanged;

class RemoveNoteHandler extends ModifyTaskHandler
{
    public function handle(RemoveNote $command): void
    {
        $task = $this->getTask($command->getTaskId());
        if ($task->removeNote($command->getNoteId())) {
            $this->eventEmitter->emit(new NotesAssigmentChanged($task->getUserId(), $command->getNoteId()));
        }
    }
}
