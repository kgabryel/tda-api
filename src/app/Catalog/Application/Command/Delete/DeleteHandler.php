<?php

namespace App\Catalog\Application\Command\Delete;

use App\Catalog\Application\Command\ModifyCatalogHandler;
use App\Catalog\Domain\Event\Deleted;
use App\Catalog\Domain\Event\Removed;

class DeleteHandler extends ModifyCatalogHandler
{
    public function handle(Delete $command): void
    {
        $catalog = $this->getCatalog($command->getCatalogId());
        if (!$catalog->delete()) {
            return;
        }
        $this->eventEmitter->emit(new Removed($catalog));
        $this->eventEmitter->emit(new Deleted($catalog->getCatalogId()));
    }
}
