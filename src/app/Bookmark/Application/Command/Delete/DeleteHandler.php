<?php

namespace App\Bookmark\Application\Command\Delete;

use App\Bookmark\Application\Command\ModifyBookmarkHandler;
use App\Bookmark\Domain\Event\Deleted;
use App\Bookmark\Domain\Event\Removed;

class DeleteHandler extends ModifyBookmarkHandler
{
    public function handle(Delete $command): void
    {
        $bookmark = $this->getBookmark($command->getBookmarkId());
        if (!$bookmark->delete()) {
            return;
        }
        $this->eventEmitter->emit(new Removed($bookmark));
        $this->eventEmitter->emit(new Deleted($bookmark->getBookmarkId()));
    }
}
