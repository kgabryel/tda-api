<?php

namespace App\Video\Application\Command\UpdateWatchedValue;

use App\Video\Application\Command\ModifyVideoHandler;
use App\Video\Domain\Event\Updated;

class UpdateWatchedValueHandler extends ModifyVideoHandler
{
    public function handle(UpdateWatchedValue $command): void
    {
        $video = $this->getVideo($command->getVideoId());
        if ($command->isWatched()) {
            $video->markAsWatched();
        } else {
            $video->markAsUnwatched();
        }
        $this->eventEmitter->emit(new Updated($video));
    }
}
