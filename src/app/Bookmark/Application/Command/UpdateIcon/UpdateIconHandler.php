<?php

namespace App\Bookmark\Application\Command\UpdateIcon;

use App\Bookmark\Application\Command\ModifyBookmarkHandler;
use App\Bookmark\Domain\Event\Updated;

class UpdateIconHandler extends ModifyBookmarkHandler
{
    public function handle(UpdateIcon $command): void
    {
        $bookmark = $this->getBookmark($command->getBookmarkId());
        if ($bookmark->updateIcon($command->getValue())) {
            $this->eventEmitter->emit(new Updated($bookmark));
        }
    }
}
