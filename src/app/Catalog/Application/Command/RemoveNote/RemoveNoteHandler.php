<?php

namespace App\Catalog\Application\Command\RemoveNote;

use App\Catalog\Application\Command\ModifyCatalogHandler;
use App\Catalog\Domain\Event\NotesAssigmentChanged;

class RemoveNoteHandler extends ModifyCatalogHandler
{
    public function handle(RemoveNote $command): void
    {
        $catalog = $this->getCatalog($command->getCatalogId());
        if ($catalog->removeNote($command->getNoteId())) {
            $this->eventEmitter->emit(new NotesAssigmentChanged($catalog->getUserId(), $command->getNoteId()));
        }
    }
}
