<?php

namespace App\Note\Application\Command\UpdateTextColor;

use App\Note\Application\Command\ModifyNoteHandler;
use App\Note\Domain\Event\Updated;

class UpdateTextColorHandler extends ModifyNoteHandler
{
    public function handle(UpdateTextColor $command): void
    {
        $note = $this->getNote($command->getNoteId());
        if ($note->updateTextColor($command->getColor())) {
            $this->eventEmitter->emit(new Updated($note));
        }
    }
}
