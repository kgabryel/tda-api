<?php

namespace App\Catalog\Application\Command\UpdateFiles;

use App\Catalog\Application\Command\ModifyCatalogHandler;
use App\Catalog\Domain\Event\FilesAssigmentChanged;

class UpdateFilesHandler extends ModifyCatalogHandler
{
    public function handle(UpdateFiles $command): void
    {
        $catalog = $this->getCatalog($command->getCatalogId());
        $changed = $catalog->updateFiles(...$command->getFiles());
        if (!$changed->isEmpty()) {
            $this->eventEmitter->emit(new FilesAssigmentChanged($catalog->getUserId(), ...$changed->get()));
        }
    }
}
