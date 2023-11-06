<?php

namespace App\Video\Application\Command\UpdateCatalogs;

use App\Video\Application\Command\ModifyVideoHandler;
use App\Video\Domain\Event\CatalogsAssigmentChanged;

class UpdateCatalogsHandler extends ModifyVideoHandler
{
    public function handle(UpdateCatalogs $command): void
    {
        $video = $this->getVideo($command->getVideoId());
        $changed = $video->updateCatalogs(...$command->getCatalogs());
        if (!$changed->isEmpty()) {
            $this->eventEmitter->emit(new CatalogsAssigmentChanged($video->getUserId(), ...$changed->get()));
        }
    }
}
