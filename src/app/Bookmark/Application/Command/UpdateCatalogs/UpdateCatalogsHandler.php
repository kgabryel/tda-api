<?php

namespace App\Bookmark\Application\Command\UpdateCatalogs;

use App\Bookmark\Application\Command\ModifyBookmarkHandler;
use App\Bookmark\Domain\Event\CatalogsAssigmentChanged;

class UpdateCatalogsHandler extends ModifyBookmarkHandler
{
    public function handle(UpdateCatalogs $command): void
    {
        $bookmark = $this->getBookmark($command->getBookmarkId());
        $changed = $bookmark->updateCatalogs(...$command->getCatalogs());
        if (!$changed->isEmpty()) {
            $this->eventEmitter->emit(new CatalogsAssigmentChanged($bookmark->getUserId(), ...$changed->get()));
        }
    }
}
