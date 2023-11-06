<?php

namespace App\Catalog\Application\Command\RemoveVideo;

use App\Catalog\Application\Command\ModifyCatalogHandler;
use App\Catalog\Domain\Event\VideosAssigmentChanged;

class RemoveVideoHandler extends ModifyCatalogHandler
{
    public function handle(RemoveVideo $command): void
    {
        $catalog = $this->getCatalog($command->getCatalogId());
        if ($catalog->removeVideo($command->getVideoId())) {
            $this->eventEmitter->emit(new VideosAssigmentChanged($catalog->getUserId(), $command->getVideoId()));
        }
    }
}
