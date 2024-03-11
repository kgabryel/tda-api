<?php

namespace App\Note\Application\Command\UpdateCatalogs;

use App\Note\Application\Command\ModifyNoteHandler;
use App\Note\Domain\Event\CatalogsAssigmentChanged;

class UpdateCatalogsHandler extends ModifyNoteHandler
{
    public function handle(UpdateCatalogs $command): void
    {
        $note = $this->getNote($command->getNoteId());
        $changed = $note->updateCatalogs(...$command->getCatalogs());
        if (!$changed->isEmpty()) {
            $this->eventEmitter->emit(new CatalogsAssigmentChanged($note->getUserId(), ...$changed->get()));
        }
    }
}
