<?php

namespace App\Video\Application\Command\UpdateName;

use App\Video\Application\Command\ModifyVideoHandler;
use App\Video\Domain\Event\Updated;

class UpdateNameHandler extends ModifyVideoHandler
{
    public function handle(UpdateName $command): void
    {
        $video = $this->getVideo($command->getVideoId());
        if ($video->changeName($command->getName())) {
            $this->eventEmitter->emit(new Updated($video));
        }
    }
}
