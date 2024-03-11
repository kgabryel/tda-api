<?php

namespace App\Catalog\Application\Command\RemoveBookmark;

use App\Catalog\Application\Command\ModifyCatalogHandler;
use App\Catalog\Domain\Event\BookmarksAssigmentChanged;

class RemoveBookmarkHandler extends ModifyCatalogHandler
{
    public function handle(RemoveBookmark $command): void
    {
        $catalog = $this->getCatalog($command->getCatalogId());
        if ($catalog->removeBookmark($command->getBookmarkId())) {
            $this->eventEmitter->emit(new BookmarksAssigmentChanged($catalog->getUserId(), $command->getBookmarkId()));
        }
    }
}
