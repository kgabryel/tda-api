<?php

namespace App\Catalog\Application\Command\RemoveFile;

use App\Catalog\Application\Command\ModifyCatalogHandler;
use App\Catalog\Domain\Event\FilesAssigmentChanged;

class RemoveFileHandler extends ModifyCatalogHandler
{
    public function handle(RemoveFile $command): void
    {
        $catalog = $this->getCatalog($command->getCatalogId());
        if ($catalog->removeFile($command->getFileId())) {
            $this->eventEmitter->emit(new FilesAssigmentChanged($catalog->getUserId(), $command->getFileId()));
        }
    }
}
