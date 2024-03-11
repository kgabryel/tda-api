<?php

namespace App\Video\Application\Command\UpdateAssignedToDashboard;

use App\Video\Application\Command\ModifyVideoHandler;
use App\Video\Domain\Event\Updated;

class UpdateAssignedToDashboardHandler extends ModifyVideoHandler
{
    public function handle(UpdateAssignedToDashboard $command): void
    {
        $video = $this->getVideo($command->getVideoId());
        if ($video->updateAssignedToDashboard($command->getAssignedToDashboard())) {
            $this->eventEmitter->emit(new Updated($video));
        }
    }
}
