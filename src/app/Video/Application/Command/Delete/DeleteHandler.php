<?php

namespace App\Video\Application\Command\Delete;

use App\Video\Application\Command\ModifyVideoHandler;
use App\Video\Domain\Event\Deleted;
use App\Video\Domain\Event\Removed;

class DeleteHandler extends ModifyVideoHandler
{
    public function handle(Delete $command): void
    {
        $video = $this->getVideo($command->getVideoId());
        if (!$video->delete()) {
            return;
        }
        $this->eventEmitter->emit(new Removed($video));
        $this->eventEmitter->emit(new Deleted($video->getVideoId()));
    }
}
