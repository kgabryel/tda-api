<?php

namespace App\Catalog\Application\Command\UpdateAssignedToDashboard;

use App\Catalog\Application\Command\ModifyCatalogHandler;
use App\Catalog\Domain\Event\Updated;

class UpdateAssignedToDashboardHandler extends ModifyCatalogHandler
{
    public function handle(UpdateAssignedToDashboard $command): void
    {
        $catalog = $this->getCatalog($command->getCatalogId());
        if ($catalog->updateAssignedToDashboard($command->isAssignedToDashboard())) {
            $this->eventEmitter->emit(new Updated($catalog));
        }
    }
}
