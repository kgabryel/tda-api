<?php

namespace App\Task\Application\Command\AddNote;

use App\Task\Application\Command\ModifyTaskHandler;
use App\Task\Domain\Event\NotesAssigmentChanged;

class AddNoteHandler extends ModifyTaskHandler
{
    public function handle(AddNote $command): void
    {
        $task = $this->getTask($command->getTaskId());
        if ($task->addNote($command->getNoteId())) {
            $this->eventEmitter->emit(new NotesAssigmentChanged($task->getUserId(), $command->getNoteId()));
        }
    }
}
