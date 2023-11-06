<?php

namespace App\Catalog\Application\Command\UpdateNotes;

use App\Catalog\Application\Command\ModifyCatalogHandler;
use App\Catalog\Domain\Event\NotesAssigmentChanged;

class UpdateNotesHandler extends ModifyCatalogHandler
{
    public function handle(UpdateNotes $command): void
    {
        $catalog = $this->getCatalog($command->getCatalogId());
        $changed = $catalog->updateNotes(...$command->getNotes());
        if (!$changed->isEmpty()) {
            $this->eventEmitter->emit(new NotesAssigmentChanged($catalog->getUserId(), ...$changed->get()));
        }
    }
}
