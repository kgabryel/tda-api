<?php

namespace App\Bookmark\Application\Command\UpdateBackgroundColor;

use App\Bookmark\Application\Command\ModifyBookmarkHandler;
use App\Bookmark\Domain\Event\Updated;

class UpdateBackgroundColorHandler extends ModifyBookmarkHandler
{
    public function handle(UpdateBackgroundColor $command): void
    {
        $bookmark = $this->getBookmark($command->getBookmarkId());
        if ($bookmark->updateBackgroundColor($command->getColor())) {
            $this->eventEmitter->emit(new Updated($bookmark));
        }
    }
}
