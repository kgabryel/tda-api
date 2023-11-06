<?php

namespace App\Note\Application\Command\Delete;

use App\Note\Application\Command\ModifyNoteHandler;
use App\Note\Domain\Event\Deleted;
use App\Note\Domain\Event\Removed;

class DeleteHandler extends ModifyNoteHandler
{
    public function handle(Delete $command): void
    {
        $note = $this->getNote($command->getNoteId());
        if (!$note->delete()) {
            return;
        }
        $this->eventEmitter->emit(new Removed($note));
        $this->eventEmitter->emit(new Deleted($note->getNoteId()));
    }
}
