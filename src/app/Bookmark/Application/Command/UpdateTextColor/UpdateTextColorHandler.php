<?php

namespace App\Bookmark\Application\Command\UpdateTextColor;

use App\Bookmark\Application\Command\ModifyBookmarkHandler;
use App\Bookmark\Domain\Event\Updated;

class UpdateTextColorHandler extends ModifyBookmarkHandler
{
    public function handle(UpdateTextColor $command): void
    {
        $bookmark = $this->getBookmark($command->getBookmarkId());
        if ($bookmark->updateTextColor($command->getColor())) {
            $this->eventEmitter->emit(new Updated($bookmark));
        }
    }
}
