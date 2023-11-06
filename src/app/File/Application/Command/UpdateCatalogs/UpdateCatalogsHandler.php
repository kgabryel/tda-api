<?php

namespace App\File\Application\Command\UpdateCatalogs;

use App\File\Application\Command\ModifyFileHandler;
use App\File\Domain\Event\CatalogsAssigmentChanged;

class UpdateCatalogsHandler extends ModifyFileHandler
{
    public function handle(UpdateCatalogs $command): void
    {
        $file = $this->getFile($command->getFileId());
        $changed = $file->updateCatalogs(...$command->getCatalogs());
        if (!$changed->isEmpty()) {
            $this->eventEmitter->emit(new CatalogsAssigmentChanged($file->getUserId(), ...$changed->get()));
        }
    }
}
