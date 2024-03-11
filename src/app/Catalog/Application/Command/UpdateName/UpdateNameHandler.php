<?php

namespace App\Catalog\Application\Command\UpdateName;

use App\Catalog\Application\Command\ModifyCatalogHandler;
use App\Catalog\Domain\Event\Updated;

class UpdateNameHandler extends ModifyCatalogHandler
{
    public function handle(UpdateName $command): void
    {
        $catalog = $this->getCatalog($command->getCatalogId());
        if ($catalog->updateName($command->getName())) {
            $this->eventEmitter->emit(new Updated($catalog));
        }
    }
}
