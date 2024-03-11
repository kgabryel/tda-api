<?php

namespace App\Bookmark\Application\Command\UpdateHref;

use App\Bookmark\Application\Command\ModifyBookmarkHandler;
use App\Bookmark\Application\FaviconServiceInterface;
use App\Bookmark\Domain\Event\Updated;
use App\Core\Cqrs\EventEmitter;
use App\Core\Cqrs\QueryBus;

class UpdateHrefHandler extends ModifyBookmarkHandler
{
    private FaviconServiceInterface $faviconService;

    public function __construct(QueryBus $queryBus, EventEmitter $eventEmitter, FaviconServiceInterface $faviconService)
    {
        parent::__construct($queryBus, $eventEmitter);
        $this->faviconService = $faviconService;
    }

    public function handle(UpdateHref $command): void
    {
        $bookmark = $this->getBookmark($command->getBookmarkId());
        $update = $bookmark->updateHref($command->getValue());
        if ($command->updateIcon()) {
            $icon = $this->faviconService->getAddress($command->getValue());
            if ($bookmark->updateIcon($icon)) {
                $update = true;
            }
        }
        if ($update) {
            $this->eventEmitter->emit(new Updated($bookmark));
        }
    }
}
