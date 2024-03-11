<?php

namespace App\Bookmark\Application\Command\UpdateName;

use App\Bookmark\Application\Command\ModifyBookmarkHandler;
use App\Bookmark\Domain\Event\Updated;

class UpdateNameHandler extends ModifyBookmarkHandler
{
    public function handle(UpdateName $command): void
    {
        $bookmark = $this->getBookmark($command->getBookmarkId());
        if ($bookmark->updateName($command->getValue())) {
            $this->eventEmitter->emit(new Updated($bookmark));
        }
    }
}
