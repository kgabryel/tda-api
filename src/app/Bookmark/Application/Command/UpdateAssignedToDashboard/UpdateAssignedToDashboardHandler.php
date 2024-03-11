<?php

namespace App\Bookmark\Application\Command\UpdateAssignedToDashboard;

use App\Bookmark\Application\Command\ModifyBookmarkHandler;
use App\Bookmark\Domain\Event\Updated;

class UpdateAssignedToDashboardHandler extends ModifyBookmarkHandler
{
    public function handle(UpdateAssignedToDashboard $command): void
    {
        $bookmark = $this->getBookmark($command->getBookmarkId());
        if ($bookmark->updateAssignedToDashboard($command->isAssignedToDashboard())) {
            $this->eventEmitter->emit(new Updated($bookmark));
        }
    }
}
