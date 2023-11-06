<?php

namespace App\Note\Application\Command\UpdateContent;

use App\Note\Application\Command\ModifyNoteHandler;
use App\Note\Domain\Event\Updated;

class UpdateContentHandler extends ModifyNoteHandler
{
    public function handle(UpdateContent $command): void
    {
        $note = $this->getNote($command->getNoteId());
        if ($note->updateContent($command->getContent())) {
            $this->eventEmitter->emit(new Updated($note));
        }
    }
}
