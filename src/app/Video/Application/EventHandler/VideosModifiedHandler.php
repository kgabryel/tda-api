<?php

namespace App\Video\Application\EventHandler;

use App\Core\Cqrs\ListenEvent;
use App\Video\Application\Notificator;
use App\Video\Domain\Event\VideosModified;

#[ListenEvent(VideosModified::class)]
class VideosModifiedHandler
{
    private Notificator $notificator;

    public function __construct(Notificator $notificator)
    {
        $this->notificator = $notificator;
    }

    public function handle(VideosModified $event): void
    {
        $this->notificator->videosChanges($event->getUserId(), ...$event->getIds());
    }
}
