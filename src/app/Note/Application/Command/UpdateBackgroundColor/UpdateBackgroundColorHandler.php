<?php

namespace App\Note\Application\Command\UpdateBackgroundColor;

use App\Note\Application\Command\ModifyNoteHandler;
use App\Note\Domain\Event\Updated;

class UpdateBackgroundColorHandler extends ModifyNoteHandler
{
    public function handle(UpdateBackgroundColor $command): void
    {
        $note = $this->getNote($command->getNoteId());
        if ($note->updateBackgroundColor($command->getColor())) {
            $this->eventEmitter->emit(new Updated($note));
        }
    }
}
