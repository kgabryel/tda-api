<?php

namespace App\Catalog\Application\Command\UpdateBookmarks;

use App\Catalog\Application\Command\ModifyCatalogHandler;
use App\Catalog\Domain\Event\BookmarksAssigmentChanged;

class UpdateBookmarksHandler extends ModifyCatalogHandler
{
    public function handle(UpdateBookmarks $command): void
    {
        $catalog = $this->getCatalog($command->getCatalogId());
        $changed = $catalog->updateBookmarks(...$command->getBookmarks());
        if (!$changed->isEmpty()) {
            $this->eventEmitter->emit(new BookmarksAssigmentChanged($catalog->getUserId(), ...$changed->get()));
        }
    }
}
