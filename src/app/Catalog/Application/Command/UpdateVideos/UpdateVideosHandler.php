<?php

namespace App\Catalog\Application\Command\UpdateVideos;

use App\Catalog\Application\Command\ModifyCatalogHandler;
use App\Catalog\Domain\Event\VideosAssigmentChanged;

class UpdateVideosHandler extends ModifyCatalogHandler
{
    public function handle(UpdateVideos $command): void
    {
        $catalog = $this->getCatalog($command->getCatalogId());
        $changed = $catalog->updateVideos(...$command->getVideos());
        if (!$changed->isEmpty()) {
            $this->eventEmitter->emit(new VideosAssigmentChanged($catalog->getUserId(), ...$changed->get()));
        }
    }
}
