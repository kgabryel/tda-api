<?php

namespace App\Note\Application\Command\UpdateName;

use App\Note\Application\Command\ModifyNoteHandler;
use App\Note\Domain\Event\Updated;

class UpdateNameHandler extends ModifyNoteHandler
{
    public function handle(UpdateName $command): void
    {
        $note = $this->getNote($command->getNoteId());
        if ($note->updateName($command->getName())) {
            $this->eventEmitter->emit(new Updated($note));
        }
    }
}
