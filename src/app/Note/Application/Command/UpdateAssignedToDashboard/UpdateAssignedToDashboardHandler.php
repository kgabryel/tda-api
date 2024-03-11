<?php

namespace App\Note\Application\Command\UpdateAssignedToDashboard;

use App\Note\Application\Command\ModifyNoteHandler;
use App\Note\Domain\Event\Updated;

class UpdateAssignedToDashboardHandler extends ModifyNoteHandler
{
    public function handle(UpdateAssignedToDashboard $command): void
    {
        $note = $this->getNote($command->getNoteId());
        if ($note->updateAssignedToDashboard($command->isAssignedToDashboard())) {
            $this->eventEmitter->emit(new Updated($note));
        }
    }
}
